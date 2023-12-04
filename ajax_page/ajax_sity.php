<?
// ПОДКЛЮЧАЕМ БАЗУ ДАННЫХ
require_once "../inc/db.php";

// подключим класс пользователя
require_once("../inc/class/class.users.php");
$users = new users();

$country = $_GET['country'];
$region = $_GET['region'];

$sity_list = $users->sity_list($country, $region);

echo "<br><select name='id_sity' onchange='ajax_street($country, $region, this.value)' class='sure' style='width:200px;'>";
echo "<option value=''>...";
foreach ($sity_list as $val){
	echo "<option value='$val[id_city]'>$val[name]";
}
echo "</select>";
?>