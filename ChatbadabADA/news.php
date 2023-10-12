<?php
        include 'template.php'
        ?>

    <div id="wrapper">
        <aside>
            <img src="./Images/<?php echo $_SESSION['connected_name'] ?>.png" alt="Portrait de l'utilisatrice"/>
        <section>
        <h3>Présentation</h3>
        <p>Sur cette page vous trouverez les derniers messages de
            toutes les utilisatrices du site.</p>
        </section>
        </aside> 
            <main>
                <!-- L'article qui suit est un exemple pour la présentation et 
                  @todo: doit etre retiré -->
                <!-- <article>
                    <h3>
                        <time datetime='2020-02-01 11:12:13' >31 février 2010 à 11h12</time>
                    </h3>
                    <address>par AreTirer</address>
                    <div>
                        <p>Ceci est un paragraphe</p>
                        <p>Ceci est un autre paragraphe</p>
                        <p>... de toutes manières il faut supprimer cet 
                            article et le remplacer par des informations en 
                            provenance de la base de donnée (voir ci-dessous)</p>
                    </div>                                            
                    <footer>
                        <small>♥1012 </small>
                        <a href="">#lorem</a>,
                        <a href="">#piscitur</a>,
                    </footer>
                </article>                -->

                <?php
                /*
                  // C'est ici que le travail PHP commence
                  // Votre mission si vous l'acceptez est de chercher dans la base
                  // de données la liste des 5 derniers messsages (posts) et
                  // de l'afficher
                  // Documentation : les exemples https://www.php.net/manual/fr/mysqli.query.php
                  // plus généralement : https://www.php.net/manual/fr/mysqli.query.php
                 */

                
                //verification si la requête est bien passée ou pas
                if ($mysqli->connect_errno)
                {
                    echo "<article>";
                    echo("Échec de la connexion : " . $mysqli->connect_error);
                    echo("<p>Indice: Vérifiez les parametres de <code>new mysqli(...</code></p>");
                    echo "</article>";
                    exit();
                }

                //$userId = intval($_GET['user_id']);


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
                    $lesInformations = $mysqli->query($lInstructionSql);
                }

                // Etape 2: Poser une question à la base de donnée et récupérer ses informations
                // cette requete vous est donnée, elle est complexe mais correcte, 
                // si vous ne la comprenez pas c'est normal, passez, on y reviendra
                $laQuestionEnSql = "
                    SELECT posts.content,
                    posts.created,
                    posts.id,
                    users.alias as author_name,
                    users.id as author_id,  
                    count(likes.id) as like_number,  
                    GROUP_CONCAT(DISTINCT tags.label) AS taglist,
                    GROUP_CONCAT(DISTINCT tags.id) AS tag_ids
                    FROM posts
                    JOIN users ON  users.id=posts.user_id
                    LEFT JOIN posts_tags ON posts.id = posts_tags.post_id  
                    LEFT JOIN tags       ON posts_tags.tag_id  = tags.id 
                    LEFT JOIN likes      ON likes.post_id  = posts.id 
                    GROUP BY posts.id
                    ORDER BY posts.created DESC  
                    LIMIT 5
                    ";
                $lesInformations = $mysqli->query($laQuestionEnSql);
                // Vérification si la requête est bien passée ou pas
                if ( ! $lesInformations)
                {
                    echo "<article>";
                    echo("Échec de la requete : " . $mysqli->error);
                    echo("<p>Indice: Vérifiez la requete  SQL suivante dans phpmyadmin<code>$laQuestionEnSql</code></p>");
                    exit();
                }

                // Etape 3: Parcourir ces données et les ranger bien comme il faut dans du html
                // NB: à chaque tour du while, la variable post ci dessous reçois les informations du post suivant.
                while ($post = $lesInformations->fetch_assoc())
                {

                     

                    //la ligne ci-dessous doit etre supprimée mais regardez ce 
                    //qu'elle affiche avant pour comprendre comment sont organisées les information dans votre 
                    //echo "<pre>" . print_r($post, 1) . "</pre>";

                    // @todo : Votre mission c'est de remplacer les AREMPLACER par les bonnes valeurs
                    // ci-dessous par les bonnes valeurs cachées dans la variable $post 
                    // on vous met le pied à l'étrier avec created
                    // 
                    // avec le ? > ci-dessous on sort du mode php et on écrit du html comme on veut... mais en restant dans la boucle
                    ?>
                    <article>
                        <h3>
                            <time><?php echo $post['created'] ?></time>
                        </h3>
                        <address>
                            <a href="wall.php?user_id=<?php echo $post['author_id'] ?>">
                            par <?php echo $post['author_name'] ?>
                            </a>
                        </address>
                        <div>
                            <p><?php echo $post['content'] ?></p>
                        </div>
                        <footer>
                        <small style="color:red" >♥</small>
                            <small><?php echo $post['like_number'] ?></small>
                            <form action="news.php" id="likes" method="post">
                                <input type="submit" name="likes" value="♥" style="color: red; cursor: pointer;">
                                <input type="hidden" name="post_id" value="<?php echo $post['id'] ?>">
                            </form>
<!-- On récupère la liste des tags en base de données, puis on utilise une boucle pour comparer avec le tag de notre message, s'il existe on l'affiche -->
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
                    <?php
                    // avec le <?php ci-dessus on retourne en mode php 
                }// cette accolade ferme et termine la boucle while ouverte avant.
                ?>

            </main>
        </div>
    </body>
</html>
