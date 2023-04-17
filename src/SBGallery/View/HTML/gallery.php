<?php
/**
 * @file
 * @brief View-HTML-Gallery module
 * @defgroup View-HTML-Gallery
 * @{
 */
namespace SBGallery\View\HTML;
use SBGallery\Model\Gallery;
use SBGallery\Model\AlbumThumbnail;
use SBGallery\Model\Settings\GallerySettings;

function generateAlbumThumbnailImageURL(AlbumThumbnail $albumThumbnail, GallerySettings $settings): string
{
	if($albumThumbnail->pictureId === null)
		return $settings->iconsPath."/thumbnail.png";
	else
		return $settings->baseURL."/".rawurlencode($albumThumbnail->albumId)."/thumbnails/".rawurlencode($albumThumbnail->pictureId).".".$albumThumbnail->fileType;
}

function displayAlbumThumbnail(AlbumThumbnail $albumThumbnail, GallerySettings $settings): void
{
	?>
	<div class="albumthumbnail">
		<a href="<?= $settings->urlGenerator->generateAlbumURL($albumThumbnail->albumId) ?>">
			<img src="<?= generateAlbumThumbnailImageURL($albumThumbnail, $settings) ?>" alt="<?= $albumThumbnail->title ?>"><br>
			<?= $albumThumbnail->title ?>
		</a>
	</div>
	<?php
}

/**
 * Displays a non-editable gallery.
 *
 * @param $gallery Gallery to display
 */
function displayGallery(Gallery $gallery): void
{
	?>
	<div class="gallery">
		<?php
		foreach($gallery->albumThumbnailIterator(true) as $albumId => $albumThumbnail)
			displayAlbumThumbnail($albumThumbnail, $gallery->settings);
		?>
	</div>
	<?php
}

function displayAddAlbumItem(GallerySettings $settings): void
{
	?>
	<div class="albumthumbnail">
		<a href="<?= $settings->urlGenerator->generateAddAlbumURL() ?>">
			<img src="<?= $settings->iconsPath ?>/add.png" alt="<?= $settings->galleryLabels->addAlbum ?>"><br>
			<?= $settings->galleryLabels->addAlbum ?>
		</a>
	</div>
	<?php
}

function displayEditableAlbumThumbnail(Gallery $gallery, AlbumThumbnail $albumThumbnail, int $count): void
{
	?>
	<div class="albumthumbnail">
		<?php
		if($gallery->settings->displayAnchors)
		{
			?>
			<a name="<?= $gallery->settings->albumAnchorPrefix."-".$count ?>"></a>
			<?php
		}
		?>
		<a href="<?= $gallery->settings->urlGenerator->generateAlbumURL($albumThumbnail->albumId) ?>">
			<img src="<?= generateAlbumThumbnailImageURL($albumThumbnail, $gallery->settings) ?>" alt="<?= $albumThumbnail->title ?>"><br>
			<?= $albumThumbnail->title ?>
		</a>
		<br>
		<a href="<?= $gallery->settings->urlGenerator->generateMoveAlbumLeftURL($count, $albumThumbnail->albumId) ?>"><img src="<?= $gallery->settings->iconsPath ?>/moveleft.png" alt="<?= $gallery->settings->galleryLabels->moveLeft ?>"></a>
		<a href="<?= $gallery->settings->urlGenerator->generateMoveAlbumRightURL($count, $albumThumbnail->albumId) ?>"><img src="<?= $gallery->settings->iconsPath ?>/moveright.png" alt="<?= $gallery->settings->galleryLabels->moveRight ?>"></a>
		<?php
		if($gallery->albumIsEmpty($albumThumbnail->albumId))
		{
			?>
			<a href="<?= $gallery->settings->urlGenerator->generateRemoveAlbumURL($count, $albumThumbnail->albumId) ?>"><img src="<?= $gallery->settings->iconsPath ?>/delete.png" alt="<?= $gallery->settings->galleryLabels->remove ?>"></a>
			<?php
		}
		?>
	</div>
	<?php
}

/**
 * Displays an editable gallery.
 *
 * @param $gallery Gallery to display
 */
function displayEditableGallery(Gallery $gallery): void
{
	$count = 0;
	?>
	<div class="gallery">
		<?php
		displayAddAlbumItem($gallery->settings);

		foreach($gallery->albumThumbnailIterator(false) as $albumId => $albumThumbnail)
		{
			displayEditableAlbumThumbnail($gallery, $albumThumbnail, $count);
			$count++;
		}
		?>
	</div>
	<?php
}

/**
 * @}
 */
?>
