/*
funciones de javascript para comprobar formularios
creadas por: Duilio Palacios
e-mail: solo@otrotiempo.com
Licencia: CreativeCommons
Modificado por Fernando Gámbaro fernandogambaro@gmail.com 12/2020
En la definición de cada campo, en el id la primer letra indica la verificación a realizar, t, n, z, q, r, e
Para el caso de los select el valor tiene que ser diferente de '' (nulo) y mayor igual a cero
En los textarea no pueden ser nulo
En el ID poner W para que no sea obligatorio
*/
function validar(formulario,mandar) {
	var campos  = $(formulario).find("input");
	var listaErrores = '';
	var listadoError='Tiene los siguientes errores:<br>';
	
	modificado = esModificado();

	campos.each(function(){

		var campo = new clsCampo( $(this) );

		if( campo.type == "text" )
			if ( !( campo.esObligatorio() && campo.vacio() ) ) {					
			  switch ( campo.tipo ) {
				case 't': campo.soloTexto(); break;
				case 'n': campo.natural(); break;
				case 'z': campo.entero(); break;
				case 'q': campo.realPositivo(); break;
				case 'r': campo.numeroReal(); break;
				case 'e': campo.correo(); break;
			  }
			}
		else if ( ( campo.type == "file" ) || ( campo.type == "password" ) )
			if ( !modificado && campo.esObligatorio() ) campo.vacio();
			if ( campo.error ){
			listadoError=listadoError+"<br>"+ campo.error;
				if(typeof showWarningToast !== 'function') {
					listaErrores+=listadoError;
					return false;
				}
			}
	
	});

	campos = $(formulario).find("textarea");
	campos.each(function(){
		var campo = new clsCampo( $(this) );
		if ( campo.esObligatorio() && campo.vacio() ) {
		listadoError=listadoError+"<br>"+ campo.error;
			if(typeof showWarningToast !== 'function') {
				listaErrores+=listadoError;
				return false;
			}
		}
	});
	campos = $(formulario).find("select");
	campos.each(function(){
		var campo = new clsCampo( $(this) );
		if ( campo.esObligatorio() && !campo.estaSeleccionado() ) {
		listadoError=listadoError+"<br>"+ campo.error;
			if(typeof showWarningToast !== 'function') {
				listaErrores+=listadoError;
				//showToast(listadoError,'error');
				return false;
			}
		}
	});

	if(typeof showWarningToast === 'function') {
		if (listadoError.length>33) {
			showToast(listadoError,'error');
			listaErrores+=listadoError;
			return false;
	    
	   }
	}

	formValidoShow=listadoError.length;
	if (formValidoShow<=33 && mandar ) enviar(formulario);
	
	//return formValido;
	if(listaErrores!=''){
		showToast(listadoError,'error');
		
		return false;
	}
	return true;

}
/***/
function clsCampo (campo) {
	if(campo!='undefined'){
		//console.log(campo[0]);
		this.campo = campo[0];
	//	this.campo.value = campo.value;
		this.type = this.campo.type;
		this.tipo = this.campo.id.charAt(0).toLowerCase();
		this.error = false;
	}
}
clsCampo.prototype.esObligatorio = function esObligatorio() {
	var chr = this.campo.id.charAt(0);
	//console.log(chr);
	if ( chr.search('[A-Z]') || (chr == 'W') ) return false;
	return true;
}
clsCampo.prototype.vacio = function vacio() {
	valor = trim(this.campo.value);
	if ( valor.length!=0 ) return false;
	this.error = 'Falta "'+this.formatoNombre()+'"';
	return true;
}
clsCampo.prototype.natural = function natural() {
	if( this.campo.value.search('[^0-9]') == -1 ) return true;
	this.error = this.formatoNombre()+'" solo puede tener numeros enteros sin signo';
	return false;
}
clsCampo.prototype.entero = function entero() {
	if( this.campo.value.search('^-?[0-9]+$') != -1 ) return true;
	this.error = this.formatoNombre()+'" solo puede tener numeros enteros';
	return false;
}
clsCampo.prototype.realPositivo = function realPositivo() {
	if( this.campo.value.search('[^0-9.]') == -1 ) return true;
	this.error = this.formatoNombre()+'" solo puede tener numeros sin signo';					 
	return false;
}
clsCampo.prototype.numeroReal = function numeroReal() {
	if( this.campo.value.search('[^0-9.-]') == -1 ) return true;
	this.error = this.formatoNombre()+'" solo puede tener numeros';
	return false;
}
clsCampo.prototype.soloTexto = function soloTexto() {
	if( this.campo.value.search('^[a-z A-Z]+$') != -1 ) return true;
	this.error = this.formatoNombre()+'" solo puede tener texto';
	return false;
}
clsCampo.prototype.correo = function correo() {
	if( this.campo.value.toLowerCase().search('(^[a-z][a-z0-9\-_.]+[@][a-z0-9\-_.]+[.][a-z]+$)') != -1 ) return true;
	this.error =this.formatoNombre()+'" debe ser un correo valido';
	return false;
} 
clsCampo.prototype.estaSeleccionado = function estaSeleccionado() {
	var valor = parseInt(this.campo.options[this.campo.selectedIndex].value);
	if ( isNaN(valor) || valor || this.campo.selectedIndex==0 ) return true;
	this.error =  'Elija un valor en "'+this.formatoNombre()+'"';
	return false;
}
/***/
clsCampo.prototype.formatoNombre = function formatoNombre() {
	nombre = this.campo.id;
	return nombre.charAt(1).toUpperCase()+nombre.replace(/_/g,' ').substr(2);
}
function enviar(formulario) {	
//	formulario.boton.setAttribute('disabled','disabled');
	formulario.submit();
}
function esModificado() {
	if ( parseInt($('#id')[0].value ) ) return true;
	else return false;
}
function trim(str) {
	return str.replace(/^\s*|\s*$/g,"");
}
