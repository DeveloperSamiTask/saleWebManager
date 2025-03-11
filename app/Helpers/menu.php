<?php

use Illuminate\Support\Facades\Route;

if (!function_exists('setActive')) {
    function setActive($routeNames, $class = 'active')
    {
        $routeNames = (array) $routeNames; // Asegura que sea un arreglo
        return in_array(Route::currentRouteName(), $routeNames) ? $class : '';
    }
}

if (!function_exists('setOpen')) {
    function setOpen($routeNames, $class = 'open')
    {
        $routeNames = (array) $routeNames; // Asegura que sea un arreglo
        return in_array(Route::currentRouteName(), $routeNames) ? $class : '';
    }
}
