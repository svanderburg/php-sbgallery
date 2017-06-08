<?php
error_reporting(E_STRICT | E_ALL);
set_include_path("./lib/sbeditor:./lib/sbgallery");

require_once("gallery/view/html/htmleditorwithgallery.inc.php");
?>
<!DOCTYPE html>

<html>
	<head>
		<title>HTML editor with gallery integration test</title>
		<link rel="stylesheet" type="text/css" href="style.css">
		<script type="text/javascript" src="lib/sbeditor/editor/scripts/htmleditor.js"></script>
	</head>

	<body>
		<?php
		if(count($_REQUEST) > 0)
		{
			if(array_key_exists("contents", $_REQUEST))
			{
				$contents = $_REQUEST["contents"];
				?>
				<p>You have submitted:</p>
				<div><?php print($_REQUEST["contents"]); ?></div>
				<?php
			}
			else
				$contents = NULL;
		}
		else
			$contents = NULL;
		?>
		<form action="<?php print(htmlspecialchars($_SERVER["PHP_SELF"])); ?>" method="post">
			<?php
			displayHTMLEditorWithGallery("editor1", "contents", "picturepicker.php", "iframepage.html", "lib/sbeditor/editor/image", $contents);
			?>
			<br>
			<input type="submit" value="Submit">
		</form>

		<script type="text/javascript">
		sbeditor.initEditors();
		</script>
	</body>
</html>
