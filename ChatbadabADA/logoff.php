<!-- Le lien déconnexion ramène sur cette page -->
<?php
  session_destroy(); //on supprime toutes les variables dans $_SESSION
  header("Location: /reseau-social-php-social_network_laura_sonia_mike/ChatbadabADA/login.php"); //On redirige vers la page de connexion
?>