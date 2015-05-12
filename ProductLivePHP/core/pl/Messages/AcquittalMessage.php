<?php

    require_once(__DIR__.'/../Resources/Data.php');
    require_once(__DIR__.'/../Resources/Util.php');

    class ErrorMessageAcquittal {
    	public $code = "";
    	public $additionalInformation = "";

    	function __construct($code, $additionalInformation)
	    {
	        $this->code = $code;
	        $this->additionalInformation = $additionalInformation;
	    }
    }

    class ModelAcquittal {
        public $idModelPl = "";
    	public $idModel = "";
    	function __construct($idModelPl, $idModel)
	    {
            $this->idModelPl = strval($idModelPl);
	        $this->idModel = strval($idModel);
	    }
    }
    class CombinationAcquittal {
        public $idCombinationPl = "";
    	public $idCombination = "";
    	function __construct($idCombinationPl, $idCombination)
	    {
            $this->idCombinationPl = strval($idCombinationPl);
	        $this->idCombination = strval($idCombination);
	    }
    }

    class ProductAcquittal {
    	public $idProductPl = "";
    	public $model = "";
    	public $combinations = array();
    	function __construct($idProductPl, $model, $combinations)
	    {
	    	$this->idProductPl = strval($idProductPl);
	        $this->model = $model;
	        $this->combinations = $combinations;
	    }
    }

    class AcquittalMessage {
    	public $messageVersion = "1.0";
    	public $success = "";
    	public $date = "";
    	public $product = "";
    	public $errors = array();
    	public $messageId = "";
    	public $type = "";

    	function __construct($success, $date, $productAcquittal, $errors, $messageId, $type)
	    {
	    	$this->messageVersion = "1.0";
	        $this->success = $success;
	        $this->date = $date;
	        $this->product = $productAcquittal;
	        $this->errors = $errors;
	        $this->messageId = $messageId;
	        $this->type = $type;
	    }
    }