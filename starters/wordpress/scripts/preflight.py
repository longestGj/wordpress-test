"""Read-only startup guard. Never creates, stops or removes Docker resources."""
import argparse
import json
import os
from pathlib import Path
import re
import socket
import subprocess
import sys


def validate_settings(project, port, protected_projects=()):
    errors = []
    if not re.fullmatch(r"[a-z0-9][a-z0-9_-]{1,47}", project):
        errors.append("Project must be 2-48 lowercase letters, numbers, underscores or hyphens.")
    if project in protected_projects:
        errors.append("Project is protected.")
    if not 1024 <= port <= 65535:
        errors.append("Use a dedicated unprivileged port (1024-65535).")
    return errors


def normalized(path):
    return os.path.normcase(str(Path(path).resolve()))


def project_errors(labels, root, resume):
    if labels and not resume:
        return ["Project already exists; choose a new name or verify --resume."]
    for item in labels:
        directory = item.get("com.docker.compose.project.working_dir", "")
        configs = item.get("com.docker.compose.project.config_files", "")
        if not directory or not configs:
            return ["Existing project has no verifiable ownership labels."]
        if normalized(directory) != normalized(root) or normalized(configs) != normalized(root / "compose.yaml"):
            return ["Project belongs to another directory/configuration; refusing adoption."]
    return []


def port_free(port):
    try:
        with socket.socket() as sock:
            # Exclusive binding on Windows; avoid false success against a listening socket.
            if hasattr(socket, "SO_EXCLUSIVEADDRUSE"):
                sock.setsockopt(socket.SOL_SOCKET, socket.SO_EXCLUSIVEADDRUSE, 1)
            sock.bind(("127.0.0.1", port))
        return True
    except OSError:
        return False


def docker(root, *args):
    result = subprocess.run(["docker", *args], cwd=root, text=True, capture_output=True)
    if result.returncode:
        # config output and daemon diagnostics can contain secret values.
        raise ValueError("Docker command failed: " + " ".join(args[:2]) + ". Check Docker and .env locally.")
    return result.stdout.strip()


def main():
    parser = argparse.ArgumentParser(description=__doc__)
    parser.add_argument("--project", required=True)
    parser.add_argument("--port", required=True, type=int)
    parser.add_argument("--resume", action="store_true")
    parser.add_argument("--protect-project", action="append", default=[])
    args = parser.parse_args()
    root = Path(__file__).resolve().parents[1]
    errors = validate_settings(args.project, args.port, args.protect_project)
    if errors:
        raise ValueError(" ".join(errors))
    config = json.loads(docker(root, "compose", "config", "--format", "json"))
    if config.get("name") != args.project:
        raise ValueError("--project differs from resolved COMPOSE_PROJECT_NAME.")
    bindings = config["services"]["wordpress"]["ports"]
    if len(bindings) != 1 or bindings[0].get("host_ip") != "127.0.0.1" or int(bindings[0]["published"]) != args.port:
        raise ValueError("WordPress must bind only the requested loopback port.")
    ids = docker(root, "ps", "-aq", "--filter", "label=com.docker.compose.project=" + args.project).split()
    containers = json.loads(docker(root, "inspect", *ids)) if ids else []
    labels = [item["Config"].get("Labels") or {} for item in containers]
    errors = project_errors(labels, root, args.resume)
    if errors:
        raise ValueError(" ".join(errors))
    volumes = docker(root, "volume", "ls", "-q", "--filter", "label=com.docker.compose.project=" + args.project)
    if volumes and not containers:
        raise ValueError("Orphan project volumes exist. Choose a new name; explicit recovery is separate.")
    # Also reject unlabeled foreign volumes with the names Compose would otherwise adopt.
    for volume in config.get("volumes", {}).values():
        name = volume.get("name")
        if not name:
            continue
        existing = docker(root, "volume", "ls", "-q", "--filter", "name=^" + name + "$")
        if existing and (not containers or name not in volumes.splitlines()):
            raise ValueError("A volume name is already owned or cannot be verified.")
    owned_port = any(
        p.get("HostIp") == "127.0.0.1" and p.get("HostPort") == str(args.port)
        for c in containers for ports in (c.get("NetworkSettings", {}).get("Ports") or {}).values()
        for p in (ports or [])
    )
    if not owned_port and not port_free(args.port):
        raise ValueError("Port is occupied by another process.")
    print("PASS: configuration, project ownership and loopback port checked; no resources changed.")


if __name__ == "__main__":
    try:
        main()
    except (ValueError, OSError, KeyError, TypeError) as exc:
        print("STOP: " + str(exc), file=sys.stderr)
        sys.exit(1)
