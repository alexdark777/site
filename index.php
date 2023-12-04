<?
// ПОДКЛЮЧАЕМ БАЗУ ДАННЫХ
require_once "inc/db.php";


// ПОДКЛЮЧАЕМ КОНФИГУРАЦИЮ
require_once "inc/config.php";

/*
УНИКАЛЬНАЯ ТЕХНОЛОГИЯ ПЕРЕКЛЮЧЕНИЯ СТРАНИЦ
АВТОР ВАЛЕРИЙ ТЕМНЫЙ 2 ОКТЯБРЯ 2009 ГОД
wallstudiomaster@mail.ru
Данная технология:
switch(позволяет){
case "1":
- избавиться от громоздкого кода в страницах;
break;
case "2":
- ускорить процесс отображения страниц в десятки раз;
break;
case "3":
- мгновенно обнаруживать шаблонизатором новые страницы;
break;

Увидев ошибку "Smarty error: unable to read resource:" не паникуйте!
Просто создайте в папке templates указанную в ошибке страницу с расширением tpl
И шаблонизатор ее мгновенно отобразит в окне браузера
*/




// ПОДДЕРЖКА ПОЛУЧЕННОЙ СЕССИИ ДЛЯ НАВИГАЦИИ ПО САЙТУ
// Администратора
if($_SESSION['admin_login_domain']){
			$insert_admin = 1;
}




// Пользователя
if($_SESSION['login_domain']){
			$insert = 1;
			$sender = $users->user_full_info('id');			  
}





// Данные другого пользователя
if($id!=false){
			$owner_array = $users->owner_info($id);
			$smarty->assign('owner', $owner_array);
}




// АВТОРИЗАЦИЯ ЗАРЕГИСТРИРОВАННОГО ПОЛЬЗОВАТЕЛЯ
if($task=="go"){
			$page = "index";
			$goto = $users->authorize();			
if($goto!=0){
			// авторизировать пользователя
			$insert = 1;
			$sender = $users->user_full_info('id');
			header("Location: ".$_SERVER['HTTP_REFERER']);
					
}else{
			header("Location: /reg.$sp");
}
}




// подключаем класс новостей
require_once("inc/class/class.news.php");
$news = new news();

// показать 10 заголовков новостей
$new_news = $news->news_list(0, 10, false);
$smarty->assign('new_news', $new_news);



// подключаем класс статических страниц
require_once("inc/class/class.content.php");
$contents = new content();

// показать 10 заголовков новостей
$new_content = $contents->content_list(0, 10, false);
$smarty->assign('new_content', $new_content);



// показать список локализаций
$locales = $users->locales_list();

// разделы анкеты
	$pref = $users->prefs_list();
	
	//массив вопросов анкеты
	$field = $users->field_list();
	
	//массив опций вопросов
	$opt = $users->opt_list();

    // все ответы пользователя
	$answer = $users->answer_list($id);

// START SWITCH PAGE
switch($page) {
			case "$page":
			if(file_exists("templates/$page.tpl")){
							include "pages/$page.php";
			}else{
				$page = "404";
				$title = "Страница не найдена";
			}
			break;
}// END SWITCH PAGE





// вычислить бота и записать в базу
$user_agent = $_SERVER['HTTP_USER_AGENT'];
// массив имен ботов (не рекомендуется более 5 имен)
$bot_name = Array("Googlebot", "Yandex", "MSNBot", "StackRambler", "Mail.Ru");
// разворачиваем массив для проверки
foreach ($bot_name as $bots){
	// функция проверки и записи в базу
	$users->check_bots($user_agent, $bots);
}


// кто на сайте
$online_users = $users->online_users();
$smarty->assign('online_users', $online_users);
// счетчик онлайн пользователей
$online_count = $users->online_count();
$smarty->assign('online_count', $online_count);
// счетчик всех посетителей (уникальных)
include "hacks/all_online.php";



// ВЫКИНУТЬ ЕСЛИ ПОЛЬЗОВАТЕЛЬ НЕ СУЩЕСТВУЕТ
if(($insert==1) AND ($task!="go")){
	$check_id = $users->owner_full_info('id', $sender);
	if($check_id==false){
		unset ($_SESSION['login_domain']);
		$insert = 0;
		// показать уведомление
		$content = $all_lang[1];
		header("Location: /");
	}
}




// УДАЛИТЬ ФАЙЛ УСТАНОВЩИК
if($task=="dellswp"){
// ссылка на файл
$swp = "swp/install.php";
// удалить файл
	@unlink($swp);
	if(file_exists($swp)){
	// нет доступа к файлу
		$content = $all_lang[147];
	}else{
	// файл удален
		$content = $all_lang[146];
	}
	// перезагрузить страницу
	$users->reload_page();
}



// ВЫХОД ИЗ СИСТЕМЫ ОЧИСТКА СЕССИИ
if($task=="out"){
		$users->out_user();
}




// локализация
$smarty->assign('locale', $locale);

// АПИ анкеты
$smarty->assign('pref', $pref);
$smarty->assign('field', $field);
$smarty->assign('opt', $opt);
$smarty->assign('info', $answer);
	
// расширение страниц
$smarty->assign('sp', $sp);

// страницы
$smarty->assign('page', $page);

// вход пользователя
$smarty->assign('insert', $insert);

// вход администратора
$smarty->assign('insert_admin', $insert_admin);

// заголовок страницы
$title = htmlspecialchars($title, ENT_QUOTES);
$smarty->assign('title', $title);

// описание страницы
$description = htmlspecialchars($description, ENT_QUOTES);
$smarty->assign('description', $description);

// ключевые слова для страницы
$smarty->assign('keywords', $keywords);

// используемый скин системы
$smarty->assign('skin', $skin);

// системные уведомления и необходимые фразы из файла локализации
$smarty->assign('content', $content);

// идентификаторы пользователя
$smarty->assign('id', $id);
$smarty->assign('sender', $sender);

// слова в шаблоны для поддержки мультиязычности
$smarty->assign('lang', $lang);
$smarty->assign('all_lang', $all_lang);
$smarty->assign('pref', $pref);

// список локализаций
$smarty->assign('locales', $locales);

// каптча для защиты от спама
$smarty->assign('kaptcha_img_url', '/kcaptcha/?' . str_replace(array(' ', '.'), '', microtime()));

// компилятор обрабатывающий страницу
$smarty->display("$page.tpl");
?>