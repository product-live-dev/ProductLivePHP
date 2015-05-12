<?php

require_once(__DIR__.'/../Resources/Data.php');
require_once(__DIR__.'/../Resources/Util.php');

class contact {
    public $idContact = "";
    public $plType = 0;
    public $displayName = array();
    public $urlImage = "";
    public $emails = array();
    public $phones = array();
    public $isActive = true;

    function __construct($idContact, $plType, $displayName, $urlImage, $emails, $phones, $isActive)
    {
        $this->idContact = $idContact;
        $this->plType = $plType;
        $this->displayName = $displayName;
        $this->urlImage = $urlImage;
        $this->emails = $emails;
        $this->phones = $phones;
        $this->isActive = $isActive;
    }
}

class contacts {
    public $apiVersion = "1.0";
    public $idNode = "";
    public $contacts = array();

    function __construct($idNode, $contacts)
    {
        $this->apiVersion = "1.0";
        $this->idNode = $idNode;
        $this->contacts = $contacts;
    }

}