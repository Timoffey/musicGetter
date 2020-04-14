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
		width: 60vw;
		font-size: 2em;
		line-height: 2em;
	}
	.wrapper2{
		width: 40vw;
	}
	.input{
		display:inline;
		float:right;
	}

</style>

<div class = "wrapper">
	<form method="post">
		Для актуализации структуры локальной БД нажмите <div class="input"><input type="submit" value="Обновить"></div>
	</form>

	<hr>

	<form method="post">
		Выберите поля для проверки их актуальности 
		<select multiple size="6">
			<option>first_field</option>
			<option>second_field</option>
			<option>third_field</option>
			<option>forth_field</option>
			<option>fifth_field</option>
			<option>sixth_field</option>
		</select>
		<div class="input"><input type="submit" value="Проверить"></div>
	</form>

	<hr>
	</div>
	<div class="wrapper wrapper2">
	<form method="post">
		<h2>Фильтры для импорта</h2>
		Адрес БД: <div class="input"><input type="url" size="50" name="db_url"></div><br>
		Адрес БД: <div class="input"><input type="url" size="50" name="db_url"></div><br>
		Адрес БД: <div class="input"><input type="url" size="50" name="db_url"></div><br>
		Адрес БД: <div class="input"><input type="url" size="50" name="db_url"></div><br>
		
			
		
		<input type="submit" value="Настроить">
	</form>
</div>