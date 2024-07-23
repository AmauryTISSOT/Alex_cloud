<?php
session_start();

// Comparaison de l'agent utilisateur de manière sécurisée
if ($_SERVER['HTTP_USER_AGENT'] !== 'TropSmartUserAgentAdminHeHeHe') {
    header('HTTP/1.0 403 Forbidden');
    echo 'Vous n\'êtes pas autorisé à être ici !';
    exit;
}

// Liste des commandes autorisées
$allowed_commands = ['id -a', 'ping -c4 1.1.1.1', 'ss -lntuop', 'ps -ef'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cmd = $_POST['cmd'] ?? '';

    if (in_array($cmd, $allowed_commands, true)) {
        // Exécution sécurisée de la commande
        $output = shell_exec(escapeshellcmd($cmd));
        echo htmlspecialchars($output);
    } else {
        echo 'Commande non autorisée.';
    }
}

