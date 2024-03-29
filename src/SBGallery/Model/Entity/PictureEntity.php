<?php
namespace SBGallery\Model\Entity;
use Exception;
use PDO;
use PDOStatement;

/**
 * Contains methods that are related to the pictures table
 */
class PictureEntity
{
	public static function queryAll(PDO $dbh, string $albumId, string $picturesTable = "pictures"): PDOStatement
	{
		$stmt = $dbh->prepare("select * from ".$picturesTable." where ALBUM_ID = ? order by Ordering");
		if(!$stmt->execute(array($albumId)))
			throw new Exception($stmt->errorInfo()[2]);
		return $stmt;
	}

	public static function queryPage(PDO $dbh, string $albumId, int $page, int $pageSize, string $picturesTable = "pictures"): PDOStatement
	{
		$offset = (int)($page * $pageSize);

		$stmt = $dbh->prepare("select * from ".$picturesTable." where ALBUM_ID = ? order by Ordering limit ?, ?");
		$stmt->bindParam(1, $albumId, PDO::PARAM_STR);
		$stmt->bindParam(2, $offset, PDO::PARAM_INT);
		$stmt->bindParam(3, $pageSize, PDO::PARAM_INT);

		if(!$stmt->execute())
			throw new Exception($stmt->errorInfo()[2]);
		return $stmt;
	}

	public static function queryOne(PDO $dbh, string $pictureId, string $albumId, string $picturesTable = "pictures"): PDOStatement
	{
		$stmt = $dbh->prepare("select * from ".$picturesTable." where PICTURE_ID = ? and ALBUM_ID = ?");
		if(!$stmt->execute(array($pictureId, $albumId)))
			throw new Exception($stmt->errorInfo()[2]);
		return $stmt;
	}

	public static function queryNumOfPicturesInAlbum(PDO $dbh, string $albumId, string $picturesTable = "pictures", string $suffix = ""): int
	{
		$stmt = $dbh->prepare("select count(*) from ".$picturesTable." where ALBUM_ID = ?".$suffix);

		if($stmt->execute(array($albumId)))
		{
			if(($row = $stmt->fetch()) === false)
				return 0;
			else
				return (int)$row[0];
		}
		else
			throw new Exception($stmt->errorInfo()[2]);
	}

	public static function queryNumOfVisiblePicturesInAlbum(PDO $dbh, string $albumId, string $picturesTable = "pictures"): int
	{
		return PictureEntity::queryNumOfPicturesInAlbum($dbh, $albumId, $picturesTable, " and Visible = 1");
	}

	public static function queryPredecessor(PDO $dbh, string $albumId, int $ordering, string $picturesTable = "pictures"): PDOStatement
	{
		$stmt = $dbh->prepare("select PICTURE_ID, Ordering ".
			"from ".$picturesTable." ".
			"where ALBUM_ID = ? and Ordering in (select max(Ordering) from ".$picturesTable." where ALBUM_ID = ? and Ordering < ?)");

		if(!$stmt->execute(array($albumId, $albumId, $ordering)))
			throw new Exception($stmt->errorInfo()[2]);
		return $stmt;
	}

	public static function querySuccessor(PDO $dbh, string $albumId, int $ordering, string $picturesTable = "pictures"): PDOStatement
	{
		$stmt = $dbh->prepare("select PICTURE_ID, Ordering ".
			"from ".$picturesTable." ".
			"where ALBUM_ID = ? and Ordering in (select min(Ordering) from ".$picturesTable." where ALBUM_ID = ? and Ordering > ?)");

		if(!$stmt->execute(array($albumId, $albumId, $ordering)))
			throw new Exception($stmt->errorInfo()[2]);
		return $stmt;
	}

	public static function insert(PDO $dbh, array $picture, string $picturesTable = "pictures"): void
	{
		$dbh->beginTransaction();

		$stmt = $dbh->prepare("select max(Ordering) from ".$picturesTable);
		if(!$stmt->execute())
		{
			$dbh->rollBack();
			throw new Exception($stmt->errorInfo()[2]);
		}

		if(($row = $stmt->fetch()) === false)
			$ordering = 1;
		else
			$ordering = $row[0] + 1;

		$stmt = $dbh->prepare("insert into ".$picturesTable." values (?, ?, ?, ?, ?, ?)");
		if(!$stmt->execute(array($picture["PICTURE_ID"], $picture["Title"], $picture["Description"], $picture["FileType"], $picture["ALBUM_ID"], $ordering)))
		{
			$dbh->rollBack();
			throw new Exception($stmt->errorInfo()[2]);
		}

		$dbh->commit();
	}

	public static function update(PDO $dbh, array $picture, string $pictureId, string $albumId, string $picturesTable = "pictures"): void
	{
		if($picture["FileType"] === null)
		{
			$stmt = $dbh->prepare("update ".$picturesTable." set ".
				"PICTURE_ID = ?, ".
				"Title = ?, ".
				"Description = ? ".
				"where ALBUM_ID = ? and PICTURE_ID = ?");

			if(!$stmt->execute(array($picture["PICTURE_ID"], $picture["Title"], $picture["Description"], $albumId, $pictureId)))
				throw new Exception($stmt->errorInfo()[2]);
		}
		else
		{
			$stmt = $dbh->prepare("update ".$picturesTable." set ".
				"PICTURE_ID = ?, ".
				"Title = ?, ".
				"Description = ?, ".
				"FileType = ? ".
				"where ALBUM_ID = ? and PICTURE_ID = ?");

			if(!$stmt->execute(array($picture["PICTURE_ID"], $picture["Title"], $picture["Description"], $picture["FileType"], $albumId, $pictureId)))
				throw new Exception($stmt->errorInfo()[2]);
		}
	}

	public static function resetFileType(PDO $dbh, string $pictureId, string $albumId, string $picturesTable = "pictures"): void
	{
		$stmt = $dbh->prepare("update ".$picturesTable." set ".
			"FileType = NULL ".
			"where ALBUM_ID = ? and PICTURE_ID = ?");

		if(!$stmt->execute(array($albumId, $pictureId)))
			throw new Exception($stmt->errorInfo()[2]);
	}

	public static function remove(PDO $dbh, string $pictureId, string $albumId, $picturesTable = "pictures"): void
	{
		$stmt = $dbh->prepare("delete from ".$picturesTable." where PICTURE_ID = ? and ALBUM_ID = ?");
		if(!$stmt->execute(array($pictureId, $albumId)))
			throw new Exception($stmt->errorInfo()[2]);
	}

	private static function switchPictureOrdering(PDO $dbh, string $albumId, array $firstPicture, array $secondPicture, string $picturesTable = "pictures"): void
	{
		$stmt = $dbh->prepare("update ".$picturesTable." set Ordering = ? where PICTURE_ID = ? and ALBUM_ID = ?");
		if(!$stmt->execute(array($secondPicture["Ordering"], $firstPicture["PICTURE_ID"], $albumId)))
			throw new Exception($stmt->errorInfo()[2]);

		$stmt = $dbh->prepare("update ".$picturesTable." set Ordering = ? where PICTURE_ID = ? and ALBUM_ID = ?");
		if(!$stmt->execute(array($firstPicture["Ordering"], $secondPicture["PICTURE_ID"], $albumId)))
			throw new Exception($stmt->errorInfo()[2]);
	}

	public static function moveLeft(PDO $dbh, string $pictureId, string $albumId, string $picturesTable = "pictures"): bool
	{
		$status = false;
		$dbh->beginTransaction();

		try
		{
			$stmt = PictureEntity::queryOne($dbh, $pictureId, $albumId, $picturesTable);

			if(($picture = $stmt->fetch()) !== false)
			{
				$stmt = PictureEntity::queryPredecessor($dbh, $albumId, $picture["Ordering"], $picturesTable);

				if(($previousPicture = $stmt->fetch()) !== false)
				{
					PictureEntity::switchPictureOrdering($dbh, $albumId, $picture, $previousPicture, $picturesTable);
					$status = true;
				}
			}

			$dbh->commit();
			return $status;
		}
		catch(Exception $ex)
		{
			$dbh->rollBack();
			throw $ex;
		}
	}

	public static function moveRight(PDO $dbh, string $pictureId, string $albumId, string $picturesTable = "pictures"): bool
	{
		$status = false;
		$dbh->beginTransaction();

		try
		{
			$stmt = PictureEntity::queryOne($dbh, $pictureId, $albumId, $picturesTable);

			if(($picture = $stmt->fetch()) !== false)
			{
				$stmt = PictureEntity::querySuccessor($dbh, $albumId, $picture["Ordering"], $picturesTable);

				if(($nextPicture = $stmt->fetch()) !== false)
				{
					PictureEntity::switchPictureOrdering($dbh, $albumId, $picture, $nextPicture, $picturesTable);
					$status = true;
				}
			}

			$dbh->commit();
			return $status;
		}
		catch(Exception $ex)
		{
			$dbh->rollBack();
			throw $ex;
		}
	}
}
?>
