<?php
function displayPicturesUploader($albumId)
{
	?>
	<form action="<?php print(htmlspecialchars($_SERVER["PHP_SELF"])); ?>" method="post" enctype="multipart/form-data">
		<input name="__operation" type="hidden" value="insert_multiple_pictures">
		<input name="ALBUM_ID" type="hidden" value="<?php print($albumId); ?>">
		<input name="Image[]" type="file" multiple><br>
		<input type="submit" value="Send files">
	</form>
	<?php
}
?>
