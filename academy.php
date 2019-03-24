<?php

header("Content-Type: text/html; charset=utf-8");
if($_SERVER['REQUEST_METHOD'] == 'POST') {
	if(empty($_POST['g-recaptcha-response'])) 
	{
		header('location: err/null_recaptcha.html');
		 exit();
	}
	
	
	$url = 'https://www.google.com/recaptcha/api/siteverify';
	
	$secret = '6LdKi5kUAAAAANBcreIYVL-7S27Ix_XF8dj-NryQ';
	$recaptcha = $_POST['g-recaptcha-response'];
	$ip = $_SERVER['REMOTE_ADDR'];
	
	$url_data = $url.'?secret='.$secret.'&response='.$recaptcha.'&remoteip='.$ip;
	
	$curl = curl_init();
	
	curl_setopt($curl,CURLOPT_URL, $url_data);
	curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, FALSE);
	
	curl_setopt($curl,CURLOPT_RETURNTRANSFER, 1);
	
	$res = curl_exec($curl);
	curl_close($curl);
	
	$res = json_decode($res);
	
	if($res->success == 0)
	{
		 header('location: err/recaptcha.html');
		 exit();
	}
}


$name =  stripslashes(htmlspecialchars(strip_tags(trim($_POST['name']))));
$phone = stripslashes(htmlspecialchars(strip_tags(trim($_POST['phone']))));
$birth =  stripslashes(htmlspecialchars(strip_tags(trim($_POST['birth']))));
$email =  stripslashes(htmlspecialchars(strip_tags(trim($_POST['email']))));
$comment = stripslashes(htmlspecialchars(strip_tags(trim($_POST['comment']))));

	
if (!preg_match('/^[А-ЯA-Z][а-яa-zА-ЯA-Z\-]{0,}\s[А-ЯA-Z][а-яa-zА-ЯA-Z\-]{1,}(\s[А-ЯA-Z][а-яa-zА-ЯA-Z\-]{1,})?$/u', $name))
	{
		header('location: err/name.html');
		exit();
	}
if (!preg_match("/^(\s*)?(\+)?([- _():=+]?\d[- _():=+]?){10,14}(\s*)?$/",$phone))
	{
		header('location: err/phone.html');
		exit();
	}
if (!filter_var($email, FILTER_VALIDATE_EMAIL))
	{
		header('location: err/email.html');
		exit();
	}
$host = "localhost"; 
$user = "root"; 
$password = "root"; 
$base = 'aero'; 
$table = 'academy';

$mysqli = new mysqli($host,$user,$password);
mysqli_set_charset($mysqli, UTF8);

$db_select = mysqli_select_db($mysqli,$base);

if (!$db_select) {
  $create_db = "CREATE DATABASE IF NOT EXISTS $base CHARACTER SET utf8 COLLATE utf8_general_ci";
  $create_tb = "CREATE TABLE $base.$table
  (
  id INT(50) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  date DATETIME NOT NULL,
  name VARCHAR(50) NOT NULL,
  phone TEXT NOT NULL,
  birth DATE NOT NULL,
  email VARCHAR(50) NOT NULL,
  comment TEXT(255) NOT NULL
  )";
  
  $mysqli->query($create_db);
  $mysqli->query($create_tb);
}

if ($mysqli->connect_error) 
{
	die('Ошибка : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
}
$name = $mysqli->real_escape_string($name);
$phone = $mysqli->real_escape_string($phone);
$birth = $mysqli->real_escape_string($birth);
$email = $mysqli->real_escape_string($email);
$comment = $mysqli->real_escape_string($comment);

$result = $mysqli->query("INSERT INTO $base.$table (date, name, phone, birth, email, comment) VALUES (NOW(), '$name', '$phone', '$birth', '$email', '$comment')");

if ($result == true)
{
	header('location: err/success.html');
	
	exit();
}
else
{
	header('location: err/error.html');
	exit();
}
$mysqli->close();
?>
