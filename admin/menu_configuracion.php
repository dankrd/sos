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

?>
<!doctype html>
<html lang='es'>
<head>
<meta charset="utf-8">
  <meta name="viewport" 
    content="width=device-width, initial-scale=1, maximum-scale=1" />
<title>Menu de la Configuración S.O.S Ethos Soluciones de Software SA</title>
<link href="../c/s.css" rel="stylesheet" type="text/css">
<link href="../c/normalize.css" rel="stylesheet" type="text/css">
<script src="../j/jquery.js"></script>
<script src="../j/prefixfree.min.js"></script>
<script src="../j/funcionGeneral.js"></script>
</head>
<body>
    <header>
        <img src="../img/Ethos-Logo.png" id='logoEthos'>
        <img src="../img/SOS-Logo.png" id='logoSos'>
        <h3 id='nombreUsuario'><?php echo $row_miUsuario['c_nombre'].' ('.$row_miUsuario['c_razon_social'].')'; ?></h3>
    </header>
    <nav>
        <a href="../menu/menu.php" title="Click Para Devolver al Menú"><li><img src="../img/menu.ico"></li></a>
        <?php do{ ?>
        <a href="<?php echo $row_menu['c_archivo']; ?>" title="Click Para Abrir <?php echo utf8_encode($row_menu['c_nombre_funcion']); ?>"><li><img src="../img/<?php echo $row_menu['c_imagen']; ?>"></li></a>
        <?php }while ($row_menu = mysql_fetch_assoc($menu)); ?>
        <a href="<?php echo $logoutAction ?>" title='Click Para Salir de la Aplicación'><li><img src="../img/salir.ico"></li></a>
    </nav>
    <section id='contenedor'>
        <h2>Haga click en la opcion que desee configurar</h2>
        <ul id='subMenu'>
          <?php do{ ?>
          <a href="<?php echo $row_subMenu['c_archivo']; ?>" title="Click Para Abrir <?php echo utf8_encode($row_subMenu['c_nombre_funcion']); ?>">
            <li>
              <?php echo utf8_encode($row_subMenu['c_nombre_funcion']); ?>
              <img src="../img/<?php echo $row_subMenu['c_imagen']; ?>">
            </li>
          </a>
          <?php }while ($row_subMenu = mysql_fetch_assoc($subMenu)); ?>
        </ul>
    </section>
    <footer>
        &copy; 2013 Ethos Soluciones de Software SA <a href="terminos-condiciones.html" target="_blank">Terminos y Condiciones</a>
    </footer>
</body>
</html>
<?php
mysql_free_result($miUsuario);
?>
