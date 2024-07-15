<?php
	require("forced.php");
	
	if(isset($_REQUEST["file"]))
	{
		$filepath = "/var/www/html/files/".$__connected["USERNAME"]."/".$_REQUEST["file"];

		if(file_exists($filepath))
		{
			unlink($filepath);
			unlink($filepath.".alexdescfile");
		}
	}

	header("Location: /");
?>
