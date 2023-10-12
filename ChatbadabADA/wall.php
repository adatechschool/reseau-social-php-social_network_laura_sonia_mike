<?php
        include 'template.php';
        ?>

        <div id="wrapper">
            <?php
            /**
             * Etape 1: Le mur concerne un utilisateur en particulier
             * La première étape est donc de trouver quel est l'id de l'utilisateur
             * Celui ci est indiqué en parametre GET de la page sous la forme user_id=...
             * Documentation : https://www.php.net/manual/fr/reserved.variables.get.php
             * ... mais en résumé c'est une manière de passer des informations à la page en ajoutant des choses dans l'url
             */
            $userId =intval($_GET['user_id']);
            ?>
            

            <aside>
                <?php
                /**
                 * Etape 3: récupérer le nom de l'utilisateur
                 */                
                $laQuestionEnSql = "SELECT * FROM users WHERE id= '$userId' ";
                $lesInformations = $mysqli->query($laQuestionEnSql);
                $user = $lesInformations->fetch_assoc();
                ?>

                <?php
                    if(file_exists('./Images/' . $user['alias'] . '.png')) { 
                        echo '<img src="./Images/' . $user['alias'] . '.png" alt="blasonuser"/>';
                    }
                    else{
                        echo '<img src="./Images/user.jpg" alt="blason"/>';
                    } 
                    ?>

                <section>
                    <h3>Présentation</h3>
                    <p>Sur cette page vous trouverez tous les messages de l'utilisatrice : <?php echo $user['alias'] ?>
                        (n° <?php echo $userId ?>)
                    </p>
                </section>

                
<!-- Si il y a une entrée dans le formulaire -->
<?php
                $enCoursDeTraitement = isset($_POST['message']); 
                    if ($enCoursDeTraitement)
                    {
                        
                        //echo "Code d'insertion exécuté."; // Ajoutez ceci pour vérifier si le code est atteint

                        // on ne fait ce qui suit que si un formulaire a été soumis.
                        // Etape 2: récupérer ce qu'il y a dans le formulaire @todo: c'est là que votre travail se situe
                        // observez le résultat de cette ligne de debug (vous l'effacerez ensuite)
                            //echo "<pre>" . print_r($_POST, 1) . "</pre>";
                        // et complétez le code ci dessous en remplaçant les ???
                        $authorId = $_SESSION['connected_id'];      //$_POST['auteur'];
                        $postContent = $_POST['message'];


                        //Etape 3 : Petite sécurité
                        // pour éviter les injection sql : https://www.w3schools.com/sql/sql_injection.asp
                        $authorId = intval($mysqli->real_escape_string($authorId));
                        $postContent = $mysqli->real_escape_string($postContent);
                        //Etape 4 : construction de la requete
                        $lInstructionSql = "INSERT INTO posts "
                                . "(id, user_id, content, created) "
                                . "VALUES (NULL, "
                                . $authorId . ", "
                                . "'" . $postContent . "', "
                                . "NOW());"
                                ;
                        //echo $lInstructionSql;

                        
                        
                        // Etape 5 : execution de la requête SQL avec query
                        $ok = $mysqli->query($lInstructionSql);
                        

                        if ( ! $ok)
                        {
                            echo "Impossible d'ajouter le message: " . $mysqli->error;
                        } else
                        {
                            echo "Message posté en tant que : " . $authorId;
                        }
                    }
