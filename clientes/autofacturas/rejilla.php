<?php
require_once __DIR__ .'/../../common/funcionesvarias.php';   
require_once __DIR__ .'/../../common/fechas.php';   

require_once __DIR__ .'/../../library/conector/consultas.php';
use App\Consultas;


$obj = new Consultas('autofacturas');
$obj->Select('clientes.nombre as nombre,autofacturas.codautofactura ,autofacturas.fecha,
totalfactura, estado, autofacturas.tipo, autofacturas.activa,
autofacturas.semanafacturacion, autofacturas.diafacturacion, autofacturas.accion,
autofacturas.moneda, autofacturas.enviada, clientes.empresa,clientes.apellido');


$obj->Join('codcliente', 'clientes', 'INNER', '', 'codcliente' ) ;


$search = explode(",","á,é,í,ó,ú,ñ,Á,É,Í,Ó,Ú,Ñ,Ã¡,Ã©,Ã­,Ã³,Ãº,Ã±,ÃÃ¡,ÃÃ©,ÃÃ­,ÃÃ³,ÃÃº,ÃÃ±");
$replace = explode(",","á,é,í,ó,ú,ñ,Á,É,Í,Ó,Ú,Ñ,á,é,í,ó,ú,ñ,Á,É,Í,Ó,Ú,Ñ");

// include header file
$page_title = "Listado facuras programadas";
include_once "header-rejilla.php";

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
            } elseif($key=='page' and $value!=''){
                $page=$value;
            }         
        }

}

$data=array_envia($data); 

$obj->Orden('autofacturas.borrado', 'ASC');    
$obj->Orden("empresa" , "ASC");
$obj->Limit($from_record_num, $records_per_page);

$paciente = $obj->Ejecutar();
//echo "<br>".$paciente["consulta"]."<br>-->";
$total_rows=$paciente["numfilas"];
$rows = $paciente["datos"];

// include pagination file
include_once 'pagination.php';

// check if more than 0 record found
if($total_rows>=0){

?>
<div class="table-responsive">
    <table class='table table-hover table-responsive table-bordered table-condensed ' id="rejilla">
    <thead class="bg-primary">
        <tr>
            <th class="fit"><?php echo _('EST..');?></th>
            <?php
            $leer=verificopermisos('Prog.AutoFacturas', 'leer', $UserID);
            $modificar=verificopermisos('Prog.AutoFacturas', 'modificar', $UserID);
            $eliminar=verificopermisos('Prog.AutoFacturas', 'eliminar', $UserID);            

            
             if ($modificar=="true") { ?>
            <th colspan="2" class="fit"><?php echo _('ACCIÓN.');?></th>
            <?php } else { ?>
            <th class="fit">&nbsp;</th>							
            <?php } ?>
            <th>&nbsp;<?php echo _('NOMBRE/RAZÓN SOCIAL');?></th>
            <th><?php echo _('SE FACTURA');?></th>
            <th colspan="2" class="fit" style="text-align:center"><?php echo _('IMPORTE');?></th>
            <?php         // delete user button
		        if ( $UserTpo == 100 or $eliminar=="true") {
            ?>
            <th class="fit"></th>
            <?php } ?>
        </tr>
    </thead>
    <tbody>
    <input type="hidden" name="numfilas" id="numfilas" value="<?php echo $total_rows; ?>">

<?php

$moneda = array();
$semana = array(1=>"1º", 2=>"2º", 3=>"3º", 4=>"4º");
$dia = array(1=>"Lunes", 2=>"Martes", 3=>"Miércoles", 4=>"Jueves", 5=>"Viernes");
$accion = array(1=>"Solo registrar", 2=>"Registrar y emitir", 3=>"Registrar y enviar", 4=>"Registrar, emitir y enviar");


$obj_monedas = new Consultas('monedas');
$obj_monedas->Select();
$obj_monedas->Where('orden', '3', '<');
$obj_monedas->Where('borrado', '0');
$obj_monedas->Orden('orden', 'ASC');

$monedas=$obj_monedas->Ejecutar();
$listadomonedas=$monedas['datos'];

$xmon=1;

foreach($listadomonedas as $Lalista){
    $descripcion=explode(" ", $Lalista['simbolo']);
    $moneda[$xmon]= $descripcion[0];    
    $xmon++;
}
$totalmoneda=[];

foreach($rows as $row){
    if ($row['borrado'] ==1) { $fondolinea="danger"; } else { $fondolinea=""; }

        echo "<tr class=\"btn-inverse trigger ".$fondolinea."\" data-codautofactura=\"".$row['codcliente']."\" >";
        ?>
        <td class="aIzquierda"><div align="left">&nbsp;&nbsp;
            <?php
                $checked="";
                if($row['activa']==1){ $leer="checked"; }else{ $leer='';} ?>
                <label><input class="checkbox1" type="checkbox" disabled <?php echo $leer;?>>
				<span></span></label>
                    
            </div>
        </td>
        <?php
        echo "<td>";
        // Modificar datos de la factura
        if ( $modificar=="true") {
            echo "<button onclick=\"OpenWindow('edit.php?codautofactura=" . $row['codautofactura'] . "', 'frame_rejilla','98%','98%')\" 
            class='btn btn-warning left-margin btn-xs'>";
            echo "<span class='glyphicon glyphicon-edit'></span> Editar";
            echo "</button>";
        }  
        echo "</td>"; 
        echo "<td>";
        //Realizo facturación manual
            echo "<button onclick=\"OpenWindow('autofactura.php?codautofactura=" . $row['codautofactura'] . "', 'frame_rejilla','300','200')\" 
            class='btn btn-secondary left-margin btn-xs'>";            
            echo '<span class="fa-stack"><i class="fa fa-shopping-cart" aria-hidden="true"></i></span>';
            echo "</button>"; 
        echo "</td>";        
        
        echo "<td class=\"fit\">";
        $Descripcion=$row['nombre']." ".$row['apellido'];
        if($row['empresa']!='') {
            $Descripcion=$row['empresa'];
        }
        echo $Descripcion;
        echo "</td>";
        echo "<td>". $semana[$row['semanafacturacion']]." ".$dia[$row['diafacturacion']].
        " - ".$accion[$row['accion']]. "</td>";
        $totalmoneda[$row['moneda']]+=$row['totalfactura'];
        echo '<td align="right"><b>'.$moneda[$row['moneda']].'</b></td>
        <td align="right">&nbsp;'.number_format($row['totalfactura'],2,",","."). "</td>";
        // delete user button
		if ( $UserTpo == 100 or $eliminar=="true") {
            echo "<td>";
            echo "<a href='#' class='btn btn-danger delete-object btn-xs' data-dismiss='modal'>";
            echo "<span class='glyphicon glyphicon-remove' onClick=\"eliminar('" . $row['codautofactura'] . "','autofacturas');\"></span>";
            echo "</a>";
            echo "</td>";        
        } 
        echo "</tr>";
    }

    echo "</tbody>";
    echo "</table>";
    echo "</div>";
}

// if there are no user
else{
    echo "<div>". _('No se encontraron registros.'). "</div>";
    }
?>
<script type="text/javascript">
parent.$('#moneda1').html('<?php echo $totalmoneda[1];?>');
parent.$('#moneda2').html('<?php echo $totalmoneda[2];?>');
</script>