var filesContainer = function (url, inputId, inputName, multiple) {
    this.url = url;
    this.openButton = inputId + '-frame';
    this.multiple = multiple;
    this.inputName = inputName;
    this.previewBlock = document.getElementById(inputId + '-preview');

    this.init();
};

filesContainer.prototype.init = function () {
    this.initFrameOpen();
    this.initCancelClick();
};

filesContainer.prototype.initFrameOpen = function () {
    var container = this;

    document.getElementById(container.openButton).onclick = function () {
        window.open(container.url, "_blank", "width=800, height=500");

        return false;
    };
};

filesContainer.prototype.addFilePreview = function (fileRow) {
    var fileId = fileRow.data('file-id'),
        fileName = fileRow.data('filename'),
        filePath = fileRow.data('file-path'),
        fileIsImage = fileRow.data('is-image');

    if (!this.multiple) {
        $(this.previewBlock).empty();
    } else {
        $(this.previewBlock).find('.not-set').remove();
    }

    $input = $('<input />')
        .attr('type', 'hidden')
        .attr('name', this.inputName)
        .val(fileId);

    $cancel = $('<a />')
        .attr('href', '#')
        .addClass('glyphicon glyphicon-remove cancel');

    if (fileIsImage) {
        $previewFile = $("<div />")
            .addClass('preview-file')
            .addClass('image')
            .css({'background-image': 'url("' + filePath + '")'});
    } else {
        $previewFile = $("<div />")
            .addClass('preview-file')
            .addClass('file')
            .text(fileName);

    }

    $previewFile
        .append($input)
        .append($cancel)
        .appendTo(this.previewBlock)
        .after("\n");

};

filesContainer.prototype.initCancelClick = function () {
    $(document).on('click', '.preview-file .cancel', function () {
        $(this).parents('.preview-file').remove();

        return false;
    });
};