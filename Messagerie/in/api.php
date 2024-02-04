<?php
session_start();

    $nom = $_SESSION["Snom"] ?? "";
    $prenom = $_SESSION["Sprenom"] ?? "";
    $formation = $_SESSION["Sformation"] ?? "";
    $UserID = $_SESSION["SId"] ?? "";
    $sessionUserID = $_SESSION["SId"] ?? "";
    $getID = $_GET["UserId"] ?? "";

    $server = "websithbadyss.mysql.db";
    $user = "websithbadyss";
    $pass = "Ilian5721";
    $dbname = "websithbadyss";

    $conn = new mysqli($server, $user, $pass, $dbname);

    if ($conn->connect_error) {
        die("La connexion a échoué : " . $conn->connect_error);
    }

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: *");

// Request : /getConversations?user_id=xx
// Return : JSON
// Get all the conversations of the user (name, id)

if (isset($_GET['getConversations'])) {
    if (!isset($_GET['user_id'])) die("Missing user_id");

    $user_id = $_GET['user_id'];

    // Requête pour obtenir les conversations basées sur les messages
    $queryMessages = "SELECT DISTINCT
        U.id, U.prenom, U.nom
        FROM
        Messages M
        JOIN
            Utilisateur U ON U.id = CASE
                WHEN M.sender_id = ? THEN M.receiver_id
                ELSE M.sender_id
            END
        WHERE
        M.sender_id = ? OR M.receiver_id = ?";

    $stmtMessages = $conn->prepare($queryMessages);
    $stmtMessages->bind_param('iii', $user_id, $user_id, $user_id);
    $stmtMessages->execute();

    $resultMessages = $stmtMessages->get_result();
    $conversationsMessages = $resultMessages->fetch_all(MYSQLI_ASSOC);

    // Requête pour obtenir les noms des groupes auxquels l'utilisateur appartient
    $queryGroups = "SELECT G.id, G.nom
        FROM
        Groupes G
        JOIN
            UtilisateursGroupes UG ON G.id = UG.id_groupe
        WHERE
        UG.id_utilisateur = ?";

    $stmtGroups = $conn->prepare($queryGroups);
    $stmtGroups->bind_param('i', $user_id);
    $stmtGroups->execute();

    $resultGroups = $stmtGroups->get_result();
    $conversationsGroups = $resultGroups->fetch_all(MYSQLI_ASSOC);

    // Fusionner les résultats des conversations basées sur les messages et les groupes
    $conversations = array_merge($conversationsMessages, $conversationsGroups);

    echo json_encode($conversations);
}

else if (isset($_GET['getMessageCount'])) {
    if (!isset($_GET['user_id'])) die("Missing user_id");
    if (!isset($_GET['chat_id'])) die("Missing chat_id");

    $user_id = $_GET['user_id'];
    $chat = $_GET['chat_id'];

    // Sélectionner le nombre total de messages pour une conversation individuelle
    $queryIndividual = "SELECT COUNT(*) as messageCount FROM Messages 
        WHERE
        (sender_id = ? AND receiver_id = ?) OR
        (sender_id = ? AND receiver_id = ?)";

    $stmtIndividual = $conn->prepare($queryIndividual);
    $stmtIndividual->bind_param('iiii', $user_id, $chat, $chat, $user_id);
    $stmtIndividual->execute();

    $resultIndividual = $stmtIndividual->get_result();
    $messageCountIndividual = $resultIndividual->fetch_assoc()['messageCount'];

    // Sélectionner le nombre total de messages pour une conversation de groupe
    $queryGroup = "SELECT COUNT(*) as messageCount FROM MessagesGroupes WHERE id_groupe = ?";

    $stmtGroup = $conn->prepare($queryGroup);
    $stmtGroup->bind_param('i', $chat);
    $stmtGroup->execute();

    $resultGroup = $stmtGroup->get_result();
    $messageCountGroup = $resultGroup->fetch_assoc()['messageCount'];

    // Retourner le nombre total de messages
    $messageCount = $messageCountIndividual + $messageCountGroup;

    echo json_encode(array("messageCount" => $messageCount));
}

// Request : /getMessages?user_id=xx&chat=xx
// Return : JSON
// Get all the messages between the user and the chat

else if (isset($_GET['getMessages'])) {
    if (!isset($_GET['user_id'])) die("Missing user_id");
    if (!isset($_GET['chat_id'])) die("Missing chat_id");

    $user_id = $_GET['user_id'];
    $chat = $_GET['chat_id'];

    // Sélectionnez les messages de la conversation individuelle
    $queryIndividual = "SELECT * FROM Messages 
        WHERE
        (sender_id = ? AND receiver_id = ?) OR
        (sender_id = ? AND receiver_id = ?)
        ORDER BY
        timestamp ASC;";

    $stmtIndividual = $conn->prepare($queryIndividual);
    $stmtIndividual->bind_param('iiii', $user_id, $chat, $chat, $user_id);
    $stmtIndividual->execute();

    $resultIndividual = $stmtIndividual->get_result();
    $messagesIndividual = $resultIndividual->fetch_all(MYSQLI_ASSOC);

    // Sélectionnez les messages du groupe
    // Sélectionnez les messages du groupe
    $queryGroup = "SELECT MG.id, MG.id_groupe, MG.sender_id, MG.message, MG.timestamp, U.nom, U.prenom, G.nom as group_name
    FROM MessagesGroupes MG
    JOIN Groupes G ON MG.id_groupe = G.id
    JOIN Utilisateur U ON MG.sender_id = U.id
    WHERE MG.id_groupe = ? ORDER BY MG.timestamp ASC;";

    $stmtGroup = $conn->prepare($queryGroup);
    $stmtGroup->bind_param('i', $chat);
    $stmtGroup->execute();

    $resultGroup = $stmtGroup->get_result();
    $messagesGroup = $resultGroup->fetch_all(MYSQLI_ASSOC);

    // Récupérer le nom et prénom de l'utilisateur pour chaque message de groupe
    foreach ($messagesGroup as &$message) {
        $userId = $message['sender_id'];

        // Effectuer une requête pour obtenir le nom et prénom de l'utilisateur
        $queryUser = "SELECT nom, prenom FROM Utilisateur WHERE id = ?";
        $stmtUser = $conn->prepare($queryUser);
        $stmtUser->bind_param('i', $userId);
        $stmtUser->execute();

        $resultUser = $stmtUser->get_result();
        $userData = $resultUser->fetch_assoc();

        // Mettre à jour les données de l'utilisateur dans le tableau des messages
        $message['nom'] = $userData['nom'];
        $message['prenom'] = $userData['prenom'];
    }

    // Fusionner les résultats des messages individuels et de groupe
    $messages = array_merge($messagesIndividual, $messagesGroup);

    echo json_encode($messages);

}

else if (isset($_GET['sendMessage'])) {
    if (!isset($_POST['user_id'])) die("Missing user_id");
    if (!isset($_POST['chat_id'])) die("Missing chat_id");
    if (!isset($_POST['message'])) die("Missing message");

    $user_id = $_POST['user_id'];
    $chat = $_POST['chat_id'];
    $message = $_POST['message'];

    // Vérifiez si la conversation est un groupe
    $isGroup = false;
    $groupQuery = "SELECT * FROM UtilisateursGroupes WHERE id_groupe = ? AND id_utilisateur = ?";
    $groupStmt = $conn->prepare($groupQuery);
    $groupStmt->bind_param('ii', $chat, $user_id);
    $groupStmt->execute();

    $groupResult = $groupStmt->get_result();
    $isGroup = $groupResult->num_rows > 0;

    if ($isGroup) {
        // Si c'est un groupe, insérez le message pour chaque membre du groupe
        $insertQuery = "INSERT INTO MessagesGroupes (id_groupe, sender_id, message, timestamp) SELECT ?, ?, ?, NOW()";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->bind_param('iis', $chat, $user_id, $message);
        $insertStmt->execute();
    } else {
        // Si ce n'est pas un groupe, insérez le message normalement
        $query = "INSERT INTO Messages (id, sender_id, receiver_id, message) VALUES (default, ?, ?, ?)";
        $stmt = $conn->prepare($query);

        if (!$stmt) {
            die("Erreur de préparation de la requête : " . $conn->error);
        }

        $stmt->bind_param('iis', $user_id, $chat, $message);

        if (!$stmt->execute()) {
            die("Erreur lors de l'exécution de la requête : " . $stmt->error);
        }
    }

    echo json_encode(array("status" => "success"));
}

else if (isset($_GET['createGroup'])) {
    if (!isset($_POST['groupName'])) die("Missing groupName");
    if (!isset($_POST['selectedUsers'])) die("Missing selectedUsers");

    $groupName = $_POST['groupName'];
    $selectedUsers = json_decode($_POST['selectedUsers']);

    // Insérer le groupe dans la table Groupes
    $query = "INSERT INTO Groupes (nom) VALUES (?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $groupName);
    $stmt->execute();

    // Récupérer l'ID du groupe nouvellement créé
    $groupId = $stmt->insert_id;

    // Associer chaque utilisateur sélectionné au groupe dans la table UtilisateursGroupes
    foreach ($selectedUsers as $userId) {
        $query = "INSERT INTO UtilisateursGroupes (id_groupe, id_utilisateur) VALUES (?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ii', $groupId, $userId);
        $stmt->execute();
    }

    echo json_encode(array("status" => "success"));
}


else {
    echo json_encode(array("status" => "error", "message" => "Route not found"));
}
?>