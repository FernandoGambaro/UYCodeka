<?php

if (!$s = new session()) {
    echo "<h2>Ocurrió un error al iniciar session!</h2>";
    echo $s->log;
    exit();
}

$languages=array('es'=>array('utf8'=>'es_ES.utf8', 'flag'=>'es', 'lang'=>'Español'),
                 'en'=>array('utf8'=>'en_US.utf8', 'flag'=>'us', 'lang'=>'English')
                );

    if ( isset( $_REQUEST['lang'] ) ) {
        if($_REQUEST['lang']=='') {
            $lang='es';
        } else {
            $lang=$_REQUEST['lang'];
        }
    } else {
        $lang = isset($s->data['language'])? $s->data['language']: 'es';
    }


    $locale = $languages[$lang]['utf8'];
    putenv("LC_ALL=$locale");
    setlocale(LC_ALL, $locale);
    
    $domain = "messages";
    bindtextdomain($domain,  __DIR__ ."/locale");
    bind_textdomain_codeset($domain, 'utf8');
    textdomain($domain); 

?>