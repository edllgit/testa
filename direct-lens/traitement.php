<?php
// Vérifier si la valeur a été envoyée depuis le formulaire
if (isset($_POST['valeurSelectionnee'])) {
    $valeurSelectionnee = $_POST['valeurSelectionnee'];
    $previousURL = isset($_POST['previousURL']) ? $_POST['previousURL'] : '/';

    // Afficher la valeur stockée dans la variable PHP
    echo 'La valeur stockée est : ' . $valeurSelectionnee;
} else {
    echo 'Aucune valeur n\'a été reçue.';
    $previousURL = '/';
}

// Ajouter un script JavaScript pour rediriger après 2 secondes
echo '
    <script>
        setTimeout(function() {
            window.location.href = "' . $previousURL . '";
        }, 2000);
    </script>
';
?>
