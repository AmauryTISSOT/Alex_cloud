<?php
	function req_db($req)
	{
		$db = new SQLite3("./truc.db");

		$results = $db->query($req);
		return $results->fetchArray();
	}

	if(isset($_POST["t"]))
	{
		$ret = req_db("SELECT * FROM mails WHERE mail = '".$_POST["t"]."';");
		if($ret == false)
		{
			$ret = req_db("INSERT INTO mails(mail) VALUES('".$_POST["t"]."');");
			$msg = "Successfully Added your address to mail list !";
		}
		else
		{
			$msg = "Error your address is already registered";
		}

	}
?>
<html>
	<head>
		<title>AlexCloud - NewsLetter !</title>
		<link rel="stylesheet" href="css/style.css"/>
		<script src="js/particles.min.js"></script>
		<script>particlesJS.load('particles-js', 'assets/particles.json', function() { });</script>
<body>
	</head>
	<body>
		<div id="particles-js"></div>
		<div id="h">
			<div id="b">Beta AlexNews !</div>
			<div id="d">Le AlexCloud est une application qui a pour vocation de détroner la suite Google ainsi que Office 365 ! (On en est pas loin)<br><br>Abonnez vous à notre newsletter pour recevoir plus d'informations à notre sujet !</div>
			<form id="e" action="" method="POST">
				<?php if(isset($msg)) printf('<p id="r">%s</p>', $msg); ?>
				<input name="t" type="mail" placeholder="Adresse Mail" required/>
				<input type="submit" value="Recevoir la Newsletter !"/>
			</form>
		</div>
	</body>
</html>
