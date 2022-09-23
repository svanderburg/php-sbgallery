<?php
/**
 * @file
 * @brief View-HTML-Picture module
 * @defgroup View-HTML-Picture
 * @{
 */
namespace SBGallery\View\HTML;
use SBGallery\Model\Picture;

function picture_displayConventionalAlbumLink(Picture $picture, string $albumURL): string
{
	return $albumURL."?ALBUM_ID=".$picture->entity["ALBUM_ID"];
}

function picture_displayConventionalPictureLink(Picture $picture, string $pictureURL): string
{
	return $pictureURL."?ALBUM_ID=".$picture->entity["ALBUM_ID"]."&amp;PICTURE_ID=".$picture->entity["PICTURE_ID"];
}

function picture_displayLayoutAlbumLink(Picture $picture, string $url): string
{
	return $url;
}

function picture_displayLayoutPictureLink(Picture $picture, string $url): string
{
	return $url;
}

function displayPictureBreadcrumbs(Picture $picture, string $galleryURL, string $albumURL, string $pictureURL, string $viewType = "Conventional"): void
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

function displayNavigationButtons(Picture $picture, string $viewType): void
{
	$displayImageLinkFunction = '\SBGallery\View\HTML\picture_display'.$viewType.'ImageLink';

	$stmt = $picture->queryPredecessor();

	if(($row = $stmt->fetch()) === false)
	{
		?>
		<img src="<?php print($picture->iconsPath); ?>/previous-disabled.png" alt="<?php print($picture->labels["Previous"]); ?>">
		<?php
	}
	else
	{
		?>
		<a href="<?php print($displayImageLinkFunction($picture->entity["ALBUM_ID"], $row["PICTURE_ID"])); ?>"><img src="<?php print($picture->iconsPath); ?>/previous.png" alt="<?php print($picture->labels["Previous"]); ?>"></a>
		<?php
	}

	$stmt = $picture->querySuccessor();

	if(($row = $stmt->fetch()) === false)
	{
		?>
		<img src="<?php print($picture->iconsPath); ?>/next-disabled.png" alt="<?php print($picture->labels["Next"]); ?>">
		<?php
	}
	else
	{
		?>
		<a href="<?php print($displayImageLinkFunction($picture->entity["ALBUM_ID"], $row["PICTURE_ID"])); ?>"><img src="<?php print($picture->iconsPath); ?>/next.png" alt="<?php print($picture->labels["Next"]); ?>"></a>
		<?php
	}
}

function displayPictureNavigation(Picture $picture, string $viewType): void
{
	?>
	<div class="picturenavigation">
		<?php displayNavigationButtons($picture, $viewType); ?>
	</div>
	<?php
}

function displayEditablePictureNavigation(Picture $picture, string $viewType): void
{
	?>
	<div class="picturenavigation">
		<?php
		displayNavigationButtons($picture, $viewType);
		$displayImageLinkFunction = '\SBGallery\View\HTML\picture_display'.$viewType.'ImageLink';
		?>
		<a href="<?php print($displayImageLinkFunction($picture->entity["ALBUM_ID"], $picture->entity["PICTURE_ID"], "remove_picture_image")); ?>"><img src="<?php print($picture->iconsPath); ?>/clear.png" alt="<?php print($picture->labels["Clear image"]); ?>"></a>
	</div>
	<?php
}

/**
 * Displays a non-editable picture.
 *
 * @param $picture Picture to display
 * @param $viewType Name of the class of functions that display the gallery
 */
function displayPicture(Picture $picture, string $viewType = "Conventional"): void
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

function picture_displayConventionalImageLink(string $albumId, string $pictureId, string $operation = null): string
{
	if($operation === null)
		$operationParam = "";
	else
		$operationParam = "&amp;__operation=".$operation;

	return $_SERVER["PHP_SELF"]."?ALBUM_ID=".$albumId."&amp;PICTURE_ID=".$pictureId.$operationParam;
}

function picture_displayLayoutImageLink(string $albumId, string $pictureId, string $operation = null): string
{
	if($operation === null)
		$operationParam = "";
	else
		$operationParam = "?__operation=".$operation;

	return dirname($_SERVER["PHP_SELF"])."/".$pictureId.$operationParam;
}

/**
 * Displays an editable picture.
 *
 * @param $picture Picture to display
 * @param $submitLabel Text to be displayed on the submit button
 * @param $generalErrorMessage Error message to be displayed if a picture is incorrect
 * @param $fieldErrorMessage Error message to be display if a field is incorrect
 * @param $viewType Name of the class of functions that display the gallery
 */
function displayEditablePicture(Picture $picture, string $submitLabel, string $generalErrorMessage, string $fieldErrorMessage, string $viewType = "Conventional"): void
{
	$displayImageLinkFunction = '\SBGallery\View\HTML\picture_display'.$viewType.'ImageLink';

	if($picture->entity !== false)
	{
		?>
		<div class="pictureitem">
			<?php
			displayEditablePictureNavigation($picture, $viewType);

			if($picture->entity["FileType"] !== null)
			{
				?>
				<p>
					<img src="<?php print($picture->baseURL."/pictures/".$picture->entity["PICTURE_ID"].".".$picture->entity["FileType"]); ?>" alt="<?php print($picture->entity["Title"]); ?>">
				</p>
				<?php
			}
			?>
		</div>
		<?php
	}

	\SBData\View\HTML\displayEditableForm($picture->form, $submitLabel, $generalErrorMessage, $fieldErrorMessage);
}

/**
 * @}
 */
?>
