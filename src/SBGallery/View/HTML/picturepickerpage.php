<?php
/**
 * @file
 * @brief View-HTML-PicturePickerPage module
 * @defgroup View-HTML-PicturePickerPage
 * @{
 */
namespace SBGallery\View\HTML;
use SBGallery\Model\Gallery;

function displayAlbumsForPicturePickerPage(Gallery $gallery, string $galleryLabel): void
{
	?>
	<p><a href="?">&laquo; <?= $galleryLabel ?></a></p>
	<div class="album">
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
					$thumbnailURL = $album->baseURL."/".rawurlencode($album->entity["ALBUM_ID"])."/thumbnails/".rawurlencode($row["PICTURE_ID"]).".".$row["FileType"];
					$pictureURL = $album->baseURL."/".rawurlencode($album->entity["ALBUM_ID"])."/pictures/".rawurlencode($row["PICTURE_ID"]).".".$row["FileType"];
					?>
					<a href="#" onclick="sbgallery.addImageFromGallery('editor1', '<?= $pictureURL ?>', '<?= $row["Title"] ?>'); return false;"><img src="<?= $thumbnailURL ?>" alt="<?= $row["Title"] ?>"></a>
					<?php
				}
				?>
			</div>
			<?php
		}
		?>
	</div>
	<?php
}

function displayPicturesForPicturePickerPage(Gallery $gallery): void
{
	?>
	<div class="gallery">
		<?php
		$stmt = $gallery->queryAlbums(false);

		while(($row = $stmt->fetch()) !== false)
		{
			?>
			<div class="galleryitem">
				<a href="?ALBUM_ID=<?= $row["ALBUM_ID"] ?>">
					<?php
					if($row["FileType"] === null)
						$imageURL = $gallery->iconsPath."/thumbnail.png";
					else
						$imageURL = $gallery->baseURL."/".rawurlencode($row["ALBUM_ID"])."/thumbnails/".rawurlencode($row["PICTURE_ID"]).".".$row["FileType"];
					?>
					<img src="<?= $imageURL ?>" alt="<?= $row["Title"] ?>"><br>
					<?= $row["Title"] ?>
				</a>
			</div>
			<?php
		}
		?>
	</div>
	<?php
}

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
					<link rel="stylesheet" type="text/css" href="<?= $style ?>">
					<?php
				}
			}
			?>
			<meta name="robots" content="noindex, nofollow">
			<script type="text/javascript" src="<?= $htmlEditorJs ?>"></script>
			<script type="text/javascript" src="<?= $galleryJs ?>"></script>
		</head>

		<body>
			<?php
			if(array_key_exists("ALBUM_ID", $_REQUEST))
				displayAlbumsForPicturePickerPage($gallery, $galleryLabel);
			else
				displayPicturesForPicturePickerPage($gallery);
			?>
		</body>
	</html>
	<?php
}

/**
 * @}
 */
?>
