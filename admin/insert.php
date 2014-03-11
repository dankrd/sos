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



$cNombre=$_POST['cNombre'];
$nTipo=$_POST['cTipo'];
$nCiudad=$_POST['nCiudad'];
$cDireccion=$_POST['cDireccion'];
$cTelefono=$_POST['cTelefono'];
/*mysql_select_db($database_sos, $sos);
                $query="INSERT INTO empresas(c_razon_social, n_tipo_entidad, n_estado, id_ciudad, c_direccion, c_telefono) 
                VALUES ('"+$cNombre+"',"+$nTipo+",1,"+nCiudad+",'"+cDireccion"','"+cTelefono"')";*/
                echo "INSERT INTO empresas(c_razon_social, n_tipo_entidad, n_estado, id_ciudad, c_direccion, c_telefono) 
                VALUES ('".$cNombre."',".$nTipo.",1,".$nCiudad.",'".$cDireccion."','".$cTelefono."')";
                mysql_select_db($database_sos, $sos);
                  $query="SELECT * FROM empresas where c_razon_social like '".$cNombre."'";
                  $result=mysql_query($query,$sos);
                  while ($row=mysql_fetch_array($result))
                  {
                  echo'<option VALUE="'.$row['id'].'">'.$row['c_tipo_contrato'].'</option>';
                  }
$productos=$_POST['productos'];
//echo $cNombre.'-'.$nTipo.'-'.$nCiudad.'-'.$cDireccion.'-'.$cTelefono.'-'.$productos.'-';
if(isset($_POST['5'])){
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
$cContrato=$_POST['cContrato'];
$cFechaInicio=$_POST['cFechaInicio'];
$cFechaFin=$_POST['cFechaFin'];
//echo $cContrato.'-'.$cFechaInicio.'-'.$cFechaFin.'-';
$cContactos=$_POST['cContactos'];

$cListaContactos= explode('-', $cContactos);
for ($i = 1; $i < count($cListaContactos); $i++) {
    $cContacto = explode('/',$cListaContactos[$i]);
    /*echo $cContacto[0].'-';
    echo $cContacto[1].'-';
    echo $cContacto[2].'-';
    echo $cContacto[3].'-';*/
}
?>
