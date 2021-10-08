<?php
    require_once(dirname(__FILE__)."/../consultas.php");
    use App\Consultas;
    switch($argv[1]){
        case 'test': 
            if($argv[2] != NULL){
                $a = new Consultas($argv[2]);
                $cancel = false;
                while(!$cancel){
                    print("\nSeleccion una opcion:\n 1-Select\n 2-Where\n 3-Join\n 4-Insertar\n 5-Update\n 6-Delete\n 0-Ejecutar\n 7-Limit\nSelecciono: ");
                    $handle = fopen ("php://stdin","r");
                    $line = fgets($handle);
                    switch(trim($line)){
                        case 0:
                            $cancel = true; break;
                        case 1: 
                        print("\nAgregue atributos para la seleccion[*]: ");
                        $handle = fopen ("php://stdin","r");
                        $attr = fgets($handle);
                            if(strlen(trim($attr)) > 0){
                                $a = $a->Select(trim($attr));
                            }else{
                                $a = $a->Select();
                            }
                             break;
                        case 2:
                            print("\nAgregue atributo: ");
                            $handle = fopen ("php://stdin","r");
                            $attr = fgets($handle);
                            print("\nAgregue valor: ");
                            $handle = fopen ("php://stdin","r");
                            $valor = fgets($handle);
                            $a = $a->Where(trim($attr), trim($valor)); 
                            break;
                        case 3:
                            print("\nAtributo a: ");
                            $handle = fopen ("php://stdin","r");
                            $attr = fgets($handle);
                            print("\nAgregue tabla intermedia: ");
                            $handle = fopen ("php://stdin","r");
                            $modelo = fgets($handle);
                            
                            print("\nTipo de join: ");
                            $handle = fopen ("php://stdin","r");
                            $tipo = fgets($handle);

                            print("\nAtributo b: ");
                            $handle = fopen ("php://stdin","r");
                            $b = fgets($handle);
                            
                            print("\nTabla b: ");
                            $handle = fopen ("php://stdin","r");
                            $tablaB = fgets($handle);

                            $a->Join(trim($attr), trim($modelo), trim($tipo), trim($tablaB), trim($b));
                            break;
                        case 4:
                            $nombres = [];
                            $valores = [];
                            $term = false;
                            while(!$term){
                                print("\nAgregue atributo: ");
                                $handle = fopen ("php://stdin","r");
                                $attr = fgets($handle);
                                print("\nAgregue valor: ");
                                $handle = fopen ("php://stdin","r");
                                $valor = fgets($handle);
                                array_push($nombres, trim($attr));
                                array_push($valores, trim($valor));
                                print("\nAgregar atributos:\n 1- Seguir agregando\n 0- Parar\n Seleccione: ");
                                $handle = fopen ("php://stdin","r");
                                $sele = fgets($handle);
                                switch($sele){
                                    case 1: break;
                                    case 0: $term = true; break;
                                }
                            }
                            $a->Insert($nombres, $valores);
                            break;
                        case 5:
                            $nombres = [];
                            $valores = [];
                            $term = false;
                            while(!$term){
                                print("\nAgregue atributo: ");
                                $handle = fopen ("php://stdin","r");
                                $attr = fgets($handle);
                                print("\nAgregue valor: ");
                                $handle = fopen ("php://stdin","r");
                                $valor = fgets($handle);
                                array_push($nombres, trim($attr));
                                array_push($valores, trim($valor));
                                print("\nAgregar atributos:\n 1- Seguir agregando\n 0- Parar\n Seleccione: ");
                                $handle = fopen ("php://stdin","r");
                                $sele = fgets($handle);
                                switch($sele){
                                    case 1: break;
                                    case 0: $term = true; break;
                                }
                            }
                            $a->Update($nombres, $valores);
                            break;
                        case 6:
                            $a->Delete();
                            break;
                        case 7:
                            print("\nAgregue start: ");
                            $handle = fopen ("php://stdin","r");
                            $start = fgets($handle);
                            print("\nAgregue max: ");
                            $handle = fopen ("php://stdin","r");
                            $max = fgets($handle);
                            $a->Limit($start, $max); 
                            break;                            
                        default:
                            printf("\nSeleccione una opcion correcta.\n");
                    }
                }
                $data = $a->Ejecutar();
                $result = "Desde: ".$data["desde"]."\n"
                         ."Estado: ".$data["estado"]."\n"
                         ."Consulta: ".$data["consulta"]."\n"
                         ."Mensaje: ".$data["mensaje"]."\n";
                
                
                if($data["datos"] != NULL){
                    $result .= "Resultados:\n";
                    
                    foreach ($data["datos"] as $row) {
                        foreach($row as $i){
                            $result .= $i." ";
                        }
                        $result .= "\n";
                    }
                }    
                   
                printf("\n".$result);
            }else{
                echo("Se debe enviar la tabla.\n");
            }
        break;
        default: printf("No es un valor valido\n");
    }
