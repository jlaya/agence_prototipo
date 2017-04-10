
jQuery(document).ready(function($) {

	$("a.relatorio").click(function(event) {
        var mes_desde = $("#mes_desde").val();
        var anyo_desde = $("#anyo_desde").val();
        var mes_hasta = $("#mes_hasta").val();
        var anyo_hasta = $("#anyo_hasta").val();

        var items_user=[];
        $('#items_user :selected').each(function(){
            items_user.push($(this).val());
        });

        $("div.container-grafico").css("display","none");
        $("div.container-relatorio").css("display","block");

		$.get(route_url("AgenceController/relatorio"), {'mes_desde': mes_desde, 'anyo_desde':anyo_desde, 'mes_hasta':mes_hasta, 'anyo_hasta':anyo_hasta, items_user:items_user }, function(response) {
            $('#div_relatorio').html('');
			var tbl = '';
			$.each(response.arr, function (i, datos) {
				tbl += '<div class="card container-relatorio" >';
                tbl += '<table id="tbl_relatorio"  class="table">';
                tbl += '<caption class="bg-success" style="text-align:center;font-weight:bold">' + datos.no_usuario + '</caption>';
                tbl += '<thead class="text-primary">';
                tbl += '<tr>';
                tbl += '<th>Período</th>';
                tbl += '<th>Receita Líquida</th>';
                tbl += '<th>Custo Fixo</th>';
                tbl += '<th>Comissão</th>';
                tbl += '<th>Lucro</th>';
                tbl += '</tr>';
                tbl += '</thead>';
                tbl += '<tbody>';
                
                $.each(datos.relatorio, function (r, relatorio) {
                    tbl += '<tr>';
                    tbl += '<td>' + relatorio[0] + '</td>';
                    tbl += '<td>R$ ' + relatorio[1] + '</td>';
                    tbl += '<td>R$ ' + relatorio[2] + '</td>';
                    tbl += '<td>R$ ' + relatorio[3] + '</td>';
                    tbl += '<td>R$ ' + relatorio[4] + '</td>';
                    tbl += '</tr>';
                });
                tbl += '</tbody>';

                tbl += '<tfoot>';
                tbl += '<tr>';
                tbl += '<th>Saldo</th>';
                tbl += '<th>R$ ' + datos.sum_ganancias + '</th>';
                tbl += '<th>R$ ' + datos.sum_costo_fijo + '</th>';
                tbl += '<th>R$ ' + datos.sum_comision + '</th>';
                tbl += '<th>R$ ' + datos.sum_lucro + '</th>';
                tbl += '</tr>';
                tbl += '</tfoot>';

                tbl += '</table>';
                tbl += '</div>';

                console.log(datos.sum_costo_fijo);

            })
			$('#div_relatorio').append(tbl);
		},'json');
	});

	$("a.grafico").click(function(event) {
		// Llamada de grafico
        var mes_desde = $("#mes_desde").val();
        var anyo_desde = $("#anyo_desde").val();
        var mes_hasta = $("#mes_hasta").val();
        var anyo_hasta = $("#anyo_hasta").val();

        var items_user=[];
        $('#items_user :selected').each(function(){
            items_user.push($(this).val());
        });

		grafico(items_user, mes_desde, anyo_desde, mes_hasta, anyo_hasta);
		$("div.container-relatorio").css("display","none");
		$("div.container-grafico").css("display","block");
	});

	$("a.pizza").click(function(event) {
		// Llamada de pizza
        var mes_desde = $("#mes_desde").val();
        var anyo_desde = $("#anyo_desde").val();
        var mes_hasta = $("#mes_hasta").val();
        var anyo_hasta = $("#anyo_hasta").val();

        var items_user=[];
        $('#items_user :selected').each(function(){
            items_user.push($(this).val());
        });
		pizza(items_user, mes_desde, anyo_desde, mes_hasta, anyo_hasta);
		$("div.container-relatorio").css("display","none");
		$("div.container-grafico").css("display","block");
	});

});

// Ruta raiz del proyecto
window.folder= 'agence_practica';
var route_url = function(path) {
	var pathArray = window.location.pathname.split( '/' );
	var pathOne = pathArray.slice(1,2);
	var base = (pathOne == folder)? window.location.origin + pathArray.slice(0,2).join('/') + '/' : window.location.origin + pathArray.slice(0,1).join('/') + '/';
	var url  = base;
	if(path !== undefined){
		url = base+path;
	}
	return url;
};


// Llamada de resultado de grafico
var grafico  = function(items_user, mes_desde, anyo_desde, mes_hasta, anyo_hasta) {

    $.get(route_url("AgenceController/grafico"), {'mes_desde': mes_desde, 'anyo_desde':anyo_desde, 'mes_hasta':mes_hasta, 'anyo_hasta':anyo_hasta, items_user:items_user }, function(response) {

        var serie = response.arr.serie;
        var anio_mes = response.arr.anio_mes;
        var linea = { type: 'spline', name: 'Average', data: response.arr.line, marker: {lineWidth: 2, lineColor: Highcharts.getOptions().colors[3], fillColor: 'white'} }

        serie.push(linea);
        Highcharts.chart('container-result', {
            title: {
                text: 'Desempeño'
            },
            xAxis: {
                categories: anio_mes
            },
            yAxis: {
                title: {
                    text: "Salarios"
                },
                labels: {
                    formatter: function () {
                        return this.value;
                    }
                }
            },
            labels: {
                items: [{
                    html: '',
                    style: {
                        left: '50px',
                        top: '18px',
                        color: (Highcharts.theme && Highcharts.theme.textColor) || 'black'
                    }
                }],
            },
            series: serie,
            credits: {
                enabled: false
            },
        });

    },'json');


}

// Llamada de resultado de pizza
var pizza  = function(items_user, mes_desde, anyo_desde, mes_hasta, anyo_hasta) {

	$.get(route_url("AgenceController/pizza"), {'mes_desde': mes_desde, 'anyo_desde':anyo_desde, 'mes_hasta':mes_hasta, 'anyo_hasta':anyo_hasta, items_user:items_user }, function(res, textStatus, xhr) {
        Highcharts.chart('container-result', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: 'Desempeño'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                        style: {
                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                        }
                    }
                }
            },
            series: [{
                name: 'Brands',
                colorByPoint: true,
                data: res
            }]
        });
	},'json');



}
