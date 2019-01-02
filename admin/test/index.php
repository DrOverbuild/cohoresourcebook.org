<?php
/**
 * Created by IntelliJ IDEA.
 * User: jasper
 * Date: 7/26/18
 * Time: 5:15 PM
 */


highlight_string("<?php\n\$categories =\n" . var_export($_GET, true) . ";\n?>\n\n");

highlight_string("<?php\n\$_POST =\n" . var_export($_POST, true) . ";\n?>\n\n");

?>

<form method="post" id="test_form" action="index.php">
	<fieldset class="multi-checkbox">
		<div class="checkbox_item"><input type="checkbox" name="categories[]" value="1" id="cat1"><label for="cat1">Cat 1</label></div>
		<div class="checkbox_item"><input type="checkbox" name="categories[]" value="2" id="cat2"><label for="cat2">Cat 2</label></div>
		<div class="checkbox_item"><input type="checkbox" name="categories[]" value="3" id="cat3"><label for="cat3">Cat 3</label></div>
		<div class="checkbox_item"><input type="checkbox" name="categories[]" value="4" id="cat4"><label for="cat4">Cat 4</label></div>
		<div class="checkbox_item"><input type="checkbox" name="categories[]" value="5" id="cat5"><label for="cat5">Cat 5</label></div>
	</fieldset>

	<fieldset class="dynamic_fieldset">
		<input type="text" class="address_input desc" placeholder="DESCRIPTION" name="addresses[0][description]">
		<input type="text" class="address_input street1" placeholder="STREET 1" name="addresses[0][street1]">
		<input type="text" class="address_input street2" placeholder="STREET 2" name="addresses[0][street2]">
		<input type="text" class="address_input city" placeholder="CITY" name="addresses[0][city]">
		<input type="text" class="address_input state" placeholder="STATE" name="addresses[0][state]">
		<input type="text" class="address_input zip" placeholder="ZIP" name="addresses[0][zip]">
	</fieldset>

	<fieldset class="dynamic_fieldset">
		<input type="text" class="address_input desc" placeholder="DESCRIPTION" name="addresses[1][description]">
		<input type="text" class="address_input street1" placeholder="STREET 1" name="addresses[1][street1]">
		<input type="text" class="address_input street2" placeholder="STREET 2" name="addresses[1][street2]">
		<input type="text" class="address_input city" placeholder="CITY" name="addresses[1][city]">
		<input type="text" class="address_input state" placeholder="STATE" name="addresses[1][state]">
		<input type="text" class="address_input zip" placeholder="ZIP" name="addresses[1][zip]">
	</fieldset>
	<button type="submit" form="test_form">Submit</button>
</form>

