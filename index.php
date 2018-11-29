<?php 
#ob_start(); 
include('include/class.crud.1.php'); 
#ob_end_flush();
?>

<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<head>
<meta charset="utf-8">
<title>Listing</title>

<script src="js/jquery.js"></script>  

<script>
	function edit(id)
	{
		$('.edit_'+id).show();
		$('.edit_text_'+id).hide();
		$('#edit_button_'+id).hide();
		$('#save_button_'+id).show();
	}
	function save_action()
	{
		jQuery.ajax({
			type: "POST",
			url: "include/ajax_request.php",
			data: jQuery('#submit').serialize()+'&method=save',
			success: function (result) 
			{
				if(result==1)
				{
					listing();
					$('#msg').show().delay(10000).fadeOut().html('Record saved with result: '+result);
				}
			}
		});
	}
	function edit_action(id)
	{
		jQuery.ajax({
			type: "POST",
			url: "include/ajax_request.php",
			data: jQuery('#submit_'+id).serialize()+'&method=edit&id='+id,
			success: function (result) 
			{
				$('#edit_button_'+id).show();
				$('#save_button_'+id).hide();
				listing();
				$('#msg').show().delay(10000).fadeOut().html('Record edited name= '+result);	
			}
		});
	}
	function listing()
	{
		jQuery.ajax({
			type: "POST",
			url: "include/ajax_request.php",
			data: 'method=listing',
			success: function (result) 
			{
				$('#replace_listing').html(result);
			}
		});
	}
	function delete_action(id,name)
	{
		var r=confirm("Do you really want to delete name:"+name);
		if (r==true)
		{
			jQuery.ajax({
				type: "POST",
				url: "include/ajax_request.php",
				data: 'method=delete&id='+id,
				success: function (result) 
				{
					listing();
					$('#msg').show().delay(5000).fadeOut().html('Record deleted; name: '+result);
				}
			});
		}
	}
	listing();
</script>

<style>
input
{ border: 1px solid lightblue;}
</style>

</head>
<body>
	<div align="center">
		<h2>Header for index.php</h2>
		<div style="width:80%;padding-top:20px;">
			<div id="msg" style="color:green;display:none;"></div>
			<div id="replace_listing"></div>
		</div>
	</div>
</body>
</html>