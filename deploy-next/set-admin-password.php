<?php
// Used by the stage bootstrap only. The password arrives through standard input.
$login = $args[0] ?? '';
$password = rtrim(stream_get_contents(STDIN), "\r\n");

if ($login === '' || strlen($password) < 16) {
    WP_CLI::error('Stage admin credentials are missing or too short.');
}

$user = get_user_by('login', $login);
if (!$user || !user_can($user, 'manage_options')) {
    WP_CLI::error('Stage admin user was not found.');
}

wp_set_password($password, $user->ID);
WP_CLI::success('Stage admin password set.');
