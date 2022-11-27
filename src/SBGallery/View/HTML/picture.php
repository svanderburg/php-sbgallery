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
	return $albumURL."?".http_build_query(array(
		"ALBUM_ID" => $picture->entity["ALBUM_ID"]
	), "", "&amp;", PHP_QUERY_RFC3986);
}

function picture_displayConventionalPictureLink(Picture $picture, string $pictureURL): string
{
	return $pictureURL."?".http_build_query(array(
		"ALBUM_ID" => $picture->entity["ALBUM_ID"],
		"PICTURE_ID" => $picture->entity["PICTURE_ID"]
	), "", "&amp;", PHP_QUERY_RFC3986);
}

function picture_displayLayoutAlbumLink(Picture $picture, string $url): string
{
	return $url;
}

function picture_displayLayoutPictureLink(Picture $picture, string $url): string
{
	return $url;
}

function displayNavigationButtons(Picture $picture, string $viewType): void
{
	$displayImageLinkFunction = '\SBGallery\View\HTML\picture_display'.$viewType.'ImageLink';

	$stmt = $picture->queryPredecessor();

	if(($row = $stmt->fetch()) === false)
	{
		?>
		<img src="<?= $picture->iconsPath ?>/previous-disabled.png" alt="<?= $picture->labels["Previous"] ?>">
		<?php
	}
	else
	{
		?>
		<a href="<?= $displayImageLinkFunction($picture->entity["ALBUM_ID"], $row["PICTURE_ID"]) ?>"><img src="<?= $picture->iconsPath ?>/previous.png" alt="<?= $picture->labels["Previous"] ?>"></a>
		<?php
	}

	$stmt = $picture->querySuccessor();

	if(($row = $stmt->fetch()) === false)
	{
		?>
		<img src="<?= $picture->iconsPath ?>/next-disabled.png" alt="<?= $picture->labels["Next"] ?>">
		<?php
	}
	else
	{
		?>
		<a href="<?= $displayImageLinkFunction($picture->entity["ALBUM_ID"], $row["PICTURE_ID"]) ?>"><img src="<?= $picture->iconsPath ?>/next.png" alt="<?= $picture->labels["Next"] ?>"></a>
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
		<a href="<?= $displayImageLinkFunction($picture->entity["ALBUM_ID"], $picture->entity["PICTURE_ID"], "remove_picture_image") ?>"><img src="<?= $picture->iconsPath ?>/clear.png" alt="<?= $picture->labels["Clear image"] ?>"></a>
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
		<p><strong>Cannot find picture with id: <?= $picture->entity["PICTURE_ID"] ?></strong></p>
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
				<img src="<?= $picture->baseURL."/pictures/".$picture->entity["PICTURE_ID"].".".$picture->entity["FileType"] ?>" alt="<?= $picture->entity["Title"] ?>">
			</p>
			<div><?= $picture->entity["Description"] ?></div>
		</div>
		<?php
	}
}

function picture_displayConventionalImageLink(string $albumId, string $pictureId, string $operation = null): string
{
	$params = array(
		"ALBUM_ID" => $albumId,
		"PICTURE_ID" => $pictureId
	);

	if($operation !== null)
		$params["__operation"] = $operation;

	return $_SERVER["PHP_SELF"]."?".http_build_query($params, "", "&amp;", PHP_QUERY_RFC3986);
}

function picture_displayLayoutImageLink(string $albumId, string $pictureId, string $operation = null): string
{
	$params = array();

	if($operation !== null)
		$params["__operation"] = $operation;

	return dirname($_SERVER["PHP_SELF"])."/".$pictureId."?".http_build_query($params, "", "&amp;", PHP_QUERY_RFC3986);
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
					<img src="<?= $picture->baseURL."/pictures/".$picture->entity["PICTURE_ID"].".".$picture->entity["FileType"] ?>" alt="<?= $picture->entity["Title"] ?>">
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
