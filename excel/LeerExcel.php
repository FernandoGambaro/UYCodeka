<?php
session_start();

require_once ('PHPExcel.php');


$tmpfname = '../tmp/reciboxres278_017-liderblex.xls';
 
?>
<!doctype>
<html>
<head>
</head>
<body>
<?php
		$excelReader = PHPExcel_IOFactory::createReaderForFile($tmpfname);
		$excelObj = $excelReader->load($tmpfname);
		$worksheet = $excelObj->getSheet(0);
		$lastRow = $worksheet->getHighestRow();
		$control='';
		echo "<table border='1'>";
		for ($row = 1; $row <= $lastRow; $row++) {
			 echo "<tr><td>";
			 echo $control=$worksheet->getCell('A'.$row)->getFormattedValue();
			 echo "</td><td>";
			 echo $worksheet->getCell('B'.$row)->getFormattedValue();
			 echo "</td><td>";
			 echo $worksheet->getCell('C'.$row)->getFormattedValue();
			 echo "</td><td>";
			 echo $worksheet->getCell('D'.$row)->getFormattedValue();
			 echo "</td><td>";
			 echo $worksheet->getCell('E'.$row)->getFormattedValue();
			 echo "</td><td>";
			 echo $worksheet->getCell('F'.$row)->getFormattedValue();
			 echo "</td><td>";
			 echo $worksheet->getCell('G'.$row)->getFormattedValue();
			 echo "</td><tr>";
			 
			 if(strpos($control, 'Pago:')>0 ) {
			 	break;
			 }
			$control='';
		}
		echo "</table>";	
?>

</body>
</html>