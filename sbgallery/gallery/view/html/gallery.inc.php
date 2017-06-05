<?php

function displayGalleryBreadcrumbs($galleryURL)
{
	?>
	<p>
		<a href="<?php print($galleryURL); ?>">Gallery</a>
	</p>
	<?php
}

function displayGalleryThumbnail(Gallery $gallery, array $thumbnail, $displayAlbumLink)
{
	?>
	<a href="<?php print($displayAlbumLink($gallery, $thumbnail["ALBUM_ID"])); ?>">
	<?php
	if($thumbnail["FileType"] === null)
		$imageURL = $gallery->iconsPath."/thumbnail.png";
	else
		$imageURL = $gallery->baseURL."/".$thumbnail["ALBUM_ID"]."/thumbnails/".$thumbnail["PICTURE_ID"].".".$thumbnail["FileType"];
	?>
	<img src="<?php print($imageURL); ?>" alt="<?php print($thumbnail["Title"]); ?>"><br>
	<?php print($thumbnail["Title"]); ?>
	</a>
	<?php
}

function displayConventionalAlbumLink(Gallery $gallery, $albumId, $operation = null)
{
	if($operation === null)
		$operationParam = "";
	else
		$operationParam = "&amp;__operation=".$operation;

	return $gallery->albumDisplayURL."?ALBUM_ID=".$albumId.$operationParam;
}

function displayLayoutAlbumLink(Gallery $gallery, $albumId, $operation = null)
{
	if($operation === null)
		$operationParam = "";
	else
		$operationParam = "?__operation=".$operation;
	
	return $_SERVER["PHP_SELF"]."/".$albumId.$operationParam;
}

function displayGallery(Gallery $gallery, $displayAlbumLink = "displayConventionalAlbumLink")
{
	$stmt = $gallery->queryAlbums(true);
	while(($row = $stmt->fetch()) !== false)
	{
		?>
		<div class="galleryitem"><?php displayGalleryThumbnail($gallery, $row, $displayAlbumLink); ?></div>
		<?php
	}
}

function displayConventionalAddAlbumLink(Gallery $gallery)
{
	return $gallery->albumDisplayURL;
}

function displayLayoutAddAlbumLink(Gallery $gallery)
{
	return $_SERVER["PHP_SELF"]."?__operation=create_album";
}

function displayEditableGallery(Gallery $gallery, $displayAlbumLink = "displayConventionalAlbumLink", $displayAddAlbumLink = "displayConventionalAddAlbumLink")
{
	?>
	<div class="galleryitem">
		<a href="<?php print($displayAddAlbumLink($gallery)); ?>">
			<img src="<?php print($gallery->iconsPath); ?>/add.png" alt="<?php print($gallery->galleryLabels["Add album"]); ?>"><br>
			<?php print($gallery->galleryLabels["Add album"]); ?>
		</a>
	</div>
	<?php
	$stmt = $gallery->queryAlbums(false);
	while(($row = $stmt->fetch()) !== false)
	{
		?>
		<div class="galleryitem">
			<?php displayGalleryThumbnail($gallery, $row, $displayAlbumLink); ?>
			<br>
			<a href="<?php print($displayAlbumLink($gallery, $row["ALBUM_ID"], "moveleft_album")); ?>"><img src="<?php print($gallery->iconsPath); ?>/moveleft.png" alt="<?php print($gallery->galleryLabels["Move left"]); ?>"></a>
			<a href="<?php print($displayAlbumLink($gallery, $row["ALBUM_ID"], "moveright_album")); ?>"><img src="<?php print($gallery->iconsPath); ?>/moveright.png" alt="<?php print($gallery->galleryLabels["Move right"]); ?>"></a>
			<?php
			$count_stmt = $gallery->queryPictureCount($row["ALBUM_ID"]);
			while(($count_row = $count_stmt->fetch()) !== false)
			{
				if($count_row["count(*)"] == 0)
				{
					?>
					<a href="<?php print($displayAlbumLink($gallery, $row["ALBUM_ID"], "remove_album")); ?>"><img src="<?php print($gallery->iconsPath); ?>/delete.png" alt="<?php print($gallery->galleryLabels["Remove"]); ?>"></a>
					<?php
				}
			}
			?>
		</div>
		<?php
	}
}
?>
