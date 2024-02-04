<?php
session_start();

$nom = $_SESSION["Snom"] ?? "";
$prenom = $_SESSION["Sprenom"] ?? "";
$formation = $_SESSION["Sformation"] ?? "";
$UserID = $_SESSION["SId"] ?? "";
$sessionUserID = $_SESSION["SId"] ?? "";
$getID = $_GET["UserId"] ?? "";

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <link rel="shortcut icon" href="../../logos/logohead.png" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" />
  <link rel="stylesheet" type="text/css" href="../css/navbar.css">
  <link rel="stylesheet" type="text/css" href="../css/dashboard.css">
  <link rel="stylesheet" type="text/css" href="../css/head.css">
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
            resultDiv.html(data); 

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
        <?php if ($sessionUserID == 324) : ?>
          <img src="../../logos/dashboard.png" class="dashboardimg" onclick="toggleDashboard()">
        <?php endif; ?>
    </div>
    </nav>
  </header>
  <section>
    <div class="vues" id="vues">
        <div class="vu">
            <h1>21 vues de profil</h1>
            <p>les 30 derniers jours</p>
        </div>
        <div class="seemore">
            <p>Voir toutes les statistiques</p>
            <img src="../../logos/fleche.png">
        </div>
    </div>
    <div class="profil" id="profil">
      <div class="banierelogo">
            <div class="baniere">
              <img src="../../logos/baniere.png" class="backban">
              <img src="../../logos/logouspn.png">
              <img src="../../logos/logobaniere.png">
            </div>
            <div class="editprofil">
              <?php
              $chemin_image = "../avatars/".$getID.".png";

              if (file_exists($chemin_image)) {
                  echo '<img src="'.$chemin_image.'?v='.time().'" class="backban">';
              } else {
                  echo '<img src="../avatars/default.png?v='.time().'" class="backban">';
              }
              ?>
              <?php if ($sessionUserID == $getID) : ?>
                <img src="../../logos/pencil.png" onclick="changerprofil()">
              <?php endif; ?>
          </div>
      </div>
      <?php if ($sessionUserID == $getID) : ?>
        <div class="overlay" id="overlay">
          <div class="fermermenu">
            <img src="../../logos/close.png" onclick="changerprofil()">
          </div>
          <div class="photo">
          <?php
              $chemin_image = "../avatars/".$getID.".png";

              if (file_exists($chemin_image)) {
                  echo '<img src="'.$chemin_image.'?v='.time().'" class="backban">';
              } else {
                  echo '<img src="../avatars/default.png?v='.time().'" class="backban">';
              }
              ?>
          </div>
          <div class="importphoto">
          <form action="" method="post" enctype="multipart/form-data">
            <label class="boutonimportphoto" for="fileInput">
              <img src="../../logos/download.png" alt="Download">
              Télécharger une photo
            </label>
            <input type="file" name="avatar" id="fileInput" class="inputfile" style="opacity:0;"/>
            
            <label for="fname" class="labelsource">Prénom</label>
            <input type="text" id="prenomnom" name="prenomnom" value="" class="inputinfo">
            
            <label for="lname" class="labelsource">Nom:</label>
            <input type="text" id="nomfamille" name="nomfamille" value="" class="inputinfo">
            
            <div class="boutonsub">
              <button type="submit" value="Submit">Enregistrer </button>
            </div>
          </form>
        </div>
        
        </div>
      <?php endif; ?>
      <div class="description">
        <h1>
          <?php $nom = $_GET["nom"] ?? ""; $prenom = $_GET["prenom"] ?? ""; echo "<h1> $prenom $nom </h1>";?>
        </h1>
        <h2>
          <?php $formation = $_GET["formation"] ?? "";echo "<h2> $formation </h2>";?>
        </h2>
        <?php 
          if ($sessionUserID != $getID) {
            $servername = "websithbadyss.mysql.db";
            $username = "websithbadyss";
            $password = "Ilian5721";
            $dbname = "websithbadyss";

            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("La connexion a échoué : " . $conn->connect_error);
            }

            $sessionUserID = $_SESSION["SId"];
            $profileUserID = $getID;

            $sql_check = "SELECT * FROM friendship WHERE (sender_id = '$sessionUserID' AND receiver_id = '$profileUserID' AND status = 1) OR (sender_id = '$profileUserID' AND receiver_id = '$sessionUserID' AND status = 1)";

            $result_check = $conn->query($sql_check);

            if ($result_check->num_rows > 0) {
                echo "En ami";
            } else {
                $sql_request_sent = "SELECT * FROM friendship WHERE sender_id = '$sessionUserID' AND receiver_id = '$profileUserID' AND status = 0";

                $result_request_sent = $conn->query($sql_request_sent);

                if ($result_request_sent->num_rows > 0) {
                    echo "<p style='color:#405d9b'>Demande d'ami envoyée <p>";
                } else {
                  echo "<form action='ajouter_ami.php' method='post'>";
                  echo "<input type='hidden' name='sessionUserID' value='$sessionUserID'>";
                  echo "<input type='hidden' name='profileUserID' value='$profileUserID'>";
                  
                  echo "<input type='hidden' name='nom' value='" . urlencode($nom) . "'>";
                  echo "<input type='hidden' name='prenom' value='" . urlencode($prenom) . "'>";
                  echo "<input type='hidden' name='formation' value='" . urlencode($formation) . "'>";
                  echo "<input type='hidden' name='UserId' value='" . urlencode($UserID) . "'>";
                  
                  echo "<button type='submit' name='addFriend' class='sendfriend'>";
                  
                  if ($usersNotFriends) {
                      echo "Demander en ami";
                  } else {
                      echo "Envoyer une demande d'ami";
                  }
                  
                  echo "</button>";
                  echo "</form>";
                  

            }}

            $conn->close();
          }
        ?>
    </div>
      </div>
    </div>
    <div class="dlc" id="dlc">
      <h1>Ressources</h1>
      <div>
        <h3>Mode créateur</h3>
        <p>Gagnez en visiblité et mettez en valeur le contenu de votre page</p>
      </div>
      <div>
        <h3>Mon réseau</h3>
        <p>Gagnez en visiblité et mettez en valeur le contenu de votre page</p>
      </div>
    </div>
