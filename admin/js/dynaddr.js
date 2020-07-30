function addAddress () {
    var len = $('#addresses').children().length;

    $('<div/>', {'class' : 'form_element', 'id' : 'address_' + len, html: getAddressHtml()}).hide().appendTo('#addresses').slideDown('fast');

    if (len > 10) {
        $('#new_address_container').hide();
    }
}

function getAddressHtml() {
    var len = $('#addresses').children().length;
    var $html = $('#address_TEMPLATE').clone();
    $html.find('.delete_button').attr('onclick', 'deleteAddress(' + len + ')');
    // $html.find('input[name=addresses[TEMPLATE][description]]').attr('name', "addresses[" + len + "][description]");
    // $html.find('input[name=addresses[TEMPLATE][street1]]').attr('name', "addresses[" + len + "][street1]");
    // $html.find('input[name=addresses[TEMPLATE][street2]]').attr('name', "addresses[" + len + "][street2]");
    // $html.find('input[name=addresses[TEMPLATE][city]]').attr('name', "addresses[" + len + "][city]");
    // $html.find('input[name=addresses[TEMPLATE][state]]').attr('name', "addresses[" + len + "][state]");
    // $html.find('input[name=addresses[TEMPLATE][zip]]').attr('name', "addresses[" + len + "][zip]");
    // $html.find('input[name=addresses[TEMPLATE][id]]').attr('name', "addresses[" + len + "][id]");

    $html.find('.desc').attr('name', "addresses[" + len + "][description]");
    $html.find('.street1').attr('name', "addresses[" + len + "][street1]");
    $html.find('.street2').attr('name', "addresses[" + len + "][street2]");
    $html.find('.city').attr('name', "addresses[" + len + "][city]");
    $html.find('.state').attr('name', "addresses[" + len + "][state]");
    $html.find('.zip').attr('name', "addresses[" + len + "][zip]");
    $html.find('.id').attr('name', "addresses[" + len + "][id]");

    return $html.html();
}

function deleteAddress(id) {
    $('#address_' + id).slideUp('fast', function () {
        $(this).remove();
    });

    var len = $('#addresses').children().length;

    if (len > 10) {
        $('#new_address_container').show();
    }
}