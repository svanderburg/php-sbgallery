<?php
require_once("data/view/html/form.inc.php");
require_once("editor/view/html/htmleditorfield.inc.php");

function displayConventionalAlbumLink(Album $album, $albumURL)
{
	return $albumURL."?ALBUM_ID=".$album->entity["ALBUM_ID"];
}

function displayLayoutAlbumLink(Album $album, $albumURL)
{
	return $albumURL;
}

function displayAlbumBreadcrumbs(Album $album, $galleryURL, $albumURL, $displayAlbumLink = "displayConventionalAlbumLink")
{
	?>
	<p>
		<a href="<?php print($galleryURL); ?>">Gallery</a>
		<?php
		if($album->entity !== false)
		{
			?>
			&raquo; <a href="<?php print($displayAlbumLink($album, $albumURL)); ?>"><?php print($album->entity["Title"]); ?></a>
			<?php
		}
		?>
	</p>
	<?php
}

function displayAlbumThumbnail(Album $album, array $picture, $displayPictureLink)
{
	if($picture["FileType"] === null)
		$imageURL = $album->iconsPath."/thumbnail.png";
	else
		$imageURL = $album->baseURL."/".$album->entity["ALBUM_ID"]."/thumbnails/".$picture["PICTURE_ID"].".".$picture["FileType"];
	?>
	<a href="<?php print($displayPictureLink($album, $album->entity["ALBUM_ID"], $picture["PICTURE_ID"])); ?>"><img src="<?php print($imageURL); ?>" alt="<?php print($picture["Title"]); ?>"></a>
	<?php
}

function displayConventionalPictureLink(Album $album, $albumId, $pictureId, $operation = null)
{
	if($operation === null)
		$operationParam = "";
	else
		$operationParam = "&amp;__operation=".$operation;

	return $album->pictureDisplayURL."?ALBUM_ID=".$albumId."&amp;PICTURE_ID=".$pictureId.$operationParam;
}

function displayLayoutPictureLink(Album $album, $albumId, $pictureId, $operation = null)
{
	if($operation === null)
		$operationParam = "";
	else
		$operationParam = "?__operation=".$operation;

	return $_SERVER["PHP_SELF"]."/".$pictureId.$operationParam;
}

function displayAlbum(Album $album, $displayPictureLink = "displayConventionalPictureLink")
{
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
			<div class="albumitem"><?php displayAlbumThumbnail($album, $row, $displayPictureLink); ?></div>
			<?php
		}
		// Clear hack to allow the enclosing div to automatically adjust its height
		?>
		<div style="clear: both;"></div>
		<?php
	}
}

function displayConventionalAddPictureLink(Album $album)
{
	return $album->pictureDisplayURL."?ALBUM_ID=".$album->entity["ALBUM_ID"];
}

function displayLayoutAddPictureLink(Album $album)
{
	return $_SERVER["PHP_SELF"]."?__operation=create_picture";
}

function displayConventionalAddMultiplePicturesLink(Album $album)
{
	return $album->addMultiplePicturesURL."?ALBUM_ID=".$album->entity["ALBUM_ID"];
}

function displayLayoutAddMultiplePicturesLink(Album $album)
{
	return $_SERVER["PHP_SELF"]."?__operation=add_multiple_pictures";
}

function displayEditableAlbum(Album $album, $submitLabel, $generalErrorMessage, $fieldErrorMessage, $displayPictureLink = "displayConventionalPictureLink", $displayAddPictureLink = "displayConventionalAddPictureLink", $displayAddMultiplePicturesLink = "displayConventionalAddMultiplePicturesLink")
{
	displayEditableForm($album->form, $submitLabel, $generalErrorMessage, $fieldErrorMessage);

	if($album->entity !== false)
	{
		?>
		<div class="albumitem">
			<a href="<?php print($displayAddPictureLink($album)); ?>">
				<img src="<?php print($album->iconsPath); ?>/add.png" alt="<?php print($album->albumLabels["Add picture"]); ?>"><br>
				<?php print($album->albumLabels["Add picture"]); ?>
			</a>
		</div>
		<div class="albumitem">
			<a href="<?php print($displayAddMultiplePicturesLink($album)); ?>">
				<img src="<?php print($album->iconsPath); ?>/add-multiple.png" alt="<?php print($album->albumLabels["Add multiple pictures"]); ?>"><br>
				<?php print($album->albumLabels["Add multiple pictures"]); ?>
			</a>
		</div>
		<?php
		$stmt = $album->queryPictures();
		while(($row = $stmt->fetch()) !== false)
		{
			?>
			<div class="albumitem">
				<?php displayAlbumThumbnail($album, $row, $displayPictureLink); ?>
				<br>
				<a href="<?php print($displayPictureLink($album, $album->entity["ALBUM_ID"], $row["PICTURE_ID"], "moveleft_picture")); ?>"><img src="<?php print($album->iconsPath); ?>/moveleft.png" alt="<?php print($album->albumLabels["Move left"]); ?>"></a>
				<a href="<?php print($displayPictureLink($album, $album->entity["ALBUM_ID"], $row["PICTURE_ID"], "moveright_picture")); ?>"><img src="<?php print($album->iconsPath); ?>/moveright.png" alt="<?php print($album->albumLabels["Move right"]); ?>"></a>
				<?php
				if($row["FileType"] !== null)
				{
					?>
					<a href="<?php print($displayPictureLink($album, $album->entity["ALBUM_ID"], $row["PICTURE_ID"], "setasthumbnail_picture")); ?>"><img src="<?php print($album->iconsPath); ?>/setasthumbnail.png" alt="<?php print($album->albumLabels["Set as album thumbnail"]); ?>"></a>
					<?php
				}
				?>
				<a href="<?php print($displayPictureLink($album, $album->entity["ALBUM_ID"], $row["PICTURE_ID"], "remove_picture")); ?>"><img src="<?php print($album->iconsPath); ?>/delete.png" alt="<?php print($album->albumLabels["Remove"]); ?>"></a>
			</div>
			<?php
		}
		// Clear hack to allow the enclosing div to automatically adjust its height
		?>
		<div style="clear: both;"></div>
		<?php
	}
}
?>
