<?
// ПОДКЛЮЧАЕМ БАЗУ ДАННЫХ
require_once "../inc/db.php";

// ПЕРЕМЕННЫЕ ДЛЯ ЗАПРОСОВ К БАЗЕ ДАННЫХ
$query = "mysql_query";
$assoc = "mysql_fetch_assoc";
$num_rows = "mysql_num_rows";
$object = "mysql_fetch_object";
$result = "mysql_result";

header('Content-type: text/html; charset=UTF-8');

// СЕССИЯ ДЛЯ КАПЧИ
session_start();


// подключим класс пользователя
require_once("../inc/class/class.users.php");
$users = new users();


// если авторизирован
//if($_SESSION['login_domain']){

// присвоим новую сессию для голосования только один раз
if (! isset($_SESSION['rate_session'])){
	$rate_session = md5(rand(9999, 99999999));
	$_SESSION['rate_session'] = $rate_session;
}else{
	$rate_session = $_SESSION['rate_session'];
}

// получим и запишем ай-пи в базу для голосования только один раз
$ip = $users->GetRealIp();

// получаем входные данные			
$rate = $_GET['rate'];
$id = $_GET['id'];
$task = $_GET['task'];

// прибавляем рейтинг
if($task=="plus_rate"){

// проверим наличие сессии в таблице рейтингов
$sql = "SELECT rate_session FROM users_rates WHERE rate_user_id = '$id' AND rate_session = '$rate_session' LIMIT 1";
$check_session = $query($sql);
$session = $result($check_session, 'rate_session');

// если сессия не найдена проверим ай-пи
if($session==false){

$sql = "SELECT rate_ip FROM users_rates WHERE rate_user_id = '$id' AND rate_ip = '$ip' LIMIT 1";
$check_ip = $query($sql);
$rate_ip = $result($check_ip, 'rate_ip');

// если ай-пи не найден добавим рейтинг
if($rate_ip==false){

$sql = "INSERT INTO users_rates 
(rate_id, rate_user_id, rate_ip, rate_session, rate_count,rate_date) 
VALUE 
('', '$id', '$ip', '$rate_session', '$rate', NOW())";

$query($sql);
$query("UPDATE users SET rate = (rate + '$rate') WHERE id = '$id' LIMIT 1");

// сосчитаем рейтинг и покажем голосующему
$new_rate = $users->user_rate($id);
echo $new_rate;
}else{
	echo "<img src='/images/baad.png'>";
}
}else{
	echo "<img src='/images/baad.png'>";
}
}
//}



?>