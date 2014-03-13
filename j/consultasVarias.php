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

if(isset($_POST['n_consulta'])){
  $salida='';
  switch ($_POST['n_consulta']) {
    case '1':
      mysql_select_db($database_sos, $sos);
      $query_tipo_empresa = sprintf("select c_tipo_empresa from empresas e, tipo_empresa te where e.n_tipo_entidad=te.id and e.id= %s", GetSQLValueString($_POST['n_entidad'], "int"));
      $tipo_empresa = mysql_query($query_tipo_empresa, $sos) or die(mysql_error());
      $row_tipo_empresa = mysql_fetch_assoc($tipo_empresa);
      $totalRows_tipo_empresa = mysql_num_rows($tipo_empresa);
      $salida = $row_tipo_empresa['c_tipo_empresa'];
      break;
    case '2':
      mysql_select_db($database_sos, $sos);
      $query_modulos_entidades = sprintf("select distinct m.* from modulos_empresa me, modulos m where me.id_modulo=m.id and me.n_estado=1 and me.id_empresa= %s order by c_nombre_modulo", GetSQLValueString($_POST['n_entidad'], "int"));
      $modulos_entidades = mysql_query($query_modulos_entidades, $sos) or die(mysql_error());
      $row_modulos_entidades = mysql_fetch_assoc($modulos_entidades);
      $totalRows_modulos_entidades = mysql_num_rows($modulos_entidades);
      $salida= $salida."<option value='0' >Seleccione</option>";
      do{
        $salida= $salida."<option value='".$row_modulos_entidades['id']."' >".utf8_encode($row_modulos_entidades['c_nombre_modulo'])."</option>";
      }while ($row_modulos_entidades = mysql_fetch_assoc($modulos_entidades));
      break; 
    case '3':
      mysql_select_db($database_sos, $sos);
      $query_funcionalidades = sprintf("select * from funcionalidades where n_estado=1 and id_modulo=%s order by c_nombre_funcionalidad", GetSQLValueString($_POST['n_modulo'], "int"));
      $funcionalidades = mysql_query($query_funcionalidades, $sos) or die(mysql_error());
      $row_funcionalidades = mysql_fetch_assoc($funcionalidades);
      $totalRows_funcionalidades = mysql_num_rows($funcionalidades);
      $salida= $salida."<option value='0' >Seleccione</option>";
      do{
        $salida= $salida."<option value='".$row_funcionalidades['id']."' >".utf8_encode($row_funcionalidades['c_nombre_funcionalidad'])."</option>";
      }while ($row_funcionalidades = mysql_fetch_assoc($funcionalidades));
      break;  
    case '4':
      mysql_select_db($database_sos, $sos);
      $query_funcionalidades_insertadas = sprintf("select fs.id, fs.i_cod_funcionalidad, f.c_nombre_funcionalidad, f.n_estado, m.c_nombre_modulo from funcionalidad_servicio fs, funcionalidades f, modulos m where fs.i_cod_funcionalidad=f.id and fs.id_servicio=%s and f.id_modulo=m.id order by m.c_nombre_modulo, f.c_nombre_funcionalidad", GetSQLValueString($_POST['n_servicio'], "int"));
      $funcionalidades_insertadas = mysql_query($query_funcionalidades_insertadas, $sos) or die(mysql_error());
      $row_funcionalidades_insertadas = mysql_fetch_assoc($funcionalidades_insertadas);
      $totalRows_funcionalidades_insertadas = mysql_num_rows($funcionalidades_insertadas);
      $salida= $salida."<table>";
      $salida= $salida."<tr><th>Modulos</th><th>Funcionalidades</th></tr>";
      do{
        $salida= $salida."<tr><td>".$row_funcionalidades_insertadas['c_nombre_modulo']."</td><td>".utf8_encode($row_funcionalidades_insertadas['c_nombre_funcionalidad'])."</td></tr>";
      }while ($row_funcionalidades_insertadas = mysql_fetch_assoc($funcionalidades_insertadas));
      $salida= $salida."<table>";
      break;
    case '5':
      mysql_select_db($database_sos, $sos);
      $query_buscar_empresa = sprintf("SELECT * FROM empresas where c_razon_social = %s",GetSQLValueString($_POST['cNombre'],"text"));
      $buscar_empresa = mysql_query($query_buscar_empresa,$sos) or die(mysql_error()); 
      $row_buscar_empresa= mysql_fetch_array($buscar_empresa);
      $salida=$row_buscar_empresa['id'];
      break;
    default:
      # code...
      break;
  }
echo $salida;
}
?>



