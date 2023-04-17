<?php
/**
 * @file
 * @brief View-HTML-PicturePickerPage module
 * @defgroup View-HTML-PicturePickerPage
 * @{
 */
namespace SBGallery\View\HTML;
use SBGallery\Model\Gallery;

function displayPicturesForPicturePickerPage(Gallery $gallery, string $galleryLabel): void
{
	?>
	<p><a href="?">&laquo; <?= $galleryLabel ?></a></p>
	<div class="album">
		<?php
		$album = $gallery->queryAlbum($_REQUEST["ALBUM_ID"]);

		foreach($album->pictureThumbnailIterator() as $pictureId => $pictureThumbnail)
		{
			?>
			<div class="picturethumbnail">
				<?php
				if($pictureThumbnail->fileType !== null)
				{
					$thumbnailURL = $album->settings->baseURL."/".rawurlencode($_REQUEST["ALBUM_ID"])."/thumbnails/".rawurlencode($pictureId).".".$pictureThumbnail->fileType;
					$pictureURL = $album->settings->baseURL."/".rawurlencode($_REQUEST["ALBUM_ID"])."/pictures/".rawurlencode($pictureId).".".$pictureThumbnail->fileType;
					?>
					<a href="#" onclick="sbgallery.addImageFromGallery('editor1', '<?= $pictureURL ?>', '<?= $pictureThumbnail->title ?>'); return false;"><img src="<?= $thumbnailURL ?>" alt="<?= $pictureThumbnail->title ?>"></a>
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

function displayAlbumsForPicturePickerPage(Gallery $gallery): void
{
	?>
	<div class="gallery">
		<?php
		foreach($gallery->albumThumbnailIterator(false) as $albumId => $albumThumbnail)
		{
			?>
			<div class="galleryitem">
				<a href="?ALBUM_ID=<?= rawurlencode($albumId) ?>">
					<?php
					if($albumThumbnail->fileType === null)
						$imageURL = $gallery->settings->iconsPath."/thumbnail.png";
					else
						$imageURL = $gallery->settings->baseURL."/".rawurlencode($albumId)."/thumbnails/".rawurlencode($albumThumbnail->pictureId).".".$albumThumbnail->fileType;
					?>
					<img src="<?= $imageURL ?>" alt="<?= $albumThumbnail->title ?>"><br>
					<?= $albumThumbnail->title ?>
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
				displayPicturesForPicturePickerPage($gallery, $galleryLabel);
			else
				displayAlbumsForPicturePickerPage($gallery);
			?>
		</body>
	</html>
	<?php
}

/**
 * @}
 */
?>
