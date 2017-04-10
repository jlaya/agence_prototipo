<?php

/**
 *
 */
class AgenceModel extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    # Listar consultores
    public function list_user()
    {
        $this->db->select('consultuser.co_usuario, consultuser.no_usuario');
        $this->db->from('cao_usuario AS consultuser');
        $this->db->join('permissao_sistema AS puser', 'consultuser.co_usuario=puser.co_usuario', 'inner');
        $this->db->where(array('puser.co_sistema' => 1, 'puser.in_ativo' => 's'));
        $this->db->where_in('puser.co_tipo_usuario', [0, 1, 2]);
        $list_user = $this->db->get();
        return $list_user->result();
    }

    public function find_user($users_ids)
    {
        $this->db->select('co_usuario, no_usuario');
        $this->db->where_in('co_usuario', $users_ids);
        $query = $this->db->get('cao_usuario');
        return $query->result();
    }

    // Reporte de relatorio
    public function relatorio($co_usuario, $mes_desde, $anios_desde, $mes_hasta, $anios_hasta)
    {

        $this->db->select("
                CONCAT_WS('-',YEAR(cao_fatura.data_emissao),MONTH(cao_fatura.data_emissao))AS anio_mes,
                cao_salario.brut_salario AS costo_fijo,
                sum(cao_fatura.valor-((cao_fatura.valor*cao_fatura.total_imp_inc)/100)) as ganancias_netas,
                sum(((cao_fatura.valor-(cao_fatura.valor*cao_fatura.total_imp_inc/100))*cao_fatura.comissao_cn/100)) as comision
            ");
        $this->db->from('cao_usuario');
        $this->db->join('cao_os', 'cao_usuario.co_usuario = cao_os.co_usuario', 'inner');
        $this->db->join('cao_fatura', 'cao_fatura.co_os = cao_os.co_os', 'inner');
        $this->db->join('cao_salario', 'cao_salario.co_usuario = cao_usuario.co_usuario', 'inner');
        $this->db->where('cao_usuario.co_usuario', $co_usuario);
        $this->db->where("DATE_FORMAT(cao_fatura.data_emissao,'%m') between '$mes_desde' and '$mes_hasta'");
        $this->db->where("DATE_FORMAT(cao_fatura.data_emissao,'%Y') between '$anios_desde' and '$anios_hasta'");
        $this->db->group_by('anio_mes, cao_usuario.co_usuario');
        $this->db->order_by('cao_usuario.co_usuario, anio_mes');
//        echo $this->db->get_compiled_select();
//        exit;
        $query = $this->db->get();

        return $query->result();
    }


    public function getCostoFijo($co_usuario, $mes_desde, $anios_desde, $mes_hasta, $anios_hasta)
    {
        $this->db->select("
            CONCAT_WS('-',YEAR(cao_fatura.data_emissao),MONTH(cao_fatura.data_emissao))AS anio_mes,
            sum(cao_salario.brut_salario)/count(DISTINCT cao_usuario.co_usuario) as costo_fijo
            ");
        $this->db->from('cao_usuario');
        $this->db->join('cao_os', 'cao_os.co_usuario = cao_usuario.co_usuario', 'inner');
        $this->db->join('cao_fatura', 'cao_fatura.co_os = cao_os.co_os', 'inner');
        $this->db->join('cao_salario', 'cao_salario.co_usuario = cao_usuario.co_usuario', 'inner');
        $this->db->where_in('cao_usuario.co_usuario', $co_usuario);
        $this->db->where("DATE_FORMAT(cao_fatura.data_emissao,'%m') between '$mes_desde' and '$mes_hasta'");
        $this->db->where("DATE_FORMAT(cao_fatura.data_emissao,'%Y') between '$anios_desde' and '$anios_hasta'");
        $this->db->group_by('anio_mes');
        $this->db->order_by('anio_mes');
        $query = $this->db->get();

        return $query->result();
    }

    public function getmeses($co_usuario, $mes_desde, $anios_desde, $mes_hasta, $anios_hasta)
    {
        $this->db->select("
            DATE_FORMAT(cao_fatura.data_emissao,'%Y-%m') as anio_mes
            ");
        $this->db->from('cao_usuario');
        $this->db->join('cao_os', 'cao_os.co_usuario = cao_usuario.co_usuario', 'inner');
        $this->db->join('cao_fatura', 'cao_fatura.co_os = cao_os.co_os', 'inner');
        $this->db->join('cao_salario', 'cao_salario.co_usuario = cao_usuario.co_usuario', 'inner');
        $this->db->where_in('cao_usuario.co_usuario', $co_usuario);
        $this->db->where("DATE_FORMAT(cao_fatura.data_emissao,'%m') between '$mes_desde' and '$mes_hasta'");
        $this->db->where("DATE_FORMAT(cao_fatura.data_emissao,'%Y') between '$anios_desde' and '$anios_hasta'");
        $this->db->group_by('anio_mes');
        $this->db->order_by('anio_mes');
       /* echo $this->db->get_compiled_select();
        exit;*/
        $query = $this->db->get();

        return $query->result();
    }

    public function get_ganancia($user, $mes_anio)
    {
        $this->db->select("
            sum(cao_fatura.valor-((cao_fatura.valor*cao_fatura.total_imp_inc)/100)) as ganancias_netas,
            ");
        $this->db->from('cao_usuario');
        $this->db->join('cao_os', 'cao_os.co_usuario = cao_usuario.co_usuario', 'inner');
        $this->db->join('cao_fatura', 'cao_fatura.co_os = cao_os.co_os', 'inner');
        $this->db->join('cao_salario', 'cao_salario.co_usuario = cao_usuario.co_usuario', 'inner');
        $this->db->where('cao_usuario.co_usuario', $user);
        $this->db->where("DATE_FORMAT(cao_fatura.data_emissao,'%Y-%m') = '$mes_anio' ");
       /* echo $this->db->get_compiled_select();
        exit;*/
        $query = $this->db->get();
        return $query->row();
    }
    // Reporte de pizza
    public function pizza($co_usuario, $mes_desde, $anios_desde, $mes_hasta, $anios_hasta)
    {
        $this->db->select("
                cao_usuario.no_usuario,
                sum(cao_fatura.valor-((cao_fatura.valor*cao_fatura.total_imp_inc)/100)) as ganancias_netas
            ");
        $this->db->from('cao_usuario');
        $this->db->join('cao_os', 'cao_usuario.co_usuario = cao_os.co_usuario', 'inner');
        $this->db->join('cao_fatura', 'cao_fatura.co_os = cao_os.co_os', 'inner');
        $this->db->where_in('cao_usuario.co_usuario', $co_usuario);
        $this->db->where("DATE_FORMAT(cao_fatura.data_emissao,'%m') between '$mes_desde' and '$mes_hasta'");
        $this->db->where("DATE_FORMAT(cao_fatura.data_emissao,'%Y') between '$anios_desde' and '$anios_hasta'");
        $this->db->group_by('cao_usuario.co_usuario');
        $this->db->order_by('cao_usuario.co_usuario');
        $query = $this->db->get();

        return $query->result();
    }


}



















