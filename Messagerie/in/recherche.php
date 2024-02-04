<?php
$servername = "websithbadyss.mysql.db";
$username = "websithbadyss";
$password = "Ilian5721";
$dbname = "websithbadyss";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("La connexion a échoué : " . $conn->connect_error);
}

if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
  $searchTerm = $_GET['search'];

  $query = "SELECT id, nom, prenom, formation FROM Utilisateur WHERE nom LIKE '%$searchTerm%' OR prenom LIKE '%$searchTerm%' LIMIT 4";
  $result = $conn->query($query);

  if ($result->num_rows > 0) {
    echo '<div class="searchResults">';
    while ($row = $result->fetch_assoc()) {
        echo '<a href="profil.php?UserId=' . urlencode($row['id']) . '&nom=' . urlencode($row['nom']) . '&prenom=' . urlencode($row['prenom']) . '&formation=' . urlencode($row['formation']) . '">';
        echo '<div class="userContainer">';
        $avatarPath = "../avatars/{$row['id']}.png";
        echo '<div class="avatarContainer">';
        echo '<img src="' . (file_exists($avatarPath) ? "{$avatarPath}?v=" . time() : '../avatars/default.png?v=' . time()) . '" class="avatar" alt="Avatar">';
        echo '</div>';
        echo '<div class="userInfoContainer">';
        echo '<p class="userName">' . $row['prenom'] . ' ' . $row['nom'] . '</p>';
        echo '</div>';
        echo '</div>';
        echo '</a>'; 
    }
    echo '</div>';
} else {
    echo '<p>Aucun résultat trouvé</p>';
}
} else {
   
}
$conn->close();
?>
