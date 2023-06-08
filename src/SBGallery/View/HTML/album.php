<?php
/**
 * @file
 * @brief View-HTML-Album module
 * @defgroup View-HTML-Album
 * @{
 */
namespace SBGallery\View\HTML;
use SBGallery\Model\Album;
use SBGallery\Model\PictureThumbnail;
use SBGallery\Model\Settings\AlbumSettings;

function generatePictureThumbnailImageURL(PictureThumbnail $pictureThumbnail, AlbumSettings $settings): string
{
	if($pictureThumbnail->fileType === null)
		return $settings->iconsPath."/thumbnail.png";
	else
		return $settings->baseURL."/".rawurlencode($pictureThumbnail->albumId)."/thumbnails/".rawurlencode($pictureThumbnail->pictureId).".".$pictureThumbnail->fileType;
}

function displayPictureThumbnail(PictureThumbnail $pictureThumbnail, AlbumSettings $settings): void
{
	?>
	<div class="picturethumbnail">
		<a href="<?= $settings->urlGenerator->generatePictureURL($pictureThumbnail->albumId, $pictureThumbnail->pictureId) ?>">
			<img src="<?= generatePictureThumbnailImageURL($pictureThumbnail, $settings) ?>" alt="<?= $pictureThumbnail->title ?>">
		</a>
	</div>
	<?php
}

function displayPictureThumbnails(Album $album): void
{
	if($album->albumId !== null)
	{
		?>
		<div class="album">
			<?php
			foreach($album->pictureThumbnailIterator() as $pictureId => $pictureThumbnail)
				displayPictureThumbnail($pictureThumbnail, $album->settings);
			?>
		</div>
		<?php
	}
}

/**
 * Displays a non-editable album.
 *
 * @param $album Album to display
 */
function displayAlbum(Album $album): void
{
	\SBData\View\HTML\displayField($album->fields["Description"]);
	displayPictureThumbnails($album);
}

function displayAddPictureItem(Album $album): void
{
	?>
	<div class="picturethumbnail">
		<a href="<?= $album->settings->urlGenerator->generateAddPictureURL($album->albumId) ?>">
			<img src="<?= $album->settings->iconsPath ?>/add.png" alt="<?= $album->settings->albumLabels->addPicture ?>"><br>
			<?= $album->settings->albumLabels->addPicture ?>
		</a>
	</div>
	<?php
}

function displayAddMultiplePicturesItem(Album $album): void
{
	?>
	<div class="picturethumbnail">
		<a href="<?= $album->settings->urlGenerator->generateAddMultiplePicturesURL($album->albumId) ?>">
			<img src="<?= $album->settings->iconsPath ?>/add-multiple.png" alt="<?= $album->settings->albumLabels->addMultiplePictures ?>"><br>
			<?= $album->settings->albumLabels->addMultiplePictures ?>
		</a>
	</div>
	<?php
}

function displayEditablePictureThumbnail(PictureThumbnail $pictureThumbnail, AlbumSettings $settings, int $count): void
{
	?>
	<div class="picturethumbnail">
		<?php
		if($settings->displayAnchors)
		{
			?>
			<a name="<?= $settings->anchorPrefix."-".$count ?>"></a>
			<?php
		}
		?>
		<a href="<?= $settings->urlGenerator->generatePictureURL($pictureThumbnail->albumId, $pictureThumbnail->pictureId) ?>">
			<img src="<?= generatePictureThumbnailImageURL($pictureThumbnail, $settings) ?>" alt="<?= $pictureThumbnail->title ?>">
		</a>
		<br>
		<a href="<?= $settings->urlGenerator->generateMovePictureLeftURL($count, $pictureThumbnail->albumId, $pictureThumbnail->pictureId) ?>"><img src="<?= $settings->iconsPath ?>/moveleft.png" alt="<?= $settings->albumLabels->moveLeft ?>"></a>
		<a href="<?= $settings->urlGenerator->generateMovePictureRightURL($count, $pictureThumbnail->albumId, $pictureThumbnail->pictureId) ?>"><img src="<?= $settings->iconsPath ?>/moveright.png" alt="<?= $settings->albumLabels->moveRight ?>"></a>
		<?php
		if($pictureThumbnail->fileType !== null)
		{
			?>
			<a href="<?= $settings->urlGenerator->generateSetAsThumbnailURL($count, $pictureThumbnail->albumId, $pictureThumbnail->pictureId) ?>"><img src="<?= $settings->iconsPath ?>/setasthumbnail.png" alt="<?= $settings->albumLabels->setAsThumbnail ?>"></a>
			<?php
		}
		?>
		<a href="<?= $settings->urlGenerator->generateRemovePictureURL($count, $pictureThumbnail->albumId, $pictureThumbnail->pictureId) ?>"><img src="<?= $settings->iconsPath ?>/delete.png" alt="<?= $settings->albumLabels->remove ?>"></a>
	</div>
	<?php
}

function displayEditablePictureThumbnails(Album $album): void
{
	if($album->albumId !== null)
	{
		$count = 0;
		?>
		<div class="album">
			<?php
			displayAddPictureItem($album);
			displayAddMultiplePicturesItem($album);

			foreach($album->pictureThumbnailIterator() as $pictureId => $pictureThumbnail)
			{
				displayEditablePictureThumbnail($pictureThumbnail, $album->settings, $count);
				$count++;
			}
			?>
		</div>
		<?php
	}
}

/**
 * Displays an editable album.
 *
 * @param $album Album to display
 */
function displayEditableAlbum(Album $album): void
{
	\SBData\View\HTML\displayEditableForm($album);
	displayEditablePictureThumbnails($album);
}

/**
 * @}
 */
?>
