<?
// ПОДКЛЮЧАЕМ БАЗУ ДАННЫХ
require_once "../inc/db.php";

// подключим класс пользователя
require_once("../inc/class/class.users.php");
$users = new users();

$email = $_GET['email'];
$check_email = $users->check_info('email', 'users', 'email', $email);

if($check_email){
		echo '<img src="/images/baad.png">';
}else{
	if(strlen($email) >= 6){
		if($users->isEmail($email)){
			echo '<img src="/images/good.png">';
		}else{
			echo '<img src="/images/baad.png">';
		}
	}else{
		echo '<img src="/images/baad.png">';
	}
}
?>