<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('getname()')) {

    function getname()
    {
        $ci = &get_instance();
        $user_id = $ci->session->user_id;
        $ci->db->select("si_user, first_name, last_name, show_panel");
        $ci->db->where("id",$user_id);
        $query = $ci->db->get('se_users',1);
        $row = $query->row();
        if($row->show_panel == 1){
            $name_show = $row->si_user;
        }else{
            $name_show = $row->first_name .' '.$row->last_name;
        }
        return strtoupper($name_show);
    }

}