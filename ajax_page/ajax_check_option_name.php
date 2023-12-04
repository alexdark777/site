<?
// ПОДКЛЮЧАЕМ БАЗУ ДАННЫХ
require_once "../inc/db.php";
require_once "$root/inc/config.php";

if($_SESSION['admin_login_domain']){

// принимаем ид нужной категории и показываем ее содержимое к редактиованию
$section_id = $_GET['section_id'];
$option_name = $_GET['value'];
if($option_name!=""){

$sql = "SELECT option_name FROM prefs_fields_options WHERE option_name = '$option_name' AND section_id = '$section_id' LIMIT 1";
$check_sql = $query($sql);
$check_name = $result($check_sql, 'option_name');

if($check_name!=false){
	print '<img src="/images/baad.png">';
}else{
	print '<img src="/images/good.png">';
}
}


}// END авторизирован
?>