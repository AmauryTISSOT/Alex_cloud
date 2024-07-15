<?php
	if(isset($_POST["redirect"]))
	{
		header("Location : ".$_POST["redirect"]);
		die();
	}

	require("lib_login.php");

	if(! ($__connected = connect_infos()))
	{
		if(! isset($LOGIN_PAGE))
		{
			header("Location: /login.php");
			die();
		}
	}
?>
