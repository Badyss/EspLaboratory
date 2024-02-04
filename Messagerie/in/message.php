<?php
    session_start();

    $nom = $_SESSION["Snom"] ?? "";
    $prenom = $_SESSION["Sprenom"] ?? "";
    $formation = $_SESSION["Sformation"] ?? "";
    $UserID = $_SESSION["SId"] ?? "";
    $sessionUserID = $_SESSION["SId"] ?? "";
    $getID = $_GET["UserId"] ?? "";

    echo '<script>';
    echo 'currentUser = ' . json_encode($getID) . ';';
    echo '</script>';

    $server = "websithbadyss.mysql.db";
    $user = "websithbadyss";
    $pass = "Ilian5721";
    $dbname = "websithbadyss";

    $conn = new mysqli($server, $user, $pass, $dbname);

    if ($conn->connect_error) {
        die("La connexion a échoué : " . $conn->connect_error);
    }

        try {
            $queryUsers = "SELECT * FROM Utilisateur";
            $resultUsers = $conn->query($queryUsers);

            if ($resultUsers) {
                $users = $resultUsers->fetch_all(MYSQLI_ASSOC);
            } else {
                die("Erreur lors de la récupération des utilisateurs: " . $conn->error);
            }
        } catch (Exception $e) {
            die("Erreur : " . $e->getMessage());
        }

?>
<html lang="en">
<head>
  <meta charset="utf-8">
  <link rel="shortcut icon" href="../../logos/logohead.png" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" />
  <link rel="stylesheet" type="text/css" href="../css/navbar.css">
  <link rel="stylesheet" type="text/css" href="../css/messages.css">
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
                                    echo "<p style='position:absolute;top:-5px;right:20px;width:20px;padding: 0.5px 0; padding-left:0.5px; border-radius:50%;background-color:red'>" . $total_notifications . "</p>";
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
        <div class="boutons">
            <button class="boutoncrer" onclick="crermsg('crergroupe')"><span>Créer un groupe</span></button>
            <button class="boutoncrer" onclick="crermsg('crerconv')"><span>Nouvelle Conversation</span></button>
            <div class="crergroupe" id="crergroupe">
                <div class="imgclose">
                    <img src="../../logos/close.png"  onclick="crermsg('fermer')">
                </div>
                <h1 style="color:rgba(35, 73, 118, 255)">Nouveau groupe</h1>
                <div class="formulairesend">
                    <form id="createGroupForm" onsubmit="return false;">
                        <label for="selectedUsers">Sélectionner les utilisateurs:</label>
                        <select id="selectedUsers" name="selectedUsers[]" multiple>
                            <?php foreach ($users as $user) : ?>
                                <option value="<?= $user['id']; ?>"><?= $user['nom'] . ' ' . $user['prenom']; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <label for="groupName">Nom du groupe:</label>
                        <input type="text" id="groupName" name="groupName" required>
                        <button type="button" onclick="createGroup()">Créer le groupe</button>
                    </form>
                </div>
            </div>
            <div class="crergroupe" id="crerconv">
                <div class="imgclose">
                    <img src="../../logos/close.png"  onclick="crermsg('fermer')">
                </div>
                <h1 style="color:rgba(35, 73, 118, 255)">Nouvelle discussion</h1>
                <div class="formulairesend">
                    <form id="sendMessageForm" method="post">
                        <label for="selectedUser">Sélectionner un utilisateur:</label>
                        <select id="selectedUser" name="selectedUser" onchange="updateSelectedUser(this)">
                            <?php foreach ($users as $user) : ?>
                                <option value="<?= $user['id']; ?>"><?= $user['nom'] . ' ' . $user['prenom']; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <label for="message">Message:</label>
                        <input type="text" id="NewmessageInput" name="message" required rows="4" cols="50">
                        <button type="button" onclick="submitForm()">Envoyer</button>
                    </form>
                </div>
            </div>
        </div>
        <audio id="messageSound">
            <source src="../son/sms.mp3" type="audio/mpeg">
        </audio>
        <audio id="messageSound2">
            <source src="../son/notif.mp3" type="audio/mpeg">
        </audio>

        <div class="messagerie">
        <div id="conversations"></div>
        <div id="messages">
            <div id="messages-container">
            </div>
            <div id="send-messages">
                <textarea placeholder="Entrez votre message" type="text" id="messageInput"></textarea>
                <button onclick="sendMessage()" id="send"><img src="../../logos/send.png" style="width:50px";></button>
            </div>
        </div>
        </div>

    </main>
</body>

<script>

let currentChat = null;
currentUser = <?php echo json_encode($sessionUserID); ?>;
last_message_id = 0;
let conversationMessageCounts = {};

function updateSelectedUser(selectElement) {
        currentChat = selectElement.value;
}

function submitForm() {
    let selectedUserId = document.getElementById("selectedUser").value;
    currentChat = selectedUserId;
    sendMessage();
}

function playMessageSound() {
    let audioElement = document.getElementById("messageSound");
    audioElement.play();
}

function playMessageSound2() {
    let audioElement = document.getElementById("messageSound2");
    audioElement.play();
}

