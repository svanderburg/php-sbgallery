<?php
require_once("data/view/html/form.inc.php");
require_once("editor/view/html/htmleditorfield.inc.php");

function displayConventionalAlbumLink(Picture $picture, $albumURL)
{
	return $albumURL."?ALBUM_ID=".$picture->entity["ALBUM_ID"];
}

function displayConventionalPictureLink(Picture $picture, $pictureURL)
{
	return $pictureURL."?ALBUM_ID=".$picture->entity["ALBUM_ID"]."&amp;PICTURE_ID=".$picture->entity["PICTURE_ID"];
}

function displayLayoutAlbumOrPictureLink(Picture $picture, $url)
{
	return $url;
}

function displayPictureBreadcrumbs(Picture $picture, $galleryURL, $albumURL, $pictureURL, $displayAlbumLink = "displayConventionalAlbumLink", $displayPictureLink = "displayConventionalPictureLink")
{
	?>
	<p>
		<a href="<?php print($galleryURL); ?>">Gallery</a>
		<?php
		if($picture->entity !== false)
		{
			$stmt = $picture->queryAlbum();
			if(($row = $stmt->fetch()) !== false)
			{
				?>
				&raquo; <a href="<?php print($displayAlbumLink($picture, $albumURL)); ?>"><?php print($row["Title"]); ?></a>
				<?php
			}
			?>
			&raquo; <a href="<?php print($displayPictureLink($picture, $pictureURL)); ?>"><?php print($picture->entity["Title"]); ?></a>
			<?php
		}
		?>
	</p>
	<?php
}

function displayPictureNavigation(Picture $picture, $displayImageLink)
{
	?>
	<div class="picturenavigation">
	<?php
	$stmt = $picture->queryPredecessor();

	if(($row = $stmt->fetch()) !== false)
	{
		?>
		<a class="picture-previous" href="<?php print($displayImageLink($picture->entity["ALBUM_ID"], $row["PICTURE_ID"])); ?>"><img src="<?php print($picture->iconsPath); ?>/previous.png" alt="Previous"></a>
		<?php
	}

	$stmt = $picture->querySuccessor();

	if(($row = $stmt->fetch()) !== false)
	{
		?>
		<a class="picture-next" href="<?php print($displayImageLink($picture->entity["ALBUM_ID"], $row["PICTURE_ID"])); ?>"><img src="<?php print($picture->iconsPath); ?>/next.png" alt="Next"></a>
		<?php
	}
	?>
	</div>
	<?php
}

function displayPicture(Picture $picture, $displayImageLink = "displayConventionalImageLink")
{
	if($picture->entity === false)
	{
		?>
		<p><strong>Cannot find picture with id: <?php print($picture->entity["PICTURE_ID"]); ?></strong></p>
		<?php
	}
	else
	{
		displayPictureNavigation($picture, $displayImageLink);
		?>
		<p>
			<img src="<?php print($picture->baseURL."/pictures/".$picture->entity["PICTURE_ID"].".".$picture->entity["FileType"]); ?>" alt="<?php print($picture->entity["Title"]); ?>">
		</p>
		<div><?php print($picture->entity["Description"]); ?></div>
		<?php
	}
}

function displayConventionalImageLink($albumId, $pictureId, $operation = null)
{
	if($operation === null)
		$operationParam = "";
	else
		$operationParam = "&amp;__operation=".$operation;

	return $_SERVER["PHP_SELF"]."?ALBUM_ID=".$albumId."&amp;PICTURE_ID=".$pictureId.$operationParam;
}

function displayLayoutImageLink($albumId, $pictureId, $operation = null)
{
	if($operation === null)
		$operationParam = "";
	else
		$operationParam = "?__operation=".$operation;

	return dirname($_SERVER["PHP_SELF"])."/".$pictureId.$operationParam;
}

function displayEditablePicture(Picture $picture, $submitLabel, $generalErrorMessage, $fieldErrorMessage, $displayImageLink = "displayConventionalImageLink")
{
	if($picture->entity !== false)
	{
		displayPictureNavigation($picture, $displayImageLink);

		if($picture->entity["FileType"] !== null)
		{
			?>
			<p>
				<img src="<?php print($picture->baseURL."/pictures/".$picture->entity["PICTURE_ID"].".".$picture->entity["FileType"]); ?>" alt="<?php print($picture->entity["Title"]); ?>">
			</p>
			<p><a href="<?php print($displayImageLink($picture->entity["ALBUM_ID"], $picture->entity["PICTURE_ID"], "remove_picture_image")); ?>"><?php print($picture->labels["Remove image"]); ?></a></p>
			<?php
		}
	}

	displayEditableForm($picture->form, $submitLabel, $generalErrorMessage, $fieldErrorMessage);
}
?>
