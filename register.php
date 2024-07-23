<?php
$LOGIN_PAGE = 1;
$LOGIN_FAILED = 0;

require("forced.php");
session_start();

if (isset($_REQUEST["username"]) && isset($_REQUEST["password"]) && isset($_REQUEST["password2"]) && isset($_REQUEST["description"]) && isset($_REQUEST["capcha"])) {
    $LOGIN_FAILED = 1;

    if (!search_user($_REQUEST["username"])) {
        if ($_REQUEST["password"] == $_REQUEST["password2"]) {
            if ($_SESSION["capcha"] == $_REQUEST["capcha"]) {
                $LOGIN_FAILED = 0;
                create_user($_REQUEST["username"], $_REQUEST["password"], $_REQUEST["description"]);
                header("Location: /");
            }
        }
    }
}
?>
<html>

<head>
    <title>Register Page</title>
</head>

<body>
    <form action="#" method="POST">
        <img src="alexcloud.png" />
        <?php if ($LOGIN_FAILED == 1) printf('<div>Register Failed, check your entries !</div>'); ?>
        <input type="text" placeholder="Username" name="username" />
        <input type="password" placeholder="Password" name="password" />
        <input type="password" placeholder="Re-enter password" name="password2" />
        <input type="text" placeholder="Description du compte" name="description" />
        <img src="capcha.php" style="width: 50%; margin-top: 2%;" />
        <input type="text" placeholder="Capcha" name="capcha" />
        <input type="submit" value="Register !" />
    </form>
</body>
<style>
    html {
        background: rgb(2, 0, 36);
        background: linear-gradient(90deg, rgba(2, 0, 36, 1) 0%, rgba(9, 9, 121, 1) 35%, rgba(0, 212, 255, 1) 100%);
    }

    html,
    body,
    div {
        margin: 0;
        padding: 0;
    }

    * {
        position: relative;
        transition: all 1s;
        text-decoration: none;
        list-style: none;
    }

    form {
        width: 50%;
        margin-left: 22%;
        margin-top: 5%;
        background: rgba(200, 200, 200, 0.4);
        padding: 3%;
        text-align: center;
    }

    form img {
        width: 85%;
        margin-bottom: 3%;
    }

    form a {
        color: #444;
        text-decoration: underline;
    }

    input {
        width: 75%;
        padding: 2%;
        margin: 1%;
    }

    input[type=submit] {
        width: 25%;
    }

    form div {
        width: 75%;
        margin: 1% 0;
        box-shadow: 0px 0px 2px red;
        padding: 1% 0;
        color: #944;
        transform: translateX(-50%);
        left: 50%;
    }
</style>

</html>