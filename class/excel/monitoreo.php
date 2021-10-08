<?php
if(isset($_POST['Estado']) and isset($_POST['File'])) {
	if (file_exists('../tmp/'.$_POST['File'].'.xlsx')) {
	echo "Fin";
	} else {
	echo "No";
	}
} else {
			if($_POST['Estado']==0) {
			if (file_exists('../tmp/'.$_POST['File'].'.xlsx')) {
			shell_exec('rm ../tmp/'.$_POST['File'].'.xlsx'); 
			}
		echo "Fin";
	}
	echo "Fin";
}
?>