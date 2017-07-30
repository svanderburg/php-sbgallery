<?php
namespace SBGallery\View\HTML;
use SBGallery\Model\Picture;

function picture_displayConventionalAlbumLink(Picture $picture, $albumURL)
{
	return $albumURL."?ALBUM_ID=".$picture->entity["ALBUM_ID"];
}

function picture_displayConventionalPictureLink(Picture $picture, $pictureURL)
{
	return $pictureURL."?ALBUM_ID=".$picture->entity["ALBUM_ID"]."&amp;PICTURE_ID=".$picture->entity["PICTURE_ID"];
}

function picture_displayLayoutAlbumLink(Picture $picture, $url)
{
	return $url;
}

function picture_displayLayoutPictureLink(Picture $picture, $url)
{
	return $url;
}

function displayPictureBreadcrumbs(Picture $picture, $galleryURL, $albumURL, $pictureURL, $viewType = "Conventional")
{
	$displayAlbumLinkFunction = '\SBGallery\View\HTML\picture_display'.$viewType.'AlbumLink';
	$displayPictureLinkFunction = '\SBGallery\View\HTML\picture_display'.$viewType.'PictureLink';
	?>
	<p>
		<a href="<?php print($galleryURL); ?>"><?php print($picture->labels["Gallery"]); ?></a>
		<?php
		if($picture->entity !== false)
		{
			$stmt = $picture->queryAlbum();
			if(($row = $stmt->fetch()) !== false)
			{
				?>
				&raquo; <a href="<?php print($displayAlbumLinkFunction($picture, $albumURL)); ?>"><?php print($row["Title"]); ?></a>
				<?php
			}
			?>
			&raquo; <a href="<?php print($displayPictureLinkFunction($picture, $pictureURL)); ?>"><?php print($picture->entity["Title"]); ?></a>
			<?php
		}
		?>
	</p>
	<?php
}

function displayPictureNavigation(Picture $picture, $viewType)
{
	$displayImageLinkFunction = '\SBGallery\View\HTML\picture_display'.$viewType.'ImageLink';

	$stmt = $picture->queryPredecessor();

	if(($row = $stmt->fetch()) !== false)
	{
		?>
		<a class="picture-previous" href="<?php print($displayImageLinkFunction($picture->entity["ALBUM_ID"], $row["PICTURE_ID"])); ?>"><img src="<?php print($picture->iconsPath); ?>/previous.png" alt="Previous"></a>
		<?php
	}

	$stmt = $picture->querySuccessor();

	if(($row = $stmt->fetch()) !== false)
	{
		?>
		<a class="picture-next" href="<?php print($displayImageLinkFunction($picture->entity["ALBUM_ID"], $row["PICTURE_ID"])); ?>"><img src="<?php print($picture->iconsPath); ?>/next.png" alt="Next"></a>
		<?php
	}
}

function displayPicture(Picture $picture, $viewType = "Conventional")
{
	$displayImageLinkFunction = '\SBGallery\View\HTML\picture_display'.$viewType.'ImageLink';

	if($picture->entity === false)
	{
		?>
		<p><strong>Cannot find picture with id: <?php print($picture->entity["PICTURE_ID"]); ?></strong></p>
		<?php
	}
	else
	{
		?>
		<div class="pictureitem">
			<?php
			displayPictureNavigation($picture, $viewType);
			?>
			<p>
				<img src="<?php print($picture->baseURL."/pictures/".$picture->entity["PICTURE_ID"].".".$picture->entity["FileType"]); ?>" alt="<?php print($picture->entity["Title"]); ?>">
			</p>
			<div><?php print($picture->entity["Description"]); ?></div>
		</div>
		<?php
	}
}

function picture_displayConventionalImageLink($albumId, $pictureId, $operation = null)
{
	if($operation === null)
		$operationParam = "";
	else
		$operationParam = "&amp;__operation=".$operation;

	return $_SERVER["PHP_SELF"]."?ALBUM_ID=".$albumId."&amp;PICTURE_ID=".$pictureId.$operationParam;
}

function picture_displayLayoutImageLink($albumId, $pictureId, $operation = null)
{
	if($operation === null)
		$operationParam = "";
	else
		$operationParam = "?__operation=".$operation;

	return dirname($_SERVER["PHP_SELF"])."/".$pictureId.$operationParam;
}

function displayEditablePicture(Picture $picture, $submitLabel, $generalErrorMessage, $fieldErrorMessage, $viewType = "Conventional")
{
	$displayImageLinkFunction = '\SBGallery\View\HTML\picture_display'.$viewType.'ImageLink';

	if($picture->entity !== false)
	{
		?>
		<div class="pictureitem">
			<?php
			displayPictureNavigation($picture, $viewType);

			if($picture->entity["FileType"] !== null)
			{
				?>
				<p>
					<img src="<?php print($picture->baseURL."/pictures/".$picture->entity["PICTURE_ID"].".".$picture->entity["FileType"]); ?>" alt="<?php print($picture->entity["Title"]); ?>">
				</p>
				<p><a href="<?php print($displayImageLinkFunction($picture->entity["ALBUM_ID"], $picture->entity["PICTURE_ID"], "remove_picture_image")); ?>"><?php print($picture->labels["Remove image"]); ?></a></p>
				<?php
			}
			?>
		</div>
		<?php
	}

	\SBData\View\HTML\displayEditableForm($picture->form, $submitLabel, $generalErrorMessage, $fieldErrorMessage);
}
?>
