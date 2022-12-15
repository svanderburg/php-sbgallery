<?php
\SBLayout\View\HTML\displayMenuItem($active, $url, $subPage);

$checker = $subPage->constructGalleryPermissionChecker();

if($checker->checkWritePermissions())
{
	$gallery = $subPage->galleryPage->gallery;
	?>
	<div class="album-buttons">
		<a href="<?= $url ?>?__operation=moveleft_album"><img src="<?= $gallery->iconsPath ?>/moveleft.png" alt="<?= $gallery->galleryLabels["Move left"] ?>"></a>
		<a href="<?= $url ?>?__operation=moveright_album"><img src="<?= $gallery->iconsPath ?>/moveright.png" alt="<?= $gallery->galleryLabels["Move right"] ?>"></a>
		<?php
		$albumId = rawurldecode(basename($url));

		$count_stmt = $gallery->queryPictureCount($albumId);
		while(($count_row = $count_stmt->fetch()) !== false)
		{
			if($count_row["count(*)"] == 0)
			{
				?>
				<a href="<?= $url ?>?__operation=remove_album"><img src="<?= $gallery->iconsPath ?>/delete.png" alt="<?= $gallery->galleryLabels["Remove"] ?>"></a>
				<?php
			}
		}
		?>
	</div>
	<?php
}
?>
