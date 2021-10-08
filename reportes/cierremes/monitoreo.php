<?php
if(isset($_POST['execGpsDiag'])) {
	if (file_exists($_POST['execGpsDiag'])) {
	echo "Fin";
	} else {
	echo "No";
	}
}
?>