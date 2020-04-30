<?php 
if($_POST){
	include_once(dirname(__FILE__)."/../c/admin_menu_server_settings_edit.php"); 
}
include_once(dirname(__FILE__)."/../m/mg_config.php");
$config = new MG_Config;
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

	.reset{
		background-color: #AF4C50;
	}
	[name=refresh_rate]{
		width: 4em;
	}
	.input{
		display:inline;
		float:right;
	}
	.input-time{
		float:none;
	}
	.wrapper{
		width: 60vw;
		font-size: 2em;
		line-height: 2em;
	}

</style>
<div class = "wrapper"><form method="post">
	<label>Включить/выключить работу плагина 
	<label class="switch">
		<input type="hidden" name="is_on" value="off">
  		<input type="checkbox" name="is_on" <?php if($config->is_on)echo'checked'?> value="1">
  		<span class="slider"></span>
	</label></label><br>
	<label>Адрес БД: <div class="input"><input type="text" size="50" name="db_url" value="<?=$config->db_url?>"></div></label><br>
	<label>Логин от БД: <div class="input"><input type="text" size="50" name="db_login" value="<?=$config->db_login?>"></div></label><br>
	<label>Пароль от БД:<div class="input"><input type="password" size="50" name="db_pass" value="<?=$config->db_pass?>"></div></label><br>
	<label>Имя БД:<div class="input"><input type="text" size="50" name="db_name" value="<?=$config->db_name?>"></div></label><br>
	<label>Имя таблицы:<div class="input"><input type="text" size="50" name="db_table_name" value="<?=$config->db_table_name?>"></div></label><br>
	<label>Cтрок БД обновляемых за раз: <div class="input"><input type="number" name="refresh_quantity" value="<?=$config->refresh_quantity?>"></div></label><br>
	<label>Обновлять БД каждые <div class="input input-time"><input name = "refresh_rate" type="number" value="<?=$config->refresh_rate?>"> <select name="refresh_period">
		<option id = "minutes" value = "m">Минут</option>
		<option id = "hours" value = "h">Часов</option>
		<option id = "days" value = "d">Дней</option>
	</select></div></label>
	<input type="submit" class="input input-time" value="Сохранить">
	</form>
	<form method="post">
		<input class="reset" name="reset" type="submit" value="Очистить базы">
		<div class="input">
			<label class="input" for="reset_checkbox">Да, я хочу удалить все базы</label>
			<input required id="reset_checkbox"type="checkbox">
		</div>
	</form>
</div>
<script type="text/javascript">

switch ('<?=$config->refresh_period?>') {
		case 'm':
			document.getElementById('minutes').setAttribute('selected', 1)
			break;
		case 'h':
			document.getElementById('hours').setAttribute('selected', 1)
			break;
		case 'd':
			document.getElementById('days').setAttribute('selected', 1)
			break;	
	}	
</script>
