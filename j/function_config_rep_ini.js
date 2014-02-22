function validar(){
	if($('#nombreReporte').val().length==0){
		alert('Debe digitar algun nombre para el reporte!!');
		return false;
	}
	if($('#columnasMostrar').val().length==0){
		alert('Debe digitar el nombre de las columnas a mostrar en el reporte!!');
		return false;
	}
	if($('#descripcinoReporte').val().length==0){
		alert('Para una mejor comprensión del reporte, debe colocar una pequeña descripción del mismo!!');
		return false;
	}
	if($('#script').val().length==0){
		alert('Hey, debes colocar el script del cual saldra el reporte, que crees, que se hace por arte de magia?');
		return false;
	}
	return true;
}