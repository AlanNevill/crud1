<?php 
include('class.crud.1.php'); 

if($_POST['method']=='listing')
{
	return $obj->listing();
}
if($_POST['method']=='edit')
{
	$obj->edit();
	return $_POST['name'];
}
if($_POST['method']=='save')
{
	$obj->save();
}
if($_POST['method']=='delete')
{
	$obj->delete_record();
}
?>
