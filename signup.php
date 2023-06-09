<?php  

require "functions.php";

$errors = array();

if($_SERVER['REQUEST_METHOD'] == "POST")
{

	$errors = signup($_POST);

	if(count($errors) == 0)
	{
		header("Location: login.php");
		die;
	}
}

?>




<style>
    body {
        background-color: #f5f5f5;
        font-family: Arial, sans-serif;
    }

    h1 {
        text-align: center;
    }

    div {
        background-color: #fff;
        border: 1px solid #ccc;
        border-radius: 5px;
        padding: 20px;
        margin: 20px auto;
        max-width: 400px;
    }

    form {
        margin-top: 20px;
    }

    input[type="text"],
    input[type="password"],
    input[type="submit"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    input[type="submit"] {
        background-color: #4CAF50;
        color: #fff;
        cursor: pointer;
    }

    input[type="submit"]:hover {
        background-color: #45a049;
    }

    .error {
        color: #f00;
        margin-bottom: 10px;
    }
</style>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Signup</title>
</head>
<body>
    <h1>Signup</h1>

    <?php include('header.php')?>

    <div>
        <div>
            <?php if(count($errors) > 0):?>
                <?php foreach ($errors as $error):?>
                    <div class="error"><?= $error?></div>
                <?php endforeach;?>
            <?php endif;?>
        </div>
        <form method="post">
            <input type="text" name="username" placeholder="Username"><br>
            <input type="text" name="email" placeholder="Email"><br>
            <input type="password" name="password" placeholder="Password"><br>
            <input type="password" name="password2" placeholder="Retype Password"><br>
            <br>
            <input type="submit" value="Signup">
        </form>
    </div>
</body>
</html>
