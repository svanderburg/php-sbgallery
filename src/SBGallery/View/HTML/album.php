<?php
/**
 * @file
 * @brief View-HTML-Album module
 * @defgroup View-HTML-Album
 * @{
 */

namespace SBGallery\View\HTML;
use SBGallery\Model\Album;

function album_displayConventionalAlbumLink(Album $album, string $albumURL): string
{
	return $albumURL."?".http_build_query(array(
		"ALBUM_ID" => $album->entity["ALBUM_ID"]
	), "", "&amp;", PHP_QUERY_RFC3986);
}

function album_displayLayoutAlbumLink(Album $album, string $albumURL): string
{
	return $albumURL;
}

function displayAlbumBreadcrumbs(Album $album, string $galleryURL, string $albumURL, string $viewType = "Conventional"): void
{
	$displayAlbumLinkFunction = '\SBGallery\View\HTML\album_display'.$viewType.'AlbumLink';
	?>
	<p>
		<a href="<?php print($galleryURL); ?>"><?php print($album->albumLabels["Gallery"]); ?></a>
		<?php
		if($album->entity !== false)
		{
			?>
			&raquo; <a href="<?php print($displayAlbumLinkFunction($album, $albumURL)); ?>"><?php print($album->entity["Title"]); ?></a>
			<?php
		}
		?>
	</p>
	<?php
}

function displayAlbumThumbnail(Album $album, array $picture, int $count, string $viewType): void
{
	$displayPictureLinkFunction = '\SBGallery\View\HTML\album_display'.$viewType.'PictureLink';

	if($picture["FileType"] === null)
		$imageURL = $album->iconsPath."/thumbnail.png";
	else
		$imageURL = $album->baseURL."/".$album->entity["ALBUM_ID"]."/thumbnails/".$picture["PICTURE_ID"].".".$picture["FileType"];
	?>
	<a href="<?php print($displayPictureLinkFunction($album, $album->entity["ALBUM_ID"], $picture["PICTURE_ID"], $count)); ?>"><img src="<?php print($imageURL); ?>" alt="<?php print($picture["Title"]); ?>"></a>
	<?php
}

function album_displayConventionalPictureLink(Album $album, string $albumId, string $pictureId, int $count, string $operation = null): string
{
	$params = array(
		"ALBUM_ID" => $albumId,
		"PICTURE_ID" => $pictureId
	);

	if($operation !== null)
	{
		$params["__operation"] = $operation;
		if($album->displayAnchors)
			$params["__id"] = $count;
	}

	return $album->pictureDisplayURL."?".http_build_query($params, "", "&amp;", PHP_QUERY_RFC3986);
}

function album_displayLayoutPictureLink(Album $album, string $albumId, string $pictureId, int $count, string $operation = null): string
{
	$params = array();

	if($operation !== null)
	{
		$params["__operation"] = $operation;
		if($album->displayAnchors)
			$params["__id"] = $count;
	}

	return $_SERVER["PHP_SELF"]."/".$pictureId."?".http_build_query($params, "", "&amp;", PHP_QUERY_RFC3986);
}

function displayAlbumItem(Album $album, array $pictureObject, string $viewType): void
{
	?>
	<div class="albumitem"><?php displayAlbumThumbnail($album, $pictureObject, 0, $viewType); ?></div>
	<?php
}

/**
 * Displays a non-editable album.
 *
 * @param $album Album to display
 * @param $viewType Name of the class of functions that display the gallery
 */
function displayAlbum(Album $album, string $viewType = "Conventional"): void
{
	$displayPictureLinkFunction = '\SBGallery\View\HTML\album_display'.$viewType.'PictureLink';

	if($album->entity === false)
	{
		?>
		<p><strong>Cannot find the requested album!</strong></p>
		<?php
	}
	else
	{
		?>
		<p><?php print($album->entity["Description"]); ?></p>
		<div class="album">
			<?php
			$stmt = $album->queryPictures();
			while(($pictureObject = $stmt->fetch()) !== false)
				displayAlbumItem($album, $pictureObject, $viewType);
			?>
		</div>
		<?php
	}
}

function album_displayConventionalAddPictureLink(Album $album): string
{
	return $album->pictureDisplayURL."?".http_build_query(array(
		"ALBUM_ID" => $album->entity["ALBUM_ID"]
	), "", "&amp;", PHP_QUERY_RFC3986);
}

function album_displayLayoutAddPictureLink(Album $album): string
{
	return $_SERVER["PHP_SELF"]."?__operation=create_picture";
}

function album_displayConventionalAddMultiplePicturesLink(Album $album): string
{
	return $album->addMultiplePicturesURL."?".http_build_query(array(
		"ALBUM_ID" => $album->entity["ALBUM_ID"]
	), "", "&amp;", PHP_QUERY_RFC3986);
}

