<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('resources')) {

    function resources()
    {
        $ci = & get_instance();
        $ci->load->helper('file');

        $file      = APPPATH.'resources/resources.json';
        $get_file  = file_get_contents($file);
        $resources = json_decode($get_file, TRUE);
        return $resources;
        }

    }