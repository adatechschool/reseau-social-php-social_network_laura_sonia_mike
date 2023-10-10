<?php
session_start();
?>
<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>ReSoC - Connexion</title> 
        <meta name="author" content="Bibi">
        <link rel="stylesheet" href="style.css"/>
    </head>
    <body>
    <style>body {background-image: url('background.jpg');}</style>
        <header>
<a href='/reseau-social-php-social_network_laura_sonia_mike/Niveau1/Niveau1/admin.php'><img src="logo-principal.gif" alt="Logo de notre réseau social"/></a>
<nav id="menu">
    <a href="/reseau-social-php-social_network_laura_sonia_mike/Niveau1/Niveau1//news.php">Actualités</a>
    <a href="/reseau-social-php-social_network_laura_sonia_mike/Niveau1/Niveau1/wall.php?user_id=5">Mur</a>
    <a href="/reseau-social-php-social_network_laura_sonia_mike/Niveau1/Niveau1/feed.php?user_id=5">Flux</a>
    <a href="/reseau-social-php-social_network_laura_sonia_mike/Niveau1/Niveau1/tags.php?tag_id=1">Mots-clés</a>
</nav>
<nav id="user">
    <a href="#">▾ Profil</a>
    <ul>
        <li><a href="/reseau-social-php-social_network_laura_sonia_mike/Niveau1/Niveau1/settings.php?user_id=1">Paramètres</a></li>
        <li><a href="/reseau-social-php-social_network_laura_sonia_mike/Niveau1/Niveau1/followers.php?user_id=5">Mes suiveurs</a></li>
        <li><a href="/reseau-social-php-social_network_laura_sonia_mike/Niveau1/Niveau1/subscriptions.php?user_id=5">Mes abonnements</a></li>
    </ul>
</nav>
</header>


<?php
            /**
             * Etape 2: se connecter à la base de donnée
             */
            $mysqli = new mysqli("localhost", "root", "root", "socialnetwork");
            ?>