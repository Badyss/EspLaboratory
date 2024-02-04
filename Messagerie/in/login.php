<?php
session_start();
$servername = "websithbadyss.mysql.db";
$username = "websithbadyss";
$password = "Ilian5721";
$dbname = "websithbadyss";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("La connexion a échoué : " . $conn->connect_error);
    }

    $mail = $_POST["mail"];
    $password = $_POST["password"];

    $date_connexion = date("Y-m-d H:i:s");

    $sql = "SELECT id, nom, prenom, formation, motdepasse FROM Utilisateur WHERE mail = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die('Erreur de préparation de la requête : ' . $conn->error);
    }

    $stmt->bind_param("s", $mail);
    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user["motdepasse"])) {
        
        if(isset($_POST['remember'])){

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            setcookie('email',$mail,time()+365*24*3600,null,null,false,true);
            setcookie('password',$hashed_password,time()+365*24*3600,null,null,false,true);
        }
        $nom = $user["nom"];
        $prenom = $user["prenom"];
        $formation = $user["formation"];
        $UserID = $user["id"];
        $_SESSION["SId"] = $UserID;
        $_SESSION["Snom"] = $nom;
        $_SESSION["Sprenom"] = $prenom;
        $_SESSION["Sformation"] = $formation;
         
        $sql = "INSERT INTO Connexions (mail, date_connexion)
        VALUES ('$mail', '$date_connexion')";

        if ($conn->query($sql) === TRUE) {
            echo "Nouvelle connexion ajoutée à la table Connexions";
        } else {
            echo "Erreur lors de l'ajout de la nouvelle connexion à la table Connexions : " . $conn->error;
        }

        header("Location: /esplab.fr/Messagerie/in/acceuil.php?nom=" . urlencode($nom) . "&prenom=" . urlencode($prenom) . "&formation=" . urlencode($formation) . "&UserId=" . urlencode($UserID));
    } else {
        $erreurMessage = "Identifiant ou mot de passe incorrect";
        header("Location: ../login.html?erreurMessage=" . urlencode($erreurMessage));
    }

    $stmt->close();
    $conn->close();
}
?>
