<?php
// Analyse sans sections
$ini_array = parse_ini_file("config.ini");
$node = $ini_array['node'];
$connectionStringProductLive = $ini_array['connectionStringProductLive'];

//if(isset($_GET["update"]) && $_GET["update"] === "matrix") echo "matrix\n";

// TODO Mettre &agrave; jour le fichier de config
if(isset($_POST['connectionString'])) {

}
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
        <h1>Param&ecirc;tres <small>Votre identifiant de connexion</small></h1>
    </div>
    <form  method="post" action="<?php $_SERVER['REQUEST_URI'] ?>">
        <div class="form-group">
            <label for="connectionString">Votre cl&eacute; de connexion</label>
            <input class="form-control" id="connectionString" name="connectionString" placeholder="Entrez votre cl&eacute; de connexion" <?php if (isset($connectionStringProductLive)) {echo 'value="'.$connectionStringProductLive.'""';}?>>
        </div>

        <button type="submit" class="btn btn-primary" style="float: right;">D&eacute;marrer le service</button>
    </form>

    <div class="page-header">
        <h1>Mise &agrave; jour des structures <small>Cliquez sur une des structures</small></h1>
    </div>
    <div>
        <p>
            <form  method="post" action="?update=matrix" style="display: inline;">
                <button type="submit" class="btn btn-primary">Envoyer le message Matrix</button>
            </form>
            <button type="submit" class="btn btn-primary">Envoyer le message Families</button>
            <button type="submit" class="btn btn-primary">Envoyer le message Contacts</button>
        </p>
    </div>
    <div class="page-header">
        <h1>Mise &agrave; jour des produits <small>S&eacute;lectionnez un nombre de produits</small></h1>
    </div>
    <div>
        <div style="float:left;">
            <div class="dropdown">
                <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="true">
                    Nombre de produits
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
                    <li role="presentation"><a role="menuitem" tabindex="-1" href="#">3</a></li>
                    <li role="presentation"><a role="menuitem" tabindex="-1" href="#">10</a></li>
                    <li role="presentation"><a role="menuitem" tabindex="-1" href="#">100</a></li>
                    <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Tous</a></li>
                </ul>
            </div>
        </div>
        <div style="float:left; margin-left: 10px;">
            <button type="submit" class="btn btn-primary">Mettre &agrave; jour les produits</button>
        </div>
    </div>
</div>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="core/js/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="core/js/bootstrap.min.js"></script>

</body>
</html>


