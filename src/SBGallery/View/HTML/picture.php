<?php
/**
 * @file
 * @brief View-HTML-Picture module
 * @defgroup View-HTML-Picture
 * @{
 */
namespace SBGallery\View\HTML;
use SBGallery\Model\Picture;

function displayNavigationButtons(Picture $picture): void
{
	$predecessorId = $picture->queryPredecessorId();

	if($predecessorId === null)
	{
		?>
		<img src="<?= $picture->settings->iconsPath ?>/previous-disabled.png" alt="<?= $picture->settings->labels->previous ?>">
		<?php
	}
	else
	{
		?>
		<a href="<?= $picture->settings->urlGenerator->generatePreviousPictureURL($picture->albumId, $predecessorId) ?>"><img src="<?= $picture->settings->iconsPath ?>/previous.png" alt="<?= $picture->settings->labels->previous ?>"></a>
		<?php
	}

	$successorId = $picture->querySuccessorId();

	if($successorId === null)
	{
		?>
		<img src="<?= $picture->settings->iconsPath ?>/next-disabled.png" alt="<?= $picture->settings->labels->next ?>">
		<?php
	}
	else
	{
		?>
		<a href="<?= $picture->settings->urlGenerator->generateNextPictureURL($picture->albumId, $successorId) ?>"><img src="<?= $picture->settings->iconsPath ?>/next.png" alt="<?= $picture->settings->labels->next ?>"></a>
		<?php
	}
}

function displayPictureNavigation(Picture $picture): void
{
	?>
	<div class="picturenavigation">
		<?php displayNavigationButtons($picture); ?>
	</div>
	<?php
}

function displayPictureOnly(Picture $picture): void
{
	?>
	<div class="picture">
		<?php
		if($picture->fileType !== null)
		{
			?>
			<p>
				<img src="<?= $picture->settings->baseURL."/pictures/".rawurlencode($picture->pictureId).".".$picture->fileType ?>" alt="<?= $picture->fields["Title"]->exportValue() ?>">
			</p>
			<?php
		}
		?>
	</div>
	<?php
}

/**
 * Displays a non-editable picture.
 *
 * @param $picture Picture to display
 */
function displayPicture(Picture $picture): void
{
	if($picture->pictureId !== null)
		displayPictureNavigation($picture);
	displayPictureOnly($picture);
	\SBData\View\HTML\displayField($picture->fields["Description"]);
}

function displayEditablePictureNavigation(Picture $picture): void
{
	?>
	<div class="picturenavigation">
		<?php
		displayNavigationButtons($picture);
		if($picture->fileType !== null)
		{
			?>
			<a href="<?= $picture->settings->urlGenerator->generateClearPictureURL($picture->albumId, $picture->pictureId) ?>"><img src="<?= $picture->settings->iconsPath ?>/clear.png" alt="<?= $picture->settings->labels->clearPicture ?>"></a>
			<?php
		}
		?>
	</div>
	<?php
}

/**
 * Displays an editable picture.
 *
 * @param $picture Picture to display
 */
function displayEditablePicture(Picture $picture): void
{
	if($picture->pictureId !== null)
		displayEditablePictureNavigation($picture);
	displayPictureOnly($picture);
	\SBData\View\HTML\displayEditableForm($picture);
}

/**
 * @}
 */
?>
