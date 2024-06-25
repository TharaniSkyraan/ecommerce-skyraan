<?php
//echo '<pre>';
//print_r(get_loaded_extensions());
//exit;
/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 * @author   Taylor Otwell <taylor@laravel.com>
 */


 
$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);



// This file allows us to emulate Apache's "mod_rewrite" functionality from the
// built-in PHP web server. This provides a convenient way to test a Laravel
// application without having installed a "real" web server software here.
if ($uri !== '/' && file_exists(__DIR__.'/S1k2y3r4a5a6n/public'.$uri)) {
    return false;
}

require_once __DIR__.'/S1k2y3r4a5a6n/public/index.php';
