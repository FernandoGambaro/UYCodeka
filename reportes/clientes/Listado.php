<?php
require_once __DIR__ .'/../../common/funcionesvarias.php';   
require_once __DIR__ .'/../../common/fechas.php';   

require_once __DIR__ .'/../../library/conector/consultas.php';
use App\Consultas;


$obj = new Consultas('clientes');
$obj->Select();

$service=isset($_GET['service']) ? $_GET['service'] : '';

// for pagination purposes
$page = isset($_GET['page']) ? $_GET['page'] : 1; // page is the current page, if there's nothing set, default is page 1
$records_per_page = 10; // set records or rows of data per page
$from_record_num = ($records_per_page * $page) - $records_per_page; // calculate for the query limit clause

$alldata="";
$data=array();
$alldata=isset($_GET['alldata']) ? $_GET['alldata'] : null ;
$alldata=array_recibe($alldata);

//var_dump($alldata);

if(is_array($alldata) && !empty($alldata)){
	foreach ($alldata as $key => $value)
	{
		if (!empty($_GET[$key])) {
			$$key=$_GET[$key];
		} else {
		$temp = is_array($value) ? $value : trim($value);
	    $$key = $temp;
        $data[$$key]=$temp;
            if(strlen(trim($value)) >0 and $key=='fechaini'){ 
                $obj->Where('fecha', explota($value)." 00:00:00", '>=');    
            }elseif(strlen(trim($value)) >0 and $key=='fechafin'){ 
                $obj->Where('fecha', explota($value)." 00:00:00", '<=');    
            } elseif(strlen(trim($value)) >0 and $key=='nombre') {          
                if(strlen(trim($value)) >0){ 
                    $obj->Where('nombre', $value, 'LIKE', '', '(');    
                    $obj->Where('apellido', $value, 'LIKE', 'or', ')');    
                }   
            } else{          
                if(strlen(trim($value)) >0){ 
                    $obj->Where($key, $value, 'LIKE');    
                }   
            }         
        }
	}
	$data=$alldata;
} else {
        foreach($_REQUEST as $key => $value){
            if($key!='page' and $key!='alldata'){
                $temp = is_array($value) ? $value : trim($value);
                $data[$key] = $$key = $temp;
                if(strlen(trim($value)) >0 and $key=='fechaini'){ 
                    $obj->Where('fecha', explota($value)." 00:00:00", '>=');    
                }elseif(strlen(trim($value)) >0 and $key=='fechafin'){ 
                    $obj->Where('fecha', explota($value)." 00:00:00", '<=');    
                } elseif(strlen(trim($value)) >0 and $key=='nombre') {          
                    if(strlen(trim($value)) >0){ 
                        $obj->Where('nombre', $value, 'LIKE', '', '(');    
                        $obj->Where('apellido', $value, 'LIKE', 'or', ')');    
                    }   
                } else{          
                    if(strlen(trim($value)) >0){ 
                        $obj->Where($key, $value, 'LIKE');    
                    }   
                }
            }         
        }

}

$data=array_envia($data); 

$obj->Where('borrado', '0');    
$obj->Orden("service" , "DESC");
$obj->Orden("empresa" , "ASC");
//$obj->Limit($from_record_num, $records_per_page);

$paciente = $obj->Ejecutar();
//echo "<br>".$paciente["consulta"]."<br>-->";
$total_rows=$paciente["numfilas"];
$rows = $paciente["datos"];

?>
<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title></title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../../library/bootstrap/bootstrap.min.css" />

    <link rel="stylesheet" href="../../library/js/jquery-ui.min.css" />

<link href="../../library/bootstrap/bootstrap.css" rel="stylesheet"/>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>

<link rel="stylesheet" href="../../library/toastmessage/jquery.toastmessage.css" type="text/css">
<script src="../../library/toastmessage/jquery.toastmessage.js" type="text/javascript"></script>
<script src="../../library/toastmessage/message.js" type="text/javascript"></script>

    <script src="../../library/js/OpenWindow-rejilla.js" type="text/javascript"></script>

    <script type="text/javascript" src="../../library/js/jquery.keyz.js"></script>

<!-- Cargo custom CSS-->
<link rel='stylesheet' type='text/css' href='../../library/estilos/style-theme.php?col=<?php echo $paleta;?>' />
<link href="../../library/estilos/customCSS.css" rel="stylesheet">
<!-- iconos para los botones -->       
<link rel="stylesheet" href="../../library/estilos/font-awesome.min.css">

<link rel="stylesheet" type="text/css" href="../../library/datatables/datatables.min.css"/>
 
<script type="text/javascript" src="../../library/datatables/datatables.min.js"></script>

<link rel="stylesheet" type="text/css" href="../../library/datatables/Bootstrap-4-4.1.1/css/bootstrap.min.css"/>
<script type="text/javascript" src="../../library/datatables/Bootstrap-4-4.1.1/js/bootstrap.min.js"></script>

<link rel="stylesheet" type="text/css" href="../../library/datatables/DataTables-1.10.18/css/jquery.dataTables.min.css"/>
<link rel="stylesheet" type="text/css" href="../../library/datatables/Buttons-1.5.6/css/buttons.dataTables.min.css"/>
<script type="text/javascript" src="../../library/datatables/Buttons-1.5.6/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="../../library/datatables/Buttons-1.5.6/js/buttons.print.min.js"></script>


<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>


<script type="text/javascript">

