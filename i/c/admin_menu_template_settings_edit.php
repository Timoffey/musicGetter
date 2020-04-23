<?php

if(isset($_POST['make_post'])){	
	$template->get_delta_data();
}

if (isset($_POST['fields'])){
	$fields['meta_title']=$_POST['meta_title'];
	$fields['meta_description']=$_POST['meta_description'];
	$fields['meta_tags']=$_POST['meta_tags'];
	$fields['post_title']=$_POST['post_title'];
	$fields['post_text']=$_POST['post_text'];
	$template->set_template($fields);
}