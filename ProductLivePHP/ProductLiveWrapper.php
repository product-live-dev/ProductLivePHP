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
        $marketingNameAttribute = new Marketing("name", array(new NameLang("Nom", LANG::FR)), true, DATA_TYPE::TEXT);
        $marketingShortDescriptionAttribute = new Marketing("description_short", array(new NameLang("Description courte", LANG::FR)), true, DATA_TYPE::RICHTEXT);
        $marketingLongDescriptionAttribute = new Marketing("description", array(new NameLang("Description longue", LANG::FR)), true, DATA_TYPE::RICHTEXT);
        $marketing = array($marketingNameAttribute, $marketingShortDescriptionAttribute, $marketingLongDescriptionAttribute);
    
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
