/*
funciones de javascript para comprobar formularios
creadas por: Duilio Palacios
e-mail: solo@otrotiempo.com
Licencia: CreativeCommons
*/

var listadoError='';

function validar(formulario,mandar) {
	listadoError='Tiene los siguientes errores:<br>';
	/*
Defino la letra inicial de cada name de los input como letra de control
T= texto;
N=número natural
Z=número entero	
Q=número positivo
R=número real
E=email
	*/
	
$('#'+formulario).on('input', 'select', 'textarea').each(function(index){  
        var input = $(this);
        var Obligatorio=esObligatorio(input); 
        if (input.prop('type')!='select-one' && Obligatorio!=false) {
			  switch (Obligatorio ) {
				case 'T': soloTexto(input); break;
				case 'N': natural(input); break;
				case 'Z': entero(input); break;
				case 'Q': realPositivo(input); break;
				case 'R': numeroReal(input); break;
				case 'E': correo(input); break;
			  }        		

        }
        if (input.prop('type')=='select-one' && Obligatorio!=false) {
        	selected(input );

        }
    });
	if (listadoError!='Tiene los siguientes errores:<br>') {
		 showWarningToast(listadoError);
		 return false;
	} else {
		if (mandar==true) {
		$('form#'+formulario).submit();
		return true;
		}
	}
};

function esObligatorio(input) {
	var upperCase= new RegExp('[A-Z]');
	var upp=input.prop('name').substr(0,1);
	if(upp.match(upperCase)){
		return upp;
	} else {
		return false;
	}

}

function soloTexto(input) {
	if (input.val()!='') {
	var re = new RegExp('[a-zA-Z]');
	var letras=re.test(input.val());
		if(letras) return true;
		listadoError=listadoError+input.attr('data-alt')+': solo puede tener texto <br>';
		return false;
	}else {
	listadoError=listadoError+input.attr('data-alt')+': Campo obligatorio <br>';
	}
}

function selected(input) {
	var id=input.prop("id");
	if ($("#"+id+" option:selected" ).val()==0) {
	listadoError=listadoError+input.attr('data-alt')+': Seleccione una opción <br>';
		return false;
	}	else {
		return true;
	}
}

function entero(numero){
    if (isNaN(numero)){
        listadoError=listadoError+input.attr('data-alt')+': No es número <br>';
    } else {
        if (numero % 1 != 0) {
            listadoError=listadoError+input.attr('data-alt')+': No es Entero <br>';
        }
    }
}
function numeroReal(numero){
    if (isNaN(numero)){
        listadoError=listadoError+input.attr('data-alt')+': No es número <br>';
    } else {
        if (numero % 1 == 0) {
            listadoError=listadoError+input.attr('data-alt')+': No es número real <br>';
        }
    }
}



