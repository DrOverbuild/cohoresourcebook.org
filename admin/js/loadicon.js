$(document).ready(function () {
    console.log("Document ready icon change.");

    setIcon($('#icon').val());

    $('#icon').change(function () {
        setIcon($(this).val());
    });
});

function setIcon(changedTo) {
	if (changedTo !== 'None') {
		var url = "../icon/" + changedTo + ".svg";

		$('#icon_preview').attr('src', url).show();

		console.log(url);
	} else {
		$('#icon_preview').hide();
	}
}