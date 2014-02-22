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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "formulario_insert")) {
  $insertSQL = sprintf("INSERT INTO reportes_home (c_nombre_reporte, n_aplica, c_columnas_mostrar, c_descripcion, c_script) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['nombreReporte'], "text"),
                       GetSQLValueString($_POST['aplica'], "int"),
                       GetSQLValueString($_POST['columnasMostrar'], "text"),
                       GetSQLValueString($_POST['descripcinoReporte'], "text"),
                       GetSQLValueString($_POST['script'], "text"));

  mysql_select_db($database_sos, $sos);
  $Result1 = mysql_query($insertSQL, $sos) or die(mysql_error());
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

?>
<!doctype html>
<html lang='es'>
<head>
<meta charset="utf-8">
  <meta name="viewport" 
    content="width=device-width, initial-scale=1, maximum-scale=1" />
<title>Reportes Home S.O.S Ethos Soluciones de Software SA</title>
<link href="../c/s.css" rel="stylesheet" type="text/css">
<link href="../c/normalize.css" rel="stylesheet" type="text/css">
<script src="../j/jquery.js"></script>
<script src="../j/prefixfree.min.js"></script>
<script src="../j/function_config_rep_ini.js"></script>
<script src="../j/funcionGeneral.js"></script>
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
      <a href="javascript:close();" title='Click Para Salir de la Aplicación'><li><img src="../img/salir.ico"></li></a>
      <a href="<?php echo $logoutAction ?>" title='Click Para Salir de la Aplicación'>.</a>
    </nav>
    <section id='contenedor'>
      <h3>Reportes Home</h3>
        <form method="post" action="<?php echo $editFormAction; ?>" name="formulario_insert" id="formulario_insert" class="pantallaCompleta">
          <article>En esta Función se administraran los diferentes reportes que se pueden ver en el home.</article>
          <label for='nombreReporte' class='tercio'>Nombre del Reporte
            <input name='nombreReporte' id='nombreReporte' type='text' placeholder='Escriba el nombre del reporte'>
          </label>
          <label for='aplica' class='tercio'>Aplica a:
            <select id='aplica' name='aplica'>
              <option value='0'>Solo Ethos</option>
              <option value='1'>A Todos</option>
            </select>
          </label>
          <label for='columnasMostrar' class='tercio'>Columnas a Mostrar:
            <input name='columnasMostrar' id='columnasMostrar' type='text' placeholder='Escriba La Columnas que Desea Ver'>
          </label>
          <label for='descripcionReporte' class='medio'>Descripción del Reporte:
            <textarea id='descripcinoReporte' name='descripcinoReporte'  placeholder='Escriba aqui una explicación breve del reporte!'></textarea>
          </label>
          <label for='script' class='medio'>Script del Reporte:
            <textarea id='script' name='script' placeholder='Escriba aqui el majestuoso script que mostrara el reporte que usted desea ver en el Home!'></textarea>
          </label>
          <input type="hidden" name="MM_insert" value="formulario_insert">
        </form>
    </section>
    <footer>
        &copy; 2013 Ethos Soluciones de Software SA <a href="terminos-condiciones.html" target="_blank">Terminos y Condiciones</a>
    </footer>
</body>
</html>
<?php
mysql_free_result($miUsuario);
?>
