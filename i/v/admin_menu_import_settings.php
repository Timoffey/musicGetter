<?php
if ($_POST){
		include_once(dirname(__FILE__)."/../c/admin_menu_import_settings_edit.php");
	}
	include_once(dirname(__FILE__)."/../m/mg_import.php");
 	$import=new MG_Import;
 	$filter=$import->get_filter();
 	$list = $import->get_local_db();
 	$list_size=count($list);
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
		<input type="hidden" name="actualize" value = "on">
		Для актуализации структуры локальной БД нажмите <div class="input"><input type="submit" value="Обновить"></div>
	</form>

	<hr>

	<form method="post">
		Выберите поля для проверки их актуальности 
		<select name="update_fields[]" multiple size="<?=$list_size?>">
			<?php
				for ($i=1;$i<=$list_size;$i++){
					echo "<option value='$list[$i]'>$list[$i]</option>";
				}
			?>
		</select>
		<div class="input"><input type="submit" value="Проверить"></div>
	</form>

	<hr>
	</div>
	<div class="wrapper wrapper2">
	<form method="post">
		<h2>Фильтры для импорта</h2>
		<?php
			for ($i=1;$i<=$list_size;$i++){
				echo "$list[$i]: <div class='input'><input type='text' size='50' name='$list[$i]' value='".trim($filter->{$list[$i]})."''></div><br>";
				echo "<input hidden name= 'import_filter[]' value=$list[$i]>";
			}
		?>
		<input type="submit" value="Настроить">
	</form>
	<form method="post">
		<input type="hidden" name="test" value="on">
		<input type="submit" value="Тест импорта">
	</form>
</div>