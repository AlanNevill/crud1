<?php
include_once 'dbconfig.1.php';
class login extends DB_con
{
	public function listing()
	{
		?>
		<table style="width:100%;">
			<tr style="background-color: lightblue;">
				<th width="30%;">Name</th>
				<th width="50%;">Address</th>
				<th width="20%;">Action</th>
			</tr>
		</table>

		<?php
		$sql="SELECT * from tbl_person";
		$result=mysqli_query($this->conn, $sql);
		while($row=mysqli_fetch_array($result))
		{
			?>
			<form name="submit" id="submit_<?php echo $row['id'];?>" method="post">
				<table style="width: 100%; border-bottom: 1px solid lightblue;">
					<tr>
						<td width="30%;">
							<span class="edit_text_<?php echo $row['id'];?>"><?php echo $row['name'];?></span>
							<input type="text" id="name" name="name" class="edit_<?php echo $row['id'];?>" style="display:none;" value="<?php echo $row['name'];?>"/>
						</td>
						<td width="50%;">
							<span  class="edit_text_<?php echo $row['id'];?>"><?php echo $row['address'];?></span>
							<input type="text" id="address" name="address" class="edit_<?php echo $row['id'];?>" style="display:none;" value="<?php echo $row['address'];?>"/>
						</td>
						<td width="20%;">
							<a href="#" onclick="edit('<?php echo $row['id'];?>');" id="edit_button_<?php echo $row['id'];?>"><img src="images/edit.png"></a>
							<a href="#" onclick="edit_action('<?php echo $row['id'];?>');" id="save_button_<?php echo $row['id'];?>" style="display:none;"><img src="images/add.png"></a>
							<a href="#" onclick="delete_action('<?php echo $row['id'];?>');"><img src="images/delete.png"></a>
						</td>
					
					</tr>
				</table>
			</form>
			<?php
		}
		?>
		
		<form name="submit" id="submit" method="post">
				<table style="width:50%;">
					<tr>
						<td width="30%;">
							<input type="text" id="name" name="name" class="edit_<?php echo $row['id'];?>"  required />
						</td>
						<td>
							<input type="text" id="address" name="address" class="edit_<?php echo $row['id'];?>" required />
						</td>
						<td width="20%;">
							<a href="#" onclick="save_action();" ><img src="images/add.png"></a>
						</td>
					</tr>
				</table>
			</form>
			
		<?php
		
	}

	function edit()
	{
		extract($_POST);
		mysqli_query($this->conn,"update tbl_person set name='$name',address='$address' where id=".$id);
	}

	function save()
	{
		extract($_POST);
		if($name || $address)
		{
			echo mysqli_query($this->conn,"insert into tbl_person(name,address) VALUES('$name','$address')");
		}
	}

	function delete_record()
	{
		extract($_POST);
		mysqli_query($this->conn,"delete from tbl_person where id=".$id);
	}
}
$obj=new login();
?>