function getConversations() {
    let url = `https://websiteisfy.fr/esplab.fr/Messagerie/in/api.php?getConversations&user_id=${currentUser}`;
    fetch(url)
        .then(response => response.json())
        .then(data => {
            console.log(data);

            let conversations = document.getElementById("conversations");
            conversations.innerHTML = '';

            data.forEach(element => {
                let div = document.createElement("div");
                div.classList.add("conversation");
                
                // Ajouter la balise img pour l'avatar
                let avatarImg = document.createElement("img");
                avatarImg.classList.add("user-avatar");
                avatarImg.src = `../avatars/${element.id}.png`; // Chemin vers l'avatar spécifique

                // Vérifier si l'image existe, sinon utiliser l'avatar par défaut
                avatarImg.onerror = function () {
                    this.src = "../avatars/default.png"; // Chemin vers l'avatar par défaut
                };

                avatarImg.alt = "Avatar";
                div.appendChild(avatarImg);

                // Ajouter le nom de l'utilisateur
                let displayName = document.createElement("span");
                displayName.classList.add("user-name");
                displayName.textContent = element.nom ? element.nom : element.prenom + " " + element.nom;
                div.appendChild(displayName);

                // Ajouter le compteur de messages
                let messageCountContainer = document.createElement("span");
                messageCountContainer.classList.add("message-count");
                div.appendChild(messageCountContainer);

                getMessageCount(element.id, messageCountContainer);
                div.setAttribute("onclick", "clickConversation(" + element.id + ")");
                conversations.appendChild(div);
            });
        });
}

function initializeMessageCounts() {
    let url = `https://websiteisfy.fr/esplab.fr/Messagerie/in/api.php?getConversations&user_id=${currentUser}`;

    fetch(url)
        .then(response => response.json())
        .then(data => {
            data.forEach(element => {
                conversationMessageCounts[element.id] = 0;
            });
            getConversations();
        });
}

function getMessages(callback) {
    let url = `https://websiteisfy.fr/esplab.fr/Messagerie/in/api.php?getMessages&user_id=${currentUser}&chat_id=${currentChat}`
    fetch(url)
        .then(response => response.json())
        .then(data => {
            callback(data)
        });
}

function clickConversation(id) {
    currentChat = id;
    last_message_id = 0;
    let messagesContainer = document.getElementById("messages-container");
    messagesContainer.innerHTML = '';
    getMessages(showMessages);
}

function isMessageAlreadyDisplayed(messageId) {
    let messagesContainer = document.getElementById("messages-container");
    let existingMessages = messagesContainer.getElementsByClassName("message");
    
    for (let i = 0; i < existingMessages.length; i++) {
        let existingMessageId = existingMessages[i].getAttribute("data-message-id");
        if (existingMessageId && parseInt(existingMessageId) === messageId) {
            return true; 
        }
    }

    return false; 
}

function updateMessageCounts(conversationId, newMessageCount) {
    conversationMessageCounts[conversationId] += newMessageCount;
    let messageCountContainer = document.getElementById(`message-count-${conversationId}`);
    if (messageCountContainer) {
        messageCountContainer.textContent = ` (${conversationMessageCounts[conversationId]} messages)`;
    }
}

function getMessageCount(conversationId, container) {
    let messageCountUrl = `https://websiteisfy.fr/esplab.fr/Messagerie/in/api.php?getMessageCount&user_id=${currentUser}&chat_id=${conversationId}&cache=${Date.now()}`;

    fetch(messageCountUrl)
    .then(response => response.json())
    .then(data => {
        let difference = data.messageCount - conversationMessageCounts[conversationId];

        if (typeof difference === 'number' && !isNaN(difference) && difference !== 0) {
            updateMessageCounts(conversationId, difference);
            container.textContent = `${difference}`;
            if (conversationId !== currentChat) {
                container.style.display = 'flex';
                playMessageSound2();
            } else {
                container.style.display = 'none';
            }
        } else {
            console.error("La valeur de difference n'est pas un nombre valide :", difference);
            container.style.display = 'none';
        }
    })
    .catch(error => {
        console.error("Erreur lors de la récupération du nombre de messages :", error);
        container.style.display = 'none';
    });

}

function sendMessage() {
    if (currentChat == null) {
        alert("Veuillez sélectionner une conversation");
        return;
    }

    let message = document.getElementById("messageInput").value;
    let newMessage = document.getElementById("NewmessageInput").value;

    if (message === "" && newMessage === "") {
        alert("Veuillez entrer un message");
        return;
    }

    document.getElementById("messageInput").value = "";
    document.getElementById("NewmessageInput").value = "";

    let url = "https://websiteisfy.fr/esplab.fr/Messagerie/in/api.php?sendMessage";

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `user_id=${currentUser}&chat_id=${currentChat}&message=${encodeURIComponent(message || newMessage)}`,
    })
    .then(response => response.json())
    .then(data => {
        console.log(data);

        playMessageSound();
        updateMessageCount(currentChat);
    });
}

