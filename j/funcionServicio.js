$(document).on("ready", inicia);

function inicia(){
	$('#n_entidad').on('change', cargaTipoEntidad);
	$('#n_modulo').on('change', cargaFuncionalidad);
	$('#InsertarFuncionalidades').on('click', insertarFuncionalidades);
}
function cargaTipoEntidad(datos){
	var nEntidad = $('#'+datos.currentTarget.id).val();
	$.post('../j/consultasVarias.php',{
		n_entidad: nEntidad,
		n_consulta: 1
		}).done(function(data){
			$( "#c_tipo_entidad" ).val(data);
		}).fail(function(){
		}).always(function(){
	});
	$.post('../j/consultasVarias.php',{
		n_entidad: nEntidad,
		n_consulta: 2
		}).done(function(data){
			$( "#n_modulo" ).empty().append(data);
		}).fail(function(){
		}).always(function(){
	});
}
function cargaFuncionalidad(datos){
	var nModulo = $('#'+datos.currentTarget.id).val();
	$.post('../j/consultasVarias.php',{
		n_modulo: nModulo,
		n_consulta: 3
		}).done(function(data){
			$( "#n_funcionalidad" ).empty().append(data);
		}).fail(function(){
		}).always(function(){
	});
}
function insertarFuncionalidades(){
	var nServicio= $( "#n_servicio" ).val();
	var nFuncionalidadesAfectadas = $('#n_funcionalidades_afe').val();
	var nFuncionalidad= $( "#n_funcionalidad" ).val();
	var nModulo= $( "#n_modulo" ).val();
	var cFuncionalidad= $( "#n_funcionalidad option:selected" ).text();
	var cModulo= $( "#n_modulo option:selected" ).text();
	if(nFuncionalidadesAfectadas==0){
		$( "#funcionalidades" ).empty().append("<table><tr><th>Modulos</th><th>Funcionalidades</th></tr></table>");
	}
	nFuncionalidadesAfectadas++; 
	$( "#funcionalidades table" ).append("<tr><td><input type='hidden' id='n_modul_"+nFuncionalidadesAfectadas+"' value='"+nModulo+"'>"+cModulo+"</td><td>"+cFuncionalidad+"<input type='hidden' id='n_funci_"+nFuncionalidadesAfectadas+"' value='"+nFuncionalidad+"'></td></tr>");
	$('#n_funcionalidades_afe').val(nFuncionalidadesAfectadas);
	$( "#n_modulo" ).val(0);
	$( "#n_funcionalidad" ).val(0);
	/*$.post('../j/insertarVarios.php',{
		n_funcionalidad: nFuncionalidad,
		n_servicio: nServicio,
		n_insertar: 1
		}).done(function(data){
			if(data=1){
				$.post('../j/consultasVarias.php',{
						n_servicio: nServicio,
						n_consulta: 4
						}).done(function(data){
							$( "#funcionalidades" ).empty().append(data);
						}).fail(function(){
						}).always(function(){
					});
			}
		}).fail(function(){
		}).always(function(){
	});	*/
}
$(document).ready(function(){
	$("#insertar").click(function(){
		var cNombre=$("#cNombre").val();
		var nTipo=$("#cTipo").val();
		var nCiudad=$("#nCiudad").val();
		var cDireccion=$("#cDireccion").val();
		var cTelefono=$("#cTelefono").val();
		var productosr=$('input:radio[name=productos]:checked').attr("id");
		var productosc="";
		var productos=productosr;

		$('input[name=productoscheck]:checked').each(function() {
			productosc+=$(this).attr("id");
		});
		if(productosc.length==1){
			productos=productos+','+productosc;
		}
		else
		var modulos="";
		$('input[name=modulos]:checked').each(function() {
			modulos+=$(this).attr("id")+",";
		});
		var cContrato=$("#cContrato").val();
		var cFechaInicio=$("#cFechaInicio").val();
		var cFechaFin=$("#cFechaFin").val();
		var cContactos=$("#cContactos").val();
		/*$.post('../j/insertarVarios.php',{
			c_Nombre: cNombre,
			n_Tipo:nTipo,
			n_Ciudad:nCiudad,
			c_Direccion:cDireccion,
			c_Telefono:cTelefono,
			n_insertar: 2
			}).done(function(data){
				console.log(data);
			}).fail(function(){
			}).always(function(){
		});*/
		$.post('../j/consultasVarias.php',{
			cNombre: cNombre,
			n_consulta: 5
			}).done(function(data){
				console.log('La empresa es:'+data);
				var producto=productos.split(",");
				for (var i = 0; i < producto.length; i++) {
					$.post('../j/insertarVarios.php',{
						n_empresa: data,
						n_producto: producto[i],
						n_insertar: 3
						}).done(function(data1){
							console.log(data1);
						}).fail(function(){
						}).always(function(){
					});
				};
				///////
				var cListado=cContactos.split("-");
				for (var i = 1; i < cListado.length; i++) {
					var datos=cListado[i].split("/");
					var name=datos[0];
					var lastname=datos[1];
					var car=datos[2];
					var correo=datos[3];				
					$.post('../j/insertarVarios.php',{
						n_empresa: data,
						c_nombreContacto:name,
						c_apellidosContacto:lastname,
						c_cargo:car,
						c_email: correo,
						n_insertar: 4
						}).done(function(data1){
							console.log(data1);
						}).fail(function(){
						}).always(function(){
					});
				};
				////////
				$.post('../j/insertarVarios.php',{
					n_empresa: data,
					n_contrato:cContrato,
					d_fechaini:cFechaInicio,
					d_fechafin:cFechaFin,
					c_observaciones: "",
					n_insertar: 5
					}).done(function(data){
						console.log(data);
					}).fail(function(){
					}).always(function(){
				});
				///////
				var listModulos=modulos.split(",");
				for (var i = 0; i < (listModulos.length-1); i++) {
					var nModulo = listModulos[i]
					$.post('../j/insertarVarios.php',{
						n_empresa: data,
						n_modulo:nModulo,
						n_insertar: 6
						}).done(function(data){
							console.log(data);
						}).fail(function(){
						}).always(function(){
					});
				};


			}).fail(function(){
			}).always(function(){
		});
	})
});