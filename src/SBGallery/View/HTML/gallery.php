<?php
/**
 * @file
 * @brief View-HTML-Gallery module
 * @defgroup View-HTML-Gallery
 * @{
 */
namespace SBGallery\View\HTML;
use SBGallery\Model\Gallery;

function displayGalleryBreadcrumbs(Gallery $gallery, string $galleryURL): void
{
	?>
	<p>
		<a href="<?php print($galleryURL); ?>"><?php print($gallery->galleryLabels["Gallery"]); ?></a>
	</p>
	<?php
}

function displayGalleryThumbnail(Gallery $gallery, array $thumbnail, int $count, string $viewType): void
{
	$displayAlbumLinkFunction = '\SBGallery\View\HTML\gallery_display'.$viewType.'AlbumLink';
	?>
	<a href="<?php print($displayAlbumLinkFunction($gallery, $thumbnail["ALBUM_ID"], $count)); ?>">
	<?php
	if($thumbnail["FileType"] === null)
		$imageURL = $gallery->iconsPath."/thumbnail.png";
	else
		$imageURL = $gallery->baseURL."/".$thumbnail["ALBUM_ID"]."/thumbnails/".$thumbnail["PICTURE_ID"].".".$thumbnail["FileType"];
	?>
	<img src="<?php print($imageURL); ?>" alt="<?php print($thumbnail["Title"]); ?>"><br>
	<?php print($thumbnail["Title"]); ?>
	</a>
	<?php
}

function gallery_displayConventionalAlbumLink(Gallery $gallery, string $albumId, int $count, string $operation = null): string
{
	if($operation === null)
		$operationParam = "";
	else
	{
		$operationParam = "&amp;__operation=".$operation;

		if($gallery->displayAnchors)
			$operationParam .= "&amp;__id=".$count;
	}

	return $gallery->albumDisplayURL."?ALBUM_ID=".$albumId.$operationParam;
}

function gallery_displayLayoutAlbumLink(Gallery $gallery, string $albumId, int $count, string $operation = null): string
{
	if($operation === null)
		$operationParam = "";
	else
	{
		$operationParam = "?__operation=".$operation;

		if($gallery->displayAnchors)
			$operationParam .= "&amp;__id=".$count;
	}

	return $_SERVER["PHP_SELF"]."/".$albumId.$operationParam;
}

/**
 * Displays a non-editable gallery.
 *
 * @param $gallery Gallery to display
 * @param $viewType Name of the class of functions that display the gallery
 */
function displayGallery(Gallery $gallery, string $viewType = "Conventional"): void
{
	$displayAlbumLinkFunction = '\SBGallery\View\HTML\gallery_'.$viewType.'AlbumLink';

	$stmt = $gallery->queryAlbums(true);
	while(($row = $stmt->fetch()) !== false)
	{
		?>
		<div class="galleryitem"><?php displayGalleryThumbnail($gallery, $row, 0, $viewType); ?></div>
		<?php
	}
	// Clear hack to allow the enclosing div to automatically adjust its height
	?>
	<div style="clear: both;"></div>
	<?php
}

function gallery_displayConventionalAddAlbumLink(Gallery $gallery): string
{
	return $gallery->albumDisplayURL;
}

function gallery_displayLayoutAddAlbumLink(Gallery $gallery): string
{
	return $_SERVER["PHP_SELF"]."?__operation=create_album";
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
	$displayAlbumLinkFunction = '\SBGallery\View\HTML\gallery_display'.$viewType.'AlbumLink';
	$displayAddAlbumLinkFunction = '\SBGallery\View\HTML\gallery_display'.$viewType.'AddAlbumLink';
	?>
	<div class="galleryitem">
		<a href="<?php print($displayAddAlbumLinkFunction($gallery)); ?>">
			<img src="<?php print($gallery->iconsPath); ?>/add.png" alt="<?php print($gallery->galleryLabels["Add album"]); ?>"><br>
			<?php print($gallery->galleryLabels["Add album"]); ?>
		</a>
	</div>
	<?php
	$count = 0;

	$stmt = $gallery->queryAlbums(false);
	while(($row = $stmt->fetch()) !== false)
	{
		?>
		<div class="galleryitem">
			<?php displayGalleryThumbnail($gallery, $row, $count, $viewType); ?>
			<br>
			<a href="<?php print($displayAlbumLinkFunction($gallery, $row["ALBUM_ID"], $count, "moveleft_album")); ?>"><img src="<?php print($gallery->iconsPath); ?>/moveleft.png" alt="<?php print($gallery->galleryLabels["Move left"]); ?>"></a>
			<a href="<?php print($displayAlbumLinkFunction($gallery, $row["ALBUM_ID"], $count, "moveright_album")); ?>"><img src="<?php print($gallery->iconsPath); ?>/moveright.png" alt="<?php print($gallery->galleryLabels["Move right"]); ?>"></a>
			<?php
			$count_stmt = $gallery->queryPictureCount($row["ALBUM_ID"]);
			while(($count_row = $count_stmt->fetch()) !== false)
			{
				if($count_row["count(*)"] == 0)
				{
					?>
					<a href="<?php print($displayAlbumLinkFunction($gallery, $row["ALBUM_ID"], $count, "remove_album")); ?>"><img src="<?php print($gallery->iconsPath); ?>/delete.png" alt="<?php print($gallery->galleryLabels["Remove"]); ?>"></a>
					<?php
				}
			}

			if($gallery->displayAnchors)
			{
				?>
				<a name="<?php print($anchorPrefix."-".$count); ?>"></a>
				<?php
			}
			?>
		</div>
		<?php
		$count++;
	}
	// Clear hack to allow the enclosing div to automatically adjust its height
	?>
	<div style="clear: both;"></div>
	<?php
}

/**
 * @}
 */
?>
