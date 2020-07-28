<?php

require_once('./dynamics/component/page/Page.php');

$page = new Page();
$page->show();

echo getcwd();
?>

<!--<!DOCTYPE html>-->
<!--<html lang="en">-->
<!--<head>-->
<!--	<meta charset="UTF-8">-->
<!--	<title>Coho Resource Book</title>-->
	<!-- scripts -->
<!--	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>-->
<!---->
	<!-- stylesheets -->
<!--	<link rel="stylesheet" href="css/body.css">-->
<!--	<link rel="stylesheet" href="css/sidebar.css">-->
<!--	<link rel="stylesheet" href="css/main.css">-->
<!--	<link rel="stylesheet" href="css/table.css">-->
<!--</head>-->
<!--<body>-->
<!---->
	<!-- left navbar -->
<!--	<div class="sidebar">-->
<!--		<div id="sidebar_header">-->
<!--			<img src="img/coho-off-white.png">-->
<!--			<h1>COHO RESOURCE BOOK</h1>-->
<!--		</div>-->
<!--		<ul class="sidebar_items_main">-->
<!--			<li>CATEGORIES</li>-->
<!--			<li>RESOURCES</li>-->
<!--			<li>USERS</li>-->
<!--		</ul>-->
<!---->
<!--		<ul class="sidebar_items_bottom">-->
<!--			<li class="sidebar_items_bottom_item">SETTINGS</li>-->
<!--			<li class="sidebar_items_bottom_item">SIGN OUT</li>-->
<!--		</ul>-->
<!--	</div>-->
<!---->
	<!-- main content -->
<!--	<div class="main">-->
<!--		<div class="main_left">-->
<!---->
<!--			<h1>Categories</h1>-->
<!---->
<!--			<table class="main_table">-->
<!--				<tr>-->
<!--					<td>-->
<!--						<div class="left_table"><a href="/">hi1</a></div>-->
<!--						<div class="right_table"><a href="/">EDIT</a></div>-->
<!--					</td>-->
<!--				</tr>-->
<!---->
<!--				<tr>-->
<!--					<td>-->
<!--						<div class="left_table"><a href="/">hi1</a></div>-->
<!--						<div class="right_table"><a href="/">EDIT</a></div>-->
<!---->
<!--					</td>-->
<!--				</tr>-->
<!---->
<!--				<tr>-->
<!--					<td>-->
<!--						<div class="left_table"><a href="/">hi1</a></div>-->
<!--						<div class="right_table"><a href="/">EDIT</a></div>-->
<!--					</td>-->
<!--				</tr>-->
<!---->
<!--				<tr>-->
<!--					<td>-->
<!--						<div class="left_table"><a href="/">hi1</a></div>-->
<!--						<div class="right_table"><a href="/">EDIT</a></div>-->
<!--					</td>-->
<!--				</tr>-->
<!---->
<!--				<tr>-->
<!--					<td class="last">-->
<!--						<div class="left_table"><a href="/">hi1</a></div>-->
<!--						<div class="right_table"><a href="/">EDIT</a></div>-->
<!--					</td>-->
<!--				</tr>-->
<!--			</table>-->
<!--		</div>-->
<!---->
<!--		<div class="main_right">-->
<!---->
<!--			<h1>Resources</h1>-->
<!---->
<!--			<table class="main_table">-->
<!--				<tr>-->
<!--					<td>-->
<!--						<div class="left_table"><a href="/">hi1</a></div>-->
<!--						<div class="right_table"><a href="/">EDIT</a></div>-->
<!--					</td>-->
<!--				</tr>-->
<!---->
<!--				<tr>-->
<!--					<td>-->
<!--						<div class="left_table"><a href="/">hi1</a></div>-->
<!--						<div class="right_table"><a href="/">EDIT</a></div>-->
<!---->
<!--					</td>-->
<!--				</tr>-->
<!---->
<!--				<tr>-->
<!--					<td>-->
<!--						<div class="left_table"><a href="/">hi1</a></div>-->
<!--						<div class="right_table"><a href="/">EDIT</a></div>-->
<!--					</td>-->
<!--				</tr>-->
<!---->
<!--				<tr>-->
<!--					<td>-->
<!--						<div class="left_table"><a href="/">hi1</a></div>-->
<!--						<div class="right_table"><a href="/">EDIT</a></div>-->
<!--					</td>-->
<!--				</tr>-->
<!---->
<!--				<tr>-->
<!--					<td class="last">-->
<!--						<div class="left_table"><a href="/">hi1</a></div>-->
<!--						<div class="right_table"><a href="/">EDIT</a></div>-->
<!--					</td>-->
<!--				</tr>-->
<!--			</table>-->
<!--		</div>-->
<!--	</div>-->
<!---->
<!--<script type="text/javascript">-->
<!---->
<!--	var sizing = function() {-->
<!--        var width = $('.sidebar').width();-->
<!--        var main = $('.main');-->
<!--        $('.sidebar_items_bottom_item').width(width - 20);-->
<!--        main.css('padding-left', (width + 40) + 'px');-->
<!--        main.width($(window).width() - width - 80 );-->
<!--	};-->
<!---->
<!--    $(window).on('resize', sizing);-->
<!--	$(document).ready(sizing);-->
<!--</script>-->
<!---->
<!--</body>-->
<!--</html>-->