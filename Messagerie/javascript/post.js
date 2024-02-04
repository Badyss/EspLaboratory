var modal = document.getElementById('myModal');

function openModal() {
    modal.style.display = 'flex';
}

function closeModal() {
    modal.style.display = 'none';
}

function submitPost() {
    var postTitle = document.getElementById('postTitle').value;
    var postContent = document.getElementById('postContent').value;

    // Vérif
    if (!postTitle || !postContent) {
        alert('Veuillez remplir le titre et le contenu du post.');
        return;
    }
    // Traitement contenu du post
    console.log('Titre du post : ' + postTitle);
    console.log('Contenu du post : ' + postContent);

    closeModal();
}

// Fermer le modal si l'utilisateur clique en dehors du contenu du modal
window.onclick = function(event) {
    if (event.target === modal) {
        closeModal();
    }
};
