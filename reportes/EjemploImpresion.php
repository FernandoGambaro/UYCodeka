<?php
$path = (getenv('MPDF_ROOT')) ? getenv('MPDF_ROOT') : __DIR__;
require_once $path . '/../mpdf/vendor/autoload.php';

if(strlen($_GET['oid'])>0){
    $oid = $_GET['oid'];
}else{
    $oid=2;
}

require_once __DIR__ .'/../library/conector/consultas.php';
use App\Consultas;


    $obj = new Consultas('foto');
    $obj->Select();
    $obj->Where('oid', '12', '='); 
    $resultado = $obj->Ejecutar();
    $datos = $resultado["datos"][0];
    if($resultado["estado"]=="ok"){	
        $imagenHeader= $datos['fotocontent'];			
    }


    $obj = new Consultas('foto');
    $obj->Select();
    $obj->Where('oid', '13', '='); 
    $resultado = $obj->Ejecutar();
    $datos = $resultado["datos"][0];
    if($resultado["estado"]=="ok"){	
        $imagenFooter= $datos['fotocontent'];			
    }


$mpdf = new \Mpdf\Mpdf([
	'mode' => 'c',
	'margin_left' => 20,
	'margin_right' => 20,
	'margin_top' => 25,
	'margin_bottom' => 20,
	'margin_header' => 0,
	'margin_footer' => 5
]);
$mpdf->mirrorMargins = 1;	// Use different Odd/Even headers and footers and mirror margins

$html = '
<style>
textarea {
}
input {
}
select {
	font-family: Arial
}
</style>

<style>
    @page {
        header: html_myHeader;
        footer: html_myFooter;
    }
    table.header {
    	border-bottom: 1px solid #000000; 
    	vertical-align: bottom; 
    	font-family: serif; 
    	font-size: 9pt; 
    	color: #000088;
    	width: 100%;
    }
    .footer {
    	text-align: center;
    }
</style>

<body>
<!-- register header and footer -->
<htmlpageheader name="myHeader">
    <table width="100%" style="border-bottom: 0px solid #000000; vertical-align: bottom; font-family: serif; font-size: 9pt; color: #000088;"><tr>
    <td width="100%" align="center"><img src="data:image/png;base64,'.base64_encode($imagenHeader).'">
    </td>
    </tr></table>
    
</htmlpageheader>


<htmlpagefooter name="myFooter">
    <div class="footer">
    <img src="data:image/png;base64,'.base64_encode($imagenFooter).'" style="position: fixed; left: 0; top: 0">    
    </div>
</htmlpagefooter>

<h1>Lorem Ipsum</h1>
<h4>"Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit..."</h4>
<h5>"No hay nadie que ame el dolor mismo, que lo busque, lo encuentre y lo quiera, simplemente porque es el dolor."</h5>


<hr />

<div id="Content">
<div id="bannerL"><div id="div-gpt-ad-1474537762122-2">
<script type="text/javascript">googletag.cmd.push(function() { googletag.display("div-gpt-ad-1474537762122-2"); });</script>
</div></div>
<div id="bannerR"><div id="div-gpt-ad-1474537762122-3">
<script type="text/javascript">googletag.cmd.push(function() { googletag.display("div-gpt-ad-1474537762122-3"); });</script>
</div></div>
<div class="boxed"><!-- 


If you want to use Lorem Ipsum within another program please contact us for details
on our API rather than parse the HTML below, we have XML and JSON available.


 --><div id="lipsum">
