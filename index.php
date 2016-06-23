<?php include('database.php'); ?>

<?php 

if(isset($_SESSION['email'])){
	if($_SESSION['email']=='admin'){
		echo "<script>window.location='admin.php';</script>";
	}
}

if(isset($_POST["login"]) and !empty($_POST["login"])){
	if(!empty($_POST["email"]) and !empty($_POST["password"])){
		$email = $_POST["email"];
		$password = $_POST["password"];
		
		$query = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email' and password = '$password'");
		if(mysqli_num_rows($query)==1){
			$_SESSION["email"] = $email;
			if($email=="admin"){
				echo "<script>window.location='admin.php';</script>";
			}
		} else {
			echo "<script>alert('email-password combination error');</script>";
		}
	} else {
		echo "<script>alert('email/password not entered');</script>";
	}
}	

if(isset($_POST["register"]) and !empty($_POST["register"])){
	if(!empty($_POST["name"]) and !empty($_POST["email"]) and !empty($_POST["dob"]) and !empty($_POST["password"]) and !empty($_POST["repassword"])){
		$name = $_POST["name"];
		$email = $_POST["email"];
		$dob = $_POST["dob"];
		$password = $_POST["password"];
		$repassword = $_POST["repassword"];
		
		if($password!=$repassword){
			echo "<script>alert('passwords dont match');</script>";
		} else {
			$query = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
			if(mysqli_num_rows($query)==1){
				echo "<script>alert('email already registered');</script>";
			} else {
				$query = mysqli_query($conn, "INSERT INTO users (name,email,dob,password) VALUES ('$name','$email','$dob','$password')");
				if($query){
					$query = mysqli_query($conn, "INSERT INTO questions (email) VALUES ('$email')");
					if($query){
						$_SESSION["email"] = $email;
					} else {
						mysqli_query($conn, "DELETE FROM users WHERE email = '$email'");
						echo "<script>alert('error occured. try later');</script>";
					}	
				} else {
					echo "<script>alert('new user registration failed');</script>";
				}
			}
		}
	} else {
		echo "<script>alert('one or more details not entered');</script>";
	}
}

if(isset($_POST["anssubmit"]) and !empty($_POST["anssubmit"])){
	$email = $_SESSION["email"];
	switch($_POST["anssubmit"]){
		case "pr1":
			$pr1q1 = $_POST["pr1q1"];
			$query = mysqli_query($conn, "UPDATE questions SET pr1q1 = '$pr1q1' WHERE email = '$email'");
			break;
		case "pr2":
			$pr2q1 = $_POST["pr2q1"];
			$query = mysqli_query($conn, "UPDATE questions SET pr2q1 = '$pr2q1' WHERE email = '$email'");
			break;
		case "pr3":
			$pr3q1 = $_POST["pr3q1"];
			$query = mysqli_query($conn, "UPDATE questions SET pr3q1 = '$pr3q1' WHERE email = '$email'");
			break;
		case "pr4":
			$pr4q1 = $_POST["pr4q1"];
			$query = mysqli_query($conn, "UPDATE questions SET pr4q1 = '$pr4q1' WHERE email = '$email'");
			break;
		case "pr5":
			$pr5q1 = $_POST["pr5q1"];
			$query = mysqli_query($conn, "UPDATE questions SET pr5q1 = '$pr1q1' WHERE email = '$email'");
			break;
		case "pr6":
			$pr6q1 = $_POST["pr6q1"];
			$query = mysqli_query($conn, "UPDATE questions SET pr6q1 = '$pr6q1' WHERE email = '$email'");
			break;
	}
	if($query){
		echo "<script>alert('Answer updated');</script>";
	} else {
		echo "<script>alert('Answer not updated. Try later.');</script>";
	}
}

?>

<html>
<head>
<title>Home Page</title>
</head>
<body>

<?php if(!isset($_SESSION["email"])): ?>
<h1>Login to the system</h1>
<form method="post" action="index.php">
	username/email: <input name="email" type="text" >
	password: <input type="password" name="password" >
	<button type="submit" name="login" value="login" >Login</button>
</form>
<h1>Register to the system</h1>
<form method="post" action="index.php">
	<table>
		<tr>
			<td>name:</td>
			<td><input name="name" type="text" value="<?= isset($_POST['name'])?$_POST['name']:''; ?>"></td>
		</tr>
		<tr>
			<td>email/username:</td>
			<td><input name="email" type="text" value="<?= isset($_POST['email'])?$_POST['email']:''; ?>"></td>
		</tr>
		<tr>
			<td>date of birth:</td>
			<td><input name="dob" type="text" value="<?= isset($_POST['dob'])?$_POST['dob']:''; ?>"></td>
		</tr>
		<tr>
			<td>password:</td>
			<td><input name="password" type="password" value="<?= isset($_POST['password'])?$_POST['password']:''; ?>"></td>
		</tr>
		<tr>
			<td>retype password:</td>
			<td><input name="repassword" type="password" value="<?= isset($_POST['repassword'])?$_POST['repassword']:''; ?>"></td>
		</tr>
		<tr>
			<td></td>
			<td><button type="submit" name="register" value="register" >Register</button></td>
		</tr>
	</table>
