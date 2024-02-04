<?php

$servername = "websithbadyss.mysql.db";
$username = "websithbadyss";
$password = "Ilian5721";
$dbname = "websithbadyss";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("La connexion a échoué : " . $conn->connect_error);
}

if (isset($_GET['verification_sent']) && $_GET['verification_sent'] === 'true') {
    echo '<script>window.onload = function() { document.getElementById("verifdash").style.opacity = 1; document.getElementById("verifdash").style.display = "block"; }</script>';
} elseif (isset($_GET['verification_sent']) && $_GET['verification_sent'] === 'false') {
    echo "Erreur lors de l'envoi de l'e-mail.";
}

if (isset($_GET['verification_success'])) {
    $verificationSuccess = $_GET['verification_success'];

    echo '<script>
            window.onload = function() {
                var dashboardDiv = document.getElementById("dashboard");

                if ("' . $verificationSuccess . '" === "true") {
                    dashboardDiv.style.display = "flex";
                } else {
                    dashboardDiv.style.display = "none";
                }
            };
          </script>';

    $sqlCountUsers = "SELECT COUNT(*) AS totalUsers FROM Utilisateur";
    $resultCountUsers = $conn->query($sqlCountUsers);

    if ($resultCountUsers) {
        $row = $resultCountUsers->fetch_assoc();
        $totalUsers = $row['totalUsers'];
    } else {
        $totalUsers = 0;
    }

    $sqlCountConnectionsToday = "SELECT COUNT(*) AS totalConnectionsToday FROM Connexions WHERE DATE(date_connexion) = CURDATE()";
    $resultCountConnectionsToday = $conn->query($sqlCountConnectionsToday);

    if ($resultCountConnectionsToday) {
        $row = $resultCountConnectionsToday->fetch_assoc();
        $totalConnectionsToday = $row['totalConnectionsToday'];
    } else {
        $totalConnectionsToday = 0;
    }

    $sqlCompteurNouveauxUtilisateurs = "SELECT COUNT(*) AS nouveauxUtilisateurs FROM Utilisateur WHERE DATE(date_creation) = CURDATE()";
    $resultatCompteurNouveauxUtilisateurs = $conn->query($sqlCompteurNouveauxUtilisateurs);
    
    if ($resultatCompteurNouveauxUtilisateurs) {
        $ligne = $resultatCompteurNouveauxUtilisateurs->fetch_assoc();
        $nouveauxUtilisateurs = $ligne['nouveauxUtilisateurs'];
    } else {
        $nouveauxUtilisateurs = 0;
    }

    $sqlCountMessagesThisWeek = "SELECT COUNT(*) AS totalMessagesThisWeek FROM Messages WHERE YEARWEEK(timestamp) = YEARWEEK(NOW())";
    $resultCountMessagesThisWeek = $conn->query($sqlCountMessagesThisWeek);
    
    if ($resultCountMessagesThisWeek) {
        $row = $resultCountMessagesThisWeek->fetch_assoc();
        $totalMessagesThisWeek = $row['totalMessagesThisWeek'];
    } else {
        $totalMessagesThisWeek = 0;
    }

    $jours = array();
    $comptesCrees = array();
    for ($i = 0; $i < 7; $i++) {
        $dateJour = date('Y-m-d', strtotime("-$i days"));
        $sqlCountNewUsers = "SELECT COUNT(*) AS newUsers FROM Utilisateur WHERE DATE(date_creation) = '$dateJour'";
        $resultCountNewUsers = $conn->query($sqlCountNewUsers);
    
        if ($resultCountNewUsers) {
            $row = $resultCountNewUsers->fetch_assoc();
            $newUsers = $row['newUsers'];
        } else {
            $newUsers = 0;
        }
        $jours[] = strftime('%e %B', strtotime("-$i days"));
        $comptesCrees[] = $newUsers;
    }

    $sqlFormationData = "SELECT formation, COUNT(*) AS count FROM Utilisateur GROUP BY formation";
    $resultFormationData = $conn->query($sqlFormationData);

    $formationLabels = [];
    $formationData = [];

    while ($row = $resultFormationData->fetch_assoc()) {
        $formationLabels[] = $row['formation'];
        $formationData[] = $row['count'];
}
    $conn->close();
}
?>