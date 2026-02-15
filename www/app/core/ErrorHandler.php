<?php

namespace MyApp;

use Error;

class ErrorHandler
{
    private array $errors = [];

    private static ?ErrorHandler $instance = null;

    /**
     * Returns the singleton instance.
     */
    public static function instance(): ErrorHandler
    {
        if (null === self::$instance) {
            self::$instance = new ErrorHandler();
        }

        return self::$instance;
    }

  
    public static function addError(string $newError){
        $errors[] = $newError;
    }

    public function printErrors(): void {
        echo "<center><h2 class='text-danger'>Error</h2>\n";
        echo "<p class='text-danger'>".implode("<br />\n", $this->errors)."</p>\n";
        echo '</center>';
        $this->errors = [];
    }
}
