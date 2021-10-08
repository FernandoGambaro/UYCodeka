<?php
class Encryption {
    var $skey = "1324657981324657"; // you can change it
    const METHOD = 'AES-256-CBC';

    /**vector
     * @var string
     */
    const IV = "1234567890123412";//16 bits
    /**
     * Default secret key
     */
    const KEY = '201707eggplant99';//16 bits


 
    public  function encode($password, $key = self::KEY,$iv = self::IV, $method = self::METHOD){ 
        if(!$password){return false;}
        return base64_encode(openssl_encrypt($password,$method,$key,OPENSSL_RAW_DATA,$iv));
      
    }

    public function decode($password, $key = self::KEY,$iv = self::IV, $method = self::METHOD){
        if(!$password){return false;}

//        $key = hash('sha256', $password);
        return openssl_decrypt(base64_decode($password),$method,$key,OPENSSL_RAW_DATA,$iv);


    }

}

?>