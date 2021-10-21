<?php
require_once __DIR__ .'/../../common/funcionesvarias.php';   
require_once __DIR__ .'/../../common/fechas.php';   
require_once __DIR__ .'/../../common/sectores.php';   //Array con lista de los sectores del sistema
require_once __DIR__ .'/../../common/verificopermisos.php';   

require_once __DIR__ .'/../../library/conector/consultas.php';
use App\Consultas;


$obj = new Consultas('usuarios');

$obj->Select('codusuarios, nombre, apellido, telefono, celular, email, estado, tratamiento');


$search = explode(",","á,é,í,ó,ú,ñ,Á,É,Í,Ó,Ú,Ñ,Ã¡,Ã©,Ã­,Ã³,Ãº,Ã±,ÃÃ¡,ÃÃ©,ÃÃ­,ÃÃ³,ÃÃº,ÃÃ±");
$replace = explode(",","á,é,í,ó,ú,ñ,Á,É,Í,Ó,Ú,Ñ,á,é,í,ó,ú,ñ,Á,É,Í,Ó,Ú,Ñ");

// include header file

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
            if(strlen(trim($value)) >0){ 
                if(is_numeric($value)){
                    $obj->Where($key, $value);    
                }elseif($key=='celular' or $key=='telefono'){
                    $obj->Where('celular', $value, 'LIKE', '', '(');
                    $obj->Where('telefono', $value, 'LIKE', 'or',  ')');
                }else{
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
                if($key=='celular' or $key=='telefono'){
                    $obj->Where('celular', $value, 'LIKE', '', '(');
                    $obj->Where('telefono', $value, 'LIKE', 'or',  ')');
                }elseif(strlen(trim($value)) >0 and $value!='0'){ 
                    if(is_numeric($value)){
                        $obj->Where($key, $value);
                    }else{
                        $obj->Where($key, $value, 'LIKE');
                    }
                }   
            }        
        }

}

if($UserTpo!=100){
    $obj->Where('tratamiento', '100', '!=');
}

// for pagination purposes

$data=array_envia($data); 
$obj->Orden("estado" , "ASC");
$obj->Orden("apellido" , "DESC");

$paciente = $obj->Ejecutar();
//echo "<br>".$paciente["consulta"]."<br>-->";
$total_rows=$paciente["numfilas"];
$rows = $paciente["datos"];


// check if more than 0 record found
if($total_rows>=0){
?>
    <table class='table table-hover table-responsive table-bordered table-condensed' id="rejilla">
    <thead class="bg-primary">
        <tr>
            <th><?php echo _("Nombre");?></th>
            <th><?php echo _("Apellido");?></th>
            <th><?php echo _("Teléfono");?></th>            
            <th><?php echo _("Email");?></th>            
            <th><?php echo _("Tipo");?></th>
        </tr>
    </thead>
    <tbody>
    <input type="hidden" name="numfilas" id="numfilas" value="<?php echo $total_rows; ?>">

<?php
foreach($rows as $row){
    if ($row['estado'] ==1) { $fondolinea="danger"; } else { $fondolinea=""; }
    
        echo "<tr class=\"triggerRejilla ".$fondolinea."\" data-codusuarios=\"".$row['codusuarios']."\" >";


        echo "<td>$row[nombre]</td>";
        echo "<td>".str_replace($search, $replace, $row['apellido'])."</td>";
        echo "<td>".$row['telefono']." - ".$row['celular']." </td>";
        echo "<td>$row[email]</td>";

        echo "<td>".@$tipo[$row['tratamiento']]."</td>";

        echo "</tr>";
    }

    echo "</tbody>";
    echo "</table>";
}

// if there are no user
else{
    echo "<div>"._("No se encontraron registros"). " </div>";
    }
?>
