<?php
/**
 * @file
 * @brief View-HTML-PicturePickerPage module
 * @defgroup View-HTML-PicturePickerPage
 * @{
 */
namespace SBGallery\View\HTML;
use SBGallery\Model\Gallery;

/**
 * Displays a page that allows a user to pick an image from the gallery.
 *
 * @param $gallery Gallery to display
 * @param $galleryLabel Title of the gallery
 * @param $styles Style sheets to use in the gallery picker package
 * @param $htmlEditorJs JavaScript include that provides HTML editor functionality
 * @param $galleryJs JavaScript include that provides gallery functionality
 */
function displayPicturePickerPage(Gallery $gallery, string $galleryLabel = "Gallery", array $styles = null, string $htmlEditorJs = "scripts/htmleditor.js", string $galleryJs = "scripts/gallery.js"): void
{
	?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

	<html>
		<head>
			<title>Picture picker</title>
			<?php
			if($styles !== null)
			{
				foreach($styles as $style)
				{
					?>
					<link rel="stylesheet" type="text/css" href="<?php print($style); ?>">
					<?php
				}
			}
			?>
			<meta name="robots" content="noindex, nofollow">
			<script type="text/javascript" src="<?php print($htmlEditorJs); ?>"></script>
			<script type="text/javascript" src="<?php print($galleryJs); ?>"></script>
		</head>

		<body>
			<?php
			if(array_key_exists("ALBUM_ID", $_REQUEST))
			{
				?>
				<p><a href="<?php print($_SERVER["PHP_SELF"]); ?>">&laquo; <?php print($galleryLabel); ?></a></p>
				<?php
				$album = $gallery->constructAlbum();
				$album->entity = array();
				$album->entity["ALBUM_ID"] = $_REQUEST["ALBUM_ID"];

				$stmt = $album->queryPictures();

				while(($row = $stmt->fetch()) !== false)
				{
					?>
					<div class="albumitem">
						<?php
						if($row["FileType"] !== null)
						{
							$imageURL = $album->baseURL."/".$album->entity["ALBUM_ID"]."/thumbnails/".$row["PICTURE_ID"].".".$row["FileType"];
							$pictureURL = $album->baseURL."/".$album->entity["ALBUM_ID"]."/pictures/".$row["PICTURE_ID"].".".$row["FileType"];
							?>
							<a href="#" onclick="sbgallery.addImageFromGallery('editor1', '<?php print($pictureURL); ?>', '<?php print($row["Title"]); ?>'); return false;"><img src="<?php print($imageURL); ?>" alt="<?php print($row["Title"]); ?>"></a>
							<?php
						}
						?>
					</div>
					<?php
				}
			}
			else
			{
				$stmt = $gallery->queryAlbums(false);

				while(($row = $stmt->fetch()) !== false)
				{
					?>
					<div class="galleryitem">
						<a href="<?php print($_SERVER["PHP_SELF"]); ?>?ALBUM_ID=<?php print($row["ALBUM_ID"]); ?>">
						<?php
						if($row["FileType"] === null)
							$imageURL = $gallery->iconsPath."/thumbnail.png";
						else
							$imageURL = $gallery->baseURL."/".$row["ALBUM_ID"]."/thumbnails/".$row["PICTURE_ID"].".".$row["FileType"];
						?>
						<img src="<?php print($imageURL); ?>" alt="<?php print($row["Title"]); ?>"><br>
						<?php print($row["Title"]); ?>
						</a>
					</div>
					<?php
				}
			}
			?>
		</body>
	</html>
	<?php
}

/**
 * @}
 */
?>
