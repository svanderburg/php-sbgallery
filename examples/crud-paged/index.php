<?php
error_reporting(E_STRICT | E_ALL);

require_once("../../vendor/autoload.php");

use SBLayout\Model\Application;
use SBLayout\Model\Section\ContentsSection;
use SBLayout\Model\Section\MenuSection;
use SBLayout\Model\Section\StaticSection;
use SBLayout\Model\Page\HiddenStaticContentPage;
use SBLayout\Model\Page\PageAlias;
use SBLayout\Model\Page\StaticContentPage;
use SBLayout\Model\Page\Content\Contents;
use Examples\CRUDPaged\Model\Page\MyGalleryPage;

require_once("includes/config.php");

$dbh = new PDO($config["dbDsn"], $config["dbUsername"], $config["dbPassword"], array(
	PDO::ATTR_PERSISTENT => true
));

$application = new Application(
	/* Title */
	"Gallery",

	/* CSS stylesheets */
	array("default.css"),

	/* Sections */
	array(
		"header" => new StaticSection("header.php"),
		"menu" => new MenuSection(0),
		"contents" => new ContentsSection(true)
	),

	/* Pages */
	new StaticContentPage("Home", new Contents("home.php"), array(
		"400" => new HiddenStaticContentPage("Bad request", new Contents("error/400.php")),
		"404" => new HiddenStaticContentPage("Page not found", new Contents("error/404.php")),

		"home" => new PageAlias("Home", ""),
		"gallery" => new MyGalleryPage($dbh)
	))
);

\SBLayout\View\HTML\displayRequestedPage($application);
?>
