<?php
namespace SBGallery\View\HTML;
use SBGallery\Model\Album;

function album_displayConventionalAlbumLink(Album $album, $albumURL)
{
	return $albumURL."?ALBUM_ID=".$album->entity["ALBUM_ID"];
}

function album_displayLayoutAlbumLink(Album $album, $albumURL)
{
	return $albumURL;
}

function displayAlbumBreadcrumbs(Album $album, $galleryURL, $albumURL, $viewType = "Conventional")
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

function displayAlbumThumbnail(Album $album, array $picture, $count, $viewType)
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

function album_displayConventionalPictureLink(Album $album, $albumId, $pictureId, $count, $operation = null)
{
	if($operation === null)
		$operationParam = "";
	else
	{
		$operationParam = "&amp;__operation=".$operation;
		if($album->displayAnchors)
			$operationParam .= "&amp;__id=".$count;
	}

	return $album->pictureDisplayURL."?ALBUM_ID=".$albumId."&amp;PICTURE_ID=".$pictureId.$operationParam;
}

function album_displayLayoutPictureLink(Album $album, $albumId, $pictureId, $count, $operation = null)
{
	if($operation === null)
		$operationParam = "";
	else
	{
		$operationParam = "?__operation=".$operation;
		if($album->displayAnchors)
			$operationParam .= "&amp;__id=".$count;
	}

	return $_SERVER["PHP_SELF"]."/".$pictureId.$operationParam;
}

function displayAlbum(Album $album, $viewType = "Conventional")
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
		<?php
		$stmt = $album->queryPictures();
		while(($row = $stmt->fetch()) !== false)
		{
			?>
			<div class="albumitem"><?php displayAlbumThumbnail($album, $row, 0, $viewType); ?></div>
			<?php
		}
		// Clear hack to allow the enclosing div to automatically adjust its height
		?>
		<div style="clear: both;"></div>
		<?php
	}
}

function album_displayConventionalAddPictureLink(Album $album)
{
	return $album->pictureDisplayURL."?ALBUM_ID=".$album->entity["ALBUM_ID"];
}

function album_displayLayoutAddPictureLink(Album $album)
{
	return $_SERVER["PHP_SELF"]."?__operation=create_picture";
}

function album_displayConventionalAddMultiplePicturesLink(Album $album)
{
	return $album->addMultiplePicturesURL."?ALBUM_ID=".$album->entity["ALBUM_ID"];
}

function album_displayLayoutAddMultiplePicturesLink(Album $album)
{
	return $_SERVER["PHP_SELF"]."?__operation=add_multiple_pictures";
}

function displayEditableAlbum(Album $album, $submitLabel, $generalErrorMessage, $fieldErrorMessage, $viewType = "Conventional", $anchorPrefix = "picture")
{
	$displayPictureLinkFunction = '\SBGallery\View\HTML\album_display'.$viewType.'PictureLink';
	$displayAddPictureLinkFunction = '\SBGallery\View\HTML\album_display'.$viewType.'AddPictureLink';
	$displayAddMultiplePicturesLinkFunction = '\SBGallery\View\HTML\album_display'.$viewType.'AddMultiplePicturesLink';

	\SBData\View\HTML\displayEditableForm($album->form, $submitLabel, $generalErrorMessage, $fieldErrorMessage);

	if($album->entity !== false)
	{
		?>
		<div class="albumitem">
			<a href="<?php print($displayAddPictureLinkFunction($album)); ?>">
				<img src="<?php print($album->iconsPath); ?>/add.png" alt="<?php print($album->albumLabels["Add picture"]); ?>"><br>
				<?php print($album->albumLabels["Add picture"]); ?>
			</a>
		</div>
		<div class="albumitem">
			<a href="<?php print($displayAddMultiplePicturesLinkFunction($album)); ?>">
				<img src="<?php print($album->iconsPath); ?>/add-multiple.png" alt="<?php print($album->albumLabels["Add multiple pictures"]); ?>"><br>
				<?php print($album->albumLabels["Add multiple pictures"]); ?>
			</a>
		</div>
		<?php
		$count = 0;
		$stmt = $album->queryPictures();
		while(($row = $stmt->fetch()) !== false)
		{
			?>
			<div class="albumitem">
				<?php displayAlbumThumbnail($album, $row, $count, $viewType); ?>
				<br>
				<a href="<?php print($displayPictureLinkFunction($album, $album->entity["ALBUM_ID"], $row["PICTURE_ID"], $count, "moveleft_picture")); ?>"><img src="<?php print($album->iconsPath); ?>/moveleft.png" alt="<?php print($album->albumLabels["Move left"]); ?>"></a>
				<a href="<?php print($displayPictureLinkFunction($album, $album->entity["ALBUM_ID"], $row["PICTURE_ID"], $count, "moveright_picture")); ?>"><img src="<?php print($album->iconsPath); ?>/moveright.png" alt="<?php print($album->albumLabels["Move right"]); ?>"></a>
				<?php
				if($row["FileType"] !== null)
				{
					?>
					<a href="<?php print($displayPictureLinkFunction($album, $album->entity["ALBUM_ID"], $row["PICTURE_ID"], $count, "setasthumbnail_picture")); ?>"><img src="<?php print($album->iconsPath); ?>/setasthumbnail.png" alt="<?php print($album->albumLabels["Set as album thumbnail"]); ?>"></a>
					<?php
				}
				?>
				<a href="<?php print($displayPictureLinkFunction($album, $album->entity["ALBUM_ID"], $row["PICTURE_ID"], $count, "remove_picture")); ?>"><img src="<?php print($album->iconsPath); ?>/delete.png" alt="<?php print($album->albumLabels["Remove"]); ?>"></a>
				<?php
				if($album->displayAnchors)
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
}
?>
