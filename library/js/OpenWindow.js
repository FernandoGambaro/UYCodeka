    function OpenWindow(url, form = "#frame_rejilla",w ,h, Close=true, Scroll=true, CloseButton=true, formulario=''){
        var n = form.includes("#");
        if(n<=0){
            form = "#"+form;
        }
        $.colorbox({
            overlayClose: Close,
            href: url, open: true,
            iframe:true, width:w, height:h,
            scrolling: Scroll,
            closeButton: CloseButton,
            onCleanup:function(){ 
                $(form).attr( 'src', function ( i, val ) { return val; });
                if(formulario!=''){
                $(formulario).submit();
                }
            }
        });
        if(Close==false){
            $("#cboxClose").remove();
        }

    }
    
    function cancelar(tabla='', param=''){
        event.preventDefault();
        var objeto = {};
        if (param.length !== 0) {
            objeto['tabla'] = tabla;
            $.each( param,function(index, value) {
                objeto[index] = value;
                console.log(index + ' ooo  '+ value);
            });

            var datasend=objeto;
            $.ajax({ 
                type: "POST",
                url: "clean.php",
                cache: false,
                data: datasend,
                success: function(text){
                console.log('text' + text);
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    console.log(XMLHttpRequest, textStatus, errorThrown);
                }
            });
        }

       // event.preventDefault();
        parent.$('idOfDomElement').colorbox.close();
    }

    $('#cboxClose').on('click', function (e) { 
        e.preventDefault();
        //parent.$('idOfDomElement').colorbox.close();
        window.parent.jQuery.colorbox.close();
        this.submit;
        });    

    $('.cancelar').on('click', function (e) { 
    e.preventDefault();
    //parent.$('idOfDomElement').colorbox.close();
    window.parent.jQuery.colorbox.close();
    this.submit;
    });
    
    $(document).ready(function() {
        $('#cancelar').on('click', function (e) { 
            e.preventDefault();
            parent.$('idOfDomElement').colorbox.close();
            });
        
        $('#rejilla tbody tr').click(function() {
            $(this).addClass('bg-success').siblings().removeClass('bg-success');
        });        
    } );    