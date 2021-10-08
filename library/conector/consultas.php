<?php 
namespace App{
    require __DIR__ . '/vendor/autoload.php';
    use Symfony\Component\Yaml\Yaml;
    class Consultas {
        private $pdo;
        private $table = "";
        
        //private $tableSelected;
    
    
        private $seleccion = "";
        private $where = "";
        private $match = "";
        private $join = "";
        private $limit = "";
        private $orden = "";
        private $group = "";

        //Insert data
        private $insert = "";
        
        private $update = "";
        private $delete = "";
        private $truncate = "";
        private $sql='';
        
    
        private $config;
    
        public function __construct($table){
            $this->table = $table;
            $this->config = Yaml::parseFile(__DIR__ .'/config/general.yaml');
        }
    
        public function Select(string $attr = "*", $sql =  null){
            if(strlen($this->seleccion) > 0){
                return NULL;
            }else{
                if(strlen($sql)>0){
                    //Viene la consulta sql
                    $this->seleccion = $attr ;
                } else {
                    $this->seleccion = "SELECT ".$attr." FROM ".$this->table;
                }
                return $this;
                
            }
        }
        public function Limit( int $start,  int $max ) {
            if(strlen($this->seleccion) > 0){
                $this->limit = " LIMIT ".$start.",". $max;
                return $this;
            }else{
                return NULL;
            }
        }        
        public function Orden( string $attr,  string $orden ='ASC' ) {
            if(strlen($this->seleccion) > 0){
                if($this->orden != ''){
                    $this->orden .= ", ".$attr." ". $orden ;
                    return $this;
                } else {
                    $this->orden = " ORDER BY ".$attr." ". $orden ;
                    return $this;
                }
            }else{
                return NULL;
            }
        } 
        public function Group( string $attr ) {
            if(strlen($this->seleccion) > 0){
                if($this->group != ''){
                    $this->group .= ", ".$attr ;
                    return $this;
                } else {
                    $this->group = " GROUP BY ".$attr ;
                    return $this;
                }
            }else{
                return NULL;
            }
        }            
        /*
            Funcion representativa del where en SQL:
            Parametros de entrada
            -attr: atributo
            -valor: valor del atributo
            -sim: Simbolo de comparacion
            -con: Conector entre consultas
            -parentesis: 
            -oTabla: Segunta tabla
            -oAtributo: Atributo de la otra tabla
        */
        public function Where(string $attr, string $valor, string $sim = "", string $con = "", string $parentesis = "", string $oTabla = "", string $oAttr = ""){
            $simbolo = " = ";
            $conector = " AND ";
            if(strlen($sim) > 0){
                $simbolo = " ".trim($sim)." ";
            }

            if(strlen($con) > 0){
                if(strlen($parentesis) > 0 and $parentesis == '('){
                $conector = " ".trim($con)." (";
                }else{
                $conector = " ".trim($con)." ";                   
                }
            } 
    
            if(strlen($attr) > 0 && strlen($valor) > 0 && strlen($this->seleccion) > 0 || strlen($this->update) > 0 || strlen($this->delete) > 0){
                if(strlen($this->where) == 0){
                    if(strlen($oTabla) > 0){
                        if(strlen($parentesis)>0 and $parentesis == '('){
                            $this->where = " WHERE ( `".$oTabla."`.`".$attr."`".$simbolo;
                        }else{
                            $this->where = " WHERE `".$oTabla."`.`".$attr."`".$simbolo;
                        }
                    }else{
                        if(strlen($parentesis)>0 and $parentesis == '('){
                            $this->where = " WHERE ( `".$attr."`".$simbolo;
                        }else{
                            $this->where = " WHERE `".$attr."`".$simbolo;
                        }
                    }
                }else{
                    if(strlen($oTabla) > 0){

                            $this->where .= $conector."`".$oTabla."`.`".$attr."`".$simbolo;
                    }else{
                            $this->where .= $conector."`".$attr."`".$simbolo;
                    }
                }
                if(is_numeric($valor) AND $sim!='LIKE'){
                    if(strlen($parentesis)>0 and $parentesis == ')'){
                        $this->where .= "'".$valor."' )";
                    }else{
                        $this->where .= "'".$valor."'";
                    }
                }elseif($valor == 'IS NULL' or $sim == 'IS NULL' ){
                    if(strlen($parentesis)>0 and $parentesis == ')'){
                        $this->where .= " )";
                    }else{
                        $this->where .= " ";
                    }
                }else{
                    if($sim=='LIKE'){
                        if(strlen($parentesis)>0 and $parentesis == ')'){
                            $this->where .= "'%".$valor."%')";
                        }else{
                            $this->where .= "'%".$valor."%'";
                        }
                    }else{
                        if(strlen($parentesis)>0 and $parentesis == ')'){
                            $this->where .= "'".$valor."')";
                        } else {
                            $this->where .= "'".$valor."'";
                        }
                    }
                }

                return $this;
            }
            return NULL;
        }
        /*
            Funcion representativa del match en SQL:
            Parametros de entrada
            -attr: atributo
            -valor: valor para la búsqueda
            -modo: Modo de la busqueda
        */
        public function Match($nombres, $valor, string $modo = "IN BOOLEAN MODE"){
            if(strlen($this->match) == 0){
                $order = " WHERE MATCH (";
                for($i = 0; $i < count($nombres); $i++){
                    if($i == count($nombres) - 1){
                        $order .= $nombres[$i];
                    }else{
                        $order .= $nombres[$i].", ";
                    }
                }
                $order .= ") AGAINST ('";

                for($i = 0; $i < count($valor); $i++){
                    if($i == count($valor) - 1){
                        $order .= $valor[$i];
                    }else{
                        $order .= "".$valor[$i]." ";
                    }
                }                
                $order .= "' " .$modo.") ";
                $this->match = $order;
            }
            return $this;        
        }

