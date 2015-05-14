<?php

require_once(__DIR__.'/../Resources/Data.php');
require_once(__DIR__.'/../Resources/Util.php');

class identificationStrategyProductLive {
	public $plStrategyName = "";
	public $plType = "";
	public $value = "";

	function __construct($plStrategyName, $plType, $value)
    {
        $this->plStrategyName = $plStrategyName;
        $this->plType = $plType;
        $this->value = $value;
    }
}

class modelProductLive {
	public $idModelPl = "";
	public $idModel = "";
	public $identificationsStrategies = array();
	public $families = array();
	public $marketing = array();
	public $features = array();
	public $media = array();
	public $nodes = array();

	function __construct($idModelPl, $idModel, $identificationsStrategies, $families, $marketing, $features, $media)
    {
        $this->idModelPl = $idModelPl;
        $this->idModel = $idModel;
        $this->identificationsStrategies = $identificationsStrategies;
        $this->families = $families;
        $this->marketing = $marketing;
        $this->features = $features;
        $this->media = $media;
    }
}

class combinationProductLive {
	public $idCombinationPl = "";
	public $idCombination = "";
	public $identificationsStrategies = array();
	public $pivots = array();
	public $media = array();
	public $nodes = array();

	function __construct($idCombinationPl, $idCombination, $identificationsStrategies, $pivots, $media)
	{
		$this->idCombinationPl = $idCombinationPl;
		$this->idCombination = $idCombination;
		$this->identificationsStrategies = $identificationsStrategies;
		$this->pivots = $pivots;
		$this->media = $media;
	}
}

class ProductMessage {
	public $idProductPl = "";
	public $messageVersion = "1.0";
	public $active = "";
	public $model = array();
	public $combinations = array();

	function __construct($idProductPl, $active, $model, $combinations)
    {
    	$this->messageVersion = "1.0";
        $this->idProductPl = $idProductPl;
        $this->active = $active;
        $this->model = $model;
        $this->combinations = $combinations;
    }
}