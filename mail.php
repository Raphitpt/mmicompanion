<?php

// Configurations de connexion
$serveur = "{upmail.univ-poitiers.fr/ssl/validate-cert}INBOX";
$utilisateur = "univ-poitiers.fr\rtiphone";
$mot_de_passe = "69Y7t5ps";

// Établir une connexion IMAP
$boiteMail = imap_open($serveur, $utilisateur, $mot_de_passe);
if (!$boiteMail) {
    die('Impossible de se connecter à la boîte mail : ' . imap_last_error());
}

// Lire les e-mails non lus
$emailsNonLus = imap_search($boiteMail, 'UNSEEN');

if ($emailsNonLus) {
    foreach ($emailsNonLus as $email) {
        $en_tete = imap_fetch_overview($boiteMail, $email, FT_UID);
        echo "De: " . $en_tete[0]->from . "<br>";
        echo "Sujet: " . $en_tete[0]->subject . "<br>";
    }
}

// Envoyer un e-mail
$destinataire = "destinataire@example.com";
$sujet = "Mon sujet d'e-mail";
$message = "Contenu de mon e-mail";

$headers = "From: " . $utilisateur . "\r\n" .
           "Reply-To: " . $utilisateur . "\r\n" .
           "X-Mailer: PHP/" . phpversion();

if (mail($destinataire, $sujet, $message, $headers)) {
    echo "E-mail envoyé avec succès.";
} else {
    echo "Erreur lors de l'envoi de l'e-mail.";
}

// Fermer la connexion IMAP
imap_close($boiteMail);

?>