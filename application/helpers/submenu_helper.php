<?php
if (!function_exists('submenu')) {
    function submenu($id)
    {
        $ci = &get_instance();
        $submenu = $ci->libreria->submenu($id);
        return $submenu;
    }
}