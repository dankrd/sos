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
		$query_insert_funcionalidadServicio=sprintf("INSERT INTO funcionalidad_servicio(i_cod_funcionalidad, id_producto) VALUES (%s,%s)",
			GetSQLValueString($_POST['n_funcionalidad'],"int"),
			GetSQLValueString($_POST['n_producto']));
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