        public function WhereForConsults(string $consulta = "", string $attr, string $sim, string $con, string $oTabla){
            $simbolo = " = ";
            $conector = " AND ";

            if(strlen($sim) > 0){
                $simbolo = " ".trim($sim)." ";
            }

            if(strlen($con) > 0){
                $conector = " ".trim($con)." ";
            }

            if(strlen($attr) > 0 && strlen($consulta) > 0 && strlen($this->seleccion) > 0 || strlen($this->update) > 0 || strlen($this->delete) > 0){
                $this->where .= $conector;

                if(strlen($oTabla) > 0){
                    $this->where .= $oTabla.".".$attr.$simbolo."(".$consulta.")";
                }else{
                    $this->where .= $attr.$simbolo."(".$consulta.")";
                }
            }
        }

        public function Join(string $attrib , string $modelo, string $tipo, string $tablaB = "", string $attribB = ""){
            if(strlen($this->join) == 0){
                //print("Hay algo: ". $tablaB);
                if(strlen($tablaB) > 0) {
                    $this->join = " ".$tipo." JOIN ".$modelo." ON ".$tablaB.".".$attrib." = ";
                }else{
                    $this->join = " ".$tipo." JOIN ".$modelo." ON ".$this->table.".".$attrib." = ";
                }
    
                if(strlen($attribB) > 0){
                    $this->join .= $modelo.".".$attribB;
                }else{
                    $this->join .= $modelo.".".$attrib;
                }
            }else{
                if(strlen($tablaB) > 0) {
                    $this->join .= " ".$tipo." JOIN ".$tablaB." ON ".$tablaB.".".$attrib." = ";
                }else{
                    $this->join .= " ".$tipo." JOIN ".$tablaB." ON ".$this->table.".".$attrib." = ";
                }
    
                if(strlen($attribB) > 0){
                    $this->join .= $modelo.".".$attribB;
                }else{
                    $this->join .= $modelo.".".$attrib;
                }
            }
            return $this;
        }
    
        public function Insert($nombres, $valores){
            $insTemp = "INSERT INTO ".$this->table;
            $order = " (";
            $valor = "VALUES ('";
            for($i = 0; $i < count($nombres); $i++){
                if($i == count($nombres) - 1){
                    $order .= $nombres[$i];
                    $valor .= $valores[$i];
                }else{
                    $order .= $nombres[$i].", ";
                    $valor .= $valores[$i]."', '";
                }
            }
            $order .= ") ";
            $valor .= "')";
            $insTemp .= $order.$valor;
            $this->insert = $insTemp;

            return $this; 
        }
        
    
        public function Update($nombres, $valores){
            $upTemp = "UPDATE ".$this->table;
            $valor = " SET ";
            for($i = 0; $i < count($nombres); $i++){
                if($i == count($nombres) - 1){
                    if(is_numeric($valores[$i])){
                        $valor .= "`".$nombres[$i]."` = '".$valores[$i]."'";
                    } else {
                        $valor .= "`".$nombres[$i]."` = '".$valores[$i]."' ";
                    }
                }else{
                    if(is_numeric($valores[$i])){
                        $valor .= "`".$nombres[$i]."` = '".$valores[$i]."',";
                    } else {
                        $valor .= "`".$nombres[$i]."` = '".$valores[$i]."', ";
                    }
                }
            }
            $upTemp .= $valor;
            $this->update = $upTemp;

            return $this; 
        }
    
        public function Delete(){
            $this->delete = "DELETE FROM ".$this->table;
            return $this;
        }

        public function Truncate(){
            $this->truncate = "TRUNCATE ".$this->table;
            return $this;
        }

        public function Sql($sql){
            $this->sql = $sql;
            return $this;
        }
    
