var cont=0;
$(document).ready(function(){
	$("#agregarPersona").click(function(){
		$('#tablaPersonas').css("display","block");
		var cTabla= $("#cPersonasContacto");
		var cNombre= $("#cNombrePersona").val();
		var cApellido= $("#cApellidoPersona").val();
		var cCargo= $("#cCargoContacto").val();
		var cEmail= $("#cEmailContacto").val();
		var cContacto=$('#cContactos').val();
		if(cEmail.indexOf('@')>=0){
			var cFila="<tr>"+
			"<td>"+cNombre+"</td>"+
			"<td>"+cApellido+"</td>"+
			"<td>"+cCargo+"</td>"+
			"<td>"+cEmail+"</td>"+"</tr>";
			$('#cContactos').val(cContacto+"-"+cNombre+"/"+cApellido+"/"+cEmail+"/"+cCargo);
			cTabla.append(cFila);
				
		}
		
	})
});
$(document).ready(function(){
	$("#recorrer").click(function() {
		$("#cPersonasContacto tbody tr").each(function(index) {
			var campo1, campo2,campo3,campo4;
			$(this).children('td').each(function(index2) {
				switch (index2){
					case 0:
						campo1=$(this).text();
						break;
					case 1:
						campo2=$(this).text();
						break;
					case 2:
						campo3=$(this).text();
						break;
					case 3:
						campo4=$(this).text();
						break;
				}
				$(this).css("background-color","#ECF8E0");
			});
			console.log(campo1+'-'+campo2+'-'+campo3+'-'+campo4);
		});
	});
});

var activarPestana = {
	'background' : 'white',
    'z-index' : '2',
    'box-shadow' : 'rgba(180,180,180,0.5) 2px -2px 2px'
}
$(document).on("ready", inicia);

function inicia(){
	$('#ctrol_pestana li').on('click',mostrarPestana);
	$('#gral').css(activarPestana);
	$('#pesta_gral').css('display','inline-block');
}
function mostrarPestana(datos){
	var inactivarPestana = {
		'background' : '#d4d2d2',
        'z-index' : '0',
        'box-shadow' : 'none'
	}
	$('#ctrol_pestana li').css(inactivarPestana);
	$('#pestanas_conten form section').css('display','none');
	var pestana = datos.currentTarget.id;
	$('#'+pestana).css(activarPestana);
	if(pestana=='consol'){
		$('#pestanas_conten form section').css('display','inline-block');
	}else{
		$('#pesta_'+pestana).css('display','inline-block');
	}
}

