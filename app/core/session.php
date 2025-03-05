<?php
if (session_status() === PHP_SESSION_NONE) {
    // Session name must be set before session starts
    session_name('secure_session');
    
    // Cookie parameters must be set before session starts
    session_set_cookie_params([
        'lifetime' => 3600,
        'path' => '/',
        'secure' => true,
        'httponly' => true,
        'samesite' => 'Strict'
    ]);

    // Session configuration
    ini_set('session.cookie_lifetime', 0);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_secure', 1);
    ini_set('session.cookie_httponly', 1);
    
    // Start the session
    
}