</section>
<div class="dashverif" id="verifdash">
  <form id="verificationForm" action="verif.php" method="post">
    <div class="boutoncode">
      <button type="submit">Cliquez ici pour envoyer le code</button>
    </div>
  </form>
  <form id="verificationCodeForm" action="verifcode.php" method="post">
    <input type="text" name="codeInput" id="codeInput" required maxlength="6">
    <div class="boutonverif">
      <button class="btn" type="button">
        <strong>Vérifier</strong>
        <div id="container-stars">
          <div id="stars"></div>
        </div>
      
        <div id="glow">
          <div class="circle"></div>
          <div class="circle"></div>
        </div>
      </button> 
  </div>   
  </form>
  <p id="verificationResult"></p>
</div>

<script src="../javascript/java.js"></script>
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
<?php
  if (isset($_FILES['avatar']) AND !empty($_FILES['avatar']['name'])) {
      $tailleMax = 2097152;
      $extensionsValides = array('jpg', 'jpeg', 'gif', 'png');
      $cheminDossier = "../avatars/";

      $path_parts = pathinfo($_FILES['avatar']['name']);
      $extensionUpload = strtolower($path_parts['extension']);

      $ancienChemin = $cheminDossier . $getID . "." . $extensionUpload;

      if (file_exists($ancienChemin)) {
          unlink($ancienChemin);
      }

      $nouveauChemin = $cheminDossier . $getID . "." . $extensionUpload;

      $resultat = move_uploaded_file($_FILES['avatar']['tmp_name'], $nouveauChemin);

      if ($resultat) {
          $updateavatar_query = $conn->prepare('UPDATE Utilisateur SET avatar = ? WHERE id = ?');
          $updateavatar_query->bind_param("si", $getID . "." . $extensionUpload, $getID);
          $updateavatar_query->execute();
          $updateavatar_query->close();

          header("Location: profil.php?nom=$nom&prenom=$prenom&formation=$formation&id=$UserID");
          exit;
      } else {
          $msg = "Erreur durant l'importation de votre photo de profil";
      }
  } else {
      $msg = "Aucun fichier n'a été sélectionné";
  }
?>
