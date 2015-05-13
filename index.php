<?php

require_once('core/pl/Resources/Util.php');
require_once('core/pl/Resources/RestAPI.php');
require_once('ProductLiveWrapper.php');

// Connection String to Product-Live
$keySaved = false;
$keyInvalid = false;

if(isset($_POST['connectionString'])) {
    $connectionString64 = base64_decode($_POST['connectionString']);
    $connectionStringJson = json_decode($connectionString64, true);
    
    if (isset($connectionStringJson['topic'])
        && isset($connectionStringJson['sender'])
        && isset($connectionStringJson['subscription'])
        && isset($connectionStringJson['receiveMessageToken'])
        && isset($connectionStringJson['sendMessageToken'])
        && isset($connectionStringJson['plTopic'])
    ) {
        $connectionStringJson['connectionStringProductLive'] = $_POST['connectionString'];
        write_php_ini($connectionStringJson, __DIR__."/core/pl/config.ini");
        $keySaved = true;
    } else {
        $keyInvalid = true;
    }
}

// Database
$databaseSaved =  false;
$databaseInvalid = false;

if(isset($_POST['hostname']) && isset($_POST['dbname']) && isset($_POST['username']) && isset($_POST['password'])) {
    $host = str_replace("http://", "", $_POST['hostname']);
    try
    {
        $bdd = new PDO('mysql:host='.$host.';dbname='.$_POST['dbname'].';charset=utf8', $_POST['username'], $_POST['password']);    // Change with your settings
        $database = array(
            "hostname" => $host,
            "dbname" => $_POST['dbname'],
            "username" => $_POST['username'],
            "password" => $_POST['password']            
        );
        write_php_ini($database, __DIR__."/config.ini");
        $databaseSaved = true;
    }
    catch (Exception $e)
    {
        // die('Erreur : ' . $e->getMessage());
        $databaseInvalid = true;
    }
}

// Actions - Updates and start service

if(isset($_GET['update'])) {
    if ($_GET['update'] == 'matrix') {
        // Get the message
        $pl = new ProductLiveWrapper();
        $matrixMessage = $pl->updateMatrixFromMyITToProductLive();
        var_dump($matrixMessage);
        // Send the message
        $rest = new RestAPI();
        $response = $rest->postMessage($matrixMessage, "matrix", "create");
        $matrixResponse = createMessageFromCode($response['http_code']);
    }
}



// Settings

$productLiveConfig = parse_ini_file(__DIR__."/core/pl/config.ini");
$connectionStringProductLive = $productLiveConfig['connectionStringProductLive'];

$databaseConfig = parse_ini_file(__DIR__."/config.ini");
$hostname = $databaseConfig['hostname'];
$dbname = $databaseConfig['dbname'];
$username = $databaseConfig['username'];
$password = $databaseConfig['password'];

?>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Product-Live Service Bus</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="core/css/bootstrap.min.css">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="core/js/html5shiv.min.js"></script>
    <script src="core/js//respond.min.js"></script>
    <![endif]-->

