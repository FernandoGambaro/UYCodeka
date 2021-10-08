    function ver_info(oid) {
        window.top.showModal(1);
        jQuery.ajax({
            type: "GET",
            url: "../../informes/informeApdf.php",
            data: {oid:oid },
            async: true,
            cache: false,
            beforeSend: function () {
            window.top.toastr.info('Generando informe');
            },				 
            success: function(respuesta) { 
            window.top.showModal(0);
            if (respuesta.estado==-1) {
                window.top.toastr.error('Error - no se puede generar informe');
            }else {
                if(respuesta.file!='' && typeof(respuesta.file) != "undefined"){
                var url = "../"+respuesta.file; 
                OpenWindow(url, '', '95%','95%', true, true, true)
                window.top.toastr.info('Mostrando informe');
                }else{
                    window.top.showModal(0);
                    window.top.toastr.error("Error - no se puede generar informe");
                }
            }
        },
            error: function() {
            window.top.showModal(0);
            window.top.toastr.error("Error - no se puede generar informe");
        }
        }); 			
	}