        public function Ejecutar(){
            try{
                $pdo = new \PDO("mysql:host=".$this->config["db"]["host"].";dbname=".$this->config["db"]["name"].";",
                 $this->config["db"]["user"], $this->config["db"]["pass"],
                  array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                   \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
                   \PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => false));

                if(strlen($this->insert) > 0){
                    $insertSt = $pdo->prepare($this->insert);
                    //var_dump($insertSt);
                    if ($insertSt->execute()) {
                        $id = $pdo->lastInsertId(); 
                        return $this->GetMensaje("insert", "ok", "El registro ha sido insertado.", $this->insert, NULL, NULL, $id);
                      } else {
                        return $this->GetMensaje("insert", "error", "El registro no ha podido ser insertado.", $this->insert, NULL, NULL, NULL);
                      }                      
                }else if(strlen($this->update)){
                    if(strlen($this->where) > 0){
                        $consulta = $this->update.$this->where;
                        $upSt = $pdo->prepare($consulta);
                        if ($upSt->execute()) {
                            return $this->GetMensaje("update", "ok", "El registro se ha modificado.", $consulta, NULL, NULL, NULL);
                        } else {
                            return $this->GetMensaje("update", "error", "El registro no ha podido ser modificado.", $consulta, NULL, NULL, NULL);
                        }
                    }else{
                        return $this->GetMensaje("update", "error", "El ejecutar update sin where no esta permitido.", $consulta, NULL, NULL, NULL);
                    }
                              
                }else if(strlen($this->delete)){
                    if(strlen($this->where) > 0){
                        $consulta = $this->delete.$this->where;
                        $delSt = $pdo->prepare($consulta);
                        if ($delSt->execute()) {
                            return $this->GetMensaje("delete", "ok", "El registro ha sido eliminado.", $consulta, NULL, NULL, NULL);
                        } else {
                            return $this->GetMensaje("delete", "error", "El registro no podido ha sido eliminado.", $consulta, NULL, NULL, NULL);
                        }
                    }else{
                        return $this->GetMensaje("delete", "error", "El ejecutar delete sin where no esta permitido.", $consulta, NULL, NULL, NULL);
                    }
                }else if(strlen($this->truncate)){
                        $consulta = $this->truncate.$this->where;
                        $truncateSt = $pdo->prepare($consulta);
                        if ($truncateSt->execute()) {
                            return $this->GetMensaje("truncate", "ok", "Se ha vaciado la tabla.", $consulta, NULL, NULL, NULL);
                        } else {
                            return $this->GetMensaje("truncate", "error", "No se ha vaciado la tabla.", $consulta, NULL, NULL, NULL);
                        }
                }else if(strlen($this->sql)){
                    $consulta = $this->sql;
                    var_dump($consulta);
                    $sqlSt = $pdo->prepare($consulta);
                    if ($sqlSt->execute()) {
                        return $this->GetMensaje("sql", "ok", "Secuencia SQl personalizada .", $consulta, NULL, NULL, NULL);
                    } else {
                        return $this->GetMensaje("sql", "error", "No se ha realizado la consulta.", $consulta, NULL, NULL, NULL);
                    }
                }else if(strlen($this->match)){
                    $consulta = $this->seleccion.$this->match.$this->limit;
                    //var_dump($consulta);
                        $data='';
                        $numfilas=$id=0;
                        if(strlen($consulta)>0){
                            $data = $pdo->query($consulta)->fetchAll();
                            $totalfilas = "SELECT COUNT(*) FROM (".$this->seleccion.$this->match.') as temp';
                            //var_dump($totalfilas);
                            $numfilas = $pdo->query($totalfilas)->fetchColumn();
                            $id = $pdo->lastInsertId(); 
                        }                   
                        return $this->GetMensaje("match", "ok", "Consulta realizada .", $consulta, $data, $numfilas, NULL);
                    
                }
                else{
                    $consulta = $this->seleccion.$this->join.$this->where.$this->group.$this->orden.$this->limit;
                    //var_dump($consulta);
                    $data='';
                    $numfilas=$id=0;
                    if(strlen($consulta)>0){
                        $data = $pdo->query($consulta)->fetchAll();
                        $totalfilas = "SELECT COUNT(*) FROM (".$this->seleccion.$this->join.$this->where.$this->group.') as temp';
                        $numfilas = $pdo->query($totalfilas)->fetchColumn();
                        $id = $pdo->lastInsertId(); 
                    }                   
                    return $this->GetMensaje("select", "ok", "Se ejecuto la consulta con exito.", $consulta, $data, $numfilas, $id);
                }
                
            }catch(PDOException $error){
                return $this->GetMensaje("todas", "error", $error->getMessage(), $consulta, NULL, NULL, NULL);
            }
        }

        private function GetMensaje($desde, $estado, $mensaje, $consulta, $datos, $numfilas, $id){
            return [
                "desde" => $desde,
                "estado" => $estado,
                "mensaje" => $mensaje,
                "datos" => $datos,
                "numfilas" => $numfilas,
                "consulta" => $consulta, 
                "id" => $id
            ];
        }
        public function Limpiar(){
            $this->seleccion = "";
            $this->where = "";
            $this->match = "";
            $this->join = "";
            $this->limit = "";
        
            //Insert data
            $this->insert = "";
            
            $this->update = "";
            $this->delete = "";
            $this->truncate = "";
            $this->sql = '';
        }  

    }
}