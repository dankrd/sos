<?php require_once('../Connections/sos.php'); ?>
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

mysql_select_db($database_sos, $sos);
$query_Recordset1 = "SELECT * FROM funciones";
$Recordset1 = mysql_query($query_Recordset1, $sos) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);
?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['cUsuario'])) {
  $loginUsername=$_POST['cUsuario'];
  $password=md5($_POST['cContrasena']);
  $MM_fldUserAuthorization = "";
  $MM_redirectLoginSuccess = "menu/menu.php";
  $MM_redirectLoginFailed = "index.php?e=1";
  $MM_redirecttoReferrer = false;
  mysql_select_db($database_sos, $sos);
  
  $LoginRS__query=sprintf("SELECT u.c_login, u.c_contra FROM usuarios u,empresas e WHERE  u.id_empresa=e.id and e.n_estado=1 and u.n_estado=1 and u.c_login =%s AND u.c_contra=%s",
    GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $sos) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
     $loginStrGroup = "";
    
  if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;       

    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];  
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
}
?>
<!doctype html>
<html lang='es'>
<head>
<meta charset="utf-8">
  <meta name="viewport" 
    content="width=device-width, initial-scale=1, maximum-scale=1" />
<title>Buscar Empresa Home S.O.S Ethos Soluciones de Software SA</title>
<link href="../c/s.css" rel="stylesheet" type="text/css">
<link href="../c/normalize.css" rel="stylesheet" type="text/css">
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
      <h3>Informacion de La empresa</h3>
      	
      	
        <form name="search" id="search" method="post" accept-charset="utf-8" action="listadoempresas.php" >
        	<label for="ciudadEmpresa" class="cuarto">Ubicacion de la empresa
	            <select name="csCiudad" id="cfCiudad">
	            	<option value="0" id="placeholderc" default selected>Seleccione la ciudad</option>
	              <?php
	              	mysql_select_db($database_sos, $sos);
	                $query="SELECT * FROM ciudades ORDER BY 2";
	                $result=mysql_query($query,$sos);
	                while ($row=mysql_fetch_array($result))
	                {
	                  	echo'<option VALUE="'.$row['id'].'">'.$row['c_nombre_ciudad'].'</option>';
	                
	                }?>
	            </select>
	        </label>
	        <label for="tipoEmpresa" class="cuarto">Tipo de empresa
	            <select name="csTipo" id="cTipo">
	            <option value="0" id="placeholderte" default selected>Seleccione tipo de empresa</option>
	              <?php
	              mysql_select_db($database_sos, $sos);
	                $query="SELECT * FROM tipo_empresa";
	                $result=mysql_query($query,$sos);
	                $validation=0;
	                while ($row=mysql_fetch_array($result))
	                {
	                	echo'<option VALUE="'.$row['id'].'">'.utf8_encode($row['c_tipo_empresa']).'</option>';
	                }?>
	            </select>
	          </label>
	        <label for="contrato" class="cuarto"> Tipo de contrato
	            <select name="csContrato" id="cContrato">
	            <option value="0" id="placeholdertc" default selected>Seleccione tipo de contrato</option>
	              <?php
	              mysql_select_db($database_sos, $sos);
	                $query="SELECT * FROM tipo_contratos";
	                $result=mysql_query($query,$sos);
	                while ($row=mysql_fetch_array($result))
	                {
	                  	echo'<option VALUE="'.$row['id'].'">'.$row['c_tipo_contrato'].'</option>';
	                }?>
	            </select>
	          </label>
            <label for="boton" class="cuarto">
              <input type="submit" name="filtrar" id="filtrar" value="Filtrar">
            </label>
            
        </form>
    </section>
    <footer>
        &copy; 2013 Ethos Soluciones de Software SA <a href="terminos-condiciones.html" target="_blank">Terminos y Condiciones</a>
    </footer>
    <script src="../j/jquery.js"></script>
    <script src="../j/prefixfree.min.js"></script>
    <script src="../j/function_config_rep_ini.js"></script>
    <script src="../j/funcionGeneral.js"></script>
    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
    <script type="text/javascript" src="../j/javascript.js"></script>
    <script src="https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
</body>

</html>