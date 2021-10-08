<?php
if(isset($_POST['execGpsDiag'])) {
	$output1 = shell_exec("pgrep -f -c  'pasodatos.sh'");
	$output2 = shell_exec("pgrep -f -c  'pasodatosirpf.sh'");
	if($output1!=0 and $output2!=0) {
		$output=1;
	}elseif($output1!=0) {
		$output=$output1;
	}elseif($output2!=0) {
		$output=$output2;
	}else {
		$output=0;
	}
	if ($output>0 and $output!='' and $_POST['execGpsDiag']>0 ) {
		echo $_POST['execGpsDiag']."* -> ".$output;
	} else {
		if($_POST['execGpsDiag']==0) {
			shell_exec('kill -KILL '.$output);
			shell_exec('rm *.png ');
			shell_exec('rm *.jpg ');
			shell_exec('rm *.tif ');
			shell_exec('rm *.pnm ');
			shell_exec('rm *.txt ');
		}
		echo "Fin";
	}
}
?>