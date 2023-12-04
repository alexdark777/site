<?
// ПОДКЛЮЧАЕМ БАЗУ ДАННЫХ
require_once "../inc/db.php";
require_once "$root/inc/config.php";


if($_SESSION['admin_login_domain']){


$pref_id = $_GET['pref_id'];

// если не равно ""
if($pref_id >=1 ){

// подключаем класс администратора
include "$root/inc/class/class.admin.php";
$admin = new admin();

// например меню ид = 3, нам нужно сделать его ид = 4, для этого нам нужно заранее освободить ид 4 и присвоить ему другой ид
$last_obj = $admin->last_obj('prefs_fields', 'prefs_field_id');
// следующий за последним ид меню
$down_obj = $last_obj + 1;

$sql = "SELECT prefs_field_sort FROM prefs_fields WHERE prefs_field_sort > '$pref_id' ORDER BY prefs_field_sort LIMIT 1";
$next_obj_query = $query($sql);
$next_obj = $result($next_obj_query, 'prefs_field_sort');
if($next_obj==false){
$sql = "SELECT prefs_field_sort FROM prefs_fields WHERE prefs_field_sort < '$pref_id' ORDER BY prefs_field_sort DESC LIMIT 1";
$next_obj_query = $query($sql);
$next_obj = $result($next_obj_query, 'prefs_field_sort');
}

// присвоить сл. за ук. сл. за посл. ид (освободить нужный ид)
$sql = "UPDATE prefs_fields SET prefs_field_sort = '$down_obj' WHERE prefs_field_sort = '$next_obj' LIMIT 1";
$query($sql);

// присвоить сл. за ук. ид освобожденному
$sql = "UPDATE prefs_fields SET prefs_field_sort = '$next_obj' WHERE prefs_field_sort = '$pref_id' LIMIT 1";
$query($sql);


// вернуть освобожденному ид указанный
$sql = "UPDATE prefs_fields SET prefs_field_sort = '$pref_id' WHERE prefs_field_sort = '$down_obj' LIMIT 1";
$query($sql);

// показать созданные разделы
$prefs_list = $admin->obj_list('*', 'prefs_fields', 0, 100, 'prefs_array', false, false, 'prefs_field_sort');
$smarty->assign('prefs', $prefs_list);

// страница
$page = "ajax_sort_prefs";

// расширение страниц
$smarty->assign('sp', $sp);

// компилятор
$smarty->display("$page.tpl");	
}

}
?>