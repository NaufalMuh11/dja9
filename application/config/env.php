<?php
defined('BASEPATH') or exit('No direct script access allowed');

function getenv($name, $default = null)
{
    return isset($_ENV[$name]) ? $_ENV[$name] : $default;
}
