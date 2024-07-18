<?php
// Fonction qui vient établie une connexion avec la base de données "users"
function getConnection()
{
    $host = 'localhost'; // ou l'adresse de votre serveur de base de données
    $dbname = 'users';
    $username = 'app';
    $password = 'app';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("Erreur de connexion : " . $e->getMessage());
    }
}

// Fonction qui vient vérifier si l'email existe dans la base de données
function checkEmailExists($pdo, $email)
{
    $stmt = $pdo->prepare("SELECT * FROM newsletter WHERE email = :email");
    $stmt->execute(['email' => $email]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Fonction qui vient insérer l'email dans la base de données
function insertEmail($pdo, $email)
{
    $stmt = $pdo->prepare("INSERT INTO newsletter (email) VALUES (:email)");
    return $stmt->execute(['email' => $email]);
}

if (isset($_POST["t"])) {
    $email = $_POST["t"];
    $pdo = getConnection();

    if (checkEmailExists($pdo, $email) === false) {
        if (insertEmail($pdo, $email)) {
            $msg = "Successfully Added your address to the mail list!";
        } else {
            $msg = "An error occurred while adding your address to the mail list.";
        }
    } else {
        $msg = "Error, your address is already registered.";
    }
}
?>
<html>

<head>
    <title>AlexCloud - NewsLetter !</title>
    <link rel="stylesheet" href="css/style.css" />
    <script src="js/particles.min.js"></script>
    <script>
        particlesJS.load('particles-js', 'assets/particles.json', function() {});
    </script>

<body>
    </head>

    <body>
        <div id="particles-js"></div>
        <div id="h">
            <div id="b">Beta AlexNews !</div>
            <div id="d">Le AlexCloud est une application qui a pour vocation de détroner la suite Google ainsi que Office 365 ! (On en est pas loin)<br><br>Abonnez vous à notre newsletter pour recevoir plus d'informations à notre sujet !</div>
            <form id="e" action="" method="POST">
                <?php if (isset($msg)) printf('<p id="r">%s</p>', $msg); ?>
                <input name="t" type="mail" placeholder="Adresse Mail" required />
                <input type="submit" value="Recevoir la Newsletter !" />
            </form>
        </div>
    </body>

</html>