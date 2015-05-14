<?php

require_once(__DIR__.'/core/pl/Messages/MatrixMessage.php');
require_once(__DIR__.'/core/pl/Messages/FamiliesMessage.php');
require_once(__DIR__.'/core/pl/Messages/ProductMessage.php');
require_once(__DIR__.'/core/pl/Messages/AcquittalMessage.php');
require_once(__DIR__.'/core/pl/Messages/Contacts.php');
require_once(__DIR__.'/core/pl/Resources/RestAPI.php');
require_once(__DIR__.'/core/pl/Resources/Util.php');

class ProductLiveWrapper
{

    public $hostname = "";
    public $dbname = "";
    public $username = "";
    public $password = "";

    function __construct()
    {
        // Product-Live config
        $databaseConfig = parse_ini_file(__DIR__."/config.ini");
        $this->hostname = $databaseConfig['hostname'];
        $this->dbname = $databaseConfig['dbname'];
        $this->username = $databaseConfig['username'];
        $this->password = $databaseConfig['password'];
    }

    /*
     *  ___________                                   ______________
     * |           |                                 |              |
     * | My system | ----------- Matrix -----------> | Product-Live |
     * |___________|                                 |______________|
     *
     * Do not change the name of this function
     */
    function updateMatrixFromMyITToProductLive() {
    
        // Create marketing attributes
        $marketing = array();
        $marketingLibelleArticleAttribute = new Marketing("libelle_article", array(new NameLang("Libellé article", LANG::FR)), true, DATA_TYPE::TEXT);
        $marketingLibelleLongAttribute = new Marketing("libelle_long", array(new NameLang("Libellé long", LANG::FR)), true, DATA_TYPE::RICHTEXT);
        $marketingDescriptifAttribute = new Marketing("descriptif", array(new NameLang("Descriptif", LANG::FR)), true, DATA_TYPE::RICHTEXT);
        array_push($marketing, $marketingLibelleArticleAttribute);
        array_push($marketing, $marketingLibelleLongAttribute);
        array_push($marketing, $marketingDescriptifAttribute);
        
        // Pivots
        $pivots = array();
        
        // Features
        $features = array();
        
        // Create the matrix message
        $matrixMessage = new MatrixMessage(
            $marketing,
            $pivots,
            $features
        );
        
        return $matrixMessage;    
    }
    
    /*
     *  ___________                                     ______________
     * |           |                                   |              |
     * | My system | ----------- Families -----------> | Product-Live |
     * |___________|                                   |______________|
     *
     * Do not change the name of this function
     */
    function updateFamiliesFromMyITToProductLive() {
        try
        {
            $bdd = new PDO('mysql:host='.$this->hostname.';dbname='.$this->dbname.';charset=utf8', $this->username, $this->password);
        }
        catch (Exception $e)
        {
            die('Erreur : ' . $e->getMessage());
        }
        // Families
        $families = array();
        $reponse = $bdd->query('SELECT * FROM family');
        while ($donnees = $reponse->fetch())
        {
            $family = new family($donnees['idfamily'], (isset($donnees['idFamilyParent']) ? $donnees['idFamilyParent'] : ""), array(new NameLang($donnees['name'], LANG::FR)), array());
            array_push($families, $family);
        }
        
        $familyMessage = new FamiliesMessage($families);

        return $familyMessage;
    }
    
    /*
     *  ___________                                     ______________
     * |           |                                   |              |
     * | My system | ----------- Contacts -----------> | Product-Live |
     * |___________|                                   |______________|
     *
     * Do not change the name of this function
     */
    function updateContactsFromMyITToProductLive() {
    
    }
    
    /*
     *  ___________                                    ______________
     * |           |                                  |              |
     * | My system | ----------- Product -----------> | Product-Live |
     * |___________|                                  |______________|
     *
     * Do not change the name of these functions
     */
    function updateProductsFromMyITToProductLive($totalProducts) {
        try
        {
            $bdd = new PDO('mysql:host='.$this->hostname.';dbname='.$this->dbname.';charset=utf8', $this->username, $this->password);
        }
        catch (Exception $e)
        {
            die('Erreur : ' . $e->getMessage());
        }
        // Products
        $productsMessage = array();
        $limit = "";
        if ($totalProducts!="all")
            $limit =  ' LIMIT '.$totalProducts;
        $reponse = $bdd->query('SELECT * FROM product'.$limit);
        while ($donnees = $reponse->fetch())
        {
            $plStrategyName = "Code article";
            $plType = IDENTIFICATION_TYPE::INTERNAL;
            $value = $donnees['code_article'];
            $identificationsStrategies = array(new identificationStrategyProductLive($plStrategyName, $plType, $value));
            $idModelPl = "";
            $idModel = $donnees['idproduct'];
            $families = array(array("idName"=>$donnees['family_idfamily']));
            $marketing = array(array(
                    "idName" => "libelle_article",
                    "values" => array(array("value"=>$donnees['libelle_article'], "lang"=>LANG::FR))
                    ),
                array(
                        "idName" => "libelle_long",
                        "values" => array(array("value"=>$donnees['libelle_long'], "lang"=>LANG::FR))
                    ),
                array(
                        "idName" => "descriptif",
                        "values" => array(array("value"=>$donnees['descriptif'], "lang"=>LANG::FR))
                    )
            );
            $media = array(
                "images" => array(
                        array(
                            "idName" => $donnees['image1'],
                            "url" => $donnees['image1'],
                            "position" => "1"
                            )
                    )
                );
            $features = array();
            $nodes = array();
            $model = new modelProductLive($idModelPl, $idModel, $identificationsStrategies, $families, $marketing, $features, $media);
            $idProductPl = "";
            $active = true;
            $combinations = array();
            $productMessage = new ProductMessage($idProductPl, $active, $model, $combinations);
            array_push($productsMessage, $productMessage);
        }

        return $productsMessage;
    }
    
    /*
     *  ___________                                    ______________
     * |           |                                  |              |
     * | My system | <----------- Product ----------- | Product-Live |
     * |___________|                                  |______________|
     *
     * Do not change the name of this function
     */
    function updateProductFromProductLiveToMyIT($productMessage) {
        // Update product table
        try
        {
            $bdd = new PDO('mysql:host='.$this->hostname.';dbname='.$this->dbname.';charset=utf8', $this->username, $this->password);
            $idModel = $productMessage['model']['idModel'];
            $marketings = $productMessage['model']['marketing'];
            foreach ($marketings as $marketing) {
                if ($marketing['idName'] == "libelle_article")
                    $libelle_article = $marketing['values'][0]['value'];
                if ($marketing['idName'] == "libelle_long")
                    $libelle_long = $marketing['values'][0]['value'];
                if ($marketing['idName'] == "descriptif")
                    $descriptif = $marketing['values'][0]['value'];
            }
            $reponse = $bdd->query('UPDATE product SET libelle_article=\''.$libelle_article.'\', libelle_long=\''.$libelle_long.'\', descriptif=\''.$descriptif.'\' WHERE idproduct = '.$idModel);
        }
        catch (Exception $e)
        {
            die('Erreur : ' . $e->getMessage());
        }        
    }
}