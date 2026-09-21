<?php
// Every database-mutating PHP test must load this before its first write.
if (!in_array(wp_parse_url(home_url(),PHP_URL_HOST),['localhost','127.0.0.1','[::1]'],true)) WP_CLI::error('Local test only: refusing database mutations on this site.');
