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
     * Creates a new SBEditorWithGallery object that turns a div with a textarea into a rich text editor with an embedded gallery.
     *
     * @param {String} iconsPath Path in which the editor icons reside
     * @param {String} galleryIframePage Path to an HTML page that in dislayed inside the gallery iframe
     * @param {String} editorIframePage Path to an HTML page that is displayed inside the editor iframe
     * @param {number} width Width of the editor and gallery (in em)
     * @param {number} galleryHeight Height of the gallery (in em)
     * @param {number} editorHeight Height of the editor (in em)
     * @param {Object} editorLabels An object that translates every label of the editor. If none is given, the editor uses the default labels
     */
    function SBEditorWithGallery(iconsPath, galleryIframePage, editorIframePage, width, galleryHeight, editorHeight, editorLabels) {
        this.sbeditor = new sbeditor.SBEditor(iconsPath, editorIframePage, width, editorHeight, editorLabels);
        this.galleryIframePage = galleryIframePage;
        this.width = width;
        this.galleryHeight = galleryHeight;
    }

    sbeditorWithGallery.SBEditorWithGallery = SBEditorWithGallery;

    /**
     * Adds editor with gallery capabilities to a div element that embeds a text area.
     *
     * @param {String} divId ID of the div to augment
     */
    SBEditorWithGallery.prototype.addEditorWithGalleryCapabilities = function(divId) {
        var div = document.getElementById(divId);
        var iframe = createGalleryIFrame(this.galleryIframePage, this.width, this.galleryHeight);
        div.insertBefore(iframe, div.firstChild);

        this.sbeditor.addEditorCapabilities(divId);
    };

    this.sbeditorWithGallery = sbeditorWithGallery;
})();
