<?
// ПОДКЛЮЧАЕМ БАЗУ ДАННЫХ
require_once "../inc/db.php";

// подключим класс пользователя
require_once("../inc/class/class.users.php");
$users = new users();

$login = $_GET['login'];
$check_login = $users->check_info('login', 'users', 'login', $login);

if($check_login){
		echo "<img src='/images/baad.png'>";
}else{
	if(strlen($login) >= 6){
		if($users->isText($login)){
			echo '<img src="/images/good.png">';
		}else{
			echo '<img src="/images/baad.png">';
		}
	}else{
		echo '<img src="/images/baad.png">';
	}
}
?>