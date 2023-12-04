<?
// ПОДКЛЮЧАЕМ БАЗУ ДАННЫХ
require_once "../inc/db.php";
require_once "$root/inc/config.php";

if($_SESSION['admin_login_domain']){

// принимаем ид нужной категории и показываем ее содержимое к редактиованию
$fields_section_name = $_GET['value'];
if($fields_section_name!=""){
$check_name = $users->check_info('fields_section_name', 'prefs_fields_sections', 'fields_section_name', $fields_section_name);
if($check_name!=false){
	print '<img src="/images/reg/baad.png">';
}else{
	print '<img src="/images/reg/good.png">';
}
}else{
	print '<img src="/images/reg/baad.png">';
}


}// END авторизирован
?>