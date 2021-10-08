<?php
/*
Include the session class. Modify path according to where you put the class
file.
*/
require_once(dirname(__FILE__).'/class/class_session.php');

/*
Instantiate a new session object. If session exists, it will be restored,
otherwise, a new session will be created--placing a sid cookie on the user's
computer.
*/
if (!$s = new session()) {
  /*
  There is a problem with the session! The class has a 'log' property that
  contains a log of events. This log is useful for testing and debugging.
  */
  echo "<h2>Ocurrió un error al iniciar session!</h2>";
  echo $s->log;
  exit();
}
include("conexion.php");
require("funcionesvarias.php");

$USERID=$s->data['UserID'];


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 3.2//EN">

<HTML>
<HEAD>
	
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8">
	<TITLE></TITLE>
	<meta name="generator" content="Bluefish 2.2.8" >
	<meta name="author" content="fernando" >
	<META NAME="CREATED" CONTENT="20111205;17151100">
	<META NAME="CHANGEDBY" CONTENT="Fernando Gámbaro">
	<META NAME="CHANGED" CONTENT="20111218;22152500">
	
	<STYLE>
		<!-- 
		@media screen {
			BODY,DIV,TABLE,THEAD,TBODY,TFOOT,TR,TH,TD,P { font-family:"Verdana"; font-size:10pt;}
			.page-break	{ height:2px; border-top:1px dotted #999; margin-bottom:13px; font-size: 10pt; }
		}
		@media print {
			BODY,DIV,TABLE,THEAD,TBODY,TFOOT,TR,TH,TD,P { background: white; font-family: "Verdana"; color:black; font-size:10pt; margin:0%;}
			@page :left {margin-left: 4cm; margin-right: 3cm; }
			@page :right { margin-left: 3cm; margin-right: 4cm; }
			@page { margin: 2cm } /* All margins set to 2cm */
			/*@page :first { margin-top: 10cm;}    /* Top margin on first page 10cm */
			#content{ margin-left:0; float:none; width:auto; margin: 0 5%; padding: 0; border: 0; float: none !important; color: black; 
			background: transparent;}
			.page-break {page-break-before:left; margin:0; margin-top: -28px; border-top:none; }
			.print-footer {display:block; position: absolute; bottom: 0;}
		}
		 -->
	</STYLE>
</HEAD>

<BODY TEXT="#000000">


<?php

	$ANIOID=$_GET['ANIOID'];
	$ordenado="ACTIVIDADID";
	$orden="ASC";
	$criterio="WHERE `ANIOID` = '$ANIOID'";

	$ssql=" SELECT * FROM `ORGANIZACION` " .$criterio. " ORDER BY `$ordenado` $orden";
//echo "<br>".$ssql;
	$rs = @mysqli_query( $conectar, $ssql);

while($DATA = @mysqli_fetch_array($rs)){
	$ACTIVIDAD[trim($DATA[ACTIVIDADID])]=trim($DATA[ACTIVIDADID]);
}

$z=1;

$largo=51;

$LargoTotal=$largo;

foreach($ACTIVIDAD as $xy=>$valor) {
//echo $xy."+++".$valor."<br>";
	$criterio="WHERE `ANIOID` = '$ANIOID' AND `ACTIVIDADID` = '$valor' ";
	$ssql=" SELECT * FROM `ORGANIZACION` " .$criterio. " ORDER BY `$ordenado` $orden";
	$rs = @mysqli_query( $conectar, $ssql);
	$total_registros = mysqli_num_rows($rs);
	$LargoTotal=$LargoTotal - 12 - $total_registros;
//echo $LargoTotal."<br>";
//echo "registros ".$total_registros."<br>";
	if ($LargoTotal <= 0) {
		$LargoTotal=$largo - 12 - $total_registros;
		$z++;
		$pagina[$z]=$valor;
	} else {
		$pagina[$z]=$pagina[$z]."-".$valor;
	}	
//echo "*".$valor."<br>";
//echo "+".$pagina[$z]."<br>";
//echo "Página ".$z."<br>";
$total_registros=0;
}
//echo $z;
$msg="Imprime Organización ".$ANIOID;
logger($USERID, $msg);

