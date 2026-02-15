<?php

namespace MyApp;

class ErrorHandler
{
    public static function addError(string $newError){
        $_SESSION['errors'][] = $newError;
    }

    public static function printErrors(): void {
        $errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];
        if ($errors != []) {
            echo "<center><h2 class='text-danger'>Error</h2>\n";
            echo "<p class='text-danger'>".implode("<br />\n", $errors)."</p>\n";
            echo '</center>';
        }
        $_SESSION['errors'] = [];
    }
}
