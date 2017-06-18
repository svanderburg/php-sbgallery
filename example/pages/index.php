<?php
error_reporting(E_STRICT | E_ALL);

set_include_path("./lib/sblayout:./lib/sbdata:./lib/sbcrud:./lib/sbeditor:./lib/sbgallery:./includes");

require_once("layout/model/Application.class.php");
require_once("layout/model/section/StaticSection.class.php");
require_once("layout/model/section/MenuSection.class.php");
require_once("layout/model/section/ContentsSection.class.php");
require_once("layout/model/page/StaticContentPage.class.php");
require_once("layout/model/page/PageAlias.class.php");
require_once("layout/model/page/HiddenStaticContentPage.class.php");

require_once("layout/view/html/index.inc.php");
require_once("model/MyGalleryPage.class.php");

$galleryPage = new MyGalleryPage();

$application = new Application(
	/* Title */
	"Gallery layout integration",

	/* CSS stylesheets */
	array("default.css"),

	/* Sections */
	array(
		"header" => new StaticSection("header.inc.php"),
		"menu" => new MenuSection(0),
		"submenu" => new StaticSection("submenu.inc.php"),
		"contents" => new ContentsSection(true)
	),

	/* Pages */
	new StaticContentPage("Home", new Contents("home.inc.php"), array(
		"404" => new HiddenStaticContentPage("Page not found", new Contents("error/404.inc.php")),

		"home" => new PageAlias("Home", ""),
		"gallery" => $galleryPage
	))
);

displayRequestedPage($application);
?>