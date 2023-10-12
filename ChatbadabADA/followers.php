<?php
        include 'template.php';
        ?>

        <div id="wrapper">          
            <aside>
                <img src = "./Images/<?php echo $_SESSION['connected_name'] ?>.png" alt = "Portrait de l'utilisatrice"/>
                <section>
                    <h3>Présentation</h3>
                    <p>Sur cette page vous trouverez la liste des personnes qui
                        suivent les messages de l'utilisatrice
                        n° <?php echo intval($_GET['user_id']) ?></p>

                </section>
            </aside>
            <main class='contacts'>
                <?php
                // Etape 1: récupérer l'id de l'utilisateur
                $userId = intval($_GET['user_id']);
                // Etape 3: récupérer le nom de l'utilisateur
                $laQuestionEnSql = "
                    SELECT users.*
                    FROM followers
                    LEFT JOIN users ON users.id=followers.following_user_id
                    WHERE followers.followed_user_id='$userId'
                    GROUP BY users.id
                    ";
                $lesInformations = $mysqli->query($laQuestionEnSql);
                // Etape 4: à vous de jouer
                //@todo: faire la boucle while de parcours des abonnés et mettre les bonnes valeurs ci dessous 
                while ($post = $lesInformations->fetch_assoc())
                { ?>
                <article>
<!-- Si l'image contenant le nom du user existe, on affiche cette image, sinon une image par défaut -->
                    <?php
                    if(file_exists('./Images/' . $post['alias'] . '.png')) { 
                        echo '<img src="./Images/' . $post['alias'] . '.png" alt="blasonuser"/>';
                    }
                    else{
                        echo '<img src="./Images/user.jpg" alt="blason"/>';
                    } 
                    ?>

                    <a href="wall.php?user_id=<?php echo $post['id'] ?>"><?php echo $post['alias'] ?></a>
                    <p><?php echo $post['id'] ?></p>                    
                </article>
                <?php } ?>
            </main>
        </div>
    </body>
</html>
