<?php
/**
 * @file
 * @brief View-Pages-AlbumMenuSection module
 * @defgroup View-Pages-AlbumMenuSection
 * @{
 */
namespace SBGallery\View\Pages;
use PDO;
use SBGallery\Model\Page\GalleryPage;
use SBGallery\Model\Page\AlbumPage;
use SBGallery\Model\Page\PicturePage;
use SBGallery\Model\Page\GalleryOperationPage;

/**
 * Displays a menu section with links derived from the albums stored in the gallery.
 *
 * @param $galleryPage Gallery page to display
 */
function displayAlbumMenuSection(GalleryPage $galleryPage, PDO $dbh): void
{
	$gallery = $galleryPage->constructGallery($dbh);
	$checker = $galleryPage->constructGalleryPermissionChecker();

	$authenticated = $checker->checkWritePermissions();

	$stmt = $gallery->queryAlbums(!$authenticated);

	while(($row = $stmt->fetch()) !== false)
	{
		if(array_key_exists("query", $GLOBALS) && array_key_exists("albumId", $GLOBALS["query"]) && $row["ALBUM_ID"] === $GLOBALS["query"]["albumId"])
			$active = ' class="active"';
		else
			$active = "";
		?>
		<a href="<?= $gallery->albumDisplayURL."/".rawurlencode($row["ALBUM_ID"]) ?>"<?= $active ?>><?= $row["Title"] ?></a>
		<?php
		if($authenticated)
		{
			?>
			<div class="album-buttons">
				<a href="<?= $gallery->albumDisplayURL."/".rawurlencode($row["ALBUM_ID"]) ?>?__operation=moveleft_album"><img src="<?= $gallery->iconsPath ?>/moveleft.png" alt="<?= $gallery->galleryLabels["Move left"] ?>"></a>
				<a href="<?= $gallery->albumDisplayURL."/".rawurlencode($row["ALBUM_ID"]) ?>?__operation=moveright_album"><img src="<?= $gallery->iconsPath ?>/moveright.png" alt="<?= $gallery->galleryLabels["Move right"] ?>"></a>
				<?php
				$count_stmt = $gallery->queryPictureCount($row["ALBUM_ID"]);
				while(($count_row = $count_stmt->fetch()) !== false)
				{
					if($count_row["count(*)"] == 0)
					{
						?>
						<a href="<?= $gallery->albumDisplayURL."/".rawurlencode($row["ALBUM_ID"]) ?>?__operation=remove_album"><img src="<?= $gallery->iconsPath ?>/delete.png" alt="<?= $gallery->galleryLabels["Remove"] ?>"></a>
						<?php
					}
				}
				?>
			</div>
			<?php
		}
	}

	if($authenticated)
	{
		?>
		<a class="create-album" href="<?= $gallery->albumDisplayURL ?>?__operation=create_album"><?= $gallery->galleryLabels["Add album"] ?></a>
		<?php
	}
}

/**
 * Checks whether the user has visited a gallery sub page
 *
 * @return TRUE if a gallery sub page is visited, else FALSE
 */
function visitedGallerySubPage(): bool
{
	return ($GLOBALS["currentPage"] instanceof GalleryPage || $GLOBALS["currentPage"] instanceof AlbumPage || $GLOBALS["currentPage"] instanceof PicturePage || $GLOBALS["currentPage"] instanceof GalleryOperationPage);
}

/**
 * @}
 */
?>
