<?php
include 'dbconfig.1.php';
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
		$sql="SELECT * from tbl_person order by id";
		$data = $this->conn->query($sql)->fetchAll();
		foreach($data as $row)
		{
			?>

			<!-- for each row of data a form is created containing a table -->
			<form name="submit" id="submit_<?php echo $row['id'];?>" method="post">
				<table style="width: 100%; border-bottom: 1px solid lightblue;">
					<tr>
						<td width="30%;">
							<span class="edit_text_<?php echo $row['id'];?>"><?php echo $row['name'];?></span>
							<input type="text" id="name" name="name" class="edit_<?php echo $row['id'];?>" style="display:none;" value="<?php echo $row['name'];?>"/>
						</td>
						<td width="60%;">
							<span  class="edit_text_<?php echo $row['id'];?>"><?php echo $row['address'];?></span>
							<input type="text" id="address" name="address" class="edit_<?php echo $row['id'];?>" style="display:none;" value="<?php echo $row['address'];?>"/>
						</td>
						<td>
							<a href="#" onclick="edit(			'<?php echo $row['id'];?>');" id="edit_button_<?php echo $row['id'];?>"><img src="images/edit.png"></a>
							<a href="#" onclick="edit_action(	'<?php echo $row['id'];?>');" id="save_button_<?php echo $row['id'];?>" style="display:none;"><img src="images/add.png"></a>
							<a href="#" onclick="delete_action(	'<?php echo $row['id'];?>');"><img src="images/delete.png"></a>
						</td>
					
					</tr>
				</table>
			</form>
			
			<?php
		}
		?>

		</br>

		<!-- Single row save new form -->
		<form name="submit" id="submit" method="post">
			<table style="width:100%;">
				<tr>
					<td width="30%;">
						<input type="text" id="name" name="name" class="edit_<?php echo $row['id'];?>"  required />
					</td>
					<td width="50%;">
						<input type="text" id="address" name="address" class="edit_<?php echo $row['id'];?>" required />
					</td>
					<td>
						<a href="#" onclick="save_action();" ><img src="images/add.png"></a>
					</td>
				</tr>
			</table>
		</form>
			
<?php
		
	} // closes public function listing()

		function edit()
		{
			extract($_POST);
			$this->conn->exec("update tbl_person set name='$name',address='$address' where id=".$id);
		}

		function save()
		{
			extract($_POST);
			if($name || $address)
			{
				$result = $this->conn->exec("insert into tbl_person(name,address) VALUES('$name','$address')");
				echo $result;
			}
		}

		function delete_record()
		{
			extract($_POST);
			$result = $this->conn->exec("delete from tbl_person where id=".$id);
		}

} // closes class login extends DB_con

	$obj=new login();

?>