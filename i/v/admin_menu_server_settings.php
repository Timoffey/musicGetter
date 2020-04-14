<?php 
if($_POST){
	include_once(dirname(__FILE__)."/../c/admin_menu_server_settings_edit.php"); 
}
include_once(dirname(__FILE__)."/../m/config.php");
$config = new Config;
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
		width: 40vw;
		font-size: 2em;
		line-height: 2em;
	}

</style>
<div class = "wrapper"><form method="post">
	Адрес БД: <div class="input"><input type="url" size="50" name="db_url" value="<?=$config->db_url?>"></div><br>
	Логин от БД: <div class="input"><input type="text" size="50" name="db_login" value="<?=$config->db_login?>"></div><br>
	Пароль от БД:<div class="input"><input type="password" size="50" name="db_pass" value="<?=$config->db_pass?>"></div><br>
	Cтрок БД обновляемых за раз: <div class="input"><input type="number" name="refresh_quantity" value="<?=$config->refresh_quantity?>"></div><br>
	Обновлять БД каждые <div class="input input-time"><input name = "refresh_rate" type="number" value="<?=$config->refresh_rate?>"> <select name="refresh_period">
		<option id = "minutes" value = "m">Минут</option>
		<option id = "hours" value = "h">Часов</option>
		<option id = "days" value = "d">Дней</option>
	</select></div>
	<input type="submit" class="input input-time" value="Сохранить">
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