</head>
<body>
<div class="container">
    <h1>Bienvenue sur Product-Live Service Bus</h1>

    <div class="page-header">
        <h1>Param&ecirc;tres <small>Vos identifiants de connexion</small></h1>
    </div>
    <form  method="post" action="<?php $_SERVER['REQUEST_URI'] ?>">
        <div class="form-group">
            <label for="connectionString">Votre cl&eacute; de connexion Product-Live</label>
            <br/>
            <input class="form-control" style="width: 1000px; display: inline;" id="connectionString" name="connectionString" placeholder="Entrez votre cl&eacute; de connexion" <?php if (isset($connectionStringProductLive)) {echo 'value="'.$connectionStringProductLive.'""';}?>>
            <button type="submit" class="btn btn-primary" style="display: inline;">Enregistrer la cl&eacute;</button>
        </div>
    </form>
    <?php
        if ($keySaved == true) {
            echo '
                <div style="clear: both;">
                    <div class="alert alert-success" role="alert">
                        <strong>Bravo!</strong> Votre cl&eacute; de connexion est au bon format et a bien &eacute;t&eacute enregistr&eacute;e.
                    </div>
                </div>
            ';
        } else if ($keyInvalid == true) {
            echo '
                <div style="clear: both;">
                    <div class="alert alert-danger" role="alert">
                        <strong>Oups!</strong> Votre cl&eacute; de connexion n\'est pas valide.
                    </div>
                </div>
            ';
        }
    ?>
    <br/>
    <form method="post" action="<?php $_SERVER['REQUEST_URI'] ?>">
        <div class="form-group">
            <label for="hostname">Les identifiants de connexion &agrave; votre base de donn&eacute;e</label>
            <br/>
            <input class="form-control" id="hostname" name="hostname" placeholder="Hostname" <?php if (isset($hostname)) {echo 'value="'.$hostname.'""';}?> style="width: 270px; display: inline;">
            <input class="form-control" id="dbname" name="dbname" placeholder="Database name" <?php if (isset($dbname)) {echo 'value="'.$dbname.'""';}?> style="width: 220px; display: inline;">
            <input class="form-control" id="username" name="username" placeholder="User name" <?php if (isset($username)) {echo 'value="'.$username.'""';}?> style="width: 220px; display: inline;">
            <input type="password" class="form-control" id="password" name="password" placeholder="Password" <?php if (isset($password)) {echo 'value="'.$password.'""';}?> style="width: 220px; display: inline;">
            <button type="submit" class="btn btn-primary" style="display: inline;">Enregistrer les identifiants</button>
        </div>
    </form>
    <?php
        if ($databaseSaved == true) {
            echo '
                <div style="clear: both;">
                    <div class="alert alert-success" role="alert">
                        <strong>Bravo!</strong> Les identifiants sont bons et ont bien &eacute;t&eacute enregistr&eacute;s.
                    </div>
                </div>
            ';
        } else if ($databaseInvalid == true) {
            echo '
                <div style="clear: both;">
                    <div class="alert alert-danger" role="alert">
                        <strong>Oups!</strong> Impossible de se connecter avec ces identifiants.
                    </div>
                </div>
            ';
        }
    ?>

    <div class="page-header">
        <h1>Mise &agrave; jour des structures <small>Cliquez sur une des structures</small></h1>
    </div>
    <div>
        <h2>Taxonomie</h2>
        <form  method="post" action="?update=matrix" style="display: inline;">
            <button type="submit" class="btn btn-primary <?php if ($keyInvalid==true | $connectionStringProductLive==="") echo 'disabled'; ?>">Mettre &agrave; jour la "Matrix"</button>
        </form>
        <button type="submit" class="btn btn-primary <?php if ($keyInvalid==true | $connectionStringProductLive==="") echo 'disabled'; ?>" style="margin-left: 10px;">Mettre &agrave; jour les "families"</button>
        
        <?php
            if (isset($matrixResponse) && $matrixResponse[0] == true) {
                echo '<br/><br/>
                <div class="alert alert-success" role="alert">
                    '.$matrixResponse[1].'
                </div>';
            } else if (isset($matrixResponse) && $matrixResponse[0] == false) {
                echo '<br/><br/>
                <div class="alert alert-danger" role="alert">
                    '.$matrixResponse[1].'
                </div>';
            }
        ?>
        <h2>Contacts</h2>
        <button type="submit" class="btn btn-primary <?php if ($keyInvalid==true | $connectionStringProductLive==="") echo 'disabled'; ?>">Mettre &agrave; jour les "contacts"</button>
    </div>
    <div class="page-header">
        <h1>Mise &agrave; jour des produits <small>S&eacute;lectionnez un nombre de produits</small></h1>
    </div>
    <div>
        <div style="float:left;">
            <div class="form-group">
                <label class="col-xs-5 control-label">Nombre de produits</label>
                <div class="col-xs-5 selectContainer">
                    <select name="products" class="form-control">
                        <option value="3">3</option>
                        <option value="10">10</option>
                        <option value="100">100</option>
                        <option value="all">Tous</option>
                    </select>
                </div>
            </div>
        </div>
        <div style="float:left;">
            <button type="submit" class="btn btn-primary <?php if ($keyInvalid==true | $connectionStringProductLive==="") echo 'disabled'; ?>">Mettre &agrave; jour les produits</button>
        </div>
    </div>
    <br/><br/>
    <div class="page-header">
        <h1>Service de message <small>D&eacute;marrer le service</small></h1>
    </div>
    <div>
        <div class="alert alert-warning" role="alert">
            <strong>Information</strong> Le service de message n'est pas en marche
        </div>
        <button type="submit" class="btn btn-primary" style="display: inline;">D&eacute;marrer le service de message</button>
    </div>
    <br/>
    <br/>
    <br/>
    <center>
        &copy;Product-Live
    </center>
</div>


<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="core/js/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="core/js/bootstrap.min.js"></script>

</body>
</html>


