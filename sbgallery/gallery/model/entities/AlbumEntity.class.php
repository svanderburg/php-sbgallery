<?php
class AlbumEntity
{
	public static function queryAll(PDO $dbh, $albumsTable = "albums")
	{
		$stmt = $dbh->prepare("select * from ".$albumsTable." order by ALBUM_ID");
		if(!$stmt->execute())
			throw new Exception($stmt->errorInfo()[2]);
		return $stmt;
	}

	public static function queryOne(PDO $dbh, $id, $albumsTable = "albums")
	{
		$stmt = $dbh->prepare("select * from ".$albumsTable." where ALBUM_ID = ?");
		if(!$stmt->execute(array($id)))
			throw new Exception($stmt->errorInfo()[2]);
		return $stmt;
	}

	public static function insert(PDO $dbh, array $album, $albumsTable = "albums", $thumbnailsTable = "thumbnails")
	{
		$dbh->beginTransaction();

		$stmt = $dbh->prepare("select max(Ordering) from ".$albumsTable);
		if(!$stmt->execute())
		{
			$dbh->rollBack();
			throw new Exception($stmt->errorInfo()[2]);
		}

		if(($row = $stmt->fetch()) === false)
			$ordering = 1;
		else
			$ordering = $row[0] + 1;

		$stmt = $dbh->prepare("insert into ".$albumsTable." values (?, ?, ?, ?, ?)");
		if(!$stmt->execute(array($album["ALBUM_ID"], $album["Title"], $album["Visible"], $album["Description"], $ordering)))
		{
			$dbh->rollBack();
			throw new Exception($stmt->errorInfo()[2]);
		}

		$stmt = $dbh->prepare("insert into ".$thumbnailsTable." values (?, NULL)");
		if(!$stmt->execute(array($album["ALBUM_ID"])))
		{
			$dbh->rollBack();
			throw new Exception($stmt->errorInfo()[2]);
		}

		$dbh->commit();
	}

	public static function update(PDO $dbh, array $album, $id, $albumsTable = "albums")
	{
		$stmt = $dbh->prepare("update ".$albumsTable." set ".
			"ALBUM_ID = ?, ".
			"Title = ?, ".
			"Visible = ?, ".
			"Description = ? ".
			"where ALBUM_ID = ?");

		if(!$stmt->execute(array($album["ALBUM_ID"], $album["Title"], $album["Visible"], $album["Description"], $id)))
			throw new Exception($stmt->errorInfo()[2]);
	}

	public static function remove(PDO $dbh, $id, $albumsTable = "albums")
	{
		$stmt = $dbh->prepare("delete from ".$albumsTable." where ALBUM_ID = ?");
		if(!$stmt->execute(array($id)))
			throw new Exception($stmt->errorInfo()[2]);
	}

	public static function queryThumbnails(PDO $dbh, $displayOnlyVisible, $albumsTable = "albums", $thumbnailsTable = "thumbnails", $picturesTable = "pictures")
	{
		$stmt = $dbh->prepare("select ".$thumbnailsTable.".ALBUM_ID, ".$thumbnailsTable.".PICTURE_ID, ".$albumsTable.".Title, ".$picturesTable.".FileType ".
			"from ".$thumbnailsTable." ".
			"inner join ".$albumsTable." on ".$thumbnailsTable.".ALBUM_ID = ".$albumsTable.".ALBUM_ID ".
			"left outer join ".$picturesTable." on ".$thumbnailsTable.".PICTURE_ID = ".$picturesTable.".PICTURE_ID ".
			($displayOnlyVisible ? "where ".$albumsTable.".Visible = 1 " : "").
			"order by ".$albumsTable.".Ordering desc");
		if(!$stmt->execute())
			throw new Exception($stmt->errorInfo()[2]);
		return $stmt;
	}

	public static function queryPictureCount(PDO $dbh, $albumId, $picturesTable = "pictures")
	{
		$stmt = $dbh->prepare("select count(*) from ".$picturesTable." where ALBUM_ID = ?");
		if(!$stmt->execute(array($albumId)))
			throw new Exception($stmt->errorInfo()[2]);
		return $stmt;
	}

	public static function setThumbnail(PDO $dbh, $pictureId, $albumId, $thumbnailsTable = "thumbnails")
	{
		$stmt = $dbh->prepare("update ".$thumbnailsTable." set PICTURE_ID = ? where ALBUM_ID = ?");

		if(!$stmt->execute(array($pictureId, $albumId)))
			throw new Exception($stmt->errorInfo()[2]);
	}

	private static function switchAlbumOrdering(PDO $dbh, array $firstAlbum, array $secondAlbum, $albumsTable = "albums")
	{
		$stmt = $dbh->prepare("update ".$albumsTable." set Ordering = ? where ALBUM_ID = ?");
		if(!$stmt->execute(array($secondAlbum["Ordering"], $firstAlbum["ALBUM_ID"])))
		{
			$dbh->rollBack();
			throw new Exception($stmt->errorInfo()[2]);
		}

		$stmt = $dbh->prepare("update ".$albumsTable." set Ordering = ? where ALBUM_ID = ?");
		if(!$stmt->execute(array($firstAlbum["Ordering"], $secondAlbum["ALBUM_ID"])))
		{
			$dbh->rollBack();
			throw new Exception($stmt->errorInfo()[2]);
		}
	}

	public static function moveLeft(PDO $dbh, $id, $albumsTable = "albums")
	{
		$dbh->beginTransaction();

		try
		{
			$stmt = AlbumEntity::queryOne($dbh, $id, $albumsTable);

			if(($album = $stmt->fetch()) !== false)
			{
				$stmt = $dbh->prepare("select ALBUM_ID, Ordering ".
					"from ".$albumsTable." ".
					"where Ordering in (select min(Ordering) from ".$albumsTable." where Ordering > ?)");
				
				if(!$stmt->execute(array($album["Ordering"])))
				{
					$dbh->rollBack();
					throw new Exception($stmt->errorInfo()[2]);
				}

				if(($previousAlbum = $stmt->fetch()) !== false)
					AlbumEntity::switchAlbumOrdering($dbh, $album, $previousAlbum, $albumsTable);
			}

			$dbh->commit();
		}
		catch(Exception $ex)
		{
			throw $ex;
			$dbh->rollBack();
		}
	}

	public static function moveRight(PDO $dbh, $id, $albumsTable = "albums")
	{
		$dbh->beginTransaction();

		try
		{
			$stmt = AlbumEntity::queryOne($dbh, $id, $albumsTable);

			if(($album = $stmt->fetch()) !== false)
			{
				$stmt = $dbh->prepare("select ALBUM_ID, Ordering ".
					"from ".$albumsTable." ".
					"where Ordering in (select max(Ordering) from ".$albumsTable." where Ordering < ?)");
				
				if(!$stmt->execute(array($album["Ordering"])))
				{
					$dbh->rollBack();
					throw new Exception($stmt->errorInfo()[2]);
				}

				if(($nextAlbum = $stmt->fetch()) !== false)
					AlbumEntity::switchAlbumOrdering($dbh, $album, $nextAlbum, $albumsTable);
			}

			$dbh->commit();
		}
		catch(Exception $ex)
		{
			throw $ex;
			$dbh->rollBack();
		}
	}
}
?>
