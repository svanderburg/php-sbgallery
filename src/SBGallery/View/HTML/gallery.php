<?php
/**
 * @file
 * @brief View-HTML-Gallery module
 * @defgroup View-HTML-Gallery
 * @{
 */
namespace SBGallery\View\HTML;
use SBCrud\Model\RouteUtils;
use SBGallery\Model\Gallery;

function displayGalleryThumbnail(Gallery $gallery, array $thumbnail, int $count, string $viewType): void
{
	$displayAlbumLinkFunction = '\SBGallery\View\HTML\gallery_display'.$viewType.'AlbumLink';

	?>
	<a href="<?= $displayAlbumLinkFunction($gallery, $thumbnail["ALBUM_ID"], $count) ?>">
	<?php
	if($thumbnail["FileType"] === null)
		$imageURL = $gallery->iconsPath."/thumbnail.png";
	else
		$imageURL = $gallery->baseURL."/".rawurlencode($thumbnail["ALBUM_ID"])."/thumbnails/".rawurlencode($thumbnail["PICTURE_ID"]).".".$thumbnail["FileType"];
	?>
	<img src="<?= $imageURL ?>" alt="<?= $thumbnail["Title"] ?>"><br>
	<?= $thumbnail["Title"] ?>
	</a>
	<?php
}

function gallery_displayConventionalAlbumLink(Gallery $gallery, string $albumId, int $count, string $operation = null): string
{
	$params = array(
		"ALBUM_ID" => $albumId
	);

	if($operation !== null)
	{
		$params["__operation"] = $operation;

		if($gallery->displayAnchors)
			$params["__id"] = $count;
	}

	return htmlspecialchars($gallery->albumDisplayURL)."?".http_build_query($params, "", "&amp;", PHP_QUERY_RFC3986);
}

function gallery_displayLayoutAlbumLink(Gallery $gallery, string $albumId, int $count, string $operation = null): string
{
	$params = array();

	if($operation !== null)
	{
		$params["__operation"] = $operation;

		if($gallery->displayAnchors)
			$params["__id"] = $count;
	}

	if(count($params) > 0)
		$extraParams = "?".http_build_query($params, "", "&amp;", PHP_QUERY_RFC3986);
	else
		$extraParams = "";

	return RouteUtils::composeSelfURL()."/".rawurlencode($albumId).$extraParams;
}

function displayGalleryItem(Gallery $gallery, array $albumObject, string $viewType): void
{
	?>
	<div class="galleryitem"><?php displayGalleryThumbnail($gallery, $albumObject, 0, $viewType); ?></div>
	<?php
}

/**
 * Displays a non-editable gallery.
 *
 * @param $gallery Gallery to display
 * @param $viewType Name of the class of functions that display the gallery
 */
function displayGallery(Gallery $gallery, string $viewType = "Conventional"): void
{
	?>
	<div class="gallery">
		<?php
		$stmt = $gallery->queryAlbums(true);
		while(($albumObject = $stmt->fetch()) !== false)
			displayGalleryItem($gallery, $albumObject, $viewType);
		?>
	</div>
	<?php
}

function gallery_displayConventionalAddAlbumLink(Gallery $gallery): string
{
	return $gallery->albumDisplayURL;
}

function gallery_displayLayoutAddAlbumLink(Gallery $gallery): string
{
	return "?__operation=create_album";
}

function displayAddAlbumButton(Gallery $gallery, string $viewType): void
{
	$displayAddAlbumLinkFunction = '\SBGallery\View\HTML\gallery_display'.$viewType.'AddAlbumLink';

	?>
	<div class="galleryitem">
		<a href="<?= $displayAddAlbumLinkFunction($gallery) ?>">
			<img src="<?= $gallery->iconsPath ?>/add.png" alt="<?= $gallery->galleryLabels["Add album"] ?>"><br>
			<?= $gallery->galleryLabels["Add album"] ?>
		</a>
	</div>
	<?php
}

function displayEditableGalleryItem(Gallery $gallery, array $albumObject, int $count, string $viewType, string $anchorPrefix): void
{
	$displayAlbumLinkFunction = '\SBGallery\View\HTML\gallery_display'.$viewType.'AlbumLink';

	?>
	<div class="galleryitem">
		<?php
		if($gallery->displayAnchors)
		{
			?>
			<a name="<?= $anchorPrefix."-".$count ?>"></a>
			<?php
		}

		displayGalleryThumbnail($gallery, $albumObject, $count, $viewType);
		?>
		<br>
		<a href="<?= $displayAlbumLinkFunction($gallery, $albumObject["ALBUM_ID"], $count, "moveleft_album") ?>"><img src="<?= $gallery->iconsPath ?>/moveleft.png" alt="<?= $gallery->galleryLabels["Move left"] ?>"></a>
		<a href="<?= $displayAlbumLinkFunction($gallery, $albumObject["ALBUM_ID"], $count, "moveright_album") ?>"><img src="<?= $gallery->iconsPath ?>/moveright.png" alt="<?= $gallery->galleryLabels["Move right"] ?>"></a>
		<?php
		$count_stmt = $gallery->queryPictureCount($albumObject["ALBUM_ID"]);
		while(($count_row = $count_stmt->fetch()) !== false)
		{
			if($count_row["count(*)"] == 0)
			{
				?>
				<a href="<?= $displayAlbumLinkFunction($gallery, $albumObject["ALBUM_ID"], $count, "remove_album") ?>"><img src="<?= $gallery->iconsPath ?>/delete.png" alt="<?= $gallery->galleryLabels["Remove"] ?>"></a>
				<?php
			}
		}
		?>
	</div>
	<?php
}

/**
 * Displays an editable gallery.
 *
 * @param $gallery Gallery to display
 * @param $viewType Name of the class of functions that display the gallery
 * @param $anchorPrefix Prefix that the hidden anchors for searching should use
 */
function displayEditableGallery(Gallery $gallery, string $viewType = "Conventional", string $anchorPrefix = "album"): void
{
	?>
	<div class="gallery">
		<?php
		displayAddAlbumButton($gallery, $viewType);

		$count = 0;

		$stmt = $gallery->queryAlbums(false);
		while(($albumObject = $stmt->fetch()) !== false)
		{
			displayEditableGalleryItem($gallery, $albumObject, $count, $viewType, $anchorPrefix);
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
