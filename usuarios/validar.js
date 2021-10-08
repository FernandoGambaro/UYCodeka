function selectAll(selectBox,selectAll) { 
    // have we been passed an ID 
    if (typeof selectBox == "string") { 
        selectBox = document.getElementById(selectBox);
    } 
    // is the select box a multiple select box? 
    if (selectBox.type == "select-multiple") { 

        for (var i = 0; i < selectBox.options.length; i++) { 
             selectBox.options[i].selected = selectAll; 
        } 
    }
}

function valEmail(valor){    
   re=/^\w+([\.\+\-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/
   if(!re.exec(valor))    {
       return false;
   }else{
       return true;
   }
}

function validar(envio)
{
    event.preventDefault();
    
	if ($("#nombre").val().length==0)
	{
     	showErrorToast('Debe ingresar nombre');
		$("#nombre").focus();
		return 0;
    } 
    
	if ($("#oidmutualista").val().length==0)
	{
     	showErrorToast('Debe ingresar Mutualista');
		$("#oidmutualista").focus();
		return 0;
	}
	document.form.submit();   
}