////////////////--------------------------///////////////////
for ($ZZ=1; $ZZ<=$z; $ZZ++) {
//$LargoTotal=36;

$ACTIVITY=explode("-",$pagina[$ZZ]);
//echo $ACTIVITY[1];

    foreach($ACTIVITY as $valor1=>$xy1) {

//echo "+ ".$xy1." - ".$valor1."*";
	  if ($xy1!="") {
		$c_usuario=" SELECT * FROM `ACTIVIDAD` WHERE `ACTIVIDADID`= '$xy1'";
		$b_usuario = @mysqli_query( $conectar, $c_usuario);
		while($r_usuario= mysqli_fetch_array($b_usuario)){
				if ($r_usuario[ACTIVIDADSECTOR]!="") {
				$ACTIVIDADSECTOR=$r_usuario[ACTIVIDADSECTOR];
				$SECTOR="SECTOR:";
				} else {
				$ACTIVIDADSECTOR="";
				$SECTOR="";
				}
			$ACTIVIDADCOD=$r_usuario[ACTIVIDADCOD];
			$ACTIVIDADDESCRIPCION=$r_usuario[ACTIVIDADDESCRIPCION];
			}
		$c_usuario=" SELECT * FROM `ORGASALAS` WHERE `ACTIVIDADID`= '$xy1' AND `ANIOID`= '$ANIOID'";
		$b_usuario = @mysqli_query( $conectar, $c_usuario);
		while($r_usuario= mysqli_fetch_array($b_usuario)){
		$SALA=$r_usuario[SALASID];
		$ORGANIZACIONDIA=$r_usuario[ORGANIZACIONDIA];
		$ORGANIZACIONHORA=$r_usuario[ORGANIZACIONHORA];
		$ORGASALASOBS=$r_usuario[ORGASALASOBS];
		}
		$c_sala=" SELECT * FROM `SALAS` WHERE `SALASID`= '$SALA'";
		$b_sala = @mysqli_query( $conectar, $c_sala);
		if ($r_sala= mysqli_fetch_array($b_sala)){
		$SALASDESCRIPCION=$r_sala[SALASDESCRIPCION];
		} else {
		$SALASDESCRIPCION=""; }
		$SALA="";
		$c_dia=" SELECT * FROM `DIAS` WHERE `DIASID`= '$ORGANIZACIONDIA'";
		$b_dia = @mysqli_query( $conectar, $c_dia);
		while($r_dia= mysqli_fetch_array($b_dia)){
		if ($r_dia[DIASID]!=1)
		$DIA=$r_dia[DIASDESC];
		else
		$DIA="";
		}
		if ($DIA!=""){
			$HoraTipo = array(
				0=>"10:00hrs",
				1=>"17:00hrs",
				2=>"18:00hrs",
				3=>"19:00hrs",
				4=>"19:30hrs",
				5=>"20:00hrs",
				6=>"21:00hrs");
			$x=0;
			foreach($HoraTipo as $i) {
				if ( $x==$ORGANIZACIONHORA)
				{
					$HORA=$i;
				}
				$x++;
			}
		} else $HORA="";
	?>
<div id="content">
<TABLE FRAME=VOID CELLSPACING=0 COLS=10 RULES=NONE BORDER=0>
	<COLGROUP><COL WIDTH=27><COL WIDTH=107><COL WIDTH=101><COL WIDTH=59><COL WIDTH=86><COL WIDTH=86><COL WIDTH=86><COL WIDTH=86><COL WIDTH=86><COL WIDTH=27></COLGROUP>
	<TBODY>
		<TR>
			<TD WIDTH=27 HEIGHT=18 ALIGN=CENTER VALIGN=MIDDLE><FONT FACE="Verdana"><BR></FONT></TD>
			<TD ROWSPAN=4 WIDTH=107 ALIGN=LEFT><FONT FACE="Verdana"><BR><IMG SRC="largo_hojas_html_b804800.png" WIDTH=76 HEIGHT=70 HSPACE=15 VSPACE=4>
			</FONT></TD>
			<TD WIDTH=101 ALIGN=LEFT><FONT FACE="Verdana"><BR></FONT></TD>
			<TD WIDTH=59 ALIGN=LEFT><FONT FACE="Verdana"><BR></FONT></TD>
			<TD WIDTH=86 ALIGN=LEFT><FONT FACE="Verdana"><BR></FONT></TD>
			<TD COLSPAN=4 WIDTH=343 ALIGN=RIGHT VALIGN=MIDDLE><FONT FACE="Verdana">Montevideo, 15 de marzo de 2014</FONT></TD>
			<TD WIDTH=27 ALIGN=LEFT><FONT FACE="Verdana"><BR></FONT></TD>
		</TR>
		<TR>
			<TD HEIGHT=20 ALIGN=LEFT><FONT FACE="Verdana" ><BR></FONT></TD>
			<TD ALIGN=LEFT><FONT FACE="Verdana" ><BR></FONT></TD>
			<TD ALIGN=LEFT><FONT FACE="Verdana" ><BR></FONT></TD>
			<TD ALIGN=LEFT><FONT FACE="Verdana" ><BR></FONT></TD>
			<TD ALIGN=LEFT><FONT FACE="Verdana" ><BR></FONT></TD>
			<TD ALIGN=LEFT><FONT FACE="Verdana" ><BR></FONT></TD>
			<TD ALIGN=LEFT><FONT FACE="Verdana" ><BR></FONT></TD>
			<TD ALIGN=LEFT><FONT FACE="Verdana" ><BR></FONT></TD>
			<TD ALIGN=LEFT><FONT FACE="Verdana" ><BR></FONT></TD>
			<TD ALIGN=LEFT><FONT FACE="Verdana" ><BR></FONT></TD>
		</TR>
		<TR>
			<TD HEIGHT=17 ALIGN=LEFT><FONT FACE="Verdana"><BR></FONT></TD>
			<TD ALIGN=RIGHT><FONT FACE="Verdana">LUGAR:</FONT></TD>
			<TD COLSPAN=5 ALIGN=LEFT VALIGN=MIDDLE STYLE="border-bottom: 1px dotted #000000;"><FONT FACE="Verdana">&nbsp;<b><?php echo $SALASDESCRIPCION;?></b></FONT></TD>
			<TD ALIGN=LEFT><FONT FACE="Verdana"></FONT></TD>
			<TD ALIGN=LEFT><FONT FACE="Verdana"><BR></FONT></TD>
		</TR>
		<TR>
			<TD ALIGN=LEFT><FONT FACE="Verdana" ></FONT></TD>
			<TD ALIGN=RIGHT><FONT FACE="Verdana" >DIA: </FONT></TD>
			<TD COLSPAN=4 ALIGN=LEFT VALIGN=MIDDLE><font FACE="Verdana" STYLE="border-bottom: 1px dotted #000000;">&nbsp;<?php echo $DIA;?>&nbsp;</FONT></TD>
			<TD ALIGN=RIGHT><FONT FACE="Verdana" >HORA:</FONT></TD>
			<TD ALIGN=LEFT SDNUM="3082;0;HH:MM:SS"><FONT FACE="Verdana" STYLE="border-bottom: 1px dotted #000000;">&nbsp;<?php echo $HORA;?>&nbsp;</FONT></TD>
		</TR>
		<TR>
			<TD HEIGHT=20 ALIGN=LEFT><FONT FACE="Verdana" ><BR></FONT></TD>
			<TD ALIGN=LEFT><FONT FACE="Verdana" ><BR></FONT></TD>
			<TD ALIGN=RIGHT SDNUM="3082;"><FONT FACE="Verdana" ><?php echo $ACTIVIDADCOD;?>&nbsp;</FONT></TD>
			<TD COLSPAN=6 ALIGN=LEFT VALIGN=MIDDLE STYLE="border-bottom: 1px dotted #000000;"><FONT FACE="Verdana" >&nbsp;<strong><?php echo $ACTIVIDADDESCRIPCION;?></strong></FONT></TD>
			<TD ALIGN=LEFT><FONT FACE="Verdana" ><BR></FONT></TD>
		</TR>
		<TR>
			<TD HEIGHT=20 ALIGN=LEFT><FONT FACE="Verdana" ><BR></FONT></TD>
			<TD ALIGN=LEFT><FONT FACE="Verdana" ><BR></FONT></TD>
			<TD ALIGN=RIGHT><FONT FACE="Verdana" ><?php echo $SECTOR;?></FONT></TD>
			<TD COLSPAN=6 ALIGN=LEFT VALIGN=MIDDLE><FONT FACE="Verdana" >&nbsp;<?php echo $ACTIVIDADSECTOR;?></FONT></TD>
			<TD ALIGN=LEFT><FONT FACE="Verdana" ><BR></FONT></TD>
		</TR>
		<TR>
			<TD HEIGHT=20 ALIGN=LEFT><FONT FACE="Verdana" ><BR></FONT></TD>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" COLSPAN=2 ALIGN=CENTER VALIGN=MIDDLE><FONT FACE="Verdana" color="#1E2A95">CARGO</FONT></TD>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=RIGHT><FONT FACE="Verdana" color="#1E2A95">NRO&nbsp;</FONT></TD>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" COLSPAN=5 ALIGN=LEFT VALIGN=MIDDLE><FONT FACE="Verdana" color="#1E2A95">&nbsp;NOMBRE</FONT></TD>
			<TD ALIGN=LEFT><FONT FACE="Verdana" ><BR></FONT></TD>
		</TR>
<?php
 $SALASDESCRIPCION="";	
 $ACTIVIDADDESCRIPCION="";
 $SECTOR="";

	$LargoTotal=$LargoTotal-9;
//	echo $xy."<br>";
		$criterio="WHERE `ANIOID` = '$ANIOID' AND `ACTIVIDADID` = '$xy1' ";
//echo $criterio."<br>";
		$ssql=" SELECT * FROM `ORGANIZACION` " .$criterio. "  ORDER BY `CALIDADID` ASC, `CONTACTOSID` ASC"; //ORDER BY `$ordenado` $orden";
		$rs1 = @mysqli_query( $conectar, $ssql);
		while($DATA = @mysqli_fetch_array($rs1)){
	$estado = array(
		0=>"",
		1=>"(*)",
		2=>"(**)",
		3=>"(#)");
	$x=0;
	foreach($estado as $i) {
		if ( $x==$DATA[ORGANIZACIONTIP])
		{
		$ORGANIZACIONTIP=$i;
		}
		$x++;
	}
			$c_usuario=" SELECT * FROM `CONTACTOS` WHERE `CONTACTOSID`= '$DATA[CONTACTOSID]'";
			$b_usuario = @mysqli_query( $conectar, $c_usuario);
				while($r_usuario= mysqli_fetch_array($b_usuario)){
				$NUMERO=$r_usuario[CONTACTOSNUMERO];
				$NOMBRE=$r_usuario[CONTACTOSNOMBRE]."  ".$r_usuario[CONTACTOSAPELLIDO]." ".$ORGANIZACIONTIP;
				}
				$c_usu=" SELECT * FROM `CALIDAD` WHERE `CALIDADID`= '$DATA[CALIDADID]'";
				$b_usu = @mysqli_query( $conectar, $c_usu);
				while($r_usu= mysqli_fetch_array($b_usu)){
				$CALIDAD=$r_usu[CALIDADDESCRIPCION];
				}
				if ($DATA[CONTACTOSID]!=0) {
	?>
		<TR>
			<TD HEIGHT=20 ALIGN=LEFT><FONT FACE="Verdana" ><BR></FONT></TD>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" COLSPAN=2 ALIGN=LEFT VALIGN=MIDDLE><FONT FACE="Verdana" >&nbsp;<?php echo $CALIDAD?></FONT></TD>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=RIGHT SDVAL="<?php echo $NUMERO?>" SDNUM="3082;"><FONT FACE="Verdana" ><?php echo $NUMERO?>&nbsp;</FONT></TD>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" COLSPAN=5 ALIGN=LEFT VALIGN=MIDDLE><FONT FACE="Verdana" >&nbsp;<?php echo $NOMBRE?></FONT></TD>
			<TD ALIGN=LEFT><FONT FACE="Verdana" ><BR></FONT></TD>
		</TR>
	<?php			}
			}
//			ksort ($NOMBRE);	
//			foreach ($NOMBRE as $key1 => $valor1) 
//			{ 
//			$LargoTotal=$LargoTotal-1;
//			}
	?>
		<TR>
			<TD HEIGHT=20 ALIGN=LEFT><FONT FACE="Verdana" ><BR></FONT></TD>
			<TD STYLE="border-top: 1px solid #000000" ALIGN=LEFT><FONT FACE="Verdana" ><BR></FONT></TD>
			<TD STYLE="border-top: 1px solid #000000" ALIGN=LEFT><FONT FACE="Verdana" ><BR></FONT></TD>
			<TD STYLE="border-top: 1px solid #000000" ALIGN=LEFT><FONT FACE="Verdana" ><BR></FONT></TD>
			<TD STYLE="border-top: 1px solid #000000" ALIGN=LEFT><FONT FACE="Verdana" ><BR></FONT></TD>
			<TD STYLE="border-top: 1px solid #000000" ALIGN=LEFT><FONT FACE="Verdana" ><BR></FONT></TD>
			<TD STYLE="border-top: 1px solid #000000" ALIGN=LEFT><FONT FACE="Verdana" ><BR></FONT></TD>
			<TD STYLE="border-top: 1px solid #000000" ALIGN=LEFT><FONT FACE="Verdana" ><BR></FONT></TD>
			<TD STYLE="border-top: 1px solid #000000" ALIGN=LEFT><FONT FACE="Verdana" ><BR></FONT></TD>
			<TD ALIGN=LEFT><FONT FACE="Verdana" ><BR></FONT></TD>
		</TR>
		<TR>
			<TD HEIGHT=20 ALIGN=LEFT><FONT FACE="Verdana" ><BR></FONT></TD>
			<TD COLSPAN=2 ALIGN=CENTER VALIGN=MIDDLE><FONT FACE="Verdana" >Observaciones:</FONT></TD>
			<TD STYLE="border-bottom: 1px solid #000000" COLSPAN=6 ALIGN=LEFT VALIGN=MIDDLE><FONT FACE="Verdana" ><?php echo $ORGASALASOBS;?><BR></FONT></TD>
			<TD ALIGN=LEFT><FONT FACE="Verdana" ><BR></FONT></TD>
		</TR>
		<TR>
			<TD HEIGHT=20 ALIGN=LEFT><FONT FACE="Verdana" ><BR></FONT></TD>
			<TD ALIGN=LEFT><FONT FACE="Verdana" ><BR></FONT></TD>
			<TD ALIGN=LEFT><FONT FACE="Verdana" ><BR></FONT></TD>
			<TD ALIGN=LEFT><FONT FACE="Verdana" ><BR></FONT></TD>
			<TD ALIGN=LEFT><FONT FACE="Verdana" ><BR></FONT></TD>
			<TD ALIGN=LEFT><FONT FACE="Verdana" ><BR></FONT></TD>
			<TD ALIGN=LEFT><FONT FACE="Verdana" ><BR></FONT></TD>
			<TD ALIGN=LEFT><FONT FACE="Verdana" ><BR></FONT></TD>
			<TD ALIGN=LEFT><FONT FACE="Verdana" ><BR></FONT></TD>
			<TD ALIGN=LEFT><FONT FACE="Verdana" ><BR></FONT></TD>
		</TR>
	
	<?php
	$LargoTotal=$LargoTotal-3;
	
	$NOMBRE="";
	$CALIDAD="";
	
	}
}
	if ($xy != 0) {
?>


<?php
	}
//$LargoTotal=46;

?>
	</TBODY>
</TABLE>
</div>
<div id="print-footer">
 </p></div>
<div class="page-break" align="right">Página <?php echo $ZZ;?></div>
<?php
}
?>

<!-- ************************************************************************** -->
</BODY>

</HTML>

<?
