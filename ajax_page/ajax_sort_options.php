<?
// ���������� ���� ������
require_once "../inc/db.php";
require_once "$root/inc/config.php";


if($_SESSION['admin_login_domain']){


$option_id = $_GET['option_id'];
$fields_section_id = $_GET['section_id'];

// ���� �� ����� ""
if($option_id >=1 ){

// ���������� ����� ��������������
include "$root/inc/class/class.admin.php";
$admin = new admin();

// �������� ���� �� = 3, ��� ����� ������� ��� �� = 4, ��� ����� ��� ����� ������� ���������� �� 4 � ��������� ��� ������ ��
$last_obj = $admin->last_obj('prefs_fields_options', 'option_id');
// ��������� �� ��������� �� ����
$down_obj = $last_obj + 1;

$sql = "SELECT option_sort FROM prefs_fields_options WHERE option_sort > '$option_id' AND section_id = '$fields_section_id' ORDER BY option_sort LIMIT 1";
$next_obj_query = $query($sql);
$next_obj = $result($next_obj_query, 'option_sort');
if($next_obj==false){
$sql = "SELECT option_sort FROM prefs_fields_options WHERE option_sort < '$option_id' AND section_id = '$fields_section_id' ORDER BY option_sort DESC LIMIT 1";
$next_obj_query = $query($sql);
$next_obj = $result($next_obj_query, 'option_sort');
}

// ��������� ��. �� ��. ��. �� ����. �� (���������� ������ ��)
$sql = "UPDATE prefs_fields_options SET option_sort = '$down_obj' WHERE option_sort = '$next_obj' AND section_id = '$fields_section_id' LIMIT 1";
$query($sql);

// ��������� ��. �� ��. �� ��������������
$sql = "UPDATE prefs_fields_options SET option_sort = '$next_obj' WHERE option_sort = '$option_id' AND section_id = '$fields_section_id' LIMIT 1";
$query($sql);


// ������� �������������� �� ���������
$sql = "UPDATE prefs_fields_options SET option_sort = '$option_id' WHERE option_sort = '$down_obj' AND section_id = '$fields_section_id' LIMIT 1";
$query($sql);

// �������� ��������� �������
$options = $admin->option_list('*', 'prefs_fields_options', 'section_id', $fields_section_id, 'option_sort', 0, 999, 'options_array');
$cn = 0;
$page = "admin_sectioneditor";

foreach ($options as $opt){
$cn++;
	print "<tr><td> {$cn}. {$opt[option_name]}</td> <td width='30px'> <a href='/admin_optionseditor/{$opt[option_id]}.{$sp}' style='text-decoration:none;' title='�������� �������� ����'><span class='main_editor_16'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></a> </td> <td width='30px'> <a href='/?page={$page}&task=unoptions&option_id={$opt[option_id]}&fields_section_id={$fields_section_id}' style='text-decoration:none;' target='hidden_frame' title='������� ����'><span class='main_delete_16'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></a> </td>"; 
if (count($options) >= 2){ 
	print "<td width='30px'><a href='javascript:void(0);' onclick='sort_obj({$opt[option_sort]}, {$opt[section_id]});' title='�����������'><img src='/images/down.gif' alt='����'></a></td>";
}
	print "</tr>";
}

}

}	
?>