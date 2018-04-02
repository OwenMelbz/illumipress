<?php
array_map(function ($file) {
    $file = "globals/{$file}.php";
    // require_once($file);
}, ['helpers', 'setup', 'filters']);