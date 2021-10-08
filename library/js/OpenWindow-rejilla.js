    function OpenWindow(url, form ,w ,h, Close , Scroll, CloseButton, formulario ){
        parent.OpenWindow(url, form,w,h, Close, Scroll, CloseButton, formulario)
    }
    
    function cancelar(tabla='', param=''){
        event.preventDefault();
        console.log('parametros '+param);
        var objeto = {};

        if (param.length !== 0) {
            objeto['tabla'] += tabla;

            param.forEach(function(arreglo, index) {
                objeto[arreglo.campo] += arreglo.valor;
            });

            console.log(JSON.stringify(objeto));
            

            var datasend=JSON.stringify(objeto);
            $.ajax({ 
                type: "POST",
                url: "clean.php",
                cache: false,
                data: datasend,
                success: function(text){
                  console.log(text);

                    }
                });
        }

        parent.$('idOfDomElement').colorbox.close();
    }
    function NuevoInforme(oidestudio){
        parent.NuevoInforme(oidestudio);
    }
 
    $(document).ready(function() {
        $('#rejilla tbody tr').click(function() {
            $(this).addClass('bg-success').siblings().removeClass('bg-success');
        });        
    } );    