$(document).ready(function () {
    $('input,textarea,select').on("change", function () {
        $(window).on("beforeunload", function () {
           return "You have made changes. Are you sure you want to leave?";
       });
    });

    $('#edit_form').on("submit", function (e) {
        $(window).off("beforeunload");
        return true;
    })
});
