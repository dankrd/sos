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
function insertarEmpresa(){
	var cNombre=$("#cNombre").val();
	var nTipo=$("#cTipo").val();
	var nCiudad=$("#nCiudad").val();
	var cDireccion=$("#cDireccion").val();
	var cTelefono=$("#cTelefono").val();
	var productosr=$('input:radio[name=productos]:checked').val();
	var productosc=new Array();
	$('input:radio[name=productoscheck]:checked').each(function() {
		productosc.push($(this).val());
	});
	console.log(productosr);
	console.log(productosc);
	var cContrato=$("#cContrato").val();
	var cFechaInicio=$("#cFechaInicio").val();
	var cFechaFin=$("#cFechaFin").val();
	var cContactos=$("#cContactos").val();
		 /* iterate through array or object */
	//echo $cNombre.'-'.$nTipo.'-'.$nCiudad.'-'.$cDireccion.'-'.$cTelefono.'-'.$productos.'-';
	/*if(isset($_POST['5'])){
	 //echo "-5-";
	}
	//echo INSERT INTO `productos_empresas`(`id`, `id_empesa`, `id_producto`, `n_estado`) VALUES ([value-1],[value-2],[value-3],[value-4]);

	$ids = array (); 
	mysql_select_db($database_sos, $sos);
	                $query="SELECT * FROM modulos";
	                $result=mysql_query($query,$sos);
	                while ($row=mysql_fetch_array($result))
	                {
	                	if(isset($_POST[$row['id']])){
	                		$ids[]=$row['id'];
	                		//echo $_POST[$row['id']];
	                	}else{
	                		$ids[]='-';
	                	}
	                }
	                for($t=0;$t<count($ids);$t++){
	   //             	echo $ids[$t];
	                }
	                */
	
}
