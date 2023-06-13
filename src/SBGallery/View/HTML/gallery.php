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
		<a href="<?= $settings->urlGenerator->generateAlbumURL($albumThumbnail->albumId, "&amp;") ?>">
			<img src="<?= generateAlbumThumbnailImageURL($albumThumbnail, $settings) ?>" alt="<?= $albumThumbnail->title ?>"><br>
			<?= $albumThumbnail->title ?>
		</a>
	</div>
	<?php
}

function displayGalleryPagesNavigationButtons(Gallery $gallery, int $page, int $numOfAlbums): void
{
	$numOfPages = ceil($numOfAlbums / $gallery->settings->galleryPageSize);

	if($numOfPages > 1)
	{
		?>
		<div class="gallerynavigation">
			<?php
			if($page > 0)
			{
				?>
				<a href="<?= $gallery->settings->urlGenerator->generateGalleryPageURL($page - 1, "&amp;") ?>"><?= $gallery->settings->galleryLabels->previous ?></a>
				<a href="<?= $gallery->settings->urlGenerator->generateGalleryPageURL(0, "&amp;") ?>">0</a>
				<?php
			}
			?>
			<a class="active_page" href="<?= $gallery->settings->urlGenerator->generateGalleryPageURL($page, "&amp;") ?>"><strong><?= $page ?></strong></a>
			<?php
			$lastPage = $numOfPages - 1;

			if($page < $lastPage)
			{
				?>
				<a href="<?= $gallery->settings->urlGenerator->generateGalleryPageURL($lastPage, "&amp;") ?>"><?= $lastPage ?></a>
				<a href="<?= $gallery->settings->urlGenerator->generateGalleryPageURL($page + 1, "&amp;") ?>"><?= $gallery->settings->galleryLabels->next ?></a>
				<?php
			}
			?>
		</div>
		<?php
	}
}

function displayVisibleGalleryPagesNavigation(Gallery $gallery, int $page): void
{
	if($gallery->settings->galleryPageSize !== null)
	{
		$numOfAlbums = $gallery->queryNumOfVisibleAlbums();
		displayGalleryPagesNavigationButtons($gallery, $page, $numOfAlbums);
	}
}

/**
 * Displays a non-editable gallery.
 *
 * @param $gallery Gallery to display
 * @param $page Page to display (defaults to 0)
 */
function displayGallery(Gallery $gallery, int $page = 0): void
{
	displayVisibleGalleryPagesNavigation($gallery, $page);
	?>
	<div class="gallery">
		<?php
		foreach($gallery->albumThumbnailIterator(true, $page) as $albumId => $albumThumbnail)
			displayAlbumThumbnail($albumThumbnail, $gallery->settings);
		?>
	</div>
	<?php
}

function displayAddAlbumItem(GallerySettings $settings): void
{
	?>
	<div class="albumthumbnail">
		<a href="<?= $settings->urlGenerator->generateAddAlbumURL("&amp;") ?>">
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
		<a href="<?= $gallery->settings->urlGenerator->generateAlbumURL($albumThumbnail->albumId, "&amp;") ?>">
			<img src="<?= generateAlbumThumbnailImageURL($albumThumbnail, $gallery->settings) ?>" alt="<?= $albumThumbnail->title ?>"><br>
			<?= $albumThumbnail->title ?>
		</a>
		<br>
		<a href="<?= $gallery->settings->urlGenerator->generateMoveAlbumLeftURL($count, $albumThumbnail->albumId, "&amp;") ?>"><img src="<?= $gallery->settings->iconsPath ?>/moveleft.png" alt="<?= $gallery->settings->galleryLabels->moveLeft ?>"></a>
		<a href="<?= $gallery->settings->urlGenerator->generateMoveAlbumRightURL($count, $albumThumbnail->albumId, "&amp;") ?>"><img src="<?= $gallery->settings->iconsPath ?>/moveright.png" alt="<?= $gallery->settings->galleryLabels->moveRight ?>"></a>
		<?php
		if($gallery->albumIsEmpty($albumThumbnail->albumId))
		{
			?>
			<a href="<?= $gallery->settings->urlGenerator->generateRemoveAlbumURL($count, $albumThumbnail->albumId, "&amp;") ?>"><img src="<?= $gallery->settings->iconsPath ?>/delete.png" alt="<?= $gallery->settings->galleryLabels->remove ?>"></a>
			<?php
		}
		?>
	</div>
	<?php
}


function displayGalleryPagesNavigation(Gallery $gallery, int $page): void
{
	if($gallery->settings->galleryPageSize !== null)
	{
		$numOfAlbums = $gallery->queryNumOfAlbums();
		displayGalleryPagesNavigationButtons($gallery, $page, $numOfAlbums);
	}
}

/**
 * Displays an editable gallery.
 *
 * @param $gallery Gallery to display
 * @param $page Page to display (defaults to 0)
 */
function displayEditableGallery(Gallery $gallery, int $page = 0): void
{
	displayGalleryPagesNavigation($gallery, $page);

	$count = 0;
	?>
	<div class="gallery">
		<?php
		displayAddAlbumItem($gallery->settings);

		foreach($gallery->albumThumbnailIterator(false, $page) as $albumId => $albumThumbnail)
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
