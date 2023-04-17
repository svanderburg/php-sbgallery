<?php
namespace SBGallery\Model\Settings\URLGenerator;

class SimplePictureURLGenerator implements PictureURLGenerator
{
	public string $operationParam;

	public function __construct(string $operationParam = "__operation")
	{
		$this->operationParam = $operationParam;
	}

	private function generatePreviousOrNextPictureURL(string $albumId, string $pictureId): string
	{
		return "picture.php?ALBUM_ID=".rawurlencode($albumId)."&amp;PICTURE_ID=".rawurlencode($pictureId);
	}

	public function generatePreviousPictureURL(string $albumId, string $pictureId): string
	{
		return $this->generatePreviousOrNextPictureURL($albumId, $pictureId);
	}

	public function generateNextPictureURL(string $albumId, string $pictureId): string
	{
		return $this->generatePreviousOrNextPictureURL($albumId, $pictureId);
	}

	public function generatePictureFormURL(string $albumId, ?string $pictureId): ?string
	{
		$result = "picture.php?ALBUM_ID=".rawurlencode($albumId);

		if($pictureId !== null)
			$result .= "&PICTURE_ID=".rawurlencode($pictureId);

		return $result;
	}

	public function generateClearPictureURL(string $albumId, string $pictureId): string
	{
		return "picture.php?ALBUM_ID=".rawurlencode($albumId)."&amp;PICTURE_ID=".rawurlencode($pictureId)."&amp;".$this->operationParam."=clear_picture";
	}
}
?>
