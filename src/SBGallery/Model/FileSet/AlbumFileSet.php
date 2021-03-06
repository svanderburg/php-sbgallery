<?php
namespace SBGallery\Model\FileSet;

class AlbumFileSet
{
	public static function createAlbumDirectories($baseDir, $albumId, $dirPermissions)
	{
		$albumDir = $baseDir."/".$albumId;
		mkdir($albumDir);
		chmod($albumDir, $dirPermissions);
		$thumbnailsDir = $albumDir."/thumbnails";
		mkdir($thumbnailsDir);
		chmod($thumbnailsDir, $dirPermissions);
		$picturesDir = $albumDir."/pictures";
		mkdir($picturesDir);
		chmod($picturesDir, $dirPermissions);
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
