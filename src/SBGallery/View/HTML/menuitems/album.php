<?php
\SBLayout\View\HTML\displayMenuItem($active, $url, $subPage);

if($subPage->checker->checkWritePermissions())
{
	$gallery = $subPage->gallery;
	?>
	<div class="album-buttons">
		<a href="<?= $url ?>?<?= $gallery->settings->operationParam ?>=moveleft_album"><img src="<?= $gallery->settings->iconsPath ?>/moveleft.png" alt="<?= $gallery->settings->galleryLabels->moveLeft ?>"></a>
		<a href="<?= $url ?>?<?= $gallery->settings->operationParam ?>=moveright_album"><img src="<?= $gallery->settings->iconsPath ?>/moveright.png" alt="<?= $gallery->settings->galleryLabels->moveRight ?>"></a>
		<?php
		$albumId = rawurldecode(basename($url));

		if($gallery->albumIsEmpty($albumId))
		{
			?>
			<a href="<?= $url ?>?<?= $gallery->settings->operationParam ?>=remove_album"><img src="<?= $gallery->settings->iconsPath ?>/delete.png" alt="<?= $gallery->settings->galleryLabels->remove ?>"></a>
			<?php
		}
		?>
	</div>
	<?php
}
?>
