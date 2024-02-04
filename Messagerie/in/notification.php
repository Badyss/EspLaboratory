<?php
$servername = "websithbadyss.mysql.db";
$username = "websithbadyss";
$password = "Ilian5721";
$dbname = "websithbadyss";

session_start();

$nom = $_SESSION["Snom"] ?? "";
$prenom = $_SESSION["Sprenom"] ?? "";
$formation = $_SESSION["Sformation"] ?? "";
$UserID = $_SESSION["SId"] ?? "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'acceptFriendRequest') {
    $userId = $_POST['userId'];

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Erreur de connexion : " . $conn->connect_error);
    }

    $updateQuery = "UPDATE friendship SET status = 1 WHERE receiver_id = $UserID AND sender_id = $userId";
    if ($conn->query($updateQuery) === TRUE) {
       
    } else {
        
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <link rel="shortcut icon" href="../../logos/logohead.png" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" />
  <link rel="stylesheet" type="text/css" href="../css/navbar.css">
  <link rel="stylesheet" type="text/css" href="../css/dashboard.css">
  <link rel="stylesheet" type="text/css" href="../css/notification.css">
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
    <div class='demande'>
        <div class="sousdemande">
        <?php
            $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error) {
                die("Erreur de connexion : " . $conn->connect_error);
            }

            $sql = "SELECT *
            FROM friendship 
            JOIN Utilisateur ON friendship.sender_id = Utilisateur.id
            WHERE friendship.receiver_id = " . $_SESSION["SId"] . "
            AND friendship.status = 0";

            $result = $conn->query($sql);
            if ($result) {
                if ($result->num_rows > 0) {
                    echo "<table>";
            
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        $chemin_image = "../avatars/" . $row['id'] . ".png";
                        echo "<td>";
                        if (file_exists($chemin_image)) {
                            echo '<img src="' . $chemin_image . '?v=' . time() . '" class="backban">';
                        } else {
                            echo '<img src="../avatars/default.png?v=' . time() . '" class="backban">';
                        }
                        echo "</td>";
                        echo "<td class='infos'>";
                        echo "<p style='color:deepskyblue;margin-bottom:5px;font-size:27px;margin-top:0;'>" . $row['prenom'] . " " . $row['nom'] . "<span style='color:black;font-size:22px'> vous a ajouté en ami </span></p>";
                        echo "<p style='margin:0;color:#888;font-style:italic;font-size:20px'>En " . $row['formation'] . "</p>";
                        echo "</td>";
                        echo "<td><button class='acceptFriend' data-user-id='" . $row['id'] . "'>Accepter la demande d'ami</button></td>";
                        echo "</tr>";
                    }
            
                    echo "</table>";
                } else {
                    echo "Vous n'avez aucune notification.";
                }
            } else {
                echo "Erreur dans la requête SQL : " . $conn->error;
            }

            $conn->close();
        ?>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('.acceptFriend').on('click', function() {
                var userId = $(this).data('user-id');

                $.ajax({
                    type: 'POST',
                    url: 'notification.php', 
                    data: { userId: userId, action: 'acceptFriendRequest' },
                    success: function(response) {
                        window.location.href = 'notification.php';
                    },
                    error: function(err) {
                        
                    }
                });
            });
        });
    </script>
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
</html>