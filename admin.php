<?php include('database.php'); ?>
<?php if(!isset($_SESSION["email"])) header("Location: index.php");?>
<?php if($_SESSION["email"]!="admin") header("Location: index.php");?>

<?php
	if(isset($_POST['change']) and !empty($_POST['change'])){
		if(!empty($_POST['oldpwd']) and !empty($_POST['newpwd']) and !empty($_POST['renewpwd'])){
			$oldpwd = $_POST['oldpwd'];
			$newpwd = $_POST['newpwd'];
			$renewpwd = $_POST['renewpwd'];
			if($newpwd != $renewpwd){
				echo "<script>alert('New passwords don\'t match');</script>";
			} else {
				$query = mysqli_query($conn, "SELECT * FROM users WHERE email = 'admin'");
				while($row = mysqli_fetch_assoc($query)){
					if($row['password']!=$oldpwd){
						echo "<script>alert('Old password don\'t match');</script>";
					} else {
						$query2 = mysqli_query($conn, "UPDATE users SET password = '$newpwd' WHERE email = 'admin'");
						if($query2){
							echo "<script>alert('Password Updated');</script>";
						} else {
							echo "<script>alert('Password not updated. Try again later.');</script>";
						}
					}
				}
			}
		} else {
			echo "<script>alert('One or more password field is empty. Fill all the fields.');</script>";	
		}
	}

	if(isset($_GET['act']) and $_GET['act']=='updateq'){
		if(isset($_POST['updtq']) and !empty($_POST['updtq'])){
			$str2 = "UPDATE questions SET ";
			$flag = 1;
			foreach($_POST as $key=>$value){
				if($key=='uid'){
					$id = $value;
				} elseif($key=='updtq'){
					// ignore
				} else {
					if($flag==1){
						$str2 = $str2."$key = '$value' ";
						$flag = 0;
					} else {
						$str2 = $str2.",$key = '$value' ";
					}
				}
			}

			$str2 = $str2." WHERE id = '$id'";

			if(mysqli_query($conn, $str2)){
				echo "<script>alert('Value Updated');window.location='admin.php';</script>";
			} else {
				echo "<script>alert('Value not updated');window.location='admin.php';</script>";
			}
		}
	}
	
?>

<html>
<head>
	<title>Admin Panel</title>
</head>
<body>
<style>
	table, th, td {
	    border: 1px solid black;
	    border-collapse: collapse;
	}

	.tableclass{
		width:100%;
		
	}

	button{
		cursor:pointer;
	}
</style>
	<h1> Welcome Admin </h1><br>
	<form method="post" action="admin.php" id="changepwdform">
		Old Password: <input type="password" name="oldpwd">
		New Password: <input type="password" name="newpwd">
		Retype New Password: <input type="password" name="renewpwd">
		<button type="submit" name="change" value="changepwd"> Change Password </button>
	</form>

	
	<table style="width:100%;margin:0px;">
		<tr>
			<th> # </th>
			<th> User </th>
			<th> Project1 <br> Question1 </th>
			<th> Project2 <br> Question1 </th>
			<th> Project3 <br> Question1 </th>
			<th> Project4 <br> Question1 </th>
			<th> Project5 <br> Question1 </th>
			<th> Project6 <br> Question1 </th>
			<th> Change value </th>
		</tr>
<?php
	$query = mysqli_query($conn, "SELECT * FROM users");
	$uid = 0;
	while($row = mysqli_fetch_assoc($query)):
		$name = $row['name'];
		$email = $row['email'];
		$dob = $row['dob'];
		if($email != "admin"): $uid = $uid + 1;
?>	
		<tr>
			<td><?= $uid; ?></td>
			<td><?= $name."<br>".$email; ?></td>
<?php
			$query2 = mysqli_query($conn, "SELECT * FROM questions WHERE email = '$email'");
			while($row2 = mysqli_fetch_assoc($query2)): $id = $row2['id'];
?>
			<form method="post" action="admin.php?act=updateq">
				<input type="hidden" name="uid" value="<?= $id; ?>" />
			<td><textarea name="pr1q1"><?= @$row2['pr1q1']; ?></textarea></td>
			<td><textarea name="pr2q1"><?= @$row2['pr2q1']; ?></textarea></td>
			<td><textarea name="pr3q1"><?= @$row2['pr3q1']; ?></textarea></td>
			<td><textarea name="pr4q1"><?= @$row2['pr4q1']; ?></textarea></td>
			<td><textarea name="pr5q1"><?= @$row2['pr5q1']; ?></textarea></td>
			<td><textarea name="pr6q1"><?= @$row2['pr6q1']; ?></textarea></td>
			<td><button type="submit" name="updtq" value="updtq">Update Question</td>
			</form>
<?php
			endwhile;
		endif;
?>
		</tr>
<?php
	endwhile;

?>
	</table>
	

<script src="js/jquery.js"></script>
</body>
</html>