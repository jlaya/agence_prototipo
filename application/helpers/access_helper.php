<?php
if ( ! function_exists('access')) {
    function access() {
        $ci = & get_instance();
        $id = $ci->session->userdata('user_id');
        $ci->db->select('change_password');
        $ci->db->where('id',$id);
        $query  = $ci->db->get('se_users');
        $change = $query->row()->change_password;
        if($change == 'f'){
            return 0;
        }else{
            return 1;
        }
    }
}
