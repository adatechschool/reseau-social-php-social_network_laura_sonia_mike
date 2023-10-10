<?php
session_start();
?>
<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>ReSoC - Connexion</title> 
        <meta name="author" content="Bibi">
        <link rel="stylesheet" href="/reseau-social-php-social_network_laura_sonia_mike/Niveau1/Niveau1/style.css"/>
    </head>
    <body>
        <style>body {background-image: url('/reseau-social-php-social_network_laura_sonia_mike/Niveau1/Niveau1/background.jpg');}</style> 
    <header>
    <a href='/reseau-social-php-social_network_laura_sonia_mike/Niveau1/Niveau1/admin.php'>
        <img src="/reseau-social-php-social_network_laura_sonia_mike/Niveau1/Niveau1/logo-principal.gif"
        alt="Logo de notre réseau social"/>
    </a>
    <nav id="menu">
    <a href="/reseau-social-php-social_network_laura_sonia_mike/Niveau2/Niveau2/login.php">Connexion</a>
    <a href="/reseau-social-php-social_network_laura_sonia_mike/Niveau1/Niveau1/news.php">Actualités</a>
    <a href="/reseau-social-php-social_network_laura_sonia_mike/Niveau1/Niveau1/wall.php?user_id=<?php echo $_SESSION['connected_id']?>">Mur</a>
    <a href="/reseau-social-php-social_network_laura_sonia_mike/Niveau1/Niveau1/feed.php?user_id=<?php echo $_SESSION['connected_id']?>">Flux</a>
    <a href="/reseau-social-php-social_network_laura_sonia_mike/Niveau1/Niveau1/tags.php?tag_id=1">Mots-clés</a>
</nav>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Borel&family=Bungee+Shade&family=Pacifico&family=Poppins:wght@200;500;600&display=swap" rel="stylesheet">
<nav id="title">Chat-badab-ADA</nav>
<nav id="user">
    <a href="#">▾ Profil</a>
    <ul>
        <li><a href="/reseau-social-php-social_network_laura_sonia_mike/Niveau1/Niveau1/settings.php?user_id=<?php echo $_SESSION['connected_id']?>">Paramètres</a></li>
        <li><a href="/reseau-social-php-social_network_laura_sonia_mike/Niveau1/Niveau1/followers.php?user_id=<?php echo $_SESSION['connected_id']?>">Mes suiveurs</a></li>
        <li><a href="/reseau-social-php-social_network_laura_sonia_mike/Niveau1/Niveau1/subscriptions.php?user_id=<?php echo $_SESSION['connected_id']?>">Mes abonnements</a></li>
    </ul>
</nav>
</header>


<?php
            /**
             * Etape 2: se connecter à la base de donnée
             */
            $mysqli = new mysqli("localhost", "root", "root", "socialnetwork");
            ?>