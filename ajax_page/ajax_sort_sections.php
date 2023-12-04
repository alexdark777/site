<?
// ПОДКЛЮЧАЕМ БАЗУ ДАННЫХ
require_once "../inc/db.php";
require_once "$root/inc/config.php";

if($_SESSION['admin_login_domain']){

$section_id = $_GET['section_id'];
$pref_id = $_GET['pref_id'];

// если не равно ""
if($section_id >=1 ){

// подключаем класс администратора
include "../inc/class/class.admin.php";
$admin = new admin();

// например меню ид = 3, нам нужно сделать его ид = 4, для этого нам нужно заранее освободить ид 4 и присвоить ему другой ид
$last_obj = $admin->last_obj('prefs_fields_sections', 'fields_section_id');
// следующий за последним ид меню
$down_obj = $last_obj + 1;

$sql = "SELECT fields_section_sort FROM prefs_fields_sections WHERE fields_section_sort > '$section_id' AND fields_section_prefs_id = '$pref_id' ORDER BY fields_section_sort LIMIT 1";
$next_obj_query = $query($sql);
$next_obj = $result($next_obj_query, 'fields_section_sort');
if($next_obj==false){
$sql = "SELECT fields_section_sort FROM prefs_fields_sections WHERE fields_section_sort < '$section_id' AND fields_section_prefs_id = '$pref_id' ORDER BY fields_section_sort DESC LIMIT 1";
$next_obj_query = $query($sql);
$next_obj = $result($next_obj_query, 'fields_section_sort');
}

// присвоить сл. за ук. сл. за посл. ид (освободить нужный ид)
$sql = "UPDATE prefs_fields_sections SET fields_section_sort = '$down_obj' WHERE fields_section_sort = '$next_obj' AND fields_section_prefs_id = '$pref_id' LIMIT 1";
$query($sql);

// присвоить сл. за ук. ид освобожденному
$sql = "UPDATE prefs_fields_sections SET fields_section_sort = '$next_obj' WHERE fields_section_sort = '$section_id' AND fields_section_prefs_id = '$pref_id' LIMIT 1";
$query($sql);


// вернуть освобожденному ид указанный
$sql = "UPDATE prefs_fields_sections SET fields_section_sort = '$section_id' WHERE fields_section_sort = '$down_obj' AND fields_section_prefs_id = '$pref_id' LIMIT 1";
$query($sql);

// показать созданные поля
$fields_list = $admin->option_list('*', 'prefs_fields_sections', 'fields_section_prefs_id', $pref_id, 'fields_section_sort', 0, 999, 'fields_array');

// страница
$page = "ajax_sort_fields";

// расширение страниц
$smarty->assign('sp', $sp);
$smarty->assign('fields', $fields_list);
$smarty->assign('prefs_field_id', $pref_id);

// компилятор
$smarty->display("$page.tpl");	
}

}
?>