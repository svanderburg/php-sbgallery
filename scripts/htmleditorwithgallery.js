(function() {
    var sbeditorWithGallery = {};

    function createGalleryIFrame(iframePage, width, height) {
        var iframe = document.createElement("iframe");
        iframe.src = iframePage;
        iframe.style.width = width + "em";
        iframe.style.height = height + "em";
        return iframe;
    }

    /**
     * Adds editor with gallery capabilities to a div element that embeds a text area.
     *
     * @param {String} id ID of the div to augment
     * @param {String} iconsPath Path in which the editor icons reside
     * @param {String} galleryIframePage Path to an HTML page that in dislayed inside the gallery iframe
     * @param {String} editorIframePage Path to an HTML page that is displayed inside the editor iframe
     * @param {number} width Width of the editor and gallery (in em)
     * @param {number} galleryHeight Height of the gallery (in em)
     * @param {number} editorHeight Height of the editor (in em)
     */
    function addEditorWithGalleryCapabilities(id, iconsPath, galleryIframePage, editorIframePage, width, galleryHeight, editorHeight) {
        var div = document.getElementById(id);
        var iframe = createGalleryIFrame(galleryIframePage, width, galleryHeight);
        div.insertBefore(iframe, div.firstChild);
        sbeditor.addEditorCapabilities(id, iconsPath, editorIframePage, width, editorHeight);
    }

    sbeditorWithGallery.addEditorWithGalleryCapabilities = addEditorWithGalleryCapabilities;

    this.sbeditorWithGallery = sbeditorWithGallery;
})();
