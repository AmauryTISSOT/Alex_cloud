<?php
require("../forced.php");

if (!($user_data = search_user("####USERNAME####"))) {
    printf("<h1>ERROR</h1>");
    die();
}
?>
<html>

<head>
    <title>Profile page</title>
</head>

<body>
    <div id="profile">
        <a href="/"><img src="/alexcloud.png" /></a>
        <h1>Bienvenue sur la page de profile de <?php echo htmlspecialchars($user_data["USERNAME"]); ?></h1>
        <h3><?php if ($user_data["admin"] == 1) printf("L'utilisateur est administrateur");
            else printf("L'utilisateur n'est pas administrateur"); ?></h3>
        <br><br>
        <div id="joli">__________</div>
        <br><br>
        <h2>Description de l'utilisateur :</h2>
        <div id="desc">
            <?php echo htmlspecialchars($user_data["DESCRIPTION"]); ?>
        </div>
        <br><br>
        <h2>Envoyer un message à l'utilisateur :</h2>
        <textarea>Message to send</textarea>
        <br>
        <button>Envoyer</button>
    </div>
</body>
<style>
    html {
        background: rgb(2, 0, 36);
        background: linear-gradient(90deg, rgba(2, 0, 36, 1) 0%, rgba(9, 9, 121, 1) 35%, rgba(0, 212, 255, 1) 100%);
        text-align: center;
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

    #profile {
        width: 75%;
        background: rgba(200, 200, 200, 0.4);
        padding: 2.5%;
        margin-left: 10%;
    }

    #desc {
        border: 1px solid white;
        box-shadow: 0px 0px 2px white;
        padding: 2% 0;
        width: 70%;
        margin-left: 15%;
        font-size: 120%;
        background: rgba(255, 255, 255, 0.4);
        color: #444;
    }

    #joli {
        width: 100%;
        background: #449;
    }

    textarea {
        width: 70%;
        padding: 2%;
        font-size: 120%;
        background: rgba(255, 255, 255, 0.4);
        color: #444;
        border: 1px solid white;
        box-shadow: 0px 0px 2px white;
    }

    button {
        padding: 1% 2%;
        margin-top: 2%;
    }
</style>

</html>