<?php
session_start();

$nom = $_SESSION["Snom"] ?? "";
$prenom = $_SESSION["Sprenom"] ?? "";
$formation = $_SESSION["Sformation"] ?? "";
$UserID = $_SESSION["SId"] ?? "";
$getID = $_GET["UserId"] ?? "";

  
include("post.php");

$post = new Post();
$id = $_SESSION["SId"] ?? "";
$posts = $post->get_posts($id);

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    var_dump($_POST);
    $post = new Post();
    $id = $_SESSION["SId"] ?? "";
    $result = $post->create_post($id, $_POST);

    if ($result == "") {
        header("Location: acceuil.php");
        exit;
    } else {
        echo "<div style='text-align:center;font-size:12px;color:white;background-color:grey;'>";
        echo "<br>Voici les erreurs suivantes:<br><br>";
        echo $result;
        echo "</div>";
    }
}


?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <link rel="shortcut icon" href="../../logos/logohead.png" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" />
  <link rel="stylesheet" type="text/css" href="../css/navbar.css">
  <link rel="stylesheet" type="text/css" href="../css/accueil.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <title> EspLaboratory</title>
  <script>
    $(document).ready(function() {
      $('#name').on('input', function() {
        var searchTerm = $(this).val();

        $.ajax({
          type: 'POST',
          url: 'recherche.php',
          data: { query: searchTerm },
          success: function(data) {
            var resultDiv = $('.result');
            resultDiv.html(data); // Mettez à jour la zone d'affichage des résultats

            if (searchTerm !== '') {
              resultDiv.addClass('visible'); // Afficher le tableau si la recherche n'est pas vide
            } else {
              resultDiv.removeClass('visible'); // Masquer le tableau si la recherche est vide
            }
          }
        });
      });
    });
  </script>
</head>

<body>
    <header>
        <nav>
            <div class="recherche">
                <img src="../../logos/logo.png" class="rechercheimgtkt">
                <input type="search" id="name" name="name" placeholder="Rechercher un utilisateur...">
                <div class='result'></div> 
            </div>
            
            <div class="logonav">
                <a href="acceuil.php?nom=<?php echo urlencode($nom); ?>&prenom=<?php echo urlencode($prenom); ?>&formation=<?php echo urlencode($formation);?>&UserId=<?php echo urlencode($UserID)?>">
                <div class="logo-container">
                    <img src="../../logos/home.png" alt="acceuil">
                    <div class="logo-text">Accueil</div>
                </div>
                </a>

                <a href="reseau.php?nom=<?php echo urlencode($nom); ?>&prenom=<?php echo urlencode($prenom); ?>&formation=<?php echo urlencode($formation);?>&UserId=<?php echo urlencode($UserID)?>">
                <div class="logo-container">
                    <img src="../../logos/reseau.svg" alt="amis">
                    <div class="logo-text">Réseau</div>
                </div>
                </a>

                <a href="message.php?nom=<?php echo urlencode($nom); ?>&prenom=<?php echo urlencode($prenom); ?>&formation=<?php echo urlencode($formation);?>&UserId=<?php echo urlencode($UserID)?>">
                <div class="logo-container">
                    <img src="../../logos/messages.png" alt="conversations">
                    <div class="logo-text">Messages</div>
                </div>
                </a>

                <a style='position:relative' href="notification.php?nom=<?php echo urlencode($nom); ?>&prenom=<?php echo urlencode($prenom); ?>&formation=<?php echo urlencode($formation);?>&UserId=<?php echo urlencode($UserID)?>">
                <div class="logo-container ">
                    <img src="../../logos/notifs.png" alt="notifications">
                    <div class="logo-text">Notifications</div>
                    <?php
                        $servername = "websithbadyss.mysql.db";
                        $username = "websithbadyss";
                        $password = "Ilian5721";
                        $dbname = "websithbadyss";

                        $conn = new mysqli($servername, $username, $password, $dbname);
                        if ($conn->connect_error) {
                            die("Erreur de connexion : " . $conn->connect_error);
                        }

                        $sql = "SELECT COUNT(*) AS total_notifications
                        FROM friendship
                        WHERE receiver_id = '" . $_SESSION["SId"] . "'
                        AND status = 0;
                        ";

                        $result = $conn->query($sql);

                        if ($result) {
                            if ($result->num_rows > 0) {
                                $row = $result->fetch_assoc();
                                $total_notifications = $row['total_notifications'];
                                // Si la variable est vide, cela signifie qu'il n'y a pas de demandes d'ami en attente
                                if (empty($total_notifications)) {
                                    
                                } else {
                                    echo "<p style='position:absolute;top:-20px;right:20px;width:20px;padding: 0.5px 0; padding-left:0.5px; border-radius:50%;background-color:red'>" . $total_notifications . "</p>";
                                }
                            } else {
                                
                            }
                        } else {
                            echo "Erreur dans la requête SQL : " . $conn->error;
                        }

                        $conn->close();
                    ?>


                </div>
                </a>
                
                <a href="profil.php?nom=<?php echo urlencode($nom); ?>&prenom=<?php echo urlencode($prenom); ?>&formation=<?php echo urlencode($formation);?>&UserId=<?php echo urlencode($UserID)?>">
                <div class="logo-container">
                    <img src="../../logos/profil.png" alt="profil">
                    <div class="logo-text">Profil</div>
                </div>
                </a>
            </div>
        </nav>
    </header>
    <main>
        <section>
            <div class="container">
                <div class="row">
                    <div class="col-md-3 col-sm-6">
                        <div class="serviceBox">
                            <div class="service-icon">
                                <?php
                                $UserID = $_GET["UserId"] ?? "";
                                $chemin_image = "../avatars/".$UserID.".png";

                                if (file_exists($chemin_image)) {
                                    echo '<img src="'.$chemin_image.'?v='.time().'" >';
                                } else {
                                    echo '<img src="..//avatars/default.png?v='.time().'" >';
                                }
                                ?>
                            </div>
                            <h3 class="title"><?php echo "$prenom $nom";?></h3>
                            <p class="description"><?php echo "En $formation";?></p>
                            <div class="cookies">
                                <h2>Vues de profil</h2><br>
                                <h2>Impressions de posts</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="suggestions">
                <div class="suggestionstexte">
                    <h1>Suggestions pour vous</h1>
                </div>
                <?php
                error_reporting(E_ALL);
                $servername = "websithbadyss.mysql.db";
                $username = "websithbadyss";
                $password = "Ilian5721";
                $dbname = "websithbadyss";

                $conn = new mysqli($servername, $username, $password, $dbname);

                if ($conn->connect_error) die("La connexion a échoué : " . $conn->connect_error);

                $query = $conn->prepare("SELECT id, nom, prenom, formation FROM Utilisateur ORDER BY RAND() LIMIT 5");
                $query->execute();
                $result = $query->get_result();

                while ($user = $result->fetch_assoc()) {
                    echo '<div class="suggestionprofil">';
                    echo '<div class="pfp">';
                    $avatarPath = "..//avatars/{$user['id']}.png";
                    echo '<img src="' . (file_exists($avatarPath) ? "{$avatarPath}?v=" . time() : '..//avatars/default.png?v=' . time()) . '" class="backban" alt="Avatar">';
                    echo '<div class="pfptxt">';
                    echo "<h1>{$user['prenom']} {$user['nom']}</h1>";
                    echo "<h2>{$user['formation']}</h2>";
                    echo '</div>';
                    echo '<div class="suggestionbouton">';
                    echo "<a href='profil.php?UserId={$user['id']}&nom={$user['nom']}&prenom={$user['prenom']}&formation={$user['formation']}'><h3>Profil</h3></a>";
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }

                $query->close();
                $conn->close();
                ?>
                <h2 class="voirtodo"><a href="reseau.php?nom=<?php echo urlencode($nom); ?>&prenom=<?php echo urlencode($prenom); ?>&formation=<?php echo urlencode($formation); ?>&UserId=<?php echo urlencode($UserID)?>"> Voir tout</a></h2>
            </div>
        </section>

        <section class="posts" style="overflow-y: auto; max-height: 800px">
            <?php
                session_start();

                $servername = "websithbadyss.mysql.db";
                $username = "websithbadyss";
                $password = "Ilian5721";
                $dbname = "websithbadyss";

                $conn = new mysqli($servername, $username, $password, $dbname);
                $UserID = $_SESSION["SId"] ?? "";

                if ($conn->connect_error) {
                    die("La connexion a échoué : " . $conn->connect_error);
                }


                if (isset($_SESSION["SId"])) {
                    $sql = "SELECT admin FROM Utilisateur WHERE id = ?";
                    $stmt = $conn->prepare($sql);

                    if ($stmt === false) {
                        die('Erreur de préparation de la requête : ' . $conn->error);
                    }

                    $stmt->bind_param("i", $UserID);

                    if ($stmt->execute()) {
                        $stmt->bind_result($adminStatus);
                        $stmt->fetch();
                    } else {
                        echo "Erreur lors de l'exécution de la requête : " . $stmt->error;
                    }

                    $stmt->close();
                }

                $conn->close();

                if ($adminStatus == 1) {
                    echo '<div class="princip">';
                    echo '<button onclick="openModal()" id="principal">Créer un post</button>';
                    echo '</div>';
                }
            ?>
            <div id="myModal" class="modal-container">
                <div class="modal">
                    <span class="close" onclick="closeModal()">&times;</span>
                    <h2>Nouveau Post</h2>
                    <form method="post">
                        <label for="postTitle">Titre:</label>
                        <input type="text" id="postTitle" name="titre" required>
                        <label for="postContent">Contenu:</label>
                        <textarea name="post" id="postContent" rows="4" placeholder="Quoi de neuf ?" required></textarea>
                        <button onclick="submitPost()">Poster</button>
                    </form>
                </div>
            </div>
            <script src="../javascript/post.js"></script>

            <div id="post_bar">
                <?php
                include("user.php");
                if($posts)
                {
                    foreach($posts as $Row){
                        $user = new User();
                        $Row_user = $user->get_user($Row['user_id']);
                        include("posts.php");
                    }
                }else{
                    echo '<h1>Aucun post pour le moment</h1>';
                }
                ?>
            </div>
        </section>
    </main>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
        const searchInput = document.getElementById('name');
        const searchResults = document.querySelector('.result');
  
        searchInput.addEventListener('input', function() {
          const searchQuery = searchInput.value.trim();
  
          const xhr = new XMLHttpRequest();
          const url = `recherche.php?search=${searchQuery}`;
  
          xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
              if (xhr.status === 200) {
                searchResults.innerHTML = xhr.responseText;
              } else {
              }
            }
          };
  
          xhr.open('GET', url, true);
          xhr.send();
        });
        searchResults.addEventListener('click', function(event) {
          if (event.target && event.target.matches('p.searchResult')) {
            const userId = event.target.dataset.userid;
            window.location.href = `profil.php?UserId=${userId}`;
          }
        });
      });

</script>
</body>
</html>