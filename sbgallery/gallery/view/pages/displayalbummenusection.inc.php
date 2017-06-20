<?php
function displayAlbumMenuSection(GalleryPage $galleryPage)
{
	$gallery = $galleryPage->constructGallery();
	$checker = $galleryPage->constructGalleryPermissionChecker();

	$authenticated = $checker->checkWritePermissions();

	$stmt =  $gallery->queryAlbums(!$authenticated);
	while(($row = $stmt->fetch()) !== false)
	{
		if(array_key_exists("query", $GLOBALS) && array_key_exists("albumId", $GLOBALS["query"]) && $row["ALBUM_ID"] === $GLOBALS["query"]["albumId"])
			$active = ' class="active"';
		else
			$active = "";
		?>
		<a href="<?php print($gallery->albumDisplayURL."/".$row["ALBUM_ID"]); ?>"<?php print($active); ?>><?php print($row["Title"]); ?></a>
		<?php
		if($authenticated)
		{
			?>
			<div class="album-buttons">
				<a href="<?php print($gallery->albumDisplayURL."/".$row["ALBUM_ID"]); ?>?__operation=moveleft_album"><img src="<?php print($gallery->iconsPath); ?>/moveleft.png" alt="<?php print($gallery->galleryLabels["Move left"]); ?>"></a>
				<a href="<?php print($gallery->albumDisplayURL."/".$row["ALBUM_ID"]); ?>?__operation=moveright_album"><img src="<?php print($gallery->iconsPath); ?>/moveright.png" alt="<?php print($gallery->galleryLabels["Move right"]); ?>"></a>
				<?php
				$count_stmt = $gallery->queryPictureCount($row["ALBUM_ID"]);
				while(($count_row = $count_stmt->fetch()) !== false)
				{
					if($count_row["count(*)"] == 0)
					{
						?>
						<a href="<?php print($gallery->albumDisplayURL."/".$row["ALBUM_ID"]); ?>?__operation=remove_album"><img src="<?php print($gallery->iconsPath); ?>/delete.png" alt="<?php print($gallery->galleryLabels["Remove"]); ?>"></a>
						<?php
					}
				}
				?>
			</div>
			<?php
		}
	}

	if($authenticated)
	{
		?>
		<a class="create-album" href="<?php print($gallery->albumDisplayURL); ?>?__operation=create_album"><?php print($gallery->galleryLabels["Add album"]); ?></a>
		<?php
	}
}

function visitedGallerySubPage()
{
	return (array_key_exists("crudModel", $GLOBALS) && ($GLOBALS["crudModel"] instanceof GalleryCRUDModel || $GLOBALS["crudModel"] instanceof AlbumCRUDModel || $GLOBALS["crudModel"] instanceof PictureCRUDModel));
}
?>
