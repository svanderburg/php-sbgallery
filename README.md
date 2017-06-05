php-sbgallery
=============
This package contains a simple embeddable gallery that can be integrated into a
web application. It supports the following features:

* Displaying a collection of albums, collections of pictures
* A gallery's content can be managed by users
* Storing GIF, JPEG and PNG images
* Automatic scaling of images
* Embedded HTML editor for managing descriptions
* Bulk importer
* Translatable to a language of choice

Prerequisites
=============
The gallery is built on top of the functionality provided by the following
packages:

- [php-sbdata](https://github.com/svanderburg/php-sbdata)
- [php-sbeditor](https://github.com/svanderburg/php-sbeditor)
- [php-sbcrud](https://github.com/svanderburg/php-sbcrud) (optional)

Usage
=====
There are two ways to use the functionality exposed by this package. It can be
controlled directly by using the low-level API requiring the user to manually
compose object instances representing parts of the gallery, displaying the
corresponding views and implement controllers to modify the gallery's state.

Alternatively, this package provides a high level API exposing `Page` instances
that can be integrated with the `php-sbcrud` framework. When using the CRUD API
you only need to compose a configuration once. The corresponding pages that
provide all functionality are generated automatically.

Low-level use cases
===================
The low-level API provides direct control over the gallery's state.

Composing a gallery
-------------------
We can compose a gallery (an object displaying a set of albums and their
thumbnails) as follows:

```php
require_once("gallery/model/Gallery.class.php");

$dbh = new PDO("mysql:host=localhost;dbname=gallery", "user", "password", array(
    PDO::ATTR_PERSISTENT => true
));

Gallery $gallery = new Gallery(
    $dbh,
    "/gallery", // Base URL where all the gallery's images reside
    "/album.php", // URL that redirects to a page displaying an album
    "/picture.php", // URL that redirects to a page displaying a picture
    "/multiplepictures.php", // URL that redirects to a page providing an image uploader
    "/icons", // Path where the gallery's icons reside
    dirname($_SERVER["SCRIPT_NAME"])."/gallery", // Base path to all images in the gallery
    160, 120, // Dimensions of the thumbnail image
    1280, 960, // Dimension of the real picture
    $galleryLabels = null, // Array that translates the gallery labels into another language (optional)
    $albumLabels = null, // Array that translates the album labels into another language (optional)
    $pictureLabels = null, // Array that translates the picture labels into another language (optional)
    $editorSettings = null, // Array that modifies the properties of the embedded editor (optional)
    $filePermissions = 0777, // The filesystem permissions for the gallery artifacts (optional)
    $albumsTable = "albums", // Name of the album table (optional)
    $thumbnailsTable = "thumbnails", // Name of thumbnails table (optional)
    $picturesTable = "pictures" // Name of the pictures table (optional)
);
```

A gallery requires various configuration properties, such a database connection,
the location where the gallery's artifacts are stored, the locations of the
display pages and the dimensions of the images.

A gallery can be displayed in read-only mode as follows:

```php
require_once("gallery/view/html/gallery.inc.php");

displayGallery($gallery);
```

To make the gallery writable so that the user can modify its contents, we can
use:

```php
displayEditableGallery($gallery);
```

Composing an album
------------------
An album (that displays a set of pictures and their thumbnails) can be managed
by composing an album object that takes similar parameters as the gallery
constructor.

Alternatively, it is possible to automatically construct an album with identical
settings to a gallery instance:

```php
require_once("gallery/model/Album.class.php");

$album = $gallery->constructAlbum();
```

We can execute CRUD operations on an album instance, by invoking the following
functions:

```php
$album->create();
$album->insert($_REQUEST);
$album->update($_REQUEST);
$album->remove($albumId);
$album->moveLeft($albumId);
$album->moveRight($albumId);
$album->view($albumId);
```

Typically, you want to execute these functions before any HTML output is
rendered.

To determine which kind of CRUD operation you need to execute, every UI element
of the album (such as hyperlinks and forms) send an `__operation` request
parameter along with it ($_REQUEST["create_album"]) that can be used to
determine what CRUD operation to execute. For example, `create_album`
corresponds to creating an album.

We can display an album in read-only mode as follows:

```php
require_once("gallery/view/html/album.inc.php");

displayAlbum($album);
```

Or in write mode by:

```php
displayEditableAlbum($album,
    "Submit",
    "One or more fields are incorrectly specified and marked with a red color!",
    "This field is incorrectly specified!");
```

The page displaying the above functions should typically correspond to the album
display URL shown in the gallery construction example shown in the previous
section.

We can also display an uploader that can be used to bulk insert a collection of
images:

```php
displayPicturesUploader($albumId);
```

The page that displays the uploader should correspond to the multiple images
upload URL shown in the previous section.

Picture
-------
An individual picture can be managed by composing a picture object that takes
similar parameters as the gallery and album constructors.

Alternatively, it is possible to automatically construct a picture with identical
settings to an album instance:

```php
require_once("gallery/model/Picture.class.php");

$picture = $album->constructPicture($albumId);
```

We can execute CRUD operations on a picture instance, by invoking the following
functions:

```php
$picture->create($albumId);
$picture->insert($_REQUEST);
$picture->update($_REQUEST)
$picture->remove($pictureId, $albumId);
$picture->removePictureImage($pictureId, $albumId);
$picture->setAsThumbnail($pictureId, $albumId);
$picture->moveLeft($pictureId, $albumId);
$picture->moveRight($pictureId, $albumId);
```

Similarly to albums, any picture UI elements sends a `$_REQUEST["__operation"]`
parameter that can be used to determine what CRUD operation to execute.

We can display a picture in read-only mode as follows:

```php
require_once("gallery/view/html/picture.inc.php");

displayPicture($picture);
```

And in write mode, by:

```php
displayEditablePicture($picture,
    "Submit",
    "One or more fields are incorrectly specified and marked with a red color!",
    "This field is incorrectly specified!");
```

High-level API usage
====================
The high-level API can be used to automatically add the entire page structure to
an application layout including authentication checks.

First, we must construct our own permission checker that determines whether a
user has write permissions:

```php
class MyGalleryPermissionChecker implements GalleryPermissionChecker
{
    public function checkWritePermissions()
    {
        return ($_COOKIE["Password"] === "secret");
    }
}
```

In the above checker, you must implement your own password policy, such as
integrating with a database or external authentication system.

Then we must construct our sub class from `GalleryPage` that provides a Gallery
object and permission checker:

```php
class MyGalleryPage extends GalleryPage
{
    public function constructGallery()
    {
        return new Gallery(array(...));
    }

    public function constructGalleryPermissionChecker()
    {
        return new MyGalleryPermissionChecker();
    }
}
```

We can add a page instance of our sub class to the application layout as
follows:

```php
$application = new Application(
    /* Title */
    "Gallery",

    /* CSS stylesheets */
    array("default.css"),

    /* Sections */
    array(
        "header" => new StaticSection("header.inc.php"),
        "menu" => new MenuSection(0),
        "contents" => new ContentsSection(true)
    ),

    /* Pages */
    new StaticContentPage("Home", new Contents("home.inc.php"), array(
        "404" => new HiddenStaticContentPage("Page not found", new Contents("error/404.inc.php")),

        "home" => new PageAlias("Home", ""),
        "gallery" => new MyGalleryPage()
    ))
);
```
Adding the page object to the application layout allows us to:

* Access the gallery through: http://localhost/gallery
* Access each album through: http://localhost/gallery/<albumId>
* Access each picture through: http://localhost/gallery/<albumId>/<pictureId>

And we can access CRUD operations (other than reading) by appending a
`__operation` request paramer, such as:

```
http://localhost/gallery/myalbum/mypicture?__operation=moveleft_picture
```

By invoking the above URL (and having met the appropriate authentication
criteria) the picture will be moved left in the album.

Examples
========
This package contains two examples in the `example/` sub folder:

* The `lowlevel` folder contains an example using the low-level API. It provides
  a gallery, album, picture and multiple pictures upload page.
* The `crud` folder contains an example using the high-level API. It provides
  a gallery with a useless authentication check (the `view=1` GET parameter).

API documentation
=================
This package includes API documentation, which can be generated with
[Doxygen](http://www.doxygen.org). The Makefile in this package contains a `doc`
target and produces the corresponding HTML files in `apidoc`:

    $ make doc

License
=======
The contents of this package is available under the
[Apache Software License](http://www.apache.org/licenses/LICENSE-2.0.html)
version 2.0

Acknowledgements
================
The icons used in this project are borrowed from the
[Tango icon library](http://tango.freedesktop.org). The icons are in the public
domain.
