var cont=true;
$(document).ready(function() {
	$("#visualizar").click(function() {
		if(cont==true){
			$( "#modulos" ).show( "blind", "slow" );
			$( "#visualizar" ).val("Ocultar Modulos");
			cont=false;	
		}else{
			$( "#modulos" ).effect( "blind", "slow" );
			$( "#visualizar" ).val("Visualizar Modulos");
			cont=true;	
		}
	});
});
$(document).ready(function(){
	$("#agregarPersona").click(function(){
		$('#tablaPersonas').css("display","block");
		var cTabla= $("#cPersonasContacto");
		var cNombre= $("#cNombrePersona").val();
		var cCargo= $("#cCargoContacto").val();
		var cEmail= $("#cEmailContacto").val();
		var cTel= $("#cTelefonoContacto").val();
		
		var cFila="<tr>"+
		"<td>"+cNombre+"</td>"+
		"<td>"+cCargo+"</td>"+
		"<td>"+cEmail+"</td>"+
		"<td>"+cTel+"</td>"+"</tr>";
		cTabla.append(cFila);
	})
});
$(document).ready(function(){
	$("#csCiudad").click(function(){
		$('#placeholderc').css("display","none");
	})
});
$(document).ready(function(){
	$("#csTipo").click(function(){
		$('#placeholderte').css("display","none");
	});
});
$(document).ready(function(){
	$("#csContrato").click(function(){
		$('#placeholdertc').css("display","none");
	});
});

