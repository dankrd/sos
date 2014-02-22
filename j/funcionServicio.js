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