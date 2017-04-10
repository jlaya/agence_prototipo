<?php
if ( ! function_exists('modulos')) {
    function modulos() {
        $ci = & get_instance();
        $modulo = $ci->libreria->modulo();
        $opciones = '';
        $opciones .= '<ul id="tristate">';
        foreach ($modulo as $key => $value) {
            $submodulo = $ci->libreria->submodulo($value->id);
            if($submodulo){
                $opciones .= '<li>';
                $opciones .= '<i id="arrow" class="fa fa-arrow-right fa-lg" style="cursor:pointer"></i>&nbsp;';
                $opciones .= '<input class="tri" type="checkbox" name="" id="'.$value->id.'" />';
                $opciones .= '<span style="cursor:pointer">'. $value->modulo.'</span>';
                $opciones .= '<ul>';
                foreach ($submodulo as $key1 => $value1) {
                    $opciones .= '<li>';
                    $opciones .= '<input  class="tri" type="checkbox"  id="'.$value1->id.'" data-parent="'.$value->id.'"/>'. $value1->modulo;
                    $opciones .= '</li>';
                }
                $opciones .= '</ul></li>';
            }else{
                $opciones .= '<ul><li>'.$value->modulo.'</li></ul>';
            }
        }
        $opciones .= '</ul>';
        return $opciones;
    }
}