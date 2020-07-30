$(document).ready(function () {
    console.log("Document ready prevent unload.");
    $('input,textarea,select').on("change", function () {
        $(window).on("beforeunload", function () {
           return "You have made changes. Are you sure you want to leave?";
       });
    });

    $('#edit_form').on("submit", function (e) {
        // probably not the right place for data validation but I don't care

        // data validation for contact

        $('.contact_input.value').each(function(){
            if ($(this).attr('name').length !== 0 && $(this).val().length === 0){
                alert("Contact values must not be empty. Please fill out fields or delete contact.");
                e.preventDefault();
                return true;
            }
        });

        $(window).off("beforeunload");
        return true;
    });
});
