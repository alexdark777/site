<?
// ПОДКЛЮЧАЕМ БАЗУ ДАННЫХ
require_once "../inc/db.php";

// подключим класс пользователя
require_once("../inc/class/class.users.php");
$users = new users();

$country = $_GET['country'];
$region_list = $users->region_list($country);

echo "<br><select name='id_region' onchange='ajax_sity($country, this.value);' class='sure' style='width:200px;'>";
echo "<option value=''>...";
foreach ($region_list as $val){
	echo "<option value='$val[id_region]'>$val[name]";
}
echo "</select>";
?>