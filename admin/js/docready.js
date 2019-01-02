var editPage = false;

jQuery(window).on('resize', sizing());

jQuery(window).on('load', function () {
    sizing();
});

jQuery(document).ready(function () {
    if(editPage) {
        checkboxIssue();
    }
});
//
// $(function() {
//     $('select').selectric();
// });

function sizing() {
    var width = jQuery('.sidebar').width();
    var main = jQuery('.main');
    jQuery('.sidebar_items_bottom_item').width(width - 20);
    main.css('padding-left', (width + 20) + 'px');
    main.width(jQuery(window).width() - width - 40 );

    if (editPage) {
        theBottom();
    }
}

function theBottom() {
    // edit footer save feature
    var sidebar = jQuery('.sidebar');
    var theBottom = jQuery('.the_bottom');
    theBottom.css('left',sidebar.width());
    theBottom.width(jQuery(window).width() - sidebar.width());
    jQuery('#edit_form').css('margin-bottom', (theBottom.height() + 40) + "px");
}

function checkboxIssue() {
    // checkbox issue
    jQuery('.category_checkbox').click( function (e) {
        e.preventDefault();
    });

    jQuery('.checkbox_item').click(function (e) {
        e.preventDefault();
        var checkbox = jQuery(this).find(':checkbox');
        checkbox.prop('checked', !checkbox[0].checked);
    });
}