<?php
class AlbumFileSet
{
	public static function createAlbumDirectories($baseDir, $albumId, $filePermissions)
	{
		$albumDir = $baseDir."/".$albumId;
		mkdir($albumDir);
		chmod($albumDir, $filePermissions);
		$thumbnailsDir = $albumDir."/thumbnails";
		mkdir($thumbnailsDir);
		chmod($thumbnailsDir, $filePermissions);
		$picturesDir = $albumDir."/pictures";
		mkdir($picturesDir);
		chmod($picturesDir, $filePermissions);
	}

	public static function removeAlbumDirectories($baseDir, $albumId)
	{
		$albumDir = $baseDir."/".$albumId;
		rmdir($albumDir."/pictures");
		rmdir($albumDir."/thumbnails");
		rmdir($albumDir);
	}

	public static function renameAlbumDirectory($baseDir, $oldAlbumId, $newAlbumId)
	{
		if($oldAlbumId !== $newAlbumId) // Only rename when needed
			rename($baseDir."/".$oldAlbumId, $baseDir."/".$newAlbumId);
	}
}
?>
