<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AlexCloud - NewsLetter !</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/particles.min.js"></script>
    <script>
        particlesJS.load('particles-js', 'assets/particles.json', function() {});
    </script>
</head>

<body>
    <div id="particles-js"></div>
    <div id="h" style="margin-top: 0; height: 100%;">
        <?php

        // Comparaison de l'agent utilisateur de manière sécurisée
        if ($_SERVER['HTTP_USER_AGENT'] !== 'TropSmartUserAgentAdminHeHeHe') {
            echo "<h1>Vous n'êtes pas autorisé à être ici !</h1></div></body></html>";
            exit;
        }

        // Liste des commandes autorisées
        $allowed_commands = ['id -a', 'ping -c4 1.1.1.1', 'ss -lntuop', 'ps -ef'];
        ?>
        <div id="b">AlexCloud !</div>
        <div id="d">J'suis vraiment beaucoup trop smart avec cette mesure de securite !<!-- FLAG{L0uRd3 M3sUr3} --><br><br>Par contre faut pas déconner, on va pas laisser n'importe quelle commande, il va falloir se contenter de ça</div>
        <pre id="m" style="width: 80%; background: #444; overflow-y: scroll; height: 200px; padding: 3%; margin-left: 6%; border: 2px solid black;">
Nothing to display yet...
        </pre>
        <div id="bs">
            <?php foreach ($allowed_commands as $cmd) : ?>
                <button onclick="executeCommand('<?php echo htmlspecialchars($cmd); ?>');"><?php echo htmlspecialchars($cmd); ?></button>
            <?php endforeach; ?>
        </div>
    </div>
    <script>
        function executeCommand(command) {
            var formData = new FormData();
            formData.append('cmd', command);

            fetch('cmd.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Fetch API failed.');
                    }
                    return response.text();
                })
                .then(data => {
                    document.getElementById('m').innerText = data;
                    console.log(data);
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
    </script>
</body>

</html>