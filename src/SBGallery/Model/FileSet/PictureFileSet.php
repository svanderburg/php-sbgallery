<?php
namespace SBGallery\Model\FileSet;

class PictureFileSet
{
	private static function scaleImageInBox(string $sourceFile, string $destinationFile, string $fileType, int $boxWidth, int $boxHeight, int $filePermissions): void
	{
		/* Get dimensions of the source image */
		list($width, $height) = getimagesize($sourceFile);

		/*
		 * Take the longest edge (width or height) and adapt the
 		 * width or height to the box size
		 */

		if($width >= $height)
		{
			$newWidth = $width < $boxWidth ? $width : $boxWidth;
			$newHeight = round($height * ($newWidth / $width));
		}
		else
		{
			$newHeight = $height < $boxHeight ? $height : $boxHeight;
			$newWidth = round($width * ($newHeight / $height));
		}

		/* Open the uploaded image */
		switch($fileType)
		{
			case "gif":
				$sourceImage = imagecreatefromgif($sourceFile);
				break;
			case "jpg":
				$sourceImage = imagecreatefromjpeg($sourceFile);
				break;
			case "png":
				$sourceImage = imagecreatefrompng($sourceFile);
				break;
		}

		/* Create a scaled image */
		$destinationImage = imagecreatetruecolor($newWidth, $newHeight);
		imagecopyresampled($destinationImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

		/* Write scaled image */
		switch($fileType)
		{
			case "gif":
				imagegif($destinationImage, $destinationFile);
				break;
			case "jpg":
				imagejpeg($destinationImage, $destinationFile, 98);
				break;
			case "png":
				imagepng($destinationImage, $destinationFile);
				break;
		}

		chmod($destinationFile, $filePermissions);
	}

	private static function composePicturePath(string $albumDir, string $type, string $id, string $fileType): string
	{
		return $albumDir."/".$type."/".$id.".".$fileType;
	}

	public static function generatePictures(string $sourceFile, string $albumDir, string $id, ?string $fileType, int $thumbnailWidth, int $thumbnailHeight, int $pictureWidth, int $pictureHeight, int $filePermissions): void
	{
		if($fileType !== null)
		{
			$destinationFile = PictureFileSet::composePicturePath($albumDir, "thumbnails", $id, $fileType);
			PictureFileSet::scaleImageInBox($sourceFile, $destinationFile, $fileType, $thumbnailWidth, $thumbnailHeight, $filePermissions);

			$destinationFile = PictureFileSet::composePicturePath($albumDir, "pictures", $id, $fileType);
			PictureFileSet::scaleImageInBox($sourceFile, $destinationFile, $fileType, $pictureWidth, $pictureHeight, $filePermissions);
		}
	}

	public static function determineImageType(string $mimeType): ?string
	{
		switch($mimeType)
		{
			case "image/gif":
				return "gif";
			case "image/jpeg":
				return "jpg";
			case "image/png":
				return "png";
			default:
				return null;
		}
	}

	public static function determineImageFileType(string $key): ?string
	{
		if(array_key_exists($key, $_FILES))
			return PictureFileSet::determineImageType($_FILES[$key]["type"]);
		else
			return null;
	}

	public static function deletePictures(string $albumDir, string $id, ?string $fileType): void
	{
		if($fileType !== null)
		{
			unlink($albumDir."/thumbnails/".$id.".".$fileType);
			unlink($albumDir."/pictures/".$id.".".$fileType);
		}
	}

	public static function renamePictures(string $albumDir, string $oldPictureId, string $newPictureId, string $oldFileType, string $newFileType): void
	{
		$fileType = ($oldFileType === null) ? $newFileType : $oldFileType;
		
		if($fileType !== null)
		{
			$oldPath = PictureFileSet::composePicturePath($albumDir, "thumbnails", $oldPictureId, $fileType);
			$newPath = PictureFileSet::composePicturePath($albumDir, "thumbnails", $newPictureId, $fileType);
			rename($oldPath, $newPath);

			$oldPath = PictureFileSet::composePicturePath($albumDir, "pictures", $oldPictureId, $fileType);
			$newPath = PictureFileSet::composePicturePath($albumDir, "pictures", $newPictureId, $fileType);
			rename($oldPath, $newPath);
		}
	}
}
?>
