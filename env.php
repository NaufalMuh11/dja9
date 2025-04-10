<?php
require_once 'vendor/autoload.php';
try {
    $dotenv = new Dotenv\Dotenv('./', '.env');
    $dotenv->load();
} catch (Exception $e) {
    echo "cannot load env";
}
