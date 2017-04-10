<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class AgenceController extends CI_Controller
{

    private $_dir   = '';
    private $_files = array('new' => 'agence');
    private $_vista = '';

    public function __construct()
    {
        parent::__construct();
        $this->_dir = $this->router->directory;
        $this->_files = (object)$this->_files;
        $this->load->model('AgenceModel', 'registro');
        $this->load->helper('captcha');
        $this->load->library('encryption');
    }

    // Vista default template
    public function index()
    {
        $list_user = $this->registro->list_user();
        $list_mes = ['01' => 'Jan', '02' => 'Fev', '03' => 'Mar', '04' => 'Abr', '05' => 'Mai', '06' => 'Jun', '07' => 'Jul', '08' => 'Ago', '09' => 'Sep', '10' => 'Out', '11' => 'Nov', '12' => 'Dez'];
        $datos = compact('titulo', 'list_mes', 'list_user');
        $this->_vista = $this->_dir . $this->_files->new;
        $this->twig->display($this->_vista, $datos);
    }

    # Consulta de lista de relatorio
    public function relatorio()
    {
        $mes_desde = $this->input->get('mes_desde');
        $anyo_desde = $this->input->get('anyo_desde');
        $mes_hasta = $this->input->get('mes_hasta');
        $anyo_hasta = $this->input->get('anyo_hasta');
        $co_usuarios = $this->input->get('items_user');

        $usuarios = $this->registro->find_user($co_usuarios);

        $arr = array();

        foreach ($usuarios as $usuario) {

            $resultados = $this->registro->relatorio($usuario->co_usuario, $mes_desde, $anyo_desde, $mes_hasta, $anyo_hasta);

            $relatorio = array();
            $sum_ganancias_netas = 0;
            $sum_costo_fijo = 0;
            $sum_comision = 0;
            $sum_lucro = 0;

            foreach ($resultados as $resultado) {
                $anio_m = explode("-", $resultado->anio_mes);
                $mes = $this->get_mes($anio_m[1]);
                $anio_me = $mes . ' del ' . $anio_m[0];
                $ganancias_netas = number_format((float)$resultado->ganancias_netas, 2, '.', '');
                $comision = number_format((float)$resultado->comision, 2, '.', '');
                $lucro = $ganancias_netas - ($resultado->costo_fijo + $comision);
                $costo_fijo = number_format((float)$resultado->costo_fijo, 2, '.', '');
                array_push($relatorio, array($anio_me, $ganancias_netas, $costo_fijo, $comision, $lucro));

                $sum_ganancias_netas += $ganancias_netas;
                $sum_costo_fijo += $costo_fijo;
                $sum_comision += $comision;
                $sum_lucro += $lucro;

            }
            $arr[] = array(
                'no_usuario' => $usuario->no_usuario,
                'relatorio' => $relatorio,
                'sum_ganancias' => $sum_ganancias_netas,
                'sum_costo_fijo' => sprintf("%.2f", $sum_costo_fijo),
                'sum_comision' => $sum_comision,
                'sum_lucro' => $sum_lucro
            );
        }

        echo json_encode(compact('arr'));

    }

    # Consulta de Gráfico
    public function grafico()
    {

        $mes_desde = $this->input->get('mes_desde');
        $anyo_desde = $this->input->get('anyo_desde');
        $mes_hasta = $this->input->get('mes_hasta');
        $anyo_hasta = $this->input->get('anyo_hasta');
        $co_usuarios = $this->input->get('items_user');

        $usuarios = $this->registro->find_user($co_usuarios);

        $arr = array();
        $arr_mes = [];
        $meses_arr = $this->registro->getmeses($co_usuarios, $mes_desde, $anyo_desde, $mes_hasta, $anyo_hasta);

        foreach ($meses_arr as $mese_arr) {
            $arr_mes[] = $mese_arr->anio_mes;
        }

        foreach ($usuarios as $usuario) {

            $gan = [];
            foreach ($arr_mes as $row){
                $anio_m = explode("-", $row);
                $mes = $this->get_mes_short($anio_m[1]);
                $arr['anio_mes'][] = $mes . ' del ' . $anio_m[0];
                $result_row = $this->registro->get_ganancia($usuario->co_usuario, $row);
                if($result_row->ganancias_netas != ''){
                    $gan[] = number_format($result_row->ganancias_netas, 2, '.', '');
                }else{
                    $gan[] = 0.00;
                }
            }
            $gan = array_map('floatval', $gan);
            $arr['serie'][] = array('type' => 'column', 'name' => $usuario->no_usuario, 'data' => $gan);

        }

        $lines = $this->registro->getCostoFijo($co_usuarios, $mes_desde, $anyo_desde, $mes_hasta, $anyo_hasta);

        $li = '';
        foreach ($lines as $line) {
            $li .= $line->costo_fijo . ";";
        }

        $li = substr($li, 0, -1);
        $lin = array_map('floatval', explode(';', $li));
        $arr['line'] = $lin;
        echo json_encode(compact('arr'));
    }

    # Consulta de Pizza
    public function pizza()
    {
        $mes_desde = $this->input->get('mes_desde');
        $anyo_desde = $this->input->get('anyo_desde');
        $mes_hasta = $this->input->get('mes_hasta');
        $anyo_hasta = $this->input->get('anyo_hasta');
        $co_usuarios = $this->input->get('items_user');

        $datos = $this->registro->pizza($co_usuarios, $mes_desde, $anyo_desde, $mes_hasta, $anyo_hasta);
        $data = [];
        foreach ($datos as $row) {
            $data[] = array('name' => $row->no_usuario, 'y' => (float)$row->ganancias_netas);
        }
        echo json_encode($data);
    }

    private function get_mes($mes)
    {

        switch ($mes) {
            case 1:
                $mes = 'Janeiro';
            break;
            case 2:
                $mes = 'Fevereiro';
            break;
            case 3:
                $mes = 'Março';
            break;
            case 4:
                $mes = 'Abril';
            break;
            case 5:
                $mes = 'Maio';
            break;
            case 6:
                $mes = 'Junho';
            break;
            case 7:
                $mes = 'Julho';
            break;
            case 8:
                $mes = 'Agosto';
            break;
            case 9:
                $mes = 'Setembro';
            break;
            case 10:
                $mes = 'Outubro';
            break;
            case 11:
                $mes = 'Novembro';
            break;
            case 12:
                $mes = 'Dezembro';
            break;
        }
        return $mes;
    }

    private function get_mes_short($mes)
    {

        switch ($mes) {
            case 1:
                $mes = 'Jan';
            break;
            case 2:
                $mes = 'Fev';
            break;
            case 3:
                $mes = 'Mar';
            break;
            case 4:
                $mes = 'Abr';
            break;
            case 5:
                $mes = 'Mai';
            break;
            case 6:
                $mes = 'Jun';
            break;
            case 7:
                $mes = 'Jul';
            break;
            case 8:
                $mes = 'Ago';
            break;
            case 9:
                $mes = 'Set';
            break;
            case 10:
                $mes = 'Out';
            break;
            case 11:
                $mes = 'Nov';
            break;
            case 12:
                $mes = 'Dez';
            break;
        }
        return $mes;
    }
}