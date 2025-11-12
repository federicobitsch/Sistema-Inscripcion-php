<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class Captcha {
    public static function generate() {
        $_SESSION['captcha'] = 5; // respuesta correcta a “2 + 3”
    }

    public static function verify($input) {
        return isset($_SESSION['captcha']) && ((int)$input === (int)$_SESSION['captcha']);
    }
}
?>
