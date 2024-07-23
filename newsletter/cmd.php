<?php
session_start();

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
