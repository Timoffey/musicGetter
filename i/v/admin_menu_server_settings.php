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
	Адрес БД: <div class="input"><input type="url" size="50" name="db_url"></div><br>
	Логин от БД: <div class="input"><input type="text" size="50" name="db_login"></div><br>
	Пароль от БД:<div class="input"><input type="password" size="50" name="db_pass"></div><br>
	Cтрок БД обновляемых за раз: <div class="input"><input type="number" name="refresh_quantity"></div><br>
	Обновлять БД каждые <div class="input input-time"><input name = "refresh_rate" type="number"> <select id="period" name="period">
		<option value="m">Минут</option>
		<option value = "h">Часов</option>
		<option value = "d">Дней</option>
	</select></div>
	<input type="submit" class="input input-time" value="Сохранить">
	</form>

</div>
<?php 
if($_POST){
echo('Введённые значенния: Адрес БД: ' . $_POST['db_url'] . 'Логин от БД: ' . $_POST['db_login'] .'Пароль от БД: ' . $_POST['db_pass'] . 'Cтрок БД обновляемых за раз: ' . $_POST['refresh_quantity']. 'Обновлять БД каждые: ' .$_POST['refresh_rate'] . $_POST['period']);
}
