<?php

require_once(__DIR__.'/../Resources/Data.php');
require_once(__DIR__.'/../Resources/Util.php');

class featForFamily {
    public $idName = "";
    public $mandatory = false;

    function __construct($idName, $mandatory)
    {
        $this->idName = $idName;
        $this->mandatory = $mandatory;
    }

}

class family {
    public $idName = "";
    public $idNameParent = "";
    public $displayName = array();
    public $features = array();

    function __construct($idName, $idNameParent, $displayName, $features)
    {
        $this->idName = $idName;
        $this->idNameParent = $idNameParent;
        $this->displayName = $displayName;
        $this->features = $features;
    }

}

class FamiliesMessage {
    public $messageVersion = "1.0";
    public $families = array();

    function __construct($families)
    {
        $this->messageVersion = "1.0";
        $this->families = $families;
    }
}