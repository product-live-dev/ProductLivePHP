<?php

class NameLang {
    public $name = "";
    public $lang = "";

    function __construct($name, $lang)
    {
        $this->name = $name;
        $this->lang = $lang;
    }
}

function createMessageFromCode($code) {
    $sendOk = false;
    $resultMessage = "";
    switch ($code) {
        case "201":
            $sendOk = true;
            $resultMessage = "<strong>Bravo!</strong> La demande de mise à jour a bien été envoyée. Pour suivre son traitement reportez vous aux tableaux de suivis ci-dessous.";
            break;
        case "400":
            $sendOk = false;
            $resultMessage = "<strong>Erreur 400.</strong> Demande incorrecte.";
            break;
        case "401":
            $sendOk = false;
            $resultMessage = "<strong>Erreur 401.</strong> Échec de l'autorisation. Votre clé d'identification ne doit pas être valide.";
            break;
        case "403":
            $sendOk = false;
            $resultMessage = "<strong>Erreur 403.</strong> Quota dépassé ou message trop volumineux. Contactez l'équipe le support Product-Live";
            break;
        case "410":
            $sendOk = false;
            $resultMessage = "<strong>Erreur 410.</strong> La file d'attente ou la rubrique spécifiée n'existe pas.";
            break;
        case "500":
            $sendOk = false;
            $resultMessage = "<strong>Erreur 500.</strong> Erreur interne. Veuillez réessayer plus tard. Si le problème persiste contactez le support Product-Live";
            break;
        case "1000":    // L'url du service bus n'est pas bonne.
            $sendOk = false;
            $resultMessage = "<strong>Erreur 1000.</strong> Votre clé de connexion n'est pas bonne. Si le problème persiste contactez le support Product-Live";
            break;
    }
    return array($sendOk, $resultMessage);
}


function write_php_ini($array, $file)
{
    $res = array();
    foreach($array as $key => $val)
    {
        if(is_array($val))
        {
            $res[] = "[$key]";
            foreach($val as $skey => $sval) $res[] = "$skey = ".(is_numeric($sval) ? $sval : '"'.$sval.'"');
        }
        else $res[] = "$key = ".(is_numeric($val) ? $val : '"'.$val.'"');
    }
    safefilerewrite($file, implode("\r\n", $res));
}



function safefilerewrite($fileName, $dataToSave)
{    if ($fp = fopen($fileName, 'w'))
{
    $startTime = microtime(TRUE);
    do
    {            $canWrite = flock($fp, LOCK_EX);
        // If lock not obtained sleep for 0 - 100 milliseconds, to avoid collision and CPU load
        if(!$canWrite) usleep(round(rand(0, 100)*1000));
    } while ((!$canWrite)and((microtime(TRUE)-$startTime) < 5));

    //file was locked so now we can store information
    if ($canWrite)
    {            fwrite($fp, $dataToSave);
        flock($fp, LOCK_UN);
    }
    fclose($fp);
}

}

/**
* Count the number of bytes of a given string.
* Input string is expected to be ASCII or UTF-8 encoded.
* Warning: the function doesn't return the number of chars
* in the string, but the number of bytes.
*
* @param string $str The string to compute number of bytes
*
* @return The length in bytes of the given string.
*/
function strBytes($str){
     // STRINGS ARE EXPECTED TO BE IN ASCII OR UTF-8 FORMAT
     
     // Number of characters in string
     $strlen_var = strlen($str);
     
     // string bytes counter
     $d = 0;
     
     /*
     * Iterate over every character in the string,
     * escaping with a slash or encoding to UTF-8 where necessary
     */
     for($c = 0; $c < $strlen_var; ++$c){
          $ord_var_c = ord($str{$c});
          switch(true){
              case(($ord_var_c >= 0x20) && ($ord_var_c <= 0x7F)):
               // characters U-00000000 - U-0000007F (same as ASCII)
               $d++;
               break;
              case(($ord_var_c & 0xE0) == 0xC0):
               // characters U-00000080 - U-000007FF, mask 110XXXXX
               $d+=2;
               break;
              case(($ord_var_c & 0xF0) == 0xE0):
               // characters U-00000800 - U-0000FFFF, mask 1110XXXX
               $d+=3;
               break;
              case(($ord_var_c & 0xF8) == 0xF0):
               // characters U-00010000 - U-001FFFFF, mask 11110XXX
               $d+=4;
               break;
              case(($ord_var_c & 0xFC) == 0xF8):
               // characters U-00200000 - U-03FFFFFF, mask 111110XX
               $d+=5;
               break;
              case(($ord_var_c & 0xFE) == 0xFC):
               // characters U-04000000 - U-7FFFFFFF, mask 1111110X
               $d+=6;
               break;
               default:
               $d++;
          };
     };
     return $d;
}