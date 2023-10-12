<?php
        include 'template.php';
        ?>

        <div id="wrapper">
            <?php
            /**
             * Cette page est similaire à wall.php ou feed.php 
             * mais elle porte sur les mots-clés (tags)
             */
            /**
             * Etape 1: Le mur concerne un mot-clé en particulier
             */
            
            $tagId = intval($_GET['tag_id']);
            ?>
            

            <aside>
                <?php
                /**
                 * Etape 3: récupérer le nom du mot-clé
                 */
                $laQuestionEnSql = "SELECT * FROM tags WHERE id= '$tagId' ";
                $lesInformations = $mysqli->query($laQuestionEnSql);
                $tag = $lesInformations->fetch_assoc();


                $req = "SELECT * FROM tags";
                $listeDesTags = $mysqli->query($req);
                //@todo: afficher le résultat de la ligne ci dessous, remplacer XXX par le label et effacer la ligne ci-dessous
                //echo "<pre>" . print_r($tag, 1) . "</pre>";
                ?>
                <img src="./Images/<?php echo $_SESSION['connected_name'] ?>.png" alt="Portrait de l'utilisatrice"/>
                <section>
                    <h3>Présentation</h3>
                    <p>Sur cette page vous trouverez les derniers messages comportant
                        le mot-clé #<?php echo $tag['label'] ?>
                        (n° <?php echo $tagId ?>)
                    </p>

                
                <h3>Mots-clefs disponibles</h3>
                <!-- On récupère la liste des tags dans la base de données et on les affiche -->
                <?php while($tagList = $listeDesTags->fetch_assoc()) { ?>
                <div class="motHashTag">               
                <a href=""><i> #<?php echo $tagList['label'] ?> </i></a>
            </div> 
                <?php };?>
            </section>

            </aside>
            <main>
                <?php
                /**
                 * Etape 3: récupérer tous les messages avec un mot clé donné
                 */
                $laQuestionEnSql = "
                    SELECT posts.content,
                    posts.created,
                    posts.id,
                    users.alias as author_name, 
                    users.id as author_id, 
                    count(likes.id) as like_number,  
                    GROUP_CONCAT(DISTINCT tags.label) AS taglist,
                    GROUP_CONCAT(DISTINCT tags.id) AS tag_ids
                    FROM posts_tags as filter 
                    JOIN posts ON posts.id=filter.post_id
                    JOIN users ON users.id=posts.user_id
                    LEFT JOIN posts_tags ON posts.id = posts_tags.post_id  
                    LEFT JOIN tags       ON posts_tags.tag_id  = tags.id 
                    LEFT JOIN likes      ON likes.post_id  = posts.id 
                    WHERE filter.tag_id = '$tagId' 
                    GROUP BY posts.id
                    ORDER BY posts.created DESC  
                    ";
                $lesInformations = $mysqli->query($laQuestionEnSql);
                if ( ! $lesInformations)
                {
                    echo("Échec de la requete : " . $mysqli->error);
                }

                //Traitement des likes
                $enCoursDeTraitement = isset($_POST['likes']);
                if ($enCoursDeTraitement){
                    $postId = $_POST['post_id'];
                    $lInstructionSql = "INSERT INTO likes "
                                    . "(id, user_id, post_id) "
                                    . "VALUES (NULL, "
                                    . "'" . $_SESSION['connected_id'] . "', "
                                    . "'" . $postId . "') "
                                    ;
                    $lesInfos = $mysqli->query($lInstructionSql);
                }

                /**
                 * Etape 4: @todo Parcourir les messsages et remplir correctement le HTML avec les bonnes valeurs php
                 */
                while ($post = $lesInformations->fetch_assoc())
                {

                    //echo "<pre>" . print_r($post, 1) . "</pre>";
                    ?>                
                             
                             <!-- Affichage du html et des valeurs dynamiques en php -->
                <article>
                    <h3>
                        <time datetime='2020-02-01 11:12:13' ><?php echo $post['created'] ?></time>
                    </h3>
                    <address><a href="wall.php?user_id=<?php echo $post['author_id'] ?>">par <?php echo $post['author_name'] ?></a></address>
                    <div>
                        <p><?php echo $post['content'] ?></p>
                    </div>                                            
                    <footer>
                    <small style="color:red" >♥</small>
                            <small><?php echo $post['like_number'] ?></small>
                                <form action="tags.php?tag_id=<?php echo $tagId ?>" id="likes" method="post">
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