$(document).ready(function(){
    $('#listado').DataTable({
    "searching":false,
    "lengthChange": false,
    "bInfo" : false,
    "bPaginate": false,
    "autoWidth": false, 
    "order": [[ 4, "asc" ]],
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'print',
                text: 'Imprimir', 
                title:'<strong>Listado de clientes</strong>',
                autoprint:false,
                footer:true,
                orientation: 'landscape',
                pageSize: 'A4',
                message:'Impreso <?php echo fechaATexto(date("Y-m-d"));?>',
                customize: function ( win ) {
                    $(win.document.body).find('table').addClass('display').css('font-size', '9px');
                    $(win.document.body).find('tr:nth-child(odd) td').each(function(index){
                    $(this).css('background-color','#D0D0D0');
                    });
                    $(win.document.body).find('h1').css('text-align','center');                    
                    $(win.document.body)
                        .css( 'font-size', '10pt' )
                        .prepend(
                            '<img src="./library/images/central_min.png" style="position:absolute; top:0; right:0;" />'
                        );
                        $(win.document.body).find( 'table' )
                        .addClass( 'compact' )
                        .css( 'font-size', 'inherit' );
                        
                },
                exportOptions: {
                    modifier: {
                        selected: null
                    }
                },
                "footerCallback": function(row, data, start, end, display) {
                 var api = this.api();
                 var rcnt=0;
                  api.columns('.sum', {
                    page: 'current'
                  }).every(function() {
                    var sum = this
                      .data()
                      .reduce(function(a, b) {
                        var x = parseFloat(a) || 0;
                        var y = parseFloat(b) || 0;
                        return x + y;
                      }, 0);
                    console.log(sum); //alert(sum);

                    if(rcnt==0){
                        $("#foot").append('<td style="background:#a1eaed;color:black; text-align: center;">Total</td>');
                    }else{
                        $("#foot").append('<td style="background:#a1eaed;color:black; text-align: center;">'+sum+'</td>');
                    }
                    rcnt++;
                    //$(this.footer()).html(sum);
                  });
                }

            },
            { extend: 'copyHtml5',
                title:'Listado de clientes',
                message:'Impreso <?php echo fechaATexto(date("Y-m-d"));?>',
                footer: true },
            { extend: 'excelHtml5',
                title:'Listado de clientes',
                message:'Impreso <?php echo fechaATexto(date("Y-m-d"));?>',
                footer: true },
            { extend: 'csvHtml5', 
                title:'Listado de clientes>',
                message:'Impreso <?php echo fechaATexto(date("Y-m-d"));?>',
                footer: true },
            { extend: 'pdfHtml5', 
                title:'Listado de clientes',
                message:'Impreso <?php echo fechaATexto(date("Y-m-d"));?>',
                footer: true }            
        ]
    });
    

});
</script>

    </head>
    <body >
        <!-- container -->
        <div>
         <!-- For the following code look at footer.php -->
<?php
// check if more than 0 record found
if($total_rows>=0){
?>
<div class="table-responsive">
    <table class='table table-hover table-responsive table-bordered table-condensed display' id="listado" style="width:100%">
    <thead class="bg-primary">
        <tr>
            <th class="fit">&nbsp;<?php echo _('NOMBRE/RAZÓN SOCIAL');?></th>
            <th><?php echo _('DOC/RUT');?></th>
            <th><?php echo _('EMAIL');?></th>
            <th><?php echo _('TELÉFONO');?></th>
            <th><?php echo _('TIPO');?></th>
            <th><?php echo _('Hr.');?></th>
        </tr>
    </thead>
    <tbody>
<?php
/*Verifico los permisos del usuario logeado*/
$tipoCliente = array("Sin&nbsp;definir", "Común","Abonado&nbsp;A", "Abonado&nbsp;B");
$tiponif = array("", "RUT","CI", "Pasaporte");
foreach($rows as $row){

        echo "<tr class=\"btn-inverse trigger\" data-codcliente=\"".$row['codcliente']."\" >";

        echo "<td class=\"fit\">";
        $Descripcion=$row['nombre']." ".$row['apellido'];
        if($row['empresa']!='') {
            $Descripcion=$row['empresa'];
        }
        echo $Descripcion;
        echo "</td>";
        echo "<td>". $tiponif[$row['tiponif']]. '&nbsp;'. $row['nif']. "</td>";
        echo "<td>".$row['email']. "</td>";
        echo "<td>".$row['telefono'];
        if ($row['movil']!='') echo " / ". $row['movil'];
        echo "</td>";
        echo "<td>".$tipoCliente[$row['service']]. "</td>";
        echo "<td>";
        if ($row['horas']<>0 and $row['service']>1){ echo $row['horas']."hr";}
        echo "</td>";
 
        echo "</tr>";
    }
    ?>
            </tbody>
            <tfoot>
                <tr>
                <th class="fit">&nbsp;<?php echo _('NOMBRE/RAZÓN SOCIAL');?></th>
                <th><?php echo _('DOC/RUT');?></th>
                <th><?php echo _('EMAIL');?></th>
                <th><?php echo _('TELÉFONO');?></th>
                <th><?php echo _('TIPO');?></th>
                <th><?php echo _('Hr.');?></th>
                </tr>
            </tfoot>
        </table>
    <?php
    }

// if there are no user
else{
    echo "<div>". _('No se encontraron registros.'). "</div>";
    }
?>
