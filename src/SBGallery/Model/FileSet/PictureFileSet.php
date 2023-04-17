<?php
namespace SBGallery\Model\FileSet;
use Exception;

/**
 * Contains methods that manage the file operations of a picture
 */
class PictureFileSet
{
	private static function scaleImageInBox(string $sourceFile, string $destinationFile, string $fileType, int $boxWidth, int $boxHeight, int $filePermissions): void
	{
		/* Get dimensions of the source image */
		if((list($width, $height) = getimagesize($sourceFile)) === false)
			throw new Exception("Cannot get the image size of: ".$sourceFile);

		/*
		 * Take the longest edge (width or height) and adapt the
 		 * width or height to the box size
		 */

		if($width >= $height)
		{
			$newWidth = $width < $boxWidth ? $width : $boxWidth;
			$newHeight = round($height * $newWidth / $width);
		}
		else
		{
			$newHeight = $height < $boxHeight ? $height : $boxHeight;
			$newWidth = round($width * $newHeight / $height);
		}

		/* Open the uploaded image */
		switch($fileType)
		{
			case "gif":
				$sourceImage = imagecreatefromgif($sourceFile);
				if($sourceImage === false)
					throw new Exception("Cannot create GIF image from: ".$sourceFile);
				break;
			case "jpg":
				$sourceImage = imagecreatefromjpeg($sourceFile);
				if($sourceImage === false)
					throw new Exception("Cannot create JPEG image from: ".$sourceFile);
				break;
			case "png":
				$sourceImage = imagecreatefrompng($sourceFile);
				if($sourceImage === false)
					throw new Exception("Cannot create PNG image from: ".$sourceFile);
				break;
			default:
				throw new Exception("Uploaded image: ".$sourceFile." is of unknown type");
		}

		/* Create a scaled image */
		$destinationImage = imagecreatetruecolor($newWidth, $newHeight);
		if($destinationImage === false)
			throw new Exception("Cannot create image: ".$destinationFile);

		if(imagecopyresampled($destinationImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height) === false)
			throw new Exception("Cannot scale image: ".$sourceFile);

		/* Write scaled image */
		switch($fileType)
		{
			case "gif":
				if(imagegif($destinationImage, $destinationFile) === false)
					throw new Exception("Cannot write GIF image: ".$destinationFile);
				break;
			case "jpg":
				if(imagejpeg($destinationImage, $destinationFile, 98) === false)
					throw new Exception("Cannot write JPEG image: ".$destinationFile);
				break;
			case "png":
				if(imagepng($destinationImage, $destinationFile) === false)
					throw new Exception("Cannot write PNG image: ".$destinationFile);
				break;
		}

		if(chmod($destinationFile, $filePermissions) === false)
			throw new Exception("Cannot change file permissions of file: ".$destinationFile);
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

	private static function delete(string $filePath): void
	{
		if(file_exists($filePath))
		{
			if(unlink($filePath) === false)
				throw new Exception("Cannot delete: ".$filePath);
		}
	}

	public static function deletePictures(string $albumDir, string $id, ?string $fileType): void
	{
		if($fileType !== null)
		{
			$thumbnailPath = $albumDir."/thumbnails/".$id.".".$fileType;
			PictureFileSet::delete($thumbnailPath);

			$picturePath = $albumDir."/pictures/".$id.".".$fileType;
			PictureFileSet::delete($picturePath);
		}
	}

	public static function renamePictures(string $albumDir, string $oldPictureId, string $newPictureId, ?string $oldFileType, ?string $newFileType): void
	{
		$fileType = ($oldFileType === null) ? $newFileType : $oldFileType;

		if($fileType !== null)
		{
			$oldPath = PictureFileSet::composePicturePath($albumDir, "thumbnails", $oldPictureId, $fileType);
			$newPath = PictureFileSet::composePicturePath($albumDir, "thumbnails", $newPictureId, $fileType);
			if(rename($oldPath, $newPath) === false)
				throw new Exception("Cannot rename: ".$oldPath." to: ".$newPath);

			$oldPath = PictureFileSet::composePicturePath($albumDir, "pictures", $oldPictureId, $fileType);
			$newPath = PictureFileSet::composePicturePath($albumDir, "pictures", $newPictureId, $fileType);
			if(rename($oldPath, $newPath) === false)
				throw new Exception("Cannot rename: ".$oldPath." to: ".$newPath);
		}
	}
}
?>
