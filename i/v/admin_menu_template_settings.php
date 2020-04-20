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
		width: 40vw;
		font-size: 2em;
		line-height: 2em;
	}
	.input{
		display:inline;
		float:right;
	}
</style>
<div class="wrapper">
	<label>Meta title <div class="input"><input type="text" size="40" name="meta_title"></div></label><br>
	<label>Meta description <div class="input"><input type="text" size="40" name="meta_description"></div></label><br>
	<label>Meta tags <div class="input"><input type="text" size="40" name="meta_tags"></div></label><br>
	<label>Post title <div class="input"><input type="text" size="40" name="post_title"></div></label><br>
	<label>Post text <div class="input"><input type="text" size="40" name="post_text"></div></label><br>
	<input type="submit" value="Сохранить">
</div>