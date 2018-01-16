require('bootstrap');
require('clipboard');
var $ = require('jquery');

$(document).ready(function() {
    //new Clipboard('div.oi-clipboard');
});

document.open_modal_copy_link = function (link) {
    $('#clipboardInputField').val(link);
    $('#copyLinkModal').modal('show');
};


