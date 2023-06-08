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

* [php-sbdata](https://github.com/svanderburg/php-sbdata)
* [php-sbeditor](https://github.com/svanderburg/php-sbeditor)
* [php-sbcrud](https://github.com/svanderburg/php-sbcrud)

Installation
============
This package can be embedded in any PHP project by using
[PHP composer](https://getcomposer.org). Add the following items to your
project's `composer.json` file:

```json
{
  "repositories": [
    {
      "type": "git",
      "url": "https://github.com/svanderburg/php-sbgallery.git"
    }
  ],

  "require": {
    "svanderburg/php-sbgallery": "@dev",
  }
}
```

and run:

```bash
$ composer install
```

Installing development dependencies
===================================
When it is desired to modify the code or run the examples inside this
repository, the development dependencies must be installed by opening
the base directory and running:

```bash
$ composer install
```

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
We can create a gallery, that displays an overview of available albums with a
thumbnail, as follows:

```php
use SBGallery\Model\Gallery;
use SBGallery\Model\Settings\GallerySettings;
use SBGallery\Model\Settings\URLGenerator\SimpleGalleryURLGenerator;

$dbh = new PDO("mysql:host=localhost;dbname=gallery", "user", "password", array(
    PDO::ATTR_PERSISTENT => true
));

GallerySettings $settings = new GallerySettings(new SimpleGalleryURLGenerator(), // An object that defines how URLs to the sub pages should be generated
    "gallery", // The directory in which the gallery's images reside
    160, 160, // The maximum dimensions of a thumbnail
    1280, 1280, // The maximum dimensions of a picture
    0666 // The permissions of picture files in the gallery directory
    0777 // The permissions of directories in the gallery directory
);

Gallery $gallery = new Gallery($dbh, $settings);
```

The above code snippet sets up a database connection and configures a gallery
with the following settings:

* It uses a `URLGenerator` object to generate URLs that link to albums, pictures
  and their corresponding operation pages. The `SimpleGalleryURLGenerator`
  implements a URL convention in which the gallery is managed by the
  `gallery.php` page, albums with the `album.php` page and pictures by the
  `picture.php` page. The pages use GET parameters to determine which album and
  picture have been requested.
* It uses the `gallery/` folder as the base directory of all images
* The maximum dimensions of a thumbnail are 160x160 pixels
* The maximum dimensions of a picture are 1280x1280 pixels
* The file permissions of the picture files are read, write support for the
  owner, group and others
* The directory permissions of a picture directory are read, write, execute
  support for the owner, group and others

There are more configuration properties supported beyond the ones shown in the
example. Consult the API documentation for more information.

We can render the gallery in read mode with the following function call:

```php
\SBGallery\View\HTML\displayGallery($gallery);
```

Rendering the gallery in write mode, in which the user can modify its contents,
can be done with the following funcion call:

```php
\SBGallery\View\HTML\displayEditableGallery($gallery);
```

Managing albums
---------------
The low level API implements a variety of album operations.

### Querying an album

We can query an album from the gallery as follows:

```php
$album = $gallery->queryAlbum("myalbum");
```

The above method invocation queries the album with the identifier: `myalbum`.
If the album does not exists, the method returns an `AlbumNotFoundException`.

### Creating a new album

We can create a new album as follows:

```php
$album = $gallery->newAlbum();
```

The above method call creates a new album that inherits the configuration
settings from the gallery.

### Checking an album for validity

We can import album settings from the request variables and check its validity
as follows:

```php
$album->importValues($_REQUEST);
$album->checkFields();
$valid = $album->checkValid(); // Returns true if the album properties are valid, otherwise false
```

### Inserting an album

We can insert an album into the database with the following method call:

```php
$gallery->insertAlbum($album);
```

### Updating an album

An existing album can be updated with the following method call:

```php
$gallery->updateAlbum("myalbum", $updatedAlbum);
```

The above method call updates the album with identifier: `myalbum` with the
properties of the `$updatedAlbum`.

### Removing an album

An album can be removed with the following method call:

```php
$myGallery->removeAlbum("myalbum");
```

### Uploading multiple pictures into an album

We can also upload multiple images into an album (albums will automatically adopt
the image filename as a title and identifier) and scale it down according to the
gallery's specifications with the following method invocaion:

```php
$album->insertMultiplePictures("Image");
```

The parameter specifies the name of the form field that facilitates the multiple
file upload.

### Moving an album left or right in the gallery

The following methods can be used to move an album left or right in the gallery:

```php
$result = $myGallery->moveLeftAlbum("myalbum");
$result = $myGallery->moveRightAlbum("myalbum");
```

The method call returns `true` if the album was moved, and `false` if it did
not. When an album has reached the beginning or end in the gallery these methods
return `false`.

### Displaying an album in read mode

An album can be rendered in read mode with the following function call:

```php
\SBGallery\View\HTML\displayAlbum($album);
```

### Displaying an album in write mode

We can also render an album in write mode, in which the user can change the
properties of the album, and adjust the pictures inside it:

```php
\SBGallery\View\HTML\displayEditableAlbum($album);
```

### Displaying a pictures uploader

As shown earlier, the album editor can also upload multiple pictures at the same
time. To render the form that provides the input fields, we can do the following
function call:

```php
$picturesUploader = $album->constructPicturesUploader();
\SBGallery\View\HTML\displayPicturesUploader($picturesUploader);
```

Managing pictures
-----------------
The low-level API makes it also possible to directly manage pictures.

### Querying a picture

We can query a picture from an album as follows:

```php
$picture = $album->queryPicture("mypicture");
```

The above method invocation queries the album with the identifier: `mypicture`.
If the album does not exists, the method returns an `PictureNotFoundException`.

### Creating a new picture

We can create a new picture as follows:

```php
$picture = $album->newPicture();
```

The above method call creates a new picture that inherits the configuration
settings from the album.

### Checking a picture for validity

We can import picture settings from the request variables and check its validity
as follows:

```php
$picture->importValues($_REQUEST);
$picture->checkFields();
$valid = $picture->checkValid(); // Returns true if the picture is valid, otherwise false
```

### Inserting a picture

We can insert a picture into the database with the following function call:

```php
$album->insertPicture($picture);
```

In addition to inserting the picture into the database, it will also
automatically upload a user provided image and scales it according to the
gallery's specifications.

### Updating a picture

An existing picture can be updated with the following method call:

```php
$album->updatePicture("mypicture", $updatedPicture);
```

The above method call updates the picture with identifier: `mypicture` with the
properties of the `$updatedPicture`.

In addition, it will also automatically upload a user provided image and scales
it according to the gallery's specifications.

### Removing a picture

A picture can be removed with the following method call:

```php
$album->removePicture("mypicture");
```

### Moving a picture left or right in an album

The following methods can be used to move a picture left or right in the abum:

```php
$album->moveLeftPicture("mypicture");
$album->moveRightPicture("mypicture")
```

The method call returns `true` if the picture was moved, and `false` if it did
not. When a picture has reached the beginning or end in the albums these methods
return `false`.

### Setting a picture as thumbnail for an album

To set a picture as a thumbnail for an album, we can use the following method
call:

```php
$album->setAsThumbnail("mypicture");
```

### Clearing an image in a picture

It is possible to add a picture to an album without an image. To clear an
existing image in a picture we can use the following method call:

```php
$album->clearPicture($_REQUEST["PICTURE_ID"]);
```

### Displaying a picture in read mode

A picture, including navigation controls, can be rendered in read mode with the
following function call:

```php
\SBGallery\View\HTML\displayPicture($picture);
```

### Displaying a picture in write mode

We can also render a picture in write mode, in which the user can change the
properties of the picture:

```php
\SBGallery\View\HTML\displayEditablePicture($picture);
```

High-level API usage
====================
The high-level API is an API that is built on top of the `php-sblayout` and
`php-sbcrud` frameworks. The idea is that you can easily embed a fully
functional gallery into an existing application (including authentication
checks) by composing `GalleryPage` object and adding it to the `Application`'s
page structure.

First, we must construct our own permission checker that determines whether a
user has write permissions:

```php
use SBGallery\Model\GalleryPermissionChecker;

class MyGalleryPermissionChecker implements GalleryPermissionChecker
{
    public function checkWritePermissions(): bool
    {
        return ($_COOKIE["Password"] === "secret");
    }
}
```

In the above checker, you must implement your own password policy, such as
integrating with a database or external authentication system.

Then we must construct a `GalleryPage` instance that we can add to an
application's page structure. A `GalleryPage` accepts many kinds of
configuration parameters including a permission checker.

A convenient way to compose an instance and keep the code clean, is to create a
sub class from `GalleryPage`:

```php
use PDO;
use SBGallery\Model\Gallery;
use SBGallery\Model\GalleryPermissionChecker;
use SBGallery\Model\Page\GalleryPage;

class MyGalleryPage extends GalleryPage
{
    public function __construct(PDO $dbh)
    {
        parent::__construct($dbh, new GalleryPageSettings("gallery"), new MyGalleryPermissionChecker());
    }
}
```

The above sub class: `MyGalleryPage` defines a new constructor that composes a
`GalleryPage` with desired configuration settings, such as:

* The database connection handler: `$dbh`
* Various gallery page settings. The base directory of the gallery images is:
  `gallery/`
* The gallery permission checker that we have defined earlier.

There are many kinds of gallery settings that can be configured, such as the
icons and labels. Consult the API documentation for the `GalleryPage` and
`GalleryPageSettings` classes for more information.

We can add a page instance of our sub class to the application layout as
follows:

```php
use SBLayout\Model\Application;
use SBLayout\Model\Page\HiddenStaticContentPage;
use SBLayout\Model\Page\PageAlias;
use SBLayout\Model\Page\StaticContentPage;
use SBLayout\Model\Page\Content\Contents;
use SBLayout\Model\Section\ContentsSection;
use SBLayout\Model\Section\MenuSection;
use SBLayout\Model\Section\StaticSection;

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
        "gallery" => new MyGalleryPage()
    ))
);
```
Adding the page object to the application layout allows us to:

* Access the gallery through: `http://localhost/gallery`
* Access each album through: `http://localhost/gallery/<albumId>`
* Access each picture through: `http://localhost/gallery/<albumId>/<pictureId>`

Because the high-level API is also using the `php-sbcrud` framework, we can
access CRUD operations (other than reading) by appending a `__operation` request
paramer, such as:

```
http://localhost/gallery/myalbum/mypicture?__operation=moveleft_picture
```

By invoking the above URL (and having met the appropriate authentication
criteria) the picture will be moved left in the album.

Exposing a gallery as a set of sub pages in an application layout
=================================================================
In addition to using the high level API to embed the gallery as a sub
application into an application layout, we can also expose albums as browable
sub pages from a menu.

By using albums as pages, we can use the gallery as a simple content manager
allowing a user to dynamic construct sub pages of a web applications including
the contents of the pages.

Instead of creating an instance of the `GalleryPage` class, we must instantiate
the `TraversableGalleryPage` class.

As with the previous example, for convenience, we can construct such a page with
its configuration settings by creating a sub class:

```php
use PDO;
use SBGallery\Model\Gallery;
use SBGallery\Model\GalleryPermissionChecker;
use SBGallery\Model\Page\TraversableGalleryPage;

class MyGalleryPage extends TraversableGalleryPage
{
    public function __construct(PDO $dbh)
    {
        parent::__construct($dbh, new GalleryPageSettings("gallery"), new MyGalleryPermissionChecker());
    }
}
```

Composing the application layout is done in exactly the same way as the previous
example.

Embedding a gallery into a HTML editor
======================================
A gallery can also be embedded into a HTML editor implemented by the
`php-sbeditor` framework allowing users to insert pictures from the gallery into
the text that he composes in the editor.

By invoking the `displayHTMLEditorWithGallery()` function:

```php
\SBGallery\View\HTML\displayHTMLEditorWithGallery("editor1", "contents", "picturepicker.php", "iframepage.html", "image");
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
$myGallery = new MyGallery();
\SBGallery\View\HTML\displayPicturePickerPage($myGallery);
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
use SBData\Model\Form;
use SBData\Model\Field\TextField;
use SBGallery\Model\Field\HTMLEditorWithGalleryField;

$form = new Form(array(
    "title" => new TextField("Title", true),
    "contents" => new HTMLEditorWithGalleryField("editor1", "Contents", "picturepicker.php", "iframepage.html", "image", true)
));
```

When displaying the above form as an editable form:

```php
\SBData\View\HTML\displayEditableForm($form);
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
  The `crud-translated` folder extends the previous example with a translated
  version the gallery to Dutch.
* The `pages` folder contains an example of exposing albums as sub pages in the
  application layout so that parts of the web application content can be
  managed by end-users.
* The `simpleeditor` folder contains a very basic example showing an HTML editor
  with embedded gallery.
* The `formeditor` folder contains an example of an editor with gallery
  integrated into the `php-sbdata` framework, so that input can be automatically
  validated and presented.
* The `formeditor-translated` folder extends the previous example with a
  translation to Dutch

API documentation
=================
This package includes API documentation that can be generated with
[Doxygen](https://www.doxygen.nl):

```bash
$ doxygen
```

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
