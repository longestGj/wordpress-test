<?php
// Stage bootstrap guard. Never treat a database connection failure as a fresh site.
mysqli_report(MYSQLI_REPORT_OFF);
$hostPort = explode(':', getenv('WORDPRESS_DB_HOST') ?: '', 2);
$host = $hostPort[0] ?? '';
$port = (int) ($hostPort[1] ?? 3306);
$connection = @mysqli_connect(
    $host,
    getenv('WORDPRESS_DB_USER') ?: '',
    getenv('WORDPRESS_DB_PASSWORD') ?: '',
    getenv('WORDPRESS_DB_NAME') ?: '',
    $port
);

if (!$connection) {
    fwrite(STDERR, "WordPress database connection or credentials failed.\n");
    exit(1);
}

if (in_array('--require-empty', $argv, true)) {
    $tables = $connection->query('SHOW TABLES');
    if (!$tables || $tables->num_rows !== 0) {
        fwrite(STDERR, "Database is not empty; refusing fresh installation.\n");
        exit(1);
    }
}

$connection->close();
