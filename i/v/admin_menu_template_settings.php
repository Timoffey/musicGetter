<?php
include_once(dirname(__FILE__)."/../m/mg_template.php");
$template = new MG_Template;
if ($_POST){
	include_once(dirname(__FILE__)."/../c/admin_menu_template_settings_edit.php");
}
$fields=$template->get_template();
?>
<style>
	[type=submit]{
	background-color: #4CAF50; /* Green */
    border: none;
    color: white;
    padding: 10px 35px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    border-radius: 10px;
  
	}

	.wrapper{
		width: 50vw;
		font-size: 2em;
		line-height: 2em;
	}
	.input{
		display:inline;
		float:right;
	}
</style>
You can use this shortcodes in the fields: [name],[release_date],[description],[file_type],[file_size],[language],[genere],[date_added]
<hr>
<div class="wrapper">
	<form method="post">
	<label>Meta title <div class="input"><input type="text" size="70" name="meta_title" value="<?=$fields['meta_title']?>"></div></label><br>
	<label>Meta description <div class="input"><input type="text" size="70" name="meta_description" value="<?=$fields['meta_description']?>"></div></label><br>
	<label>Post title <div class="input"><input type="text" size="70" name="post_title" value="<?=$fields['post_title']?>"></div></label><br>
	<label>Post text <div class="input"><input type="text" size="70" name="post_text" value="<?=$fields['post_text']?>"></div></label><br>
	<input type="submit" name="fields" value="Сохранить">
	</form>
</div>
<!--<form method="post">
<input name="make_post" value ="Сделать тестовый пост" type="submit">
</form>-->