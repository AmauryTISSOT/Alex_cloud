<?php
$LOGIN_PAGE = 1;
$LOGIN_FAILED = 0;

require("forced.php");

function getDatabaseConnection() {
    $host = 'your_host';
    $dbname = 'your_dbname';
    $username = 'your_dbusername';
    $password = 'your_dbpassword';
    
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("Could not connect to the database: " . $e->getMessage());
    }
}

function login_user($username, $password) {
    $pdo = getDatabaseConnection();
    
    // Requête préparée
    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = :username AND password = :password');
    $stmt->execute([
        ':username' => $username,
        ':password' => $password  
    ]);
    
    return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
}

if(isset($_REQUEST["username"]) && isset($_REQUEST["password"])) {
    if(login_user($_REQUEST["username"], $_REQUEST["password"])) {
        header("Location: /");
    } else {
        $LOGIN_FAILED = 1;
    }
}
?>
<html>
    <head>
        <title>Login Page</title>
    </head>
    <body>
        <form action="#" method="POST">
            <img src="alexcloud.png"/>
            <?php if($LOGIN_FAILED == 1) echo '<div>Login Failed !</div>'; ?>
            <input type="text" placeholder="Username" name="username" required/>
            <input type="password" placeholder="Password" name="password" required/>
            <input type="submit" value="Connect !"/>
            <br>
            <a href="register.php"><u>Create a free account</u></a>
        </form>
    </body>
    <style>
        html { background: rgb(2,0,36); background: linear-gradient(90deg, rgba(2,0,36,1) 0%, rgba(9,9,121,1) 35%, rgba(0,212,255,1) 100%); }
        html, body, div { margin: 0; padding: 0; }
        * { position: relative; transition: all 1s; text-decoration: none; list-style: none; }
        form { width: 50%; margin-left: 22%; margin-top: 5%; background: rgba(200,200,200,0.4); padding: 3%; text-align: center; }

        form img { width: 85%; margin-bottom: 3%; }
        form a { color: #444; text-decoration: underline; }

        input { width: 75%; padding: 2%; margin: 1%; }
        input[type=submit] { width: 25%; }

        form div { width: 75%; margin: 1% 0; box-shadow: 0px 0px 2px red; padding: 1% 0; color: #944; transform: translateX(-50%); left: 50%; }
    </style>
</html>
