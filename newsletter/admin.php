<?php
require("../admin_check.php");
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AlexCloud - NewsLetter !</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/particles.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            particlesJS.load('particles-js', 'assets/particles.json');
        });
    </script>
</head>

<body>
    <div id="particles-js"></div>
    <div id="h" style="margin-top: 0; height: 100%;">
        <?php
        session_start();
        // Liste des commandes autorisées
        $allowed_commands = ['id -a', 'ping -c4 1.1.1.1', 'ss -lntuop', 'ps -ef'];
        ?>
        <div id="b">AlexCloud !</div>
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
            const formData = new FormData();
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