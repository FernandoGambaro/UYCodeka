<?php
class SelectList
{
    protected $conn;

        public function __construct()
        {
            $this->DbConnect();
        }
 
        protected function DbConnect()
        {
            include "../db_config.php";
            $this->conn = ($GLOBALS["___mysqli_ston"] = mysqli_connect('localhost', 'root', 'mcc123mcc')) OR die("Unable to connect to the database");
            ((bool)mysqli_query($this->conn, "USE " . CEDIVA)) OR die("can not select the database $db");
            return TRUE;
        }
 
        public function ShowCategory()
        {
        	mysqli_query($GLOBALS["___mysqli_ston"], "SET NAMES 'utf8'");
			header("Content-Type: text/html;charset=utf-8");

            $sql = "SELECT * FROM `ProcedimientoGeneral` WHERE `nop`='".$_POST['id']."'";
            $res = mysqli_query($this->conn, $sql);
            $category = '<option value="0">Seleccione uno...</option>';
            while($row = mysqli_fetch_array($res))
            {
                $category .= '<option value="' . $row['oid'] . '">' . $row['descripcion'] . '</option>';
            }
            return $category;
        }
 
        public function ShowType()
        {
        	mysqli_query($GLOBALS["___mysqli_ston"], "SET NAMES 'utf8'");
			header("Content-Type: text/html;charset=utf-8");
            $sql = "SELECT * FROM  `ProcedimientoParticular` WHERE `oidgeneral`='".$_POST['id']."'";
            $res = mysqli_query($this->conn, $sql);
            $type = '<option value="0">Seleccione uno...</option>';
            while($row = mysqli_fetch_array($res))
            {
                $type .= '<option value="' . $row['oid'] . '">' . $row['descripcion'] . '</option>';
            }
            return $type;
        }
        public function ShowWord()
        {
        	mysqli_query($GLOBALS["___mysqli_ston"], "SET NAMES 'utf8'");
			header("Content-Type: text/html;charset=utf-8");
            $sql = "SELECT * FROM `ProcedimientoPablabraClave` WHERE `oidparticular`='".$_POST['id']."'";
            $res = mysqli_query($this->conn, $sql);
            $type = '<option value="0">Seleccione uno...</option>';
            while($row = mysqli_fetch_array($res))
            {
                $type .= '<option value="' . $row['oid'] . '">' . $row['descripcion'] . '</option>';
            }
            return $type;
        }
}
 
$opt = new SelectList();
?>
