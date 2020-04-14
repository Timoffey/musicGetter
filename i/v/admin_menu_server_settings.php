<style>
	[name=time]{
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
<div class = "wrapper">
	Адрес БД: <div class="input"><input type="url" size="50" name="db_url"></div><br>
	Логин от БД: <div class="input"><input type="text" size="50" name="db_url"></div><br>
	Пароль от БД:<div class="input"><input type="password" size="50" name="db_url"></div><br>
	Обновлять каждые <div class="input input-time"><input name = "time" type="number"> <select id="period">
		<option value="m">Минут</option>
		<option value = "h">Часов</option>
		<option value = "d">Дней</option>
	</select></div>
</div>