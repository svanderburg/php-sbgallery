(function() {
    var sbgallery = {};

    function insertHTML(id, html) {
        var editorDivElement = parent.document.getElementById(id);
        sbeditor.insertHTMLIntoEditorDivElement(editorDivElement, html);
    }

    /**
     * Adds the given image from the gallery to the HTML editor
     *
     * @param {String} url URL of the image
     * @param {String} alt Alternate text
     */
    function addImageFromGallery(id, url, alt) {
        var html = '<img src="'+url+'" alt="'+alt+'">';
        insertHTML(id, html);
    }

    sbgallery.addImageFromGallery = addImageFromGallery;

    this.sbgallery = sbgallery;
})();
