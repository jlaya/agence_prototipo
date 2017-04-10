<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('projectname()')) {

    function projectname()
    {
        $ci = &get_instance();
        $project_name = $ci->config->item('project_name');
        return $project_name;
    }

}