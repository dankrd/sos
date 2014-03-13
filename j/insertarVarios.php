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
  $salida='';
  switch ($_POST['n_insertar']) {
    case '1':
		mysql_select_db($database_sos, $sos);
		$insertSQL = sprintf("insert into funcionalidad_servicio (id_servicio, i_cod_funcionalidad, id_producto) values(%s, %s, 1)", GetSQLValueString($_POST['n_servicio'], "int"), GetSQLValueString($_POST['n_funcionalidad'], "int"));
		break;
    case '2':
		mysql_select_db($database_sos,$sos);
		$query_insert_empresa="INSERT INTO empresas(c_razon_social, n_tipo_entidad, n_estado, id_ciudad, c_direccion, c_telefono) VALUES ( %s , $s , 1 , $s , $s , $s )",
		GetSQLValueString($_POST['c_Nombre'],"text"),
		GetSQLValueString($_POST['c_Tipo'],"int"),
		GetSQLValueString($_POST['n_Ciudad'],"int"),
		GetSQLValueString($_POST['c_Direccion'],"text"),
		GetSQLValueString($_POST['c_Telefono'],"text"));
		mysql_query($query_insert_empresa,$sos) or die(mysql_error());
		/*mysql_select_db($database_sos, $sos);
		$query_modulos_entidades = sprintf("select distinct m.* from modulos_empresa me, modulos m where me.id_modulo=m.id and me.n_estado=1 and me.id_empresa= %s order by c_nombre_modulo", GetSQLValueString($_POST['n_entidad'], "int"));
		$modulos_entidades = mysql_query($query_modulos_entidades, $sos) or die(mysql_error());
		$row_modulos_entidades = mysql_fetch_assoc($modulos_entidades);
		$totalRows_modulos_entidades = mysql_num_rows($modulos_entidades);
		$salida= $salida."<option value='0' >Seleccione</option>";
		do{
		$salida= $salida."<option value='".$row_modulos_entidades['id']."' >".utf8_encode($row_modulos_entidades['c_nombre_modulo'])."</option>";
		}while ($row_modulos_entidades = mysql_fetch_assoc($modulos_entidades));*/
     	break; 
    case '3':
		mysql_select_db($database_sos,$sos);
		$query_insert_productosEmpresa="INSERT INTO productos_empresas(id_empesa, id_producto, n_estado) VALUES (%s,%s,1)",
		GetSQLValueString($_POST['n_enmpresa'],"int"),
		GetSQLValueString($_POST['n_producto'],"int"));
		mysql_query($query_insert_productosEmpresa,$sos) or die(mysql_error());

		/*mysql_select_db($database_sos, $sos);
		$query_funcionalidades = sprintf("select * from funcionalidades where n_estado=1 and id_modulo=%s order by c_nombre_funcionalidad;", GetSQLValueString($_POST['n_modulo'], "int"));
		$funcionalidades = mysql_query($query_funcionalidades, $sos) or die(mysql_error());
		$row_funcionalidades = mysql_fetch_assoc($funcionalidades);
		$totalRows_funcionalidades = mysql_num_rows($funcionalidades);
		$salida= $salida."<option value='0' >Seleccione</option>";
		do{
		$salida= $salida."<option value='".$row_funcionalidades['id']."' >".utf8_encode($row_funcionalidades['c_nombre_funcionalidad'])."</option>";
		}while ($row_funcionalidades = mysql_fetch_assoc($funcionalidades));*/
    	break;     
    case '4':
		mysql_select_db($database_sos,$sos);
		$query_insert_contactosEmpresas="INSERT INTO contactos_empresas(id_empresa, c_nombres, c_apellidos, id_cargo, n_estado, c_email) VALUES ($s,$s,$s,$s,$s,$s)",
		GetSQLValueString($_POST('n_empresa'),"int"),
		GetSQLValueString($_POST('c_nombreEmpresa'),"text"),
		GetSQLValueString($_POST('c_apellidosEmpresa'),"text"),}
		GetSQLValueString($_POST('c_cargo'),"text"),
		GetSQLValueString($_POST('n_estado'),"int"),
		GetSQLValueString($_POST('c_email'),"text"));
		mysql_query($query_insert_contactosEmpresas,$sos) or die(mysql_error());
   		break;
   	case '5':
   		mysql_select_db($database_sos,$sos);
   		$query_insert_contratosEmpresa="INSERT INTO contratos_empresas(id_empresa, id_tipo_contrato, f_fecha_inicial,f_fecha_final, n_estado, c_observaciones) VALUES (%s,%s,%s,%s,1,%s)",
   		GetSQLValueString($_POST('n_empresa'),"int"),
   		GetSQLValueString($_POST('n_contrato'),"int"),
   		GetSQLValueString($_POST('d_fechaini'),"data"),
   		GetSQLValueString($_POST('d_fechafin'),"data"),
   		GetSQLValueString($_POST('c_observaciones'),"text"));
		mysql_query($query_insert_contratosEmpresa,$sos) or die(mysql_error());
		break;
	case '6':
		mysql_select_db($database_sos,$sos);
		$query_insert_modulosEmpresa="INSERT INTO modulos_empresa(id_empresa, id_modulo, n_estado) VALUES (%s,%s,1)",
		GetSQLValueString($_POST('n_empresa'),"int"),
		GetSQLValueString($_POST('n_modulo'),"int"));
    default:
      # code...
      break;
  }
  mysql_select_db($database_sos, $sos);
  $Result1 = mysql_query($insertSQL, $sos) or die(mysql_error());
  echo $Result1;
}
?>



