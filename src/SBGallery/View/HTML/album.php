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
		<a href="<?= $settings->urlGenerator->generatePictureURL($pictureThumbnail->albumId, $pictureThumbnail->pictureId, "&amp;") ?>">
			<img src="<?= generatePictureThumbnailImageURL($pictureThumbnail, $settings) ?>" alt="<?= $pictureThumbnail->title ?>">
		</a>
	</div>
	<?php
}

function displayAlbumPagesNavigationButtons(Album $album, int $page, int $numOfPictures): void
{
	$numOfPages = ceil($numOfPictures / $album->settings->pageSize);

	if($numOfPages > 1)
	{
		?>
		<div class="albumnavigation">
			<?php
			if($page > 0)
			{
				?>
				<a href="<?= $album->settings->urlGenerator->generateAlbumPageURL($album->albumId, $page - 1, "&amp;") ?>"><?= $album->settings->albumLabels->previous ?></a>
				<a href="<?= $album->settings->urlGenerator->generateAlbumPageURL($album->albumId, 0, "&amp;") ?>">0</a>
				<?php
			}
			?>
			<a class="active_page" href="<?= $album->settings->urlGenerator->generateAlbumPageURL($album->albumId, $page, "&amp;") ?>"><strong><?= $page ?></strong></a>
			<?php
			$lastPage = $numOfPages - 1;

			if($page < $lastPage)
			{
				?>
				<a href="<?= $album->settings->urlGenerator->generateAlbumPageURL($album->albumId, $lastPage, "&amp;") ?>"><?= $lastPage ?></a>
				<a href="<?= $album->settings->urlGenerator->generateAlbumPageURL($album->albumId, $page + 1, "&amp;") ?>"><?= $album->settings->albumLabels->next ?></a>
				<?php
			}
			?>
		</div>
		<?php
	}
}

function displayVisibleAlbumPagesNavigation(Album $album, int $page): void
{
	if($album->settings->pageSize !== null)
	{
		$numOfPictures = $album->queryNumOfPicturesInAlbum();
		displayAlbumPagesNavigationButtons($album, $page, $numOfPictures);
	}
}

function displayPictureThumbnails(Album $album, int $page): void
{
	if($album->albumId !== null)
	{
		displayVisibleAlbumPagesNavigation($album, $page);
		?>
		<div class="album">
			<?php
			foreach($album->pictureThumbnailIterator($page) as $pictureId => $pictureThumbnail)
				displayPictureThumbnail($pictureThumbnail, $album->settings);
			?>
		</div>
		<?php
		displayVisibleAlbumPagesNavigation($album, $page);
	}
}

/**
 * Displays a non-editable album.
 *
 * @param $album Album to display
 * @param $page Page to display (defaults to 0)
 */
function displayAlbum(Album $album, int $page = 0): void
{
	\SBData\View\HTML\displayField($album->fields["Description"]);
	displayPictureThumbnails($album, $page);
}

function displayAddPictureItem(Album $album): void
{
	?>
	<div class="picturethumbnail">
		<a href="<?= $album->settings->urlGenerator->generateAddPictureURL($album->albumId, "&amp;") ?>">
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
		<a href="<?= $album->settings->urlGenerator->generateAddMultiplePicturesURL($album->albumId, "&amp;") ?>">
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
		<a href="<?= $settings->urlGenerator->generatePictureURL($pictureThumbnail->albumId, $pictureThumbnail->pictureId, "&amp;") ?>">
			<img src="<?= generatePictureThumbnailImageURL($pictureThumbnail, $settings) ?>" alt="<?= $pictureThumbnail->title ?>">
		</a>
		<br>
		<a href="<?= $settings->urlGenerator->generateMovePictureLeftURL($count, $pictureThumbnail->albumId, $pictureThumbnail->pictureId, "&amp;") ?>"><img src="<?= $settings->iconsPath ?>/moveleft.png" alt="<?= $settings->albumLabels->moveLeft ?>"></a>
		<a href="<?= $settings->urlGenerator->generateMovePictureRightURL($count, $pictureThumbnail->albumId, $pictureThumbnail->pictureId, "&amp;") ?>"><img src="<?= $settings->iconsPath ?>/moveright.png" alt="<?= $settings->albumLabels->moveRight ?>"></a>
		<?php
		if($pictureThumbnail->fileType !== null)
		{
			?>
			<a href="<?= $settings->urlGenerator->generateSetAsThumbnailURL($count, $pictureThumbnail->albumId, $pictureThumbnail->pictureId, "&amp;") ?>"><img src="<?= $settings->iconsPath ?>/setasthumbnail.png" alt="<?= $settings->albumLabels->setAsThumbnail ?>"></a>
			<?php
		}
		?>
		<a href="<?= $settings->urlGenerator->generateRemovePictureURL($count, $pictureThumbnail->albumId, $pictureThumbnail->pictureId, "&amp;") ?>"><img src="<?= $settings->iconsPath ?>/delete.png" alt="<?= $settings->albumLabels->remove ?>"></a>
	</div>
	<?php
}

function displayAlbumPagesNavigation(Album $album, int $page): void
{
	if($album->settings->pageSize !== null)
	{
		$numOfPictures = $album->queryNumOfPicturesInAlbum();
		displayAlbumPagesNavigationButtons($album, $page, $numOfPictures);
	}
}

function displayEditablePictureThumbnails(Album $album, int $page): void
{
	if($album->albumId !== null)
	{
		displayAlbumPagesNavigation($album, $page);

		$count = 0;
		?>
		<div class="album">
			<?php
			displayAddPictureItem($album);
			displayAddMultiplePicturesItem($album);

			foreach($album->pictureThumbnailIterator($page) as $pictureId => $pictureThumbnail)
			{
				displayEditablePictureThumbnail($pictureThumbnail, $album->settings, $count);
				$count++;
			}
			?>
		</div>
		<?php
		displayAlbumPagesNavigation($album, $page);
	}
}

/**
 * Displays an editable album.
 *
 * @param $album Album to display
 * @param $page Page to display (defaults to 0)
 */
function displayEditableAlbum(Album $album, int $page = 0): void
{
	\SBData\View\HTML\displayEditableForm($album);
	displayEditablePictureThumbnails($album, $page);
}

/**
 * @}
 */
?>
