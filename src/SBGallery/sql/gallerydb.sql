create table albums
(
	ALBUM_ID           VARCHAR(255)  NOT NULL check(ALBUM_ID <> ''),
	Title              VARCHAR(255)  NOT NULL check(Title <> ''),
	Visible            TINYINT       NOT NULL,
	Description        VARCHAR(255),
	Ordering           INTEGER       NOT NULL,
	PRIMARY KEY(ALBUM_ID)
);

create index album_ordering on albums(Ordering);

create table pictures
(
	PICTURE_ID    VARCHAR(255)  NOT NULL check(PICTURE_ID <> ''),
	Title         VARCHAR(255)  NOT NULL check(Title <> ''),
	Description   VARCHAR(255),
	FileType      VARCHAR(3)    check(FileType in ('gif', 'jpg', 'png')),
	ALBUM_ID      VARCHAR(255)  NOT NULL,
	Ordering      INTEGER       NOT NULL,
	PRIMARY KEY(PICTURE_ID, ALBUM_ID),
	FOREIGN KEY(ALBUM_ID) references albums(ALBUM_ID) on update cascade on delete restrict
);

create index pictures_ordering on pictures(Ordering);

create table thumbnails
(
	ALBUM_ID    VARCHAR(255) NOT NULL,
	PICTURE_ID  VARCHAR(255),
	PRIMARY KEY(ALBUM_ID),
	FOREIGN KEY(ALBUM_ID) references albums(ALBUM_ID) on update cascade on delete cascade,
	FOREIGN KEY(PICTURE_ID) references pictures(PICTURE_ID) on update cascade on delete set null
);
