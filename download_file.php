<?php
	require("forced.php");
	
	if(isset($_REQUEST["file"]))
	{
		$filepath = "/var/www/html/files/".$__connected["USERNAME"]."/".$_REQUEST["file"];

		if(file_exists($filepath))
		{
			header("Content-Description: File Transfer");
			header("Content-Type: application/octet-stream");
			header("Content-Disposition: attachment; filename=".$_REQUEST["file"]);
			header("Content-Length: ".filesize($filepath));

			flush();
			readfile($filepath);
		}
	}

	header("Location: /");
?>