</form>
<?php else: ?>
<?php 
	$email = $_SESSION['email'];
	$query = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
	if(mysqli_num_rows($query)==1){
		while($row=mysqli_fetch_assoc($query)){
			$name = $row["name"];
		}
	}
	$query = mysqli_query($conn, "SELECT * FROM questions WHERE email = '$email'");
	if(mysqli_num_rows($query)==1){
		while($row=mysqli_fetch_assoc($query)){
			$pr1q1 = $row["pr1q1"];
			$pr2q1 = $row["pr2q1"];
			$pr3q1 = $row["pr3q1"];
			$pr4q1 = $row["pr4q1"];
			$pr5q1 = $row["pr5q1"];
			$pr6q1 = $row["pr6q1"];
		}
	}
?>
<h1>Welcome to the system, <?= $name; ?> <a href="logout.php">Logout</a></h1>
<form action="index.php" method="post">
<table style="width:90%;margin-left:5%;margin-right:5%">
	<tr>
		<td style="width:25%">
			<div style="width:90%;margin-left:5%;margin-right:5%;border:1px solid black;border-radius:8px;">
				<table style="width:100%" >
					<tr>
						<th> Project 1 </th>	
					</tr>
					<tr>
						<td><b>Q1: What is your interest?</b></td>
					</tr>
					<tr>
						<td><textarea style="width:100%" rows="6" name="pr1q1" placeholder="your answer here"><?= !empty($pr1q1)?$pr1q1:''; ?></textarea></td>
					</tr>
					<tr>
						<td><button type="submit" name="anssubmit" value="pr1">Submit</button></td>
					</tr>
				</table>
			</div>
		</td>
		<td style="width:25%">
			<div style="width:90%;margin-left:5%;margin-right:5%;border:1px solid black;border-radius:8px;">
			<table style="width:100%">
				<tr>
					<th> Project 2 </th>	
				</tr>
				<tr>
					<td><b>Q1: What is your interest?</b></td>
				</tr>
				<tr>
					<td><textarea style="width:100%" rows="6" name="pr2q1" placeholder="your answer here"><?= !empty($pr2q1)?$pr2q1:''; ?></textarea></td>
				</tr>
				<tr>
					<td><button type="submit" name="anssubmit" value="pr2">Submit</button></td>
				</tr>
			</table>
			</div>
		</td>
		<td style="width:25%">
			<div style="width:90%;margin-left:5%;margin-right:5%;border:1px solid black;border-radius:8px;">
			<table style="width:100%">
				<tr>
					<th> Project 3 </th>	
				</tr>
				<tr>
					<td><b>Q1: What is your interest?</b></td>
				</tr>
				<tr>
					<td><textarea style="width:100%" rows="6" name="pr3q1" placeholder="your answer here"><?= !empty($pr3q1)?$pr3q1:''; ?></textarea></td>
				</tr>
				<tr>
					<td><button type="submit" name="anssubmit" value="pr3">Submit</button></td>
				</tr>
			</table>
			</div>
		</td>
		<td style="width:25%">
			<div style="width:90%;margin-left:5%;margin-right:5%;border:1px solid black;border-radius:8px;">
			<table style="width:100%">
				<tr>
					<th> Project 4 </th>	
				</tr>
				<tr>
					<td><b>Q1: What is your interest?</b></td>
				</tr>
				<tr>
					<td><textarea style="width:100%" rows="6" name="pr4q1" placeholder="your answer here"><?= !empty($pr4q1)?$pr4q1:''; ?></textarea></td>
				</tr>
				<tr>
					<td><button type="submit" name="anssubmit" value="pr4">Submit</button></td>
				</tr>
			</table>
			</div>
		</td>
	</tr>
	<tr>
		<td style="width:25%">
			<div style="width:90%;margin-left:5%;margin-right:5%;border:1px solid black;border-radius:8px;">
			<table style="width:100%">
				<tr>
					<th> Project 5 </th>	
				</tr>
				<tr>
					<td><b>Q1: What is your interest?</b></td>
				</tr>
				<tr>
					<td><textarea style="width:100%" rows="6" name="pr5q1" placeholder="your answer here"><?= !empty($pr5q1)?$pr5q1:''; ?></textarea></td>
				</tr>
				<tr>
					<td><button type="submit" name="anssubmit" value="pr5">Submit</button></td>
				</tr>
			</table>
			</div>
		</td>
		<td style="width:25%">
			<div style="width:90%;margin-left:5%;margin-right:5%;border:1px solid black;border-radius:8px;">
			<table style="width:100%">
				<tr>
					<th> Project 6 </th>	
				</tr>
				<tr>
					<td><b>Q1: What is your interest?</b></td>
				</tr>
				<tr>
					<td><textarea style="width:100%" rows="6" name="pr6q1" placeholder="your answer here"><?= !empty($pr6q1)?$pr6q1:''; ?></textarea></td>
				</tr>
				<tr>
					<td><button type="submit" name="anssubmit" value="pr6">Submit</button></td>
				</tr>
			</table>
			</div>
		</td>
	</td>
</table>
</form>
<?php endif; ?>
</body>
</html>