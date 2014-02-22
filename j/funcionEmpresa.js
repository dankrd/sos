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