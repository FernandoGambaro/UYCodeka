<?php
$path=realpath(dirname(__FILE__));
$largo=strlen($path)-4;
$conexionPath=substr($path,0,$largo);

include('cron_class.php');

$crons=Crontab::getJobs();
$php=shell_exec('which php')." ".$conexionPath;

function extract_unit($string, $start, $end)
{
	$pos = stripos($string, $start);
	$str = substr($string, $pos);
	$str_two = substr($str, strlen($start));
	$second_pos = stripos($str_two, $end);
	$str_three = substr($str_two, 0, $second_pos);
	$unit = trim($str_three); /*/ remove whitespaces*/
	return $unit;
}


include_once "header.php";


?>
<div class="form-group"> 
<div class="col-xs-3">
<form method="post" action="" id="crontab-form">

<div class="form-group">
<fieldset class="scheduler-border">
  <legend class="scheduler-border"><?php echo _('Programar tarea'); ?></legend>
<div class="row">
  <div class="col-xs-12">

	<input type="hidden" name="command" id="command" size="70" value="<?php echo $php;?>" readonly="readonly">
	&nbsp;Tarea&nbsp;

	<select name="tareas" id="tareas" class="form-control input-sm">
	<option value="clientes/backup/NewGetBackup.php">Verifico respaldos </option>
	<option value="clientes/backup/ComunicoError.php">Comunico errores de respaldos </option>
	<option value="clientes/autofacturas/autofactura.php">Auto facturación mensual </option>
	<option value="tipocambio/obtener_tipo_cambio.php">Tipo de cambio</option>
	<option value="feedback/proximaevaluacion.php">Comunico Próxima evaluación </option>
	<option value="usuarios/biometric/auto_get_info.php">Descargar datos de los equipos huella digital</option>
	<option value="controlhoras/comunicoPendientes.php">Pendientes registro de horas</option>
	</select>
	</div>
</div>
<div class="row">
	<div class="col-xs-6">
		<h3>Minuto</h3>
		<label for="minute_chooser_every">Cada Minuto</label>
		<input type="radio" name="minute_chooser" id="minute_chooser_every" class="chooser" value="0" checked="checked"><br>

		<label for="minute_chooser_choose">En el/los minuto/s</label>
		<input type="radio" name="minute_chooser" id="minute_chooser_choose" class="chooser" value="1"><br>


		<select name="minute" id="minute" multiple="multiple" disabled="disabled" class="browser-default custom-select custom-select-lg mb-2">
			<?php 
			for($z=0;$z<=59;$z++){
				?>
				<option value="<?php echo $z;?>"><?php echo $z;?></option>
				<?php
			}
			?>
		</select>
		</div>

	<div class="col-xs-6">
		<h3>Hora</h3>
		<label for="hour_chooser_every">CadaHora</label>
		<input type="radio" name="hour_chooser" id="hour_chooser_every" class="chooser" value="0" checked="checked"><br>

		<label for="hour_chooser_choose">Elja la hora</label>
		<input type="radio" name="hour_chooser" id="hour_chooser_choose" class="chooser" value="1"><br>

		<select name="hour" id="hour" multiple="multiple" disabled="disabled" class="browser-default custom-select custom-select-lg mb-2">
		<option value="0">12 Medianoche</option>
		<option value="1">1 AM</option><option value="2">2 AM</option><option value="3">3 AM</option>
		<option value="4">4 AM</option><option value="5">5 AM</option><option value="6">6 AM</option>
		<option value="7">7 AM</option><option value="8">8 AM</option><option value="9">9 AM</option>
		<option value="10">10 AM</option><option value="11">11 AM</option><option value="12">12 Mediodía</option>
		<option value="13">1 PM</option><option value="14">2 PM</option><option value="15">3 PM</option>
		<option value="16">4 PM</option><option value="17">5 PM</option><option value="18">6 PM</option>
		<option value="19">7 PM</option><option value="20">8 PM</option><option value="21">9 PM</option>
		<option value="22">10 PM</option><option value="23">11 PM</option></select>
		</div>
</div>
<div class="row">
	<div class="col-xs-6">
		<h3>Día</h3>
		<label for="day_chooser_every">Todos los días</label>
		<input type="radio" name="day_chooser" id="day_chooser_every" class="chooser" value="0" checked="checked"><br>

		<label for="day_chooser_choose">Elija el día</label>
		<input type="radio" name="day_chooser" id="day_chooser_choose" class="chooser" value="1"><br>

		<select name="day" id="day" multiple="multiple" disabled="disabled" class="browser-default custom-select custom-select-lg mb-2">
		<?php 
			for($z=1;$z<=30;$z++){
				?>
				<option value="<?php echo $z;?>"><?php echo $z;?></option>
				<?php
			}
			?>	
		</select>
		</div>
	<div class="col-xs-6">
		<h3>Mes</h3>
		<label for="month_chooser_every">Todos los mese</label>
		<input type="radio" name="month_chooser" id="month_chooser_every" class="chooser" value="0" checked="checked"><br>

		<label for="month_chooser_choose">Elja el mes</label>
		<input type="radio" name="month_chooser" id="month_chooser_choose" class="chooser" value="1"><br>

		<select name="month" id="month" multiple="multiple" disabled="disabled" class="browser-default custom-select custom-select-lg mb-2">
		<option value="1">Enero</option>
		<option value="2">Febrero</option>
		<option value="3">Marzo</option>
		<option value="4">Abril</option>
		<option value="5">Mayo</option>
		<option value="6">Junio</option>
		<option value="7">Julio</option>
		<option value="8">Augosto</option>
		<option value="9">Setiembre</option>
		<option value="10">Octubre</option>
		<option value="11">Noviembre</option>
		<option value="12">Diciembre</option>
		</select>
		</div>
	</div>
<div class="row">
	<div class="col-xs-12">
		<h3>Día de la semana</h3>
		<label for="weekday_chooser_every">Todos los días</label>
		<input type="radio" name="weekday_chooser" id="weekday_chooser_every" class="chooser" value="0" checked="checked"><br>

		<label for="weekday_chooser_choose">Elija día</label>
		<input type="radio" name="weekday_chooser" id="weekday_chooser_choose" class="chooser" value="1"><br>

		<select name="weekday" id="weekday" multiple="multiple" disabled="disabled" class="browser-default custom-select custom-select-lg mb-2">
		<option value="0">Domingo</option>
		<option value="1">Lunes</option>
		<option value="2">Martes</option>
		<option value="3">Miércoles</option>
		<option value="4">Jueves</option>
		<option value="5">Viernes</option>
		<option value="6">Sábado</option>
		</select>
		</div>
	</div>
	</fieldset>

</div>
<div class="row">
	<div class="col-xs-12">
	<textarea name="cron" id="cron" rows="1" cols="140" style="display:none"></textarea>

<!--input type="submit" name="action" id="action" value="Crear programación"-->
<button class="boletin" onClick='"$("#crontab-form").submit();' onMouseOver="style.cursor=cursor">
<i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Crear tarea</button>
	</div>
</div>

</form>
</div>

<div class="col-xs-9">
<fieldset class="scheduler-border">
  <legend class="scheduler-border"><?php echo _(' Tarea Programada '); ?></legend>

	<div class="table-responsive">
    <table class='table table-hover table-responsive table-bordered table-condensed ' id="programadas">
		<tbody>


	<?php 
	if(isset($crons)) {
		foreach($crons as $key=>$item){
		?>
			<tr id="<?php echo $key;?>"	 class="<?php echo $fondolinea?> trigger">
			<td><?php echo $item;?></td>	
			<td>
				<a href="#">
					<img id="botonBusqueda" src="../img/eliminar.png" width="16" height="16" border="0" onClick="eliminar('<?php echo $item;?>');" 
					title="Eliminar tarea"></a></td>
			</tr>						
			<?php
		}
	} else { ?>
			<tr>
				<td width="100%" class="mensaje" colspan="12"><?php echo "No hay ninguna factura que cumpla con los criterios de b&uacute;squeda";?></td>
			</tr>
<?php } ?>					
		</table>
	</div>
</fieldset>
		<?php
				$estado=shell_exec('grep "/usr/bin/php" /var/log/cron');					
		?>

<fieldset class="scheduler-border">
  <legend class="scheduler-border"><?php echo _(' Tarea realizadas '); ?></legend>

			<div class="fixed-table-container" style="width:100%;">
      		<div class="header-background cabeceraTabla"> </div>      			
			<div class="fixed-table-container-inner">
			<table class='table table-hover table-responsive table-bordered table-condensed ' id="realizadas">
				<tbody>				
			<?php
			$lines = array();
			$lines = explode("\n", $estado);
			$lin=array_reverse($lines, true);

			foreach ($lin as $key=>$line) {				
				if(extract_unit($line, "", "localhost")!="") {

				echo "<tr class=\"btn-inverse\"><td> ". extract_unit($line, "", "localhost")."-  Tarea: "
				.substr( extract_unit($line, "clientes/", ".php"), strrpos(extract_unit($line, "clientes/", ".php"), '/')+1 )
				."</td></tr>";
				}
			}					
			?>	
		</tbody>
</table>
</div>
</div>
</fieldset>


</div>
</div>
</div>
</div>

</body>
</html>