<p>
Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur in posuere libero, sed bibendum elit. Ut volutpat lacus egestas massa maximus, sit amet fringilla mauris vestibulum. Aenean ac neque sodales, semper ipsum quis, vehicula nibh. Nunc metus ex, elementum quis euismod nec, vestibulum dignissim sapien. Nam volutpat leo ut mi ultrices, et efficitur nulla vehicula. Curabitur mauris tellus, eleifend ut aliquam in, molestie ut urna. Nunc laoreet dolor lorem, non efficitur turpis volutpat a. Vestibulum porttitor nunc eu fringilla blandit.
</p>
<p>
Curabitur semper finibus aliquam. Vivamus semper nibh sit amet luctus porttitor. Morbi tempus gravida ornare. Integer dictum risus ac vulputate volutpat. Mauris efficitur fringilla posuere. Duis sed porttitor enim. Duis vel elementum elit, non commodo nulla. Praesent ut enim quis massa maximus pharetra aliquam id turpis. Morbi consequat vestibulum justo, sit amet dictum nisl maximus tincidunt. Proin purus purus, ultrices non nulla at, imperdiet egestas ligula. Aenean vel urna varius, ultrices libero ac, tempor turpis. Nullam mollis libero dictum blandit fermentum. Maecenas bibendum vitae est eget semper. Morbi fringilla, est sed accumsan commodo, eros odio cursus tortor, convallis ultrices lectus eros at mauris. Nullam cursus venenatis nisi et elementum. Donec malesuada nulla a nibh eleifend interdum.
</p>
<p>
Proin lacinia laoreet sollicitudin. Aenean porta felis sit amet commodo ullamcorper. Curabitur nec viverra libero, at fringilla orci. Fusce non metus sollicitudin est egestas cursus ac non libero. Quisque accumsan imperdiet congue. Sed vehicula eu metus a viverra. Nulla luctus et lacus nec blandit. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Curabitur finibus arcu sed nisi efficitur porta. Curabitur tristique, diam sed commodo porta, lacus mi sollicitudin tortor, nec ultrices diam nisl id velit. Donec id leo aliquam, ornare diam eu, porttitor elit. Nulla sit amet lectus non mauris ultricies ornare. Aenean elementum orci dui, id ornare turpis aliquam id. Duis blandit, augue non euismod iaculis, magna metus fermentum tortor, a lacinia justo tellus in nulla.
</p>
<p>
Aenean dui erat, porta vitae pellentesque non, lobortis non tortor. Phasellus sagittis lorem sapien, nec elementum augue rutrum id. Fusce nisi purus, porttitor a auctor eu, suscipit sed odio. Nam egestas, arcu vitae efficitur sollicitudin, tortor nibh vestibulum dolor, quis auctor dui dolor at lorem. Ut condimentum dolor quis pellentesque egestas. Cras volutpat euismod massa, ac lacinia tortor sollicitudin vitae. Nam sit amet lorem quam. In laoreet blandit lorem et accumsan. Fusce eu ullamcorper orci. Praesent sit amet enim sit amet sapien hendrerit sodales ut id tortor. Duis nec felis lectus. Aliquam libero nisi, dapibus id luctus et, pulvinar eget elit. Nullam dictum id mauris vitae dapibus. Maecenas dictum dictum tincidunt.
</p>
<p>
Maecenas varius nunc nec sagittis volutpat. Sed a quam orci. Nunc pellentesque enim quam, in varius est tincidunt quis. Praesent iaculis neque sit amet cursus aliquet. Cras sed dui ultricies, scelerisque sapien ut, interdum justo. Aliquam maximus imperdiet diam in sodales. Maecenas tempus velit est, non aliquam risus rhoncus vel.
</p></div>
<div id="generated">Generados 5 p&aacute;rrafos, 479 palabras, 3242 bytes de <a href="https://es.lipsum.com/" title="Lorem Ipsum">Lorem Ipsum</a></div>
</div>
</div>
';
//==============================================================
$mpdf->useActiveForms = true;
/*
// Try playing around with these (these are also in config.php)
$mpdf->formSubmitNoValueFields = true;
$mpdf->formExportType = 'xfdf'; // 'html' or 'xfdf'
$mpdf->formSelectDefaultOption = true;	// for Select drop down box; if no option is explicitly maked as selected,
							// this determines whether to select 1st option (as per browser)
							// - affects whether "required" attribute is relevant
$mpdf->form_border_color = '0.0 0.820 0.0';
$mpdf->form_background_color = '0.941 0.941 0.941';
$mpdf->form_border_width = '1';
$mpdf->form_border_style = 'S';
$mpdf->form_radio_color = '0.0 0.820 0.0';
$mpdf->form_radio_background_color = '0.941 0.5 0.5';
$mpdf->form_button_border_color = '0.0 0.820 0.0';
$mpdf->form_button_background_color = '0.941 0.941 0.941';
$mpdf->form_button_border_width = '1';
$mpdf->form_button_border_style = 'S';
*/
$mpdf->WriteHTML($html);
//==============================================================
// JAVASCRIPT FOR WHOLE DOCUMENT
/*
$mpdf->SetJS('
var dialogTitle = "Enter details";
var defaultAnswer = "";
var reply = app.response("This is javascript set to run when the document opens. Enter value for first field", dialogTitle, defaultAnswer);
if (reply != null) {
this.getField("inputfield").value = reply;
}
');
*/
//==============================================================
// OUTPUT
$mpdf->Output(); exit;
//==============================================================
//==============================================================
//==============================================================
//==============================================================

?>