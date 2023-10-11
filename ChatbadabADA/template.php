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
        <style>body {background-image: url('./Images/background.jpg');}</style> 
    <header>
    <a href='admin.php'>
        <img src="./Images/logo-principal.gif"
        alt="Logo de notre réseau social"/>
    </a>
    <nav id="menu">
    <a href="login.php">Connexion</a>
    <a href="news.php">Actualités</a>
    <a href="wall.php?user_id=<?php echo $_SESSION['connected_id']?>">Mur</a>
    <a href="feed.php?user_id=<?php echo $_SESSION['connected_id']?>">Flux</a>
    <a href="tags.php?tag_id=1">Mots-clés</a>
</nav>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Borel&family=Bungee+Shade&family=Pacifico&family=Poppins:wght@200;500;600&display=swap" rel="stylesheet">
<nav id="title">Chat-badab-ADA</nav>
<nav id="user">
    
    <a href="#">▾ Profil</a>
    <ul>
        <li><a href="settings.php?user_id=<?php echo $_SESSION['connected_id']?>">Paramètres</a></li>
        <li><a href="followers.php?user_id=<?php echo $_SESSION['connected_id']?>">Mes suiveurs</a></li>
        <li><a href="subscriptions.php?user_id=<?php echo $_SESSION['connected_id']?>">Mes abonnements</a></li>
        <li><a href="logoff.php">Déconnexion</a></li>
    </ul>
</nav>
</header>


<?php
            /**
             * Etape 2: se connecter à la base de donnée
             */
            $mysqli = new mysqli("localhost", "root", "root", "socialnetwork");
            ?>