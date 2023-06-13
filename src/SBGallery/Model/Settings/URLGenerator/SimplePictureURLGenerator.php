<?php
namespace SBGallery\Model\Settings\URLGenerator;

class SimplePictureURLGenerator implements PictureURLGenerator
{
	public string $operationParam;

	public function __construct(string $operationParam = "__operation")
	{
		$this->operationParam = $operationParam;
	}

	private function generatePreviousOrNextPictureURL(string $albumId, string $pictureId, string $argSeparator): string
	{
		return "picture.php?ALBUM_ID=".rawurlencode($albumId).$argSeparator."PICTURE_ID=".rawurlencode($pictureId);
	}

	public function generatePreviousPictureURL(string $albumId, string $pictureId, string $argSeparator): string
	{
		return $this->generatePreviousOrNextPictureURL($albumId, $pictureId, $argSeparator);
	}

	public function generateNextPictureURL(string $albumId, string $pictureId, string $argSeparator): string
	{
		return $this->generatePreviousOrNextPictureURL($albumId, $pictureId, $argSeparator);
	}

	public function generatePictureFormURL(string $albumId, ?string $pictureId, string $argSeparator): ?string
	{
		$result = "picture.php?ALBUM_ID=".rawurlencode($albumId);

		if($pictureId !== null)
			$result .= $argSeparator."PICTURE_ID=".rawurlencode($pictureId);

		return $result;
	}

	public function generateClearPictureURL(string $albumId, string $pictureId, string $argSeparator): string
	{
		return "picture.php?ALBUM_ID=".rawurlencode($albumId).$argSeparator."PICTURE_ID=".rawurlencode($pictureId).$argSeparator.$this->operationParam."=clear_picture";
	}
}
?>