?>
<!-- Si il y a une entrée dans le formulaire (clic sur le bouton d'abonnement) -->
<?php
$enCoursDeTraitement = isset($_POST['button']);
if ($enCoursDeTraitement){
                        $stalkerId = $_SESSION['connected_id'];      //id de la personne connectée
                        $followedId = $userId;                       //id de la personne à suivre


                        //Etape 3 : Petite sécurité
                        // pour éviter les injection sql : https://www.w3schools.com/sql/sql_injection.asp
                        $stalkerId = intval($mysqli->real_escape_string($stalkerId));
                        $followedId = $mysqli->real_escape_string($followedId);
                        //Etape 4 : construction de la requete
                        $lInstructionSql = "INSERT INTO followers "
                                . "(id, followed_user_id, following_user_id) "
                                . "VALUES (NULL, "
                                . "'" . $followedId . "', "
                                . "'" . $stalkerId . "')"
                                ;
                        //echo $lInstructionSql;

$ok = $mysqli->query($lInstructionSql);
if ( ! $ok)
{
    echo "Abonnement non pris en compte !" . $mysqli->error;
}
}
?>
<!-- Si il y a une entrée dans le formulaire (clic sur le bouton like) -->
<!-- On fait une requête pour insérer le like dans la base de données -->
<?php
$enCoursDeTraitement = isset($_POST['likes']);
if ($enCoursDeTraitement){
            $postId = $_POST['post_id'];
    $lInstructionSql = "INSERT INTO likes "
                                    . "(id, user_id, post_id) "
                                    . "VALUES (NULL, "
                                    . "'" . $_SESSION['connected_id'] . "', "
                                    . "'" . $postId . "') "
                                    ;
    $lesInformations = $mysqli->query($lInstructionSql);
    }
?>

<!-- Si l'utilisateur connecté est le même que l'utilisateur dont le mur est affiché -->
<!-- On affiche le champ pour écrire un message sur le mur -->
<?php if ($_SESSION['connected_id'] == $userId): ?>
    <form action="wall.php?user_id=<?php echo $userId ?>" id="messageForm" method="post">
        <input type="hidden" name="user_id" value="<?php echo $_SESSION['connected_id']; ?>">
        <dl>
            <dt><label for="message">Écris sur ton mur :</label></dt><br>
            <dd><textarea rows="10" cols="30" name="message" id="message"></textarea></dd>
        </dl>
        <input type="submit" value="Envoyer">
    </form>
<!-- Sinon si l'utilisateur connecté n'est pas le même que l'utilisateur dont le mur est affiché -->
<!-- On vérifie dans la base si l'abonnement existe déjà -->
<?php else:
    $connected_id = $_SESSION['connected_id'];
    $requeteSql = "
                    SELECT followers.followed_user_id, followers.following_user_id 
                    FROM followers
                    WHERE (followers.followed_user_id='$userId') AND (followers.following_user_id='$connected_id')
                    ";
                $lesInformations = $mysqli->query($requeteSql);
                //Si non on affiche le bouton pour s'abonner
                if ($lesInformations->num_rows == 0): ?>
                
                    <form action="wall.php?user_id=<?php echo $userId ?>" id="abonnement" method="post">
                        <p>Voulez-vous vous abonner à cet utilisateur ?</p>
                        <input type="submit" name="button" class="button" value="S'abonner">
                        </form>
                        <!--Si oui on affiche qu'il est déjà abonné-->
                        <?php else:
                            echo "Vous êtes abonné à : " . $user['alias'];
                        ?>
                        <?php endif; ?>
<?php endif; ?> 



            </aside>
            <main>
                <?php
                /**
                 * Etape 3: récupérer tous les messages de l'utilisatrice
                 */
                $laQuestionEnSql = "
                    SELECT posts.content, posts.created, posts.id, users.alias as author_name, users.id as author_id,
                    COUNT(likes.id) as like_number, GROUP_CONCAT(DISTINCT tags.label) AS taglist, 
                    GROUP_CONCAT(DISTINCT tags.id) AS tag_ids 
                    FROM posts
                    JOIN users ON  users.id=posts.user_id
                    LEFT JOIN posts_tags ON posts.id = posts_tags.post_id  
                    LEFT JOIN tags       ON posts_tags.tag_id  = tags.id 
                    LEFT JOIN likes      ON likes.post_id  = posts.id 
                    WHERE posts.user_id='$userId' 
                    GROUP BY posts.id
                    ORDER BY posts.created DESC  
                    ";
                $lesInformations = $mysqli->query($laQuestionEnSql);
                if ( ! $lesInformations)
                {
                    echo("Échec de la requete : " . $mysqli->error);
                }

                /**
                 * Etape 4: @todo Parcourir les messsages et remplir correctement le HTML avec les bonnes valeurs php
                 */
                while ($post = $lesInformations->fetch_assoc())
                {
                    
                    //echo "<pre>" . print_r($post, 1) . "</pre>";
                    $postId = $post['id'];
                    echo $postId;

                    ?>                
                    <article>
                        <h3>
                            <time datetime='2020-02-01 11:12:13' ><?php echo $post['created'] ?></time>
                        </h3>
                        <address>
                            <a href="wall.php?user_id=<?php echo $post['author_id'] ?>">
                            par <?php echo $post['author_name'] ?>
                            </a>
                        </address>
                        <div>
                            <p>
                                <!-- On récupère le message du formulaire de message et on vérifie s'il y a des retours à la ligne \n -->
            <?php 
                            $message = $post['content'];
                            $messageArray = explode("\n", $message);
                            foreach ($messageArray as $sentence){
                                echo $sentence."<br>"; //S'il y en a on les affiche
                            }

                            if (preg_match_all('/#(\p{L}+)/u', $message, $matches)) {
                                //echo "<pre>" . print_r($matches, 1) . "</pre>";
                                $labelMatch = strval($matches[1][0]);
                                // Vérifier si le tag existe déjà dans la base de données
                                $requeteSql = "SELECT label FROM tags WHERE label = '$labelMatch'";
                                $lesInfos = $mysqli->query($requeteSql);

                                if ($lesInfos->num_rows == 0) {
                                    // Si le tag n'existe pas, l'insérer dans la base de données
                                    $lInstructionSql = "INSERT INTO tags (id, label) VALUES (NULL, '$labelMatch')";
                                    $ok = $mysqli->query($lInstructionSql);

                                    // Récupérer l'ID du tag inséré ou existant
                                    $result = $mysqli->query("SELECT id FROM tags WHERE label = '$labelMatch'");
                                    $row = $result->fetch_assoc();
                                    $tagId0 = $row['id'];

                                    // Insérer dans la table posts_tags
                                    $lInstructionSql = "INSERT INTO posts_tags (post_id, tag_id) VALUES ('$postId', '$tagId0')";
                                    $ok = $mysqli->query($lInstructionSql);

                                    // if (!$ok) {
                                    //     echo "Erreur lors de l'insertion du tag : " . $mysqli->error;
                                    // } else {
                                    //     echo "Tag inséré avec succès : " . $labelMatch;
                                    // }
                            }};
                            //echo $post['content'] 
                            ?></p>
                        </div>                                            
                        <footer>
                            <small style="color:red" >♥</small>
                            <small><?php echo $post['like_number'] ?></small>
                                <form action="wall.php?user_id=<?php echo $userId ?>" id="likes" method="post">
                                    <input type="submit" name="likes" value="♥" style="color: red; cursor: pointer;">
                                    <input type="hidden" name="post_id" value="<?php echo $post['id'] ?>">
                                </form>

                            <?php
                                if (!empty($post['taglist'])) {
                                    $tagLabels = explode(',', $post['taglist']);
                                    $tagIds = explode(',', $post['tag_ids']);
                                    
                                    foreach ($tagLabels as $key => $tagLabel) {
                                        $tagId = $tagIds[$key];
                                        echo "<a href='tags.php?tag_id=$tagId'>#" . htmlspecialchars($tagLabel) . "</a>";
                                    }
                                } else {
                                    echo "<a href='#'>#notag</a>";
                                }
                                ?>

                        </footer>
                    </article>
                <?php } ?>


            </main>
        </div>
    </body>
</html>
