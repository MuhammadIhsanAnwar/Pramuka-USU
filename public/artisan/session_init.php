<?php
// public/artisan/session_init.php
// Initialize PHP session safely for public artisan UI.

if (session_status() === PHP_SESSION_NONE) {
    // Prefer Laravel storage sessions folder
    $preferred = realpath(__DIR__ . '/../../storage/framework/sessions');
    if ($preferred === false) {
        $preferred = sys_get_temp_dir();
    }
    // ensure directory exists
    if (!is_dir($preferred)) {
        @mkdir($preferred, 0755, true);
    }
    if (is_writable($preferred)) {
        session_save_path($preferred);
    }
    // safer settings
    ini_set('session.use_strict_mode', 1);
    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_secure', isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 1 : 0);
    @session_start();
}
