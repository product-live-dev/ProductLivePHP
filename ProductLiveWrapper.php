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
        $marketingLibelleArticleAttribute = new Marketing("libelle_article", array(new NameLang("Libelle article", LANG::FR)), true, DATA_TYPE::TEXT);
        $marketingLibelleLongAttribute = new Marketing("libelle_long", array(new NameLang("Libelle long", LANG::FR)), true, DATA_TYPE::RICHTEXT);
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
            $bdd = new PDO('mysql:host=localhost;dbname=node;charset=utf8', 'root', '');    // Change with your settings
        }
        catch (Exception $e)
        {
            die('Erreur : ' . $e->getMessage());
        }
        // Find colors
        $valuesColor = array();
        $reponse = $bdd->query('SELECT * FROM mycolortable');   // Change with your color table
        while ($donnees = $reponse->fetch())
        {
            array_push($valuesColor, new SingleOption($donnees['id'], array(new NameLang($donnees['nameFR'], "FR")), $donnees['code'], "", ""));
        }
        $colors = new Pivot("color", array(new NameLang("Couleur", "FR")), "singleOption", $valuesColor);
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
    
    }
    
    /*
     *  ___________                                    ______________
     * |           |                                  |              |
     * | My system | <----------- Product ----------- | Product-Live |
     * |___________|                                  |______________|
     *
     * Do not change the name of this function
     */
    function updateProductFromProductLiveToMyIT() {
    
    }
}
