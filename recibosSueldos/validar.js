function validar()
{
    event.preventDefault();
    
	if ($("#codusuarios").val().length==0)
	{
     	showToast('Debe ingresar nombre', "error");
		$("#Ausuarios").focus();
		return 0;
    } 
	if ($("#Acodcliente").val().length==0)
	{
     	showToast('Debe ingresar cliente', "error");
		$("#Acliente").focus();
		return 0;
    }    
	if ($("#fecha").val().length==0)
	{
     	showToast('Debe ingresar fecha', "error");
		$("#fecha").focus();
		return 0;
    }
	if ($("#horasminutos").val()=='00:00')
	{
     	showToast('Debe ingresar/seleccionar horas', "error");
		$("#horaini").focus();
		return 0;
    }    
	$("form").submit();   
}