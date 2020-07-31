function addContact() {
    var len = $('#contact').children().length;

    $('<div/>', {'class' : 'form_element', 'id' : 'contact_' + len, html: getContactHtml()})
        .hide()
        .appendTo('#contact')
        .slideDown('fast');
}

function getContactHtml() {
    var len = $('#contact').children().length;
    var $html = $('#contact_TEMPLATE').clone();
    $html.find('.delete_button').attr('onclick', 'deleteContact(' + len + ')');

    $html.find('.type').attr('name', "contact[" + len + "][type]");
    $html.find('.name').attr('name', "contact[" + len + "][name]");
    $html.find('.value').attr('name', "contact[" + len + "][value]");
    $html.find('.id').attr('name', "contact[" + len + "][id]");
    $html.find('.id').attr('value', "0");

    return $html.html();
}

function deleteContact(id) {
    $('#contact_' + id).slideUp('fast', function () {
        $(this).remove();
    });
}