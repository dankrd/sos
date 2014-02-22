<?php require_once('../Connections/sos.php'); ?>
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

$colname_miUsuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_miUsuario = $_SESSION['MM_Username'];
}
mysql_select_db($database_sos, $sos);
$query_miUsuario = sprintf("SELECT u.c_nombre, e.c_razon_social FROM usuarios u,empresas e WHERE  u.id_empresa=e.id and e.n_estado=1 and u.n_estado=1 and u.c_login =%s", GetSQLValueString($colname_miUsuario, "text"));
$miUsuario = mysql_query($query_miUsuario, $sos) or die(mysql_error());
$row_miUsuario = mysql_fetch_assoc($miUsuario);
$totalRows_miUsuario = mysql_num_rows($miUsuario);

mysql_select_db($database_sos, $sos);
$query_menu = sprintf("SELECT f.* FROM usuarios u,empresas e, opciones o, perfiles p, funciones f
WHERE  u.id_empresa=e.id and e.n_estado=1 and u.n_estado=1 and p.id=u.n_perfil and p.id=o.id_perfil
and f.id=o.id_funcion and o.n_estado=1 and f.n_padre=0 and u.c_login =%s", GetSQLValueString($colname_miUsuario, "text"));
$menu = mysql_query($query_menu, $sos) or die(mysql_error());
$row_menu = mysql_fetch_assoc($menu);
$totalRows_menu = mysql_num_rows($menu);

mysql_select_db($database_sos, $sos);
$query_subMenu = sprintf("SELECT f.* FROM usuarios u,empresas e, opciones o, perfiles p, funciones f
WHERE  u.id_empresa=e.id and e.n_estado=1 and u.n_estado=1 and p.id=u.n_perfil and p.id=o.id_perfil
and f.id=o.id_funcion and o.n_estado=1 and f.n_padre=1 and u.c_login =%s", GetSQLValueString($colname_miUsuario, "text"));
$subMenu = mysql_query($query_subMenu, $sos) or die(mysql_error());
$row_subMenu = mysql_fetch_assoc($subMenu);
$totalRows_subMenu = mysql_num_rows($subMenu);

mysql_select_db($database_sos, $sos);
$query_entidades = "select * from empresas where n_estado=1 order by c_razon_social";
$entidades = mysql_query($query_entidades, $sos) or die(mysql_error());
$row_entidades = mysql_fetch_assoc($entidades);
$totalRows_entidades = mysql_num_rows($entidades);

mysql_select_db($database_sos, $sos);
$query_criticidad = "select * from criticidad where n_estado=1 order by 1";
$criticidad = mysql_query($query_criticidad, $sos) or die(mysql_error());
$row_criticidad = mysql_fetch_assoc($criticidad);
$totalRows_criticidad = mysql_num_rows($criticidad);

mysql_select_db($database_sos, $sos);
$query_tipo_servicio = "select * from tipo_servicio where n_estado=1 order by 1";
$tipo_servicio = mysql_query($query_tipo_servicio, $sos) or die(mysql_error());
$row_tipo_servicio = mysql_fetch_assoc($tipo_servicio);
$totalRows_tipo_servicio = mysql_num_rows($tipo_servicio);

mysql_select_db($database_sos, $sos);
$query_nuevo_servicio = "select max(id) from servicios";
$nuevo_servicio = mysql_query($query_nuevo_servicio, $sos) or die(mysql_error());
$row_nuevo_servicio = mysql_fetch_assoc($nuevo_servicio);
$totalRows_nuevo_servicio = mysql_num_rows($nuevo_servicio);

?>
<!doctype html>
<html lang='es'>
<head>
<meta charset="utf-8">
  <meta name="viewport" 
    content="width=device-width, initial-scale=1, maximum-scale=1" />
  <title>Administración de Servicios S.O.S Ethos Soluciones de Software SA</title>
  <link href="../c/normalize.css" rel="stylesheet" type="text/css">
  <link href="../c/s.css" rel="stylesheet" type="text/css">
<style type="text/css">
  .messages{
    float: left;
    font-family: sans-serif;
    display: none;
    font-size: 0.6em;
  }
  .info{
    padding: 10px;
    border-radius: 10px;
    background: orange;
    color: #fff;
    font-size: 18px;
    text-align: center;
  }
  .before{
    padding: 10px;
    border-radius: 10px;
    background: blue;
    color: #fff;
    font-size: 18px;
    text-align: center;
  }
  .success{
    padding: 10px;
    border-radius: 10px;
    background: green;
    color: #fff;
    font-size: 18px;
    text-align: center;
  }
  .error{
    padding: 10px;
    border-radius: 10px;
    background: red;
    color: #fff;
    font-size: 18px;
    text-align: center;
  }
</style>  
</head>
<body data-focus='nombreReporte' data-script='function_config_rep_ini.js'>
    <header>
        <img src="../img/Ethos-Logo.png" id='logoEthos'>
        <img src="../img/SOS-Logo.png" id='logoSos'>
        <h3 id='nombreUsuario'><?php echo $row_miUsuario['c_nombre'].' ('.$row_miUsuario['c_razon_social'].')'; ?></h3>
    </header>
    <nav>
      <a href=""><li id='nuevo'></li></a>
      <a href=""><li id='grabar'></li></a>
      <a href=""><li id='buscar'></li></a>
      <a href=""><li id='reporte'></li></a>
      <a href=""><li id='imprimir'></li></a>
      <a href=""><li id='cancelar'></li></a>
      <a href="<?php echo $logoutAction ?>" title='Click Para Salir de la Aplicación'><li><img src="../img/salir.ico"></li></a>
    </nav>
    <section id='contenedor'>
      <fieldset class='pantalla_completa'>
        <legend>Creación de Servicios</legend>
        <form name="formulario_insert" id="formulario_insert" enctype="multipart/form-data" class="formulario">
          <label for="" class='octavo'>Fecha Radicado
            <input id='' name='' type="text" value='<?php echo date("Y/m/d");?>'>
          </label>
          <label for="n_servicio" class='octavo'>No. de Servicio
            <input id='n_servicio' name='n_servicio' type="text" value='<?php echo $row_nuevo_servicio['max(id)']+1; ?>'>
          </label>
          <label for="n_entidad" class="tercio">Entidad:
            <select id='n_entidad' name='n_entidad'>
              <option value='0'>Seleccione la Entidad</option>
              <?php do{?>
                <option value='<?php echo $row_entidades['id']; ?>'><?php echo $row_entidades['c_razon_social']; ?></option>
              <?php }while ($row_entidades = mysql_fetch_assoc($entidades)); ?>
            </select>
          </label>
          <label for="c_tipo_entidad" class='octavo'>Tipo de Entidad:
            <input type="text" id='c_tipo_entidad' readonly>
          </label>
          <label for="" class="octavo">Tipo Servicio:
            <select>
              <option value='0'>Seleccione</option>
              <?php do{?>
                <option value='<?php echo $row_tipo_servicio['id']; ?>'><?php echo utf8_encode($row_tipo_servicio['c_descripcionTipo']); ?></option>
              <?php }while ($row_tipo_servicio = mysql_fetch_assoc($tipo_servicio)); ?>
              <option value='99'>Crear</option>
            </select>
          </label>
          <label for="" class="octavo">Criticidad:
            <select>
              <?php do{ ?>
              <option value='<?php echo $row_criticidad['id']; ?>'><?php echo $row_criticidad['c_nombreCriticidad']; ?></option>
              <?php }while ($row_criticidad = mysql_fetch_assoc($criticidad)); ?>              
              <option value='3'>Crear</option>
            </select>
          </label>
          <fieldset class='medio'>
            <legend>Funcionalidades Afectadas</legend>
            Para una mejor comprensión del servicio por favor ingrese las funcionalidades que se ven afectadas<p>
            <input type="hidden" id='n_funcionalidades_afe' value='0'>
            <label for="n_modulo"  class='tercio'>Modulo:
              <select name="n_modulo" id="n_modulo">
                <option value="0">Seleccione</option>
              </select>
            </label>
            <label for="n_funcionalidad" class='medio'>Funcionalidad:
              <select name="n_funcionalidad" id="n_funcionalidad">
                <option value="0">Seleccione</option>
              </select>
            </label>
            <img src="../img/reporte.ico" alt="" id='InsertarFuncionalidades'>
            <div id='funcionalidades'>
            </div>
          </fieldset>
          <fieldset class='medio'>
            <legend>Archivos Soporte del Servicio</legend>
            Anexe documentos que puedan ayudar a la solución de este servicio.
            <input name="archivo" type="file" id="imagen" />
            <input type="button" value="Subir imagen" /><br />
            <!--div para visualizar mensajes-->
            <div class="messages"></div><br /><br />
            <!--div para visualizar en el caso de imagen-->
            <div class="showImage"></div>            
          </fieldset>
          <label for="" class='tercio'>Procedimiento Realizado
            <textarea id='c_procedimiento' placeholder='Explique detallamente el procemiento que esta realizando.'></textarea>
          </label>
          <label for="" class='tercio'>Resultado Presentado
            <textarea id='c_procedimiento' placeholder='Sea preciso en el resultado.'></textarea>
          </label>
          <label for="" class='tercio'>Resultado Esperado
            <textarea id='c_procedimiento' placeholder='Sea lo mas detallado posible, en el resultado que deberia arrojar el software.'></textarea>
          </label>
          <input type="hidden" name="MM_insert" value="formulario_insert">
        </form>
      </fieldset>        
    </section>
    <footer>
        &copy; 2013 Ethos Soluciones de Software SA <a href="terminos-condiciones.html" target="_blank">Terminos y Condiciones</a>
    </footer>
  <script src="../j/jquery.js"></script>
  <script src="../j/prefixfree.min.js"></script>
  <script src="../j/function_config_rep_ini.js"></script>
  <script src="../j/funcionGeneral.js"></script>
  <script src="../j/funcionServicio.js"></script>
  <script src="../j/functions.js"></script>
</body>
</html>
<?php
mysql_free_result($miUsuario);
?>


