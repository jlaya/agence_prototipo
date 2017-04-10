<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('price()')) {

   function price($number, $decimals = 0, $decPoint = '.', $thousandsSep = ',')
    {
        $price = number_format($number, $decimals, $decPoint, $thousandsSep);
        return $price;
    }

}