<?php
session_start();

$nom = $_SESSION["Snom"] ?? "";
$prenom = $_SESSION["Sprenom"] ?? "";
$formation = $_SESSION["Sformation"] ?? "";
$UserID = $_SESSION["SId"] ?? "";
$sessionUserID = $_SESSION["SId"] ?? "";
$getID = $_GET["UserId"] ?? "";
if (!isset($sessionUserID) || empty($sessionUserID)) {
  echo "Redirection vers login.html...";
  
  header("Location: https://websiteisfy.fr/esplab.fr/Messagerie/login.html");
  exit(); 
}

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <link rel="shortcut icon" href="../../logos/logohead.png" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" />
  <link rel="stylesheet" type="text/css" href="../css/navbar.css">
  <link rel="stylesheet" type="text/css" href="../css/reseau.css">
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

                <a href="notification.php?nom=<?php echo urlencode($nom); ?>&prenom=<?php echo urlencode($prenom); ?>&formation=<?php echo urlencode($formation);?>&UserId=<?php echo urlencode($UserID)?>">
                <div class="logo-container">
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
    <div class="users">
        <?php
        error_reporting(E_ALL);

        $servername = "websithbadyss.mysql.db";
        $username = "websithbadyss";
        $password = "Ilian5721";
        $dbname = "websithbadyss";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) die("La connexion a échoué : " . $conn->connect_error);

        $query = $conn->prepare("SELECT id, nom, prenom, formation, date_creation FROM Utilisateur");
        $query->execute();
        $result = $query->get_result();

        while ($user = $result->fetch_assoc()) {
            $avatarPath = "../avatars/{$user['id']}.png";

            $dateCreation = strtotime($user['date_creation']);
            $troisJoursAvant = strtotime('-3 days');
            $nouveauUtilisateur = ($dateCreation >= $troisJoursAvant) ? '<span class="new-user-tag">Nouveau utilisateur</span>' : '';

            echo '<div class="user-container">';
            echo $nouveauUtilisateur;
            echo '<img src="' . (file_exists($avatarPath) ? "{$avatarPath}?v=" . time() : '../avatars/default.png?v=' . time()) . '" class="user-avatar" alt="Avatar">';
            echo '<div class="user-info">';
            echo "<p class='user-name'>{$user['prenom']} {$user['nom']}</p>";
            echo "<p class='user-formation'>{$user['formation']}</p>";
            echo '<a href="profil.php?UserId=' . urlencode($user['id']) . '&nom=' . urlencode($user['nom']) . '&prenom=' . urlencode($user['prenom']) . '&formation=' . urlencode($user['formation']) . '" class="view-profile-btn">Voir le profil</a>';
            echo '</div>';
            echo '</div>';
        }

        $conn->close();
        ?>
    </div>
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