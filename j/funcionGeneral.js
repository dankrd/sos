$(document).on("ready", inicia);

function inicia(){
	$('#'+$('body').data('focus')).focus();
	$('#contenedor').css('min-height',$(window).innerHeight()-$('footer').innerHeight()-$('header').innerHeight()-40);
	$(window).resize(function(){
		$('#contenedor').css('min-height',$(window).innerHeight()-$('footer').innerHeight()-$('header').innerHeight()-40)
	});
	$('#nuevo').on('click',function(){
		location.href='';
	});
	$('#cancelar').on('click',function(){
		location.href='';
	});	
	$('#grabar').on('click',function(){
		if(validar()){
			$('#formulario_insert').submit();
		}
	});
	$('#buscar').on('click',buscarlo);

}
function buscarlo(){
	$('body').append("<div id='fondo'><div id='opcionBuscar'></di></div>");
	var opciones_buscar = $('#buscar').data('busca');//con este codigo, llama desde la tabla para saber cuales son las opciones para buscar
	$('#opcionBuscar').load('../j/opcion-buscar.php?id_busca='+opciones_buscar, function( response, status, xhr ) {
	 	if ( status == "error" ) {
	    	alert('Ocurrio un error al consultar!!');
	    }else{
	    	$('#bton_buscar').on('click', buscarAlgo);
	    }
		});
	$('#fondo').on('click', cerrar);
}
function buscarAlgo(){w
	var s_pagina = $('#buscar').data('resultado');
	var s_aqui_va = $('#buscar').data('colocar');
	$.post(s_pagina,{
		//A enviar
		}).done(function(data){
			$( "#"+s_aqui_va).empty().append(data);
		}).fail(function(){
		}).always(function(){
	});
}
