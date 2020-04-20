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
	  		<input type="checkbox" name="multilinks" value="on">
	  		<span class="slider"></span>
		</label></label>
		<input type="submit" value="Сохранить">
	</form>

	<hr>

	<form method="post">
		<h2>Формирование листов</h2>
		<label>Link1 
		<select id = "select_link1" onchange="checkSelect(1)">
		<option value="n">None</option>	
		<option value="w">White</option>	
		<option value="b">Black</option>	
		</select>
		<input id = "link1" type="text" size="60" name="link1" placeholder="Введите коды стран через запятую: DE,EN,RU..." hidden></label><br>
		
		<label>Link2
		<select id = "select_link2" onchange="checkSelect(2)">
		<option value="n">None</option>	
		<option value="w">White</option>	
		<option value="b">Black</option>	
		</select>
		<input id = "link2" type="text" size="60" name="link2" placeholder="Введите коды стран через запятую: DE,EN,RU..." hidden></label><br>
		<input type="submit" value="Сохранить все листы">
	</form>
</div>
<script>
	function checkSelect(i){
		if(document.getElementById('select_link'+i).value == 'n'){
			document.getElementById('link'+i).style.display='none';
		}else{
			document.getElementById('link'+i).style.display='inline';
		}
	}
</script>
