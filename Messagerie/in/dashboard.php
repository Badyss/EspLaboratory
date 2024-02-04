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
        
        header("Location: https://websiteisfy.fr/403.html");
      }
    include('stats.php');
?>
<html>
<head>
  <meta charset="utf-8">
  <link rel="shortcut icon" href="../../logos/logohead.png" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" />
  <link rel="stylesheet" type="text/css" href="../css/navbar.css">
  <link rel="stylesheet" type="text/css" href="../css/dashboard.css">
  <link rel="stylesheet" type="text/css" href="../css/vraidashboard.css">
  <link rel="stylesheet" type="text/css" href="../css/head.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="../javascript/java.js"></script>
  <title> EspLaboratory</title>
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
  <div class="dashverif" id="verifdash">
  <form id="verificationForm" action="verif.php" method="post">
    <div class="boutoncode">
      <button type="submit">Cliquez ici pour envoyer le code</button>
    </div>
  </form>
  <form id="verificationCodeForm" action="verifcode.php" method="post">
    <input type="text" name="codeInput" id="codeInput" required maxlength="6">
    <div class="boutonverif">
      <button class="btn" type="submit">
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
 <div class="vraidash" id=dashboard>
        <section class="navside">
            <div class="logoesp">
                <img src="../../logos/logo.png">
            </div>
            <div class="mininav" onclick="panelcontrol('maindash')">
                <div class="mininav-logo">
                    <img src="../../logos/dashboard.png">
                </div>
                <h1>Panneau d'admin</h1>
            </div>
            <div class="mininav" onclick="panelcontrol('usersdash')">
                <div class="mininav-logo">
                    <img src="../../logos/profil.png">
                </div>
                <h1>Utilisateurs</h1>
            </div>   
        </section>
        <section class="maindash" id="maindash">
            <div class="barrestats">
                <div class="ministats">
                    <div class="ministats-texte">
                        <h1>Nombre d'Utilisateurs</h1>
                        <h2><?php echo $totalUsers; ?></h2>
                    </div>
                    <div class="barrestats-logo">
                            <img src="../../logos/reseau.svg">
                    </div>
                </div>    
                <div class="ministats">
                    <div class="ministats-texte">
                        <h1>Nouveaus comptes</h1>
                        <h2><?php echo $nouveauxUtilisateurs; ?><span>/24h</span></h2>
                    </div>
                    <div class="barrestats-logo">
                        <img src="../../logos/newuser.png">
                    </div>
                </div>  
                <div class="ministats">
                    <div class="ministats-texte">
                        <h1>Conexions</h1>
                        <h2><?php echo $totalConnectionsToday ; ?><span>/24h</span></h2>
                    </div>
                    <div class="barrestats-logo">
                        <img src="../../logos/dashboard.png">
                    </div>
                </div>  
                <div class="ministats">
                    <div class="ministats-texte">
                        <h1>Nombre de messages</h1>
                        <h2><?php echo $totalMessagesThisWeek ; ?><span>/24h</span></h2>
                    </div>
                    <div class="barrestats-logo">
                        <img src="../../logos/poster.png">
                    </div>
                </div>     
            </div>
            <div class="graphiques">
                <div class="graphique">
                    <canvas id="graphiqueComptes" width="400" height="200"></canvas>
                        <?php
                            echo '<script>afficherGraphique(' . json_encode(array_reverse($jours)) . ', ' . json_encode(array_reverse($comptesCrees)) . ')</script>';
                        ?>
                </div>
                <div class="camembert">
                    <canvas id="graphiqueFormation" width="400" height="200"></canvas>
                        <?php
                           echo '<script>afficherGraphiqueFormation(' . json_encode($formationLabels) . ', ' . json_encode($formationData) . ')</script>';
                        ?>
                </div>
            </div>
        </section>
        <section class="usersdash" id="usersdash">
            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Adresse Mail</th>
                        <th>Formation</th>
                        <th style="color:darkred";>Admin</th>
                        <th>Editer</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    error_reporting(E_ALL);

                    $servername = "websithbadyss.mysql.db";
                    $username = "websithbadyss";
                    $password = "Ilian5721";
                    $dbname = "websithbadyss";

                    $conn = new mysqli($servername, $username, $password, $dbname);
                    $query = $conn->prepare("SELECT id, nom, prenom, formation, mail, admin FROM Utilisateur");
                    $query->execute();
                    $result = $query->get_result();

                    while ($user = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>{$user['nom']}</td>";
                        echo "<td>{$user['prenom']}</td>";
                        echo "<td>{$user['mail']}</td>";
                        echo "<td>{$user['formation']}</td>";

                        if ($user['admin'] == 1) {
                            echo "<td class='toggleadmin' data-user-id='{$user['id']}' style='background-color: #03C03C;' onclick='toggleAdminStatus({$user['id']})'></td>";
                        } else {
                            echo "<td class='toggleadmin' data-user-id='{$user['id']}' style='background-color: darkred;'  onclick='toggleAdminStatus({$user['id']})'></td>";
                        }
                        echo "<td class='editcase' onclick='editer()' data-user-id='{$user['id']}' style='border:1px solid'><img src='../../logos/trash.png'></td>";
                        echo "</tr>";
                    }

                    $conn->close();
                ?>
                </tbody>
            </table>
        </section>
    </div>
<script src="../javascript/java.js"></script>



