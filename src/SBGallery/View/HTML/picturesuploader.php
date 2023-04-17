<?php
/**
 * @file
 * @brief View-HTML-PicturesUploader module
 * @defgroup View-HTML-PicturesUploader
 * @{
 */
namespace SBGallery\View\HTML;
use SBGallery\Model\PicturesUploader;

/**
 * Displays a form making it possible for the user to upload multiple pictures
 * that get added to an album.
 *
 * @param $uploader Uploader object that contains the configuration of the uploader
 */
function displayPicturesUploader(PicturesUploader $uploader): void
{
	?>
	<form action="<?= $uploader->settings->urlGenerator->generatePicturesUploaderFormURL($uploader->albumId) ?>" method="post" enctype="multipart/form-data">
		<input name="<?= $uploader->settings->operationParam ?>" type="hidden" value="insert_multiple_pictures">
		<input name="ALBUM_ID" type="hidden" value="<?= $uploader->albumId ?>">
		<input name="Image[]" type="file" multiple><br>
		<input type="submit" value="<?= $uploader->settings->albumLabels->sendFiles ?>">
	</form>
	<?php
}

/**
 * @}
 */
?>