function album_displayLayoutAddMultiplePicturesLink(Album $album): string
{
	return $_SERVER["PHP_SELF"]."?__operation=add_multiple_pictures";
}

function displayAddPictureButton(Album $album, string $viewType): void
{
	$displayAddPictureLinkFunction = '\SBGallery\View\HTML\album_display'.$viewType.'AddPictureLink';

	?>
	<div class="albumitem">
		<a href="<?php print($displayAddPictureLinkFunction($album)); ?>">
			<img src="<?php print($album->iconsPath); ?>/add.png" alt="<?php print($album->albumLabels["Add picture"]); ?>"><br>
			<?php print($album->albumLabels["Add picture"]); ?>
		</a>
	</div>
	<?php
}

function displayAddMultiplePicturesButton(Album $album, string $viewType): void
{
	$displayAddMultiplePicturesLinkFunction = '\SBGallery\View\HTML\album_display'.$viewType.'AddMultiplePicturesLink';

	?>
	<div class="albumitem">
		<a href="<?php print($displayAddMultiplePicturesLinkFunction($album)); ?>">
			<img src="<?php print($album->iconsPath); ?>/add-multiple.png" alt="<?php print($album->albumLabels["Add multiple pictures"]); ?>"><br>
			<?php print($album->albumLabels["Add multiple pictures"]); ?>
		</a>
	</div>
	<?php
}

function displayEditableAlbumItem(Album $album, array $pictureObject, int $count, string $viewType, string $anchorPrefix): void
{
	$displayPictureLinkFunction = '\SBGallery\View\HTML\album_display'.$viewType.'PictureLink';

	?>
	<div class="albumitem">
		<?php
		if($album->displayAnchors)
		{
			?>
			<a name="<?php print($anchorPrefix."-".$count); ?>"></a>
			<?php
		}

		displayAlbumThumbnail($album, $pictureObject, $count, $viewType);
		?>
		<br>
		<a href="<?php print($displayPictureLinkFunction($album, $album->entity["ALBUM_ID"], $pictureObject["PICTURE_ID"], $count, "moveleft_picture")); ?>"><img src="<?php print($album->iconsPath); ?>/moveleft.png" alt="<?php print($album->albumLabels["Move left"]); ?>"></a>
		<a href="<?php print($displayPictureLinkFunction($album, $album->entity["ALBUM_ID"], $pictureObject["PICTURE_ID"], $count, "moveright_picture")); ?>"><img src="<?php print($album->iconsPath); ?>/moveright.png" alt="<?php print($album->albumLabels["Move right"]); ?>"></a>
		<?php
		if($pictureObject["FileType"] !== null)
		{
			?>
			<a href="<?php print($displayPictureLinkFunction($album, $album->entity["ALBUM_ID"], $pictureObject["PICTURE_ID"], $count, "setasthumbnail_picture")); ?>"><img src="<?php print($album->iconsPath); ?>/setasthumbnail.png" alt="<?php print($album->albumLabels["Set as album thumbnail"]); ?>"></a>
			<?php
		}
		?>
		<a href="<?php print($displayPictureLinkFunction($album, $album->entity["ALBUM_ID"], $pictureObject["PICTURE_ID"], $count, "remove_picture")); ?>"><img src="<?php print($album->iconsPath); ?>/delete.png" alt="<?php print($album->albumLabels["Remove"]); ?>"></a>
	</div>
	<?php
}

/**
 * Displays an editable album.
 *
 * @param $album Album to display
 * @param $submitLabel Text to be displayed on the submit button
 * @param $generalErrorMessage Error message to be displayed if an album is incorrect
 * @param $fieldErrorMessage Error message to be display if a field is incorrect
 * @param $viewType Name of the class of functions that display the gallery
 * @param $anchorPrefix Prefix that the hidden anchors for searching should use
 */
function displayEditableAlbum(Album $album, string $submitLabel, string $generalErrorMessage, string $fieldErrorMessage, string $viewType = "Conventional", string $anchorPrefix = "picture"): void
{
	\SBData\View\HTML\displayEditableForm($album->form, $submitLabel, $generalErrorMessage, $fieldErrorMessage);

	if($album->entity !== false)
	{
		?>
		<div class="album">
			<?php
			displayAddPictureButton($album, $viewType);
			displayAddMultiplePicturesButton($album, $viewType);

			$count = 0;
			$stmt = $album->queryPictures();
			while(($pictureObject = $stmt->fetch()) !== false)
			{
				displayEditableAlbumItem($album, $pictureObject, $count, $viewType, $anchorPrefix);
				$count++;
			}
			?>
		</div>
		<?php
	}
}

/**
 * @}
 */
?>
