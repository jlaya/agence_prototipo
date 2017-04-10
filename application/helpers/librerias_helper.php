<?php
if ( ! function_exists('librerias')) {
    function cedula($c) {
        $cedula = number_format($c, 0, '', '.');
        return $cedula;
    }

    function phones($p)
    {
        $total = strlen(trim($p));
        $phone = preg_replace("/[\-]+/","",$p);
        $phone = preg_replace("/[\s]+/","#",$phone);
        $phone = preg_replace("/[\/]+/","#",$phone);
        $phone = preg_replace("/[#]+/","#",$phone);
        preg_match_all('/[0-9]+/', $phone, $matches);

        $phones = '';
        for ($i=0; $i < count($matches[0]); $i++) {
            if(strlen($matches[0][$i]) >= 11){
                $phones .= preg_replace("/^([0-9]{4})([0-9]{3})([0-9]{2})([0-9]{2})$/", "($1) $2-$3-$4", $matches[0][$i]).'/';
            }

        }
        echo substr($phones,0,-1);
        $phone = '';
        return $phone;
    }

    function phone($p)
    {
        $phone = preg_replace("/^([0-9]{4})([0-9]{3})([0-9]{2})([0-9]{2})$/", "($1) $2-$3-$4", $data);
        return $phone;
    }
}