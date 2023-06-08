<?php
namespace Examples\CRUDTranslated\Model\Page;
use PDO;
use SBGallery\Model\Page\GalleryPage;
use SBGallery\Model\Page\Content\GalleryContents;
use SBGallery\Model\Settings\EditorSettings;
use SBGallery\Model\Page\Settings\GalleryPageSettings;
use SBGallery\Model\Page\Settings\Labels\GalleryPageLabels;
use SBGallery\Model\Page\Settings\Labels\AlbumPageLabels;
use SBGallery\Model\Page\Settings\Labels\PicturePageLabels;
use SBGallery\Model\Settings\Labels\GalleryLabels;
use SBGallery\Model\Settings\Labels\AlbumLabels;
use SBGallery\Model\Settings\Labels\PictureLabels;
use SBGallery\Model\GalleryPermissionChecker;
use Examples\CRUDTranslated\Model\MyGalleryPermissionChecker;

class MyGalleryPage extends GalleryPage
{
	public function __construct(PDO $dbh)
	{
		parent::__construct($dbh, new GalleryPageSettings(
			"gallery", /* base URL */
			"gallery", /* base directory */
			160, 160, /* Thumbnail size */
			1280, 1280, /* Picture size */
			0666, /* File permissions */
			0777, /* Directory permissions */
			true, /* Display anchors */
			"image/gallery", /* Icons path */
			"album", /* Album anchor prefix */
			"picture", /* Picture anchor prefix */
			new GalleryPageLabels(
				"Fotoalbum",
				"Ongeldig album met id:",
				"Ongeldige operatie:"
			),
			new AlbumPageLabels(
				"Album",
				"Ongeldig album met id:",
				"Ongeldige operatie:",
				"Nieuw album",
				"Album toevoegen",
				"Album bijwerken",
				"Album verwijderen",
				"Verplaats links",
				"Verplaats rechts",
				"Meerdere toevoegen",
				"Meerdere afbeeldingen toevoegen"
			),
			new PicturePageLabels(
				"Afbeelding",
				"Nieuwe afbeelding",
				"Afbeelding toevoegen",
				"Afbeelding bijwerken",
				"Afbeelding verwijderen",
				"Afbeelding opschonen",
				"Verplaats links",
				"Verplaats rechts",
				"Stel afbeelding in als miniatuur"
			),
			new GalleryLabels(
				"Album toevoegen",
				"Verplaats links",
				"Verplaats rechts",
				"Verwijderen",
				"Kan album niet vinden met ID:"
			),
			new AlbumLabels(
				"Id",
				"Titel",
				"Zichtbaar",
				"Beschrijving",
				"Afbeelding toevoegen",
				"Meerdere toevoegen",
				"Verplaats links",
				"Verplaats rechts",
				"Instellen als miniatuur",
				"Verwijderen",
				"Verstuur",
				"Verstuur bestanden",
				"Ongeldig bestand:",
				"Een of meer velden zijn ongeldig en gemarkeerd met een rode kleur",
				"Deze waarde is incorrect!",
				"Kan afbeelding niet vinden met ID: "
			),
			new PictureLabels(
				"Id",
				"Titel",
				"Beschrijving",
				"Afbeelding",
				"Verstuur",
				"Vorige",
				"Volgende",
				"Afbeelding opschonen",
				"Een of meer velden zijn ongeldig en gemarkeerd met een rode kleur",
				"Deze waarde is incorrect!"
			),
			new EditorSettings(
				"editor1",
				null,
				"image/editor",
				40, 20
			),
			new EditorSettings(
				"editor1",
				null,
				"image/editor",
				40, 20
			),
			"dutcheditorlabels.js",
			"dutcheditorlabels.js"
		), new MyGalleryPermissionChecker(), new GalleryContents(null, "contents", "HTML", array("gallery.css")));
	}
}
?>