function insertMessage(message) {
    let messagesContainer = document.getElementById("messages-container");

    let messageDiv = document.createElement("div");
    messageDiv.classList.add("message");
    messageDiv.setAttribute("data-message-id", message.id);

    if (message.sender_id == currentUser) {
        messageDiv.classList.add("sent");
    } else {
        messageDiv.classList.add("received");

        // Ajouter la photo de profil pour les messages reçus (si ce n'est pas l'utilisateur actuel)
        if (message.sender_id !== currentUser) {
            let profileImg = document.createElement("img");
            profileImg.classList.add("user-avatar");
            profileImg.src = `../avatars/${message.sender_id}.png`; // Chemin vers l'avatar spécifique

            // Vérifier si l'image existe, sinon utiliser l'avatar par défaut
            profileImg.onerror = function () {
                this.src = "../avatars/default.png"; // Chemin vers l'avatar par défaut
            };

            profileImg.alt = "Avatar";
            messageDiv.appendChild(profileImg);
        }
    }

    let messageContent = document.createElement("div");
    messageContent.classList.add("message-content");
    messageDiv.appendChild(messageContent);
    let displayName = '';

    if (message.prenom && message.sender_id !== currentUser) {
        displayName = message.prenom;

        if (message.nom) {
            displayName += ' ' + message.nom;
        }
    }

    let messageText = displayName ? `${displayName}: ${message.message}` : message.message;
    messageContent.innerHTML = messageText;

    let messageDate = document.createElement("div");
    messageDate.classList.add("message-date");
    messageDiv.appendChild(messageDate);

    // Utiliser une fonction pour formater la date
    messageDate.innerHTML = formatMessageDate(message.timestamp);

    messagesContainer.appendChild(messageDiv);
}

function formatMessageDate(timestamp) {
    const options = { day: 'numeric', month: 'long', hour: 'numeric', minute: 'numeric', second: 'numeric' };
    const formattedDate = new Date(timestamp).toLocaleDateString('fr-FR', options);
    return formattedDate;
}

function showMessages(messages) {
    console.log(messages); 

    let messagesContainer = document.getElementById("messages-container");

    for (let i = 0; i < messages.length; i++) {
        const message = messages[i];
        if (message.id > last_message_id) {
            last_message_id = message.id;
            if (!isMessageAlreadyDisplayed(message.id)) {
                insertMessage(message);
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }
        }
    }
}

function clickGroupConversation(id) {
    currentChat = id;
    last_message_id = 0;
    let messagesContainer = document.getElementById("messages-container");
    messagesContainer.innerHTML = '';
    getMessages(showMessages);
}

function getGroupConversations() {
    let url = `https://websiteisfy.fr/esplab.fr/Messagerie/in/api.php?getGroupConversations&user_id=${currentUser}`;
    fetch(url)
        .then(response => response.json())
        .then(data => {
            let conversations = document.getElementById("conversations");

            data.forEach(element => {
                let div = document.createElement("div");
                div.classList.add("conversation");
                div.innerHTML = element.nom;
                div.setAttribute("onclick", "clickGroupConversation(" + element.id + ")");
                conversations.appendChild(div);
            });
        });
}

function getGroupMessages(callback) {
    let url = `https://websiteisfy.fr/esplab.fr/Messagerie/in/api.php?getGroupMessages&user_id=${currentUser}&group_id=${currentChat}`
    fetch(url)
        .then(response => response.json())
        .then(data => {
            callback(data);
        });
}

function createGroup() {
        let groupName = document.getElementById("groupName").value;
        let selectedUsers = Array.from(document.getElementById("selectedUsers").options)
            .filter(option => option.selected)
            .map(option => option.value);

        if (groupName === "" || selectedUsers.length === 0) {
            alert("Veuillez remplir tous les champs");
            return;
        }

        let url = "https://websiteisfy.fr/esplab.fr/Messagerie/in/api.php?createGroup";

        fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `groupName=${encodeURIComponent(groupName)}&selectedUsers=${encodeURIComponent(JSON.stringify(selectedUsers))}`,
    })
    .then(response => response.json())
    .then(data => {
        // Ajoutez une vérification du statut dans la réponse
        if (data && data.status) {
            if (data.status === 'success') {
                // Votre code de gestion du succès ici
                // Par exemple, réactualiser la liste des groupes ou effectuer d'autres actions nécessaires
                getGroupConversations();  // Mise à jour de la liste des groupes
            } else {
                console.error("Échec de la création du groupe :", data);
                // Gérez l'échec de la création du groupe ici
            }
        } else {
            console.error("Réponse inattendue lors de la création du groupe :", data);
            // Gérez les réponses inattendues ici
        }
    })
    .catch(error => {
        console.error("Erreur lors de la création du groupe :", error);
    });
}

setInterval(() => {
    if (currentChat != null)
        getMessages(showMessages);
}, 1000);

setInterval(() => {
    if (currentChat != null)
        getConversations();
        getGroupConversations();
}, 3000);

initializeMessageCounts();
getConversations();
getGroupConversations();
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
<script src="../javascript/java.js"></script>
</html>