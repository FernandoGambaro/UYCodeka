<?php
$file=$_GET['file'];
					if (file_exists('../tmp/'.$file.'.xlsx')){
					unlink('../tmp/'.$file.'.xlsx') ;
					}
?>