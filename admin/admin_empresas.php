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
  <title>Administración de Empresas S.O.S Ethos Soluciones de Software SA</title>
  <link href="../c/s.css" rel="stylesheet" type="text/css">
  <link href="../c/normalize.css" rel="stylesheet" type="text/css">
</head>
<!--data-script es el script que tiene los datos de validacion del formulario-->
<body data-focus='nombreEmpresa' data-script='function_config_rep_ini.js'>
    <header>
        <img src="../img/Ethos-Logo.png" id='logoEthos'>
        <img src="../img/SOS-Logo.png" id='logoSos'>
        <h3 id='nombreUsuario'><?php echo $row_miUsuario['c_nombre'].' ('.$row_miUsuario['c_razon_social'].')'; ?></h3>
    </header>
    <nav>
      <a href=""><li id='nuevo'></li></a>
      <a href=""><li id='grabar'></li></a>
      <!--
      data-busca es el codigo para cargar los posibles campos a buscar
      data-resultado es la pagina a donde va a ir a buscar
      data-colocar es donde va a colocar el resultado que encontro
      -->
      <a><li id='buscar' data-busca='1' data-resultado='../j/buscar-empresa.php' data-colocar='' ></li></a>
      <a href=""><li id='reporte'></li></a>
      <a href=""><li id='imprimir'></li></a>
      <a href=""><li id='cancelar'></li></a>
      <a href="<?php echo $logoutAction ?>" title='Click Para Salir de la Aplicación'><li><img src="../img/salir.ico"></li></a>
    </nav>
    <section id='contenedor'>
      <ul id='ctrol_pestana'>
        <li id='gral'>General</li>
        <li id='modul'>Modulos</li>
        <li id='contra'>Contratos</li>
        <li id='ctos'>Contactos</li>
        <li id='consol'>Consolidado</li>
      </ul>

      <fieldset id='pestanas_conten'>
        <form name="formulario_insert" id="formulario_insert" class="pantallaCompleta">
        <!--<form method="post" action="<?php echo $editFormAction; ?>" name="formulario_insert" id="formulario_insert" class="pantallaCompleta">-->
          <section class='pantalla_completa' id='pesta_gral'>
            <label for='nombreEmpresa' class='tercio'>Nombre de la Empresa
              <input type='text' name='cNombre' id='cNombre' placeholder='Ingrese el nombre de la empresa' title='Coloque Aqui el nombre de la empresa'>
            </label>
            <label for="tipoEmpresa" class="tercio">Tipo de empresa
              <select name="cTipo" id="cTipo">
                <option value="0">Seleccione</option>
                <?php
                mysql_select_db($database_sos, $sos);
                  $query="SELECT * FROM tipo_empresa";
                  $result=mysql_query($query,$sos);
                  while ($row=mysql_fetch_array($result))
                  {
                  echo'<option VALUE="'.$row['id'].'">'.utf8_encode($row['c_tipo_empresa']).'</option>';
                  }?>
              </select>
            </label>
            <label for="ciudadEmpresa" class="tercio">Ubicacion de la empresa
              <select name="nCiudad" id="nCiudad">
                <option value="0">Seleccione</option>
                <?php
                mysql_select_db($database_sos, $sos);
                  $query="SELECT * FROM ciudades ORDER BY 2";
                  $result=mysql_query($query,$sos);
                  while ($row=mysql_fetch_array($result))
                  {
                    //echo $row['id'];
                  echo'<option VALUE="'.$row['id'].'">'.$row['c_nombre_ciudad'].'</option>';
                  }?>
              </select>
            </label>
            <label for="dirreccion" class="tercio">Direcion de laempresa
              <input type='text' name='cDireccion' id='cDireccion' placeholder='Ingrese la direccion de la empresa' title='Coloque Aqui la direccion de la empresa'>
            </label>
            <label for="telefono" class="tercio">Telefono de la empresa
              <input type="tel" name="cTelefono" id="cTelefono" placeholder="digite el numero de la empresa">
            </label>
            <hr size="20" />
            <label for="producto" class="octavo">Tipo Producto</label><br>
              <?php
              //<input type="radio" name="group1" value="Milk"> Milk<br>
              $cont=0;
                mysql_select_db($database_sos, $sos);
                  $query="SELECT * FROM tipo_producto";
                  $result=mysql_query($query,$sos);
                  while ($row=mysql_fetch_array($result))
                  {
                    if($row['c_nombre_producto']=="Portal Web"){
                      echo '<input type="checkbox" name="productoscheck"  id="'.$row['id'].'">'.$row['c_nombre_producto'].'<br>';
                    }else{
                      echo '<input type="radio" name="productos" id="'.$row['id'].'" value="'.$row['c_nombre_producto'].'">'.$row['c_nombre_producto'].'<br>';  
                    }
                    
                  }?>
            
          </section>
          <section class='pantalla_completa' id='pesta_modul'>
            <ul id='modulos'>
           <?php
            $cont=0;
              mysql_select_db($database_sos, $sos);
                $query="SELECT * FROM modulos";
                $result=mysql_query($query,$sos);
                while ($row=mysql_fetch_array($result))
                {
                  echo '<li><input type="checkbox" name="modulos"  id="'.$row['id'].'" value="'.$row['c_nombre_modulo'].'">'.$row['c_nombre_modulo'].'</li>';
                }?>
            </ul>
          </section>
          <section class='pantalla_completa' id='pesta_contra'>
            <label for="contrato" class="tercio"> Tipo de contrato
              <select name="cContrato" id="cContrato" >
              <option value="0" default selected>Seleccione</option>
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
            <label for="fechaInicio" class="tercio">Fecha Inicio:
              <input type="date" name="cFechaInicio" id="cFechaInicio" >
            </label>
            <label for="fechaFin" class="tercio">Fecha de Finalizacion:
              <input type="date" name="cFechaFin" id="cFechaFin" >
            </label>
            
            
          </section>
          <section class='pantalla_completa' id='pesta_ctos'>
            <label for="personasContacto" class="tercio">Personas de Contacto:
              <input type="text" name="cNombrePersona" id="cNombrePersona" placeholder="Ingrese el nombre de la persona de contacto">
              <input type="tel" name="cApellidoPersona" id="cApellidoPersona" placeholder="Ingrese el apellido de la persona de contacto">
              <input type="text" name="cCargoContacto" id="cCargoContacto" placeholder="Cargo de la persona de contacto">
              <input type="email" name="cEmailContacto" id="cEmailContacto" placeholder="Email de la persona de contacto">
              <input type="button" id="agregarPersona" name="agregarPersona" value="Agregar Persona">
              <input type="text" name="cContactos" id="cContactos" style="display:none;">
            </label>
            <fieldset class='pantallaCompleta' id="tablaPersonas" style="display:none;">
             
                <table id="">
                    <tr>
                      <th>Nombre</th>
                      <th>Apellido</th>
                      <th>Cargo</th>
                      <th>Email</th>
                    </tr>
                </table>
                <table id="cPersonasContacto"> </table>       
            </fieldset>
            <input type="Button" name="recorrer" id="recorrer" value="recorrer"></input>
          </section>
          <section class='pantalla_completa' id='pesta_consol'>
            <input type="Button" name="insertar" id="insertar" value="Insertar">
          </section>
        </form>
      </fieldset>        
    </section>
    <footer>
        &copy; 2013 Ethos Soluciones de Software SA <a href="terminos-condiciones.html" target="_blank">Terminos y Condiciones</a>
    </footer>
  <script src="../j/jquery.js"></script>
  <script src="../j/prefixfree.min.js"></script>
  <script src="../j/funcionGeneral.js"></script>
  <script src="../j/funcionEmpresa.js"></script>    
  <script src="../j/funcionServicio.js"></script>
</body>
</html>
<?php
mysql_free_result($miUsuario);
?>


