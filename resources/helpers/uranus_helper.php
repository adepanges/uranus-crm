<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('dd')) {
    function dd()
    {
        $args = func_get_args();
        call_user_func_array('dump', $args);
    }
}

if (!function_exists('d')) {
    function d()
    {
        $args = func_get_args();
        call_user_func_array('dump', $args);
    }
}

if (!function_exists('dump')) {
    function dump()
    {
        $args = func_get_args();
        echo '<pre>';
        if(is_array($args) && isset($args[0])) var_dump($args[0]);
        else var_dump($args);
        echo '</pre>';
        exit;
    }
}

if (!function_exists('is_valid_md5')) {
    function is_valid_md5($md5 ='')
    {
        return preg_match('/^[a-f0-9]{32}$/', $md5);
    }
}
