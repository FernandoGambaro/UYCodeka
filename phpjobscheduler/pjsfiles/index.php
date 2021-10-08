<?php
$app_name = "phpJobScheduler";
$phpJobScheduler_version = "3.9";
?>
<link rel="stylesheet" href="../css3/css/font-awesome.min.css">

<script type="text/javascript">
parent.document.getElementById("msganio").innerHTML="<i class='fa fa-long-arrow-right' aria-hidden='true'></i>&nbsp;  <?php echo $app_name. ' - '. $phpJobScheduler_version;?>  ";
</script>
<?php
// -------------------------------
include_once("functions.php");
update_db(); // check database is up-to-date, if not add required tables
include("header.html");
if (isset($_GET['add'])) include("add-modify.html");
else include("main.html");
include("footer.html");
?>