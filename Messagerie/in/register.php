<?php
$servername = "websithbadyss.mysql.db";
$username = "websithbadyss";
$password = "Ilian5721";
$dbname = "websithbadyss";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("La connexion a échoué : " . $conn->connect_error);
}

$prenom = $_POST["prenom"];
$nom = $_POST["nom"];
$mail = $_POST["mail"];
$formation = $_POST["formation"];
$motdepasse = $_POST["password"];
$password = password_hash($_POST["password"], PASSWORD_BCRYPT);

$date_creation = date("Y-m-d H:i:s");
$date_connexion = date("Y-m-d H:i:s");

$check_email_sql = "SELECT mail FROM Utilisateur WHERE mail = ?";
$check_stmt = $conn->prepare($check_email_sql);
$check_stmt->bind_param("s", $mail);
$check_stmt->execute();
$check_stmt->store_result();

if ($check_stmt->num_rows > 0) {
    header("Location: ../register.html?error=email_exists");
} else {
    // Insérer l'utilisateur dans la base de données
    $sql = "INSERT INTO Utilisateur (prenom, nom, mail, formation, motdepasse, date_creation) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die('Erreur de préparation de la requête : ' . $conn->error);
    }

    $stmt->bind_param("ssssss", $prenom, $nom, $mail, $formation, $password, $date_creation);

    if ($stmt->execute()) {
        $UserID = $stmt->insert_id;
        session_start();
        $_SESSION["SId"] = $UserID;
        $_SESSION["Snom"] = $nom;
        $_SESSION["Sprenom"] = $prenom;
        $_SESSION["Sformation"] = $formation;
        header("Location: profil.php?nom=$nom&prenom=$prenom&formation=$formation&UserId=$UserID");

        $to = $mail;
        $subject = "Bienvenue chez Esplab";
    
        $message = "
        <html>
        <head>
            <title>Bienvenue chez Esplab</title>
            <style>
                .header {
                    background-color: rgba(35, 73, 118, 255);
                    padding: 20px;
                    border-radius: 10px 10px 0px 0px;
                    margin: 0px auto;
                    max-width: 720px;
                    margin-left: -400px;
                }
                
                .header h1 {
                    color: white;
                    font-size: 24px;
                    font-weight: bold;
                    text-align: center;
                }
                
                /* Media query for large screens (desktop) */
                
                @media (min-width: 760px) {
                    .header {
                    padding: 40px;
                    }
                }
                
                /* Corps */
                
                .body {
                    background-color: #eeeeee;
                    padding: 20px;
                    border-radius: 0px 0px 10px 10px;
                    text-align: center;
                    margin: 0px auto;
                    max-width: 760px;
                }
                
                .body h2 {
                    color: #000000;
                    font-size: 30px;
                    margin-bottom: 20px;
                }
                
                .body p {
                    color: #000000;
                    font-size: 16px;
                }
                
                /* Formulaire d'inscription */
                
                form {
                    width: 50%;
                    margin: 20px auto;
                    padding: 20px;
                    background-color: #f2f2f2;
                    border-radius: 10px;
                }
                
                .input {
                    width: 100%;
                    padding: 10px;
                    border: 1px solid #ccc;
                    margin-bottom: 10px;
                }
                
                .submit {
                    background-color: #28a745;
                    color: #fff;
                    padding: 10px 20px;
                    border: none;
                    border-radius: 5px;
                    cursor: pointer;
                }
                
                /* Lien de connexion */
                
                .login {
                    text-align: center;
                }
                
                .login a {
                    color: #333333;
                    text-decoration: none;
                }
               
                /* Media query for small screens (mobile) */
                
                @media (max-width: 767px) {
                    .body {
                    padding: 20px;
                    margin-bottom: 20px;
                    }
                
                    .submit {
                    margin-bottom: 0px;
                    }
                    
                    .body ul {
                        margin: 0 auto;
                        text-align: center;
                    }

                    .body ul li .circle {
                        margin: 0 auto;
                        position: absolute;
                        top: 50%;
                        transform: translateY(-50%);
                    }
                      
                      
                }
            </style>
        </head>
        <body>
            <div class='header'>
            <h1>EspLaboratory</h1>
            </div>

            <div class='body'>
            <h1 style='color: #333333;'>$prenom $nom bienvenue chez Esplab !</h1>
            <p>Merci d'avoir rejoint la communauté Esplab ! Ton compte a été créé avec succès.</p>
            <p class='info'>Tes informations :
                <ul class = 'info'>
                    <li class = 'info'>Prénom : $prenom</li>
                    <li class = 'info'>Nom : $nom</li>
                    <li class = 'info'><strong>Adresse e-mail :</strong> $mail</li>
                    <li class = 'info'>Formation : $formation</li>
                </ul>
            </p>
            <p>Explore notre plateforme et découvre tout ce qu'elle a à t'offrir.</p>
            <p>Merci encore pour ton inscription !</p>
            <p style='color: #888888;'>Cordialement,<br>L'équipe Esplab</p>
            </div>
        </body>
        </html>";
    
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        $headers .= "From: ESPLAB\r\n";

    if (mail($to, $subject, $message, $headers)) {
        echo "Un email de bienvenue a été envoyé à $mail.";

        $mail = $_SESSION["Smail"];
        $sql = "UPDATE Connexions SET nb_connexions = nb_connexions + 1 WHERE mail = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $mail);
        $stmt->execute();
        $stmt->close();

        $sql = "INSERT INTO Connexions (mail, date_connexion)
        VALUES ('$mail', '$date_connexion')";

        if ($conn->query($sql) === TRUE) {
            echo "Nouvelle connexion ajoutée à la table Connexions";
        } else {
            echo "Erreur lors de l'ajout de la nouvelle connexion à la table Connexions : " . $conn->error;
        }

    } else {
        echo "Erreur lors de l'envoi de l'email.";
    }
    } else {
        echo "Erreur lors de l'enregistrement de l'utilisateur : " . $stmt->error;
    }

    $stmt->close();
}

$check_stmt->close();
$conn->close();
?>
