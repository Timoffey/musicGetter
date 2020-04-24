<?php
include_once(dirname(__FILE__)."/../m/mg_geo.php");
$geo = new MG_Geo;
$links=$geo->get_links();
if ($_POST){
	include_once(dirname(__FILE__)."/../c/admin_menu_geo_settings_edit.php");
}
$lists=$geo->get_lists();
$iskey=$geo->get_api_key();
$geo->get_visitor_country();
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

.wrapper{
		width: 45vw;
		font-size: 2em;
		line-height: 2em;
	}
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
</style>
<div class = "wrapper">
	<form method="post">
		<label>MultiLinks 
		<label class="switch">
			<input type="hidden" name="multilinks" value="off">
	  		<input type="checkbox" <?php if($geo->get_multilinks())echo"checked "?>name="multilinks" value="on">
	  		<span class="slider"></span>
		</label></label>
		<input type="submit" value="Сохранить">
	</form>

	<hr>
		<form method="post">
		<label><a href="https://db-ip.com/api/pricing/">IP Geolocation</a> API Key
		<label class="switch">
			<input type="hidden" name="api_key_use" value="off">
	  		<input type="checkbox" <?php if($iskey['is'])echo"checked "?>name="use_api_key" value="on">
	  		<span class="slider"></span>
		</label></label><br>
		<input type = "text" size=50 name = "api_key" required value="<?=$iskey['key']?>"><br>
		<input type="submit" value="Сохранить">
	</form>

	<hr>

	<form method="post">
		<h2>Формирование листов</h2>
		<?php
		foreach ($links as $key => $link_name) {
			// Небольшой блок устраняющий косяк с несозданнм объектом
			/*if (!$lists[$link_name]){
				$lists[$link_name]=(object)$lists[$link_name];
				$lists[$link_name]->type='n';
				$lists[$link_name]->value='';
			}*/
			?>
			<label><?=$link_name?> 
			<select id = "select_<?=$link_name?>" name = "select_<?=$link_name?>" onchange="checkSelect('<?=$link_name?>')">
			<option <?php if ($lists[$link_name]->type == 'n') echo 'selected '?>value="n">None</option>	
			<option <?php if ($lists[$link_name]->type == 'w') echo 'selected '?>value="w">White</option>	
			<option <?php if ($lists[$link_name]->type == 'b') echo 'selected '?>value="b">Black</option>	
			</select>
			<input id = "<?=$link_name?>" 
				type="text" 
				size="60" 
				name="<?=$link_name?>" 
				placeholder="Введите коды стран через запятую: DE,EN,RU..." <?php if ($lists[$link_name]->type == 'n') echo 'hidden'?>
				value=<?=$lists[$link_name]->value?>>
			</label><br>
			<?php
		}
		?>
		<input type="submit" value="Сохранить все листы">
	</form>
</div>
<script>
	function checkSelect(link){
		if(document.getElementById('select_'+link).value == 'n'){
			document.getElementById(link).style.display='none';
		}else{
			document.getElementById(link).style.display='inline';
		}
	}
</script>
