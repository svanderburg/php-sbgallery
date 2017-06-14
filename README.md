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
* Translatable into a language of choice
* Integratable into the `php-sbeditor`, `php-sblayout` and `php-sbdata` frameworks

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
controlled directly by using the *low-level* API requiring the user to manually
compose object instances representing parts of the gallery, displaying the
corresponding views and implement controllers to modify the gallery's state.

Alternatively, this package provides a *high level* API exposing `Page` instances
that can be integrated with the `php-sbcrud` framework. When using the CRUD API
you only need to compose a configuration once. The corresponding pages that
provide all functionality are generated automatically.

In addition to embedding a gallery components into pages or pages into an
application, a gallery can also be integrated into a HTML editor managed by the
`php-sbeditor` framework and optionally into forms managed by `php-sbdata`.
With this functionality users can automatically insert pictures from the gallery
into HTML code generated by the editor.

Low-level use cases
===================
The low-level API provides direct control over the gallery's state.

Managing a gallery
------------------
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

Managing albums
---------------
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
parameter along with it (e.g. `$_REQUEST["create_album"]`) that can be used to
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

Managing pictures
-----------------
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

Similarly to albums, any picture UI element sends a `$_REQUEST["__operation"]`
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
    public function __construct()
    {
        parent::__construct("Gallery");
    }

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

* Access the gallery through: `http://localhost/gallery`
* Access each album through: `http://localhost/gallery/<albumId>`
* Access each picture through: `http://localhost/gallery/<albumId>/<pictureId>`

And we can access CRUD operations (other than reading) by appending a
`__operation` request paramer, such as:

```
http://localhost/gallery/myalbum/mypicture?__operation=moveleft_picture
```

By invoking the above URL (and having met the appropriate authentication
criteria) the picture will be moved left in the album.

Exposing a gallery as a set of sub pages
========================================
In addition to using the high level API to embed the gallery as a sub
application into an application layout, we can also expose albums as sub pages.

By using albums as pages, we can use the gallery as a simple content manager
allowing a user to dynamic construct sub pages of a web applications including
the contents of the pages.

When defining the gallery page, we must configure it to use the `pages` views
(as opposed to the `html` views) and we must override the root of the gallery
page to prevent the gallery overview from showing up:

```php
class MyGalleryPage extends GalleryPage
{
    public function __construct()
    {
        parent::__construct("Gallery", null, "pages", "gallery.inc.php");
    }

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

Composing the application layout is done in a similar way as the previous
example:

```php
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
```

In the above example, we compose a layout in a similar way except that we
globally declare the gallery page and we add a `submenu` section whose
responsibility is to display the available albums as menu options.

The `submenu.inc.php` section module should be written as follows:

```php
<?php
require_once("gallery/view/pages/displayalbummenusection.inc.php");

if(visitedGallerySubPage())
    displayAlbumMenuSection($GLOBALS["galleryPage"]);
?>
```

The above code fragement checks whether the user is actually visiting one of the
gallery's sub pages and if this is the case, it will display all the albums as
menu options.

Embedding a gallery into a HTML editor
======================================
A gallery can also be embedded into a HTML editor implemented by the
`php-sbeditor` framework allowing users to insert pictures from the gallery into
the text that he composes in the editor.

By invoking the `displayHTMLEditorWithGallery()` function:

```php
require_once("gallery/view/html/htmleditorwithgallery.inc.php");

displayHTMLEditorWithGallery("editor1", "contents", "picturepicker.php", "iframepage.html", "image");
```

we can directly generate a `div` providing a `textarea` and two `iframe`s
showing the gallery and editor.

The corresponding div has an id: `editor1`, the textarea field has the name:
`contents`, the gallery iframe embeds the `picturepicker.php` page and the
editor iframe embeds the `iframepage.html` page. The editor's icons are stored
in the `image/` sub folder.

The generated `div` should be embedded inside a `form` element.

We also have to create the `picturepicker.php` page showing the embedded album
that integrates with a gallery object:

```php
<?php
require_once("includes/model/MyGallery.class.php");
require_once("gallery/view/html/picturepickerpage.inc.php");

$myGallery = new MyGallery();
displayPicturePickerPage($myGallery);
?>
```

As can be seen in the above example, the `picturepicker.php` page can be
generated in a straight forward manner -- simply composing a gallery object and
invoking `displayPicturePickerPage()` suffices.

Embedding a HTML editor with embedded gallery into a form
=========================================================
This framework also provides a `HTMLEditorWithGalleryField` class that can be
used to construct fields that can be added to a `Form` managed by the
`php-sbdata` framework:

```php
require_once("gallery/model/field/HTMLEditorWithGalleryField.class.php");

$form = new Form(array(
    "title" => new TextField("Title", true),
    "contents" => new HTMLEditorWithGalleryField("editor1", "Contents", "picturepicker.php", "iframepage.html", "image", true)
));
```

When displaying the above form as an editable form:

```php
require_once("gallery/view/html/htmleditorwithgalleryfield.inc.php");

displayEditableForm($form,
    "Submit",
    "One or more of the field values are incorrect!",
    "This field is incorrect!");
```

Then the corresponding field will be displayed as an HTML editor with embedded
gallery (or a text area if JavaScript functionality is absent). The parameters
to the HTML editor field constructor are similar to those of the view function
shown in the previous section.

Examples
========
This package contains two examples in the `example/` sub folder:

* The `lowlevel` folder contains an example using the low-level API. It provides
  a gallery, album, picture and multiple pictures upload page.
* The `crud` folder contains an example using the high-level API. It provides
  a gallery with a useless authentication check (the `view=1` GET parameter).
* The `pages` folder contains an example of exposing albums as sub pages in the
  application layout so that parts of the web application content can be
  managed by end-users.
* The `simpleeditor` folder contains a very basic example showing an HTML editor
  with embedded gallery.
* The `formeditor` folder contains an example of an editor with gallery
  integrated into the `php-sbdata` framework, so that input can be automatically
  validated and presented.

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
