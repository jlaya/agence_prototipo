<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
if (!function_exists('menu')) {
    function menu($tipo) {

        if($tipo == 1){
            $ci = & get_instance();
            $menu = $ci->libreria->menu();

        }else{
            $file     = APPPATH.'resources/menu.json';
            $get_file = file_get_contents($file);
            $menu     = json_decode($get_file, TRUE);
        }
        return $menu;
    }
}