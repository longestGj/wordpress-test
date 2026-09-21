"""Configuration checks must fail before any Docker mutation."""
import importlib.util
from pathlib import Path
import tempfile
import unittest

MODULE = Path(__file__).parents[1] / "scripts/preflight.py"


class PreflightTests(unittest.TestCase):
    @classmethod
    def setUpClass(cls):
        spec = importlib.util.spec_from_file_location("preflight", MODULE)
        cls.p = importlib.util.module_from_spec(spec)
        spec.loader.exec_module(cls.p)

    def test_settings(self):
        self.assertTrue(self.p.validate_settings("protected-site", 18081, ("protected-site",)))
        for name in ("../other", "", "UPPER", "space here"):
            self.assertTrue(self.p.validate_settings(name, 18081))
        for port in (0, 65536, 80):
            self.assertTrue(self.p.validate_settings("probe-a", port))
        self.assertEqual(self.p.validate_settings("probe-a", 18081), [])

    def test_duplicate_project_never_adopted(self):
        with tempfile.TemporaryDirectory() as tmp:
            root = Path(tmp).resolve()
            labels = {"com.docker.compose.project.working_dir": str(root),
                      "com.docker.compose.project.config_files": str(root / "compose.yaml")}
            self.assertTrue(self.p.project_errors([labels], root, False))
            self.assertEqual(self.p.project_errors([labels], root, True), [])
            labels["com.docker.compose.project.working_dir"] = str(root / "other")
            self.assertTrue(self.p.project_errors([labels], root, True))

    def test_config_file_mismatch(self):
        root = Path.cwd()
        labels = {"com.docker.compose.project.working_dir": str(root),
                  "com.docker.compose.project.config_files": str(root / "other.yaml")}
        self.assertTrue(self.p.project_errors([labels], root, True))

    def test_missing_labels_not_trusted(self):
        self.assertTrue(self.p.project_errors([{}], Path.cwd(), True))

    def test_loopback_port_is_bindable(self):
        import socket
        with socket.socket() as sock:
            sock.bind(("127.0.0.1", 0))
            port = sock.getsockname()[1]
            self.assertFalse(self.p.port_free(port))


if __name__ == "__main__":
    unittest.main()
