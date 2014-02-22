$(document).on("ready", arranca);

function arranca(){
	$('#n_perfil').on('change',cargarMenu);
    $('#grabar').on('click', function(){
    	if(validarUsuario()){
    	   	$('#agregarUsuario').submit();
    	}
    });
    $('#reporte').on('click', function(){
    	var c_direccion_reporte = $('#reporte').data('buscar');
		$('#contenido').load('../js/'+c_direccion_reporte, function( response, status, xhr ) {
	 	if ( status == "error" ) {
	    	alert('Ocurrio un error al consultar!!');
	    }else{
	    	$('#reporteUsuarios tr').on('click', buscarUsuario);
	    }
		});
    });
    $('#agregar').on('click', function(){
    	var c_direccion_agregar = $('#agregar').data('direccion');
    	location.href= c_direccion_agregar;
    });
}
function cargarMenu(){
	//console.log('Esto es una prueba');
	$.post('../js/consultarMenu.php',{
			n_perfil: $('#n_perfil').val()
		}).done(function(data){
			$( "#result" ).empty().append(data);
			$(".opciones_padres li").on('click', grabarMenu);
		}).fail(function(){
		}).always(function(){
			$('#fondo').remove();
		});
}
function buscarUsuario(datos){
	var usuarioElegido = datos.currentTarget.id;
	$('#form_'+usuarioElegido).submit();
	/*$.post('../js/rep-usuarios.php',{
			n_usuario: usuarioElegido
		}).done(function(data){
			$( "#contenido" ).empty().append(data);
		}).fail(function(){
		}).always(function(){
			$('#fondo').remove();
		});*/
}
function grabarMenu(datos){
	var opcionElegida = datos.currentTarget.id;
	var cambioscs='';
	var valorCheck;
	if($('#opcion_'+opcionElegida).val()==1){
		$('#opcion_'+opcionElegida).val(0);
		cambioscss = {
			background: '#CCC',
			left: '0.8em'
		};
		$('#interruptor_'+opcionElegida+' div').css(cambioscss);

	}else{
		$('#opcion_'+opcionElegida).val(1);
		cambioscss = {
			background: 'green',
			left: '0.1em'
		};
		$('#interruptor_'+opcionElegida+' div').css(cambioscss);
	}
	valorCheck = $('#opcion_'+opcionElegida).val();
	var s_pag ='../js/grabarMenu.php';
	var grabar = $.post(s_pag,{
					n_perfil: $('#n_perfil').val(),
					n_funcion: opcionElegida,
					n_estado: valorCheck
		}).done(function(){
		}).fail(function(){
		}).always(function(){
		});
}
function validarUsuario(){
	var s_passw1 = $('#c_password').val();
	var s_passw2 = $('#c_password2').val();
	if($('#c_nombre').val().length==0){
		alert('El Campo de Nombre NO puede estar vacio!');
		return false;
	}
	if($('#n_perfil').val()==0){
		alert('El usuario debe tener un perfil establecido!');
		return false;
	}
	if($('#c_login').val().length==0){
		alert('El Campo de Usuario NO puede estar vacio!');
		return false;
	}
	console.log($('#id').val());
	if($('#id').val()>0){
		return true;
	}else{
		if($('#c_password').val().length==0){
			alert('El Campo de Contraseña NO puede estar vacio!');
			return false;
		}
		if($('#c_password2').val().length==0){
			alert('El Campo de Repetir Contraseña NO puede estar vacio!');
			return false;
		}
		if(s_passw1!=s_passw2){
			alert('Las contraseñas no coinciden!');
			return false;	
		}
	}
	return true;
}
