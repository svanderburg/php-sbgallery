<?php
namespace SBGallery\Model\FileSet;
use Exception;

class AlbumFileSet
{
	private static function createDirectoryWithPermissions(string $filePath, int $permissions): void
	{
		if(mkdir($filePath, $permissions) === false)
			throw new Exception("Cannot create directory: ".$filePath);
	}

	public static function createAlbumDirectories(string $baseDir, string $albumId, int $dirPermissions): void
	{
		$albumDir = $baseDir."/".$albumId;
		AlbumFileSet::createDirectoryWithPermissions($albumDir, $dirPermissions);

		$thumbnailsDir = $albumDir."/thumbnails";
		AlbumFileSet::createDirectoryWithPermissions($thumbnailsDir, $dirPermissions);

		$picturesDir = $albumDir."/pictures";
		AlbumFileSet::createDirectoryWithPermissions($picturesDir, $dirPermissions);
	}

	private static function removeEmptyDirectory(string $filePath): void
	{
		if(file_exists($filePath))
		{
			if(rmdir($filePath) === false)
				throw new Exception("Cannot remove empty directory: ".$filePath);
		}
	}

	public static function removeAlbumDirectories(string $baseDir, string $albumId): void
	{
		$albumDir = $baseDir."/".$albumId;

		$picturesDir = $albumDir."/pictures";
		AlbumFileSet::removeEmptyDirectory($picturesDir);

		$thumbnailsDir = $albumDir."/thumbnails";
		AlbumFileSet::removeEmptyDirectory($thumbnailsDir);

		AlbumFileSet::removeEmptyDirectory($albumDir);
	}

	private static function rename(string $oldFilePath, string $newFilePath): void
	{
		if(rename($oldFilePath, $newFilePath) === false)
			throw new Exception("Cannot rename: ".$oldFilePath." to: ".$newFilePath);
	}

	public static function renameAlbumDirectory(string $baseDir, string $oldAlbumId, string $newAlbumId): void
	{
		if($oldAlbumId !== $newAlbumId) // Only rename when needed
			AlbumFileSet::rename($baseDir."/".$oldAlbumId, $baseDir."/".$newAlbumId);
	}
}
?>
