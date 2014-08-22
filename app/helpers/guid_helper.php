<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!function_exists('is_valid_guid')) {

    function is_valid_guid($guid) {
        return preg_match('/^\{?[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}\}?$/', $guid);
    }

}