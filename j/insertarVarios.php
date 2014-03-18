<?php require_once('../Connections/sos.php'); ?>
<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);
	
  $logoutGoTo = "../index.php?e=3";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "../index.php?e=2";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

if(isset($_POST['n_insertar'])){
  $Result1 = '';
  switch ($_POST['n_insertar']) {
    case '1':
		mysql_select_db($database_sos, $sos);
		$insertSQL = sprintf("insert into funcionalidad_servicio (id_servicio, i_cod_funcionalidad, id_producto) values(%s, %s, 1)", GetSQLValueString($_POST['n_servicio'], "int"), GetSQLValueString($_POST['n_funcionalidad'], "int"));
		break;
    case '2':
		mysql_select_db($database_sos,$sos);
		$query_insert_empresa=sprintf("INSERT INTO empresas(c_razon_social, n_tipo_entidad, n_estado, id_ciudad, c_direccion, c_telefono) VALUES ( %s , %s , 1 , %s , %s , %s )",
		GetSQLValueString($_POST['c_Nombre'],"text"),
		GetSQLValueString($_POST['n_Tipo'],"int"),
		GetSQLValueString($_POST['n_Ciudad'],"int"),
		GetSQLValueString($_POST['c_Direccion'],"text"),
		GetSQLValueString($_POST['c_Telefono'],"text"));
		$Result1 = mysql_query($query_insert_empresa,$sos) or die(mysql_error());
     	break; 
    case '3':
		mysql_select_db($database_sos,$sos);
		$query_insert_productosEmpresa=sprintf("INSERT INTO productos_empresas(id_empesa, id_producto, n_estado) VALUES (%s,%s,1)",
		GetSQLValueString($_POST['n_empresa'],"int"),
		GetSQLValueString($_POST['n_producto'],"int"));
		$Result1 = mysql_query($query_insert_productosEmpresa,$sos) or die(mysql_error());
    	break;     
    case '4':
		mysql_select_db($database_sos,$sos);
		$query_insert_contactosEmpresas=sprintf("INSERT INTO contactos_empresas(id_empresa, c_nombres, c_apellidos, id_cargo, n_estado, c_email) VALUES (%s,%s,%s,%s,1,%s)",
		GetSQLValueString($_POST['n_empresa'],"int"), 
		GetSQLValueString($_POST['c_nombreContacto'],"text"),
		GetSQLValueString($_POST['c_apellidosContacto'],"text"),
		GetSQLValueString($_POST['c_cargo'],"text"),
		GetSQLValueString($_POST['c_email'],"text"));
		$Result1 = mysql_query($query_insert_contactosEmpresas,$sos) or die(mysql_error());
   		break;
   	case '5':
   		mysql_select_db($database_sos,$sos);
   		$query_insert_contratosEmpresa=sprintf("INSERT INTO contratos_empresas(id_empresa, id_tipo_contrato, f_fecha_inicial,f_fecha_final, n_estado, c_observaciones) VALUES (%s,%s,%s,%s,1,%s)",
   		GetSQLValueString($_POST['n_empresa'],"int"),
   		GetSQLValueString($_POST['n_contrato'],"int"),
   		GetSQLValueString($_POST['d_fechaini'],"date"),
   		GetSQLValueString($_POST['d_fechafin'],"date"),
   		GetSQLValueString($_POST['c_observaciones'],"text"));
		$Result1 = mysql_query($query_insert_contratosEmpresa,$sos) or die(mysql_error());
		break;
	case '6':
		mysql_select_db($database_sos,$sos);
		$query_insert_modulosEmpresa=sprintf("INSERT INTO modulos_empresa(id_empresa, id_modulo, n_estado) VALUES (%s,%s,1)",
		GetSQLValueString($_POST['n_empresa'],"int"),
		GetSQLValueString($_POST['n_modulo'],"int"));
		$Result1 = mysql_query($query_insert_modulosEmpresa,$sos) or die(mysql_error());
		break;
	case '7':
		mysql_select_db($database_sos,$sos);
		$query_insert_descripcionServicio=sprintf("INSERT INTO descripcion_servicio(id_servicio, n_tipo, c_descripcion) VALUES (%s,%s,%s)",
			GetSQLValueString($_POST['n_servicio'],"int"),
			GetSQLValueString($_POST['n_tipo'],"int"),
			GetSQLValueString($_POST['c_descripcion'],"text"));
		$Result1=mysql_query($query_insert_descripcionServicio,$sos) or die(mysql_error());
		break;
	case '8':
		mysql_select_db($database_sos,$sos);
		$query_insert_funcionalidades=sprintf("INSERT INTO funcionalidades(c_nombre_funcionalidad, n_estado) VALUES (%s,1)",
			GetSQLValueString($_POST['c_nombre'],"text"),);
		$Result1=mysql_query($query_insert_funcionalidades,$sos) or die(mysql_error());
		break;
	case '9':
		mysql_select_db($database_sos,$sos);
		$query_insert_tipoProducto=sprintf("INSERT INTO tipo_producto(c_nombre_producto) VALUES ($s)",
			GetSQLValueString($_POST['c_producto'],"text"));
		$Result1=mysql_query($query_insert_tipoProducto,$sos)
		break;
	case '10':
		mysql_select_db($database_sos,$sos);
		$query_insert_accesoReporte=sprintf("INSERT INTO acceso_reporte(id_tipo_acceso, n_usu_perfil, n_estado, n_orden) VALUES (%s,%s,1,%s)",
			GetSQLValueString($_POST['n_tAcceso'],"int"),
			GetSQLValueString($_POST['n_uPerfil'],"int"),
			GetSQLValueString($_POST['n_orden'],"int"));
		$Result1=mysql_query($query_insert_accesoReporte,$sos) or die(mysql_error());
		break;
	case '11':
		mysql_select_db($database_sos,$sos);
		$query_insert_archivoServicios=sprintf("INSERT INTO archivos_servicios(id_seguimiento, id_tipo_archivo, c_archivo, n_estado) VALUES (%s,%s,%s,1)",
			GetSQLValueString($_POST['n_seguimiento'],"int"),
			GetSQLValueString($_POST['n_tArchivo'],"int"),
			GetSQLValueString($_POST['c_archivo'],"text"));
		$Result1=mysql_query($query_insert_archivoServicios,$sos) or die(mysql_error());
		break;
	case '12':
		mysql_select_db($database_sos,$sos);
		$query_insert_avisame=sprintf("INSERT INTO avisame( id_usuario_avisar, i_tipo, c_descripcion, n_estado) VALUES ($s,$s,$s,1)",
			GetSQLValueString($_POST['n_usuarioAvisar'],"int"),
			GetSQLValueString($_POST['n_tipo'],"int"),
			GetSQLValueString($_POST['c_descripcion'],"text"));
		$Result1=mysql_query($query_insert_avisame,$sos) or die(mysql_error());
		break;
	case '13':
		mysql_select_db($database_sos,$sos);
		$query_insert_ciudades=sprintf("INSERT INTO ciudades(c_nombre_ciudad) VALUES ($s)",
			GetSQLValueString($_POST['c_ciudad'],"text"));
		$Result1=mysql_query($query_insert_ciudades,$sos) or die(mysql_error());
		break;
	case '14':
		mysql_select_db($database_sos,$sos);
		$query_insert_criticidad=sprintf("INSERT INTO criticidad(c_nombreCriticidad, n_estado) VALUES ($s,1)",
			GetSQLValueString($_POST['c_nombre'],"text"));
		$Result1=mysql_query($query_insert_criticidad,$sos) or die(mysql_error());
		break;
	case '15':
		mysql_select_db($database_sos,$sos);
		$query_insert_direccionEmpresa=sprintf("INSERT INTO direccion_empresas(id_empresa, c_direccion, c_barrio, id_ciudad, n_principal, n_estado) VALUES ($s,$s,$s,$s,$s,1)",
			GetSQLValueString($_POST['n_empresa'],"int"),
			GetSQLValueString($_POST['c_direccion'],"text"),
			GetSQLValueString($_POST['c_barrio'],"text"),
			GetSQLValueString($_POST['n_ciudad'],"int"),
			GetSQLValueString($_POST['n_principal'],"int"));
		$Result1=mysql_query($query_insert_direccionEmpresa,$sos) or die(mysql_error());
		break;
	case '16':
		mysql_select_db($database_sos,$sos);
		$query_insert_funciones=sprintf("INSERT INTO funciones(c_nombre_funcion, c_archivo, c_imagen, n_estado, n_padre) VALUES ($s,$s,$s,1,$s)",
			GetSQLValueString($_POST['c_nombre'],"text"),
			GetSQLValueString($_POST['c_archivo'],"text"),
			GetSQLValueString($_POST['c_imagen'],"text"),
			GetSQLValueString($_POST['n_padre'],"int"));
		$Result1=mysql_query($query_insert_funciones,$sos) or die(mysql_error());
		break;
	case '17':
		mysql_select_db($database_sos,$sos);
		$query_insert_modulos=sprintf("INSERT INTO modulos(c_nombre_modulo, n_estado) VALUES ($s,1)",
			GetSQLValueString($_POST['c_nombre'],"text"));
		$Result1=mysql_query($query_insert_modulos,$sos) or die(mysql_error());
		break;
	case '18':
		mysql_select_db($database_sos,$sos);
		$query_insert_opciones=sprintf("INSERT INTO opciones(id_funcion, id_perfil, n_estado) VALUES ($s,$s,1)",
			GetSQLValueString($_POST['n_funcion'],"int"),
			GetSQLValueString($_POST['n_perfil'],"int"));
		$Result1=mysql_query($query_insert_opciones,$sos) or die(mysql_error());
		break;
	case '19':
		mysql_select_db($database_sos,$sos);
		$query_insert_opcionBuscar=sprintf("INSERT INTO opcion_buscar(n_opcion_buscar, c_columna_buscar, c_nombre_columa_buscar, n_tipo_dato, c_tabla_buscar, c_columna_tabla) VALUES ($s,$s,$s,$s,$s,$s)",
			GetSQLValueString($_POST['n_opcBuscar'],"int"),
			GetSQLValueString($_POST['c_columna'],"text"),
			GetSQLValueString($_POST['c_nombreColumna'],"text"),
			GetSQLValueString($_POST['n_tDato'],"int"),
			GetSQLValueString($_POST['c_tBuscar'],"text"),
			GetSQLValueString($_POST['c_columTabla'],"text"));
		$Result1=mysql_query($query_insert_opcionBuscar,$sos) or die(mysql_error());
		break;
	case '20':
		mysql_select_db($database_sos,$sos);
		$query_insert_perfiles=sprintf("INSERT INTO perfiles(c_nombre_perfil) VALUES ($s)",
			GetSQLValueString($_POST['c_nPerfil'],"text"));
		$Result1=mysql_query($query_insert_perfiles,$sos) or die(mysql_error());
		break;
	case '21':
		mysql_select_db($database_sos,$sos);
		$query_insert_reporteHome=sprintf("INSERT INTO reportes_home(c_nombre_reporte, n_aplica, c_columnas_mostrar, c_descripcion, c_script) VALUES ($s,$s,$s,$s,$s)",
			GetSQLValueString($_POST['c_reporte'],"text"),
			GetSQLValueString($_POST['n_aplica'],"text"),
			GetSQLValueString($_POST['c_columnMostrar'],"text"),
			GetSQLValueString($_POST['c_descripcion'],"text"),
			GetSQLValueString($_POST['c_script'],"text"));
		$Result1=mysql_query($query_insert_reporteHome,$sos)or die(mysql_error());
		break;
	case '22':
		mysql_select_db($database_sos,$sos);
		$query_insert_seguimientoServicio=sprintf("INSERT INTO seguimiento_servicios(id_servicio, id_tipo, n_muestra_cliente, id_usuario_radica, id_usuario_asigna, c_descripcion, n_estado) VALUES ($s,$s,$s,$s,$s,$s,1)",
			GetSQLValueString($_POST['n_servicio'],"int"),
			GetSQLValueString($_POST['n_tipo'],"int"),
			GetSQLValueString($_POST['n_cliente'],"int"),
			GetSQLValueString($_POST['n_radica'],"int"),
			GetSQLValueString($_POST['n_asigna'],"int"),
			GetSQLValueString($_POST['c_descripcion'],"text"));
		$Result1=mysql_query($query_insert_seguimientoServicio,$sos)or die(mysql_error());
		break;
	case '23':
		mysql_select_db($database_sos,$sos);
		$query_insert_servicios=sprintf("INSERT INTO servicios(id_entidad, id_tipo_servicio, id_criticidad, f_fecha_programa, f_fecha_ingreso, f_fecha_cierre, n_estado_actual, n_asignado_actual) VALUES ($s,$s,$s,$s,$s,$s,$s,$s)",
			GetSQLValueString($_POST['n_entidad'],"int"),
			GetSQLValueString($_POST['n_tServicio'],"int"),
			GetSQLValueString($_POST['n_criticidad'],"int"),
			GetSQLValueString($_POST['f_fechaProg'],"date"),
			GetSQLValueString($_POST['f_fechaIngre'],"date"),
			GetSQLValueString($_POST['f_fechaCierre'],"date"),
			GetSQLValueString($_POST['n_estadoActual'],"int"),
			GetSQLValueString($_POST['n_asignadoActual'],"int"));
		$Result1=mysql_query($query_insert_servicios,$sos) or die(mysql_error());
		break;
	case '24':
		mysql_select_db($database_sos,$sos);
		$query_insert_telefonos=sprintf("INSERT INTO telefonos_empresas(id_empresa, c_telefono, id_ciudad, n_estado) VALUES ($s,$s,$s,1)",
			GetSQLValueString($_POST['n_empresa'],"int"),
			GetSQLValueString($_POST['c_telefono'],"text"),
			GetSQLValueString($_POST['n_ciudad'],"id"));
		$Result1=mysql_query($query_insert_telefonos,$sos) or die(mysql_error());
		break;
	case '25':
		mysql_select_db($database_sos,$sos);
		$query_insert_tipoCargos=sprintf("INSERT INTO tipo_cargos(c_tipo_cargo) VALUES ($s)",
			GetSQLValueString($_POST['c_cargo'],"text"));
		$Result1=mysql_query($query_insert_tipoCargos,$sos) or die(mysql_error());
		break;
	case '26':
		mysql_select_db($database_sos,$sos);
		$query_insert_tipoCargos=sprintf("INSERT INTO tipo_cargos(c_tipo_cargo) VALUES ($s)"
			GetSQLValueString($_POST['c_tCargo'],"text"));
		$Result1=mysql_query($query_insert_tipoCargos,$sos) or die(mysql_error());
		break;
	case '27':
		mysql_select_db($database_sos,$sos);
		$query_insert_tipoContratos=sprintf("INSERT INTO tipo_contratos(c_tipo_contrato, n_estado) VALUES ($s,1)",
			GetSQLValueString($_POST['c_tContrato'],"text"));
		$Result1=mysql_query($query_insert_tipoContratos,$sos);
		break;
	case '28':
		mysql_select_db($database_sos,$sos);
		$query_insert_tipoServicio=sprintf("INSERT INTO tipo_servicio(c_nombreTipoServicio, n_estado, c_descripcionTipo) VALUES ($s,1,$s)",
			GetSQLValueString($_POST['c_nombreServicio'],"text"),
			GetSQLValueString($_POST['c_descripcion'],"text"));
		$Result1=mysql_query($query_insert_tipoServicio,$sos);
		break;
	case '29':
		mysql_select_db($database_sos,$sos);
		$query_insert_usuarios=sprintf("INSERT INTO usuarios(c_nombre, c_login, c_contra, c_email, n_estado, n_perfil, id_empresa) VALUES ($s,$s,$s,$s,1,$s,$s)",
			GetSQLValueString($_POST['c_nombre'],"text"),
			GetSQLValueString($_POST['c_login'],"text"),
			GetSQLValueString($_POST['c_password'],"text"),
			GetSQLValueString($_POST['c_email'],"text"),
			GetSQLValueString($_POST['n_perfil'],"int"),
			GetSQLValueString($_POST['n_empresa'],"int"));
		$Result1=mysql_query($query_insert_usuarios,$sos);
		break;
    default:
      # code...
      break;
  }
  //mysql_select_db($database_sos, $sos);
  //$Result1 = mysql_query($insertSQL, $sos) or die(mysql_error());
  echo $Result1;
}
?>



