<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('dateformat()')) {

   function dateformat($date)
    {
        $date_format = preg_replace("/(\d+)\D+(\d+)\D+(\d+)/","$3-$2-$1",$date);
        return $date_format;
    }

}