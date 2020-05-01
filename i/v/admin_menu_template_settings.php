<?php
include_once(dirname(__FILE__)."/../m/mg_template.php");
$template = new MG_Template;
if ($_POST){
	include_once(dirname(__FILE__)."/../c/admin_menu_template_settings_edit.php");
}
$fields=$template->get_template();
?>
<style>
		/* The switch - the box around the slider */
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

/* Hide default HTML checkbox */
.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

/* The slider */
.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* The end of sliders */
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
		width: 60vw;
		font-size: 2em;
		line-height: 2em;
	}
	.input{
		display:inline;
		float:right;
	}
</style>
You can use this shortcodes in the fields: [name],[release_date],[description],[file_type],[file_size],[language],[genere],[date_added],[cover]
<hr>
<div class="wrapper">
	<form method="post">
	<label>Meta title <div class="input"><input type="text" size="70" name="meta_title" value="<?=$fields['meta_title']?>"></div></label><br>
	<label>Meta description <div class="input"><input type="text" size="70" name="meta_description" value="<?=$fields['meta_description']?>"></div></label><br>
	<label>Post title <div class="input"><input type="text" size="70" name="post_title" value="<?=$fields['post_title']?>"></div></label><br>
	<label>Post text <div class="input"><input type="text" size="70" name="post_text" value="<?=$fields['post_text']?>"></div></label><br>
	<input type="submit" name="fields" value="Сохранить">
	</form>
	<form method="post">
	<label>Загружать картинки в Featured Image
		<label class="switch">
			<input type="hidden" name="thumbs" value="off">
	  		<input type="checkbox" <?php if($template->get_thumbs())echo"checked "?>name="thumbs" value="on">
	  		<span class="slider"></span>
		</label>
	</label>
	<input type="submit" value="Сохранить">
	</form>
</div>
<!--<form method="post">
<input name="make_post" value ="Сделать тестовый пост" type="submit">
</form>-->