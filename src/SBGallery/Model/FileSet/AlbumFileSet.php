<?php
namespace SBGallery\Model\FileSet;

class AlbumFileSet
{
	public static function createAlbumDirectories(string $baseDir, string $albumId, string $dirPermissions): void
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

	public static function removeAlbumDirectories(string $baseDir, string $albumId): void
	{
		$albumDir = $baseDir."/".$albumId;
		rmdir($albumDir."/pictures");
		rmdir($albumDir."/thumbnails");
		rmdir($albumDir);
	}

	public static function renameAlbumDirectory(string $baseDir, string $oldAlbumId, string $newAlbumId)
	{
		if($oldAlbumId !== $newAlbumId) // Only rename when needed
			rename($baseDir."/".$oldAlbumId, $baseDir."/".$newAlbumId);
	}
}
?>
