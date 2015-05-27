<?
if (!defined('RAPIDGET')) {
  die("not load primary script");
}
error_reporting(0);
$errors2show = E_ALL & ~(E_WARNING|E_STRICT|E_NOTICE|( !defined( 'E_DEPRECATED' )? null : E_DEPRECATED));
error_reporting($errors2show);
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
ini_set('error_reporting', $errors2show);
#все "строковые" параметры введённые пользователем в этом конфиге должны быть в "кавычках"

$disable_str = @ini_get('disable_functions');
$disable_function = @explode(',', $disable_str);
#Отображение информации об администраторе
$adminperson = 'NAME';
$adminemail = 'E-MAIL:';
$adminICQ = 'ICQ:';
$external_auth = FALSE;          #true - Использование внешней авторизации
if (file_exists("login.php")) {
  include "login.php";
}
else {
  $authorization = FALSE;
}
$admin_logins = FALSE;          #$admin_logins=array("admin1","admin2");

$workpath = "files";            #false - папка со скриптом; "строка" - путь к каталогу с файлами (и списком файлов)
$loginpath = FALSE;            #true - если включена авторизация, то путь к папке с файлами будет выглядеть как $workpath/ваш_логин/ ; не забудьте её сначала создать и поставить права 777;
#Администратор может перемещаться по всем папкам пользователей. Пользователь может находится только внутри свое папки.
$maysaveto = TRUE;            #true  - Использовать возможность указывать каталог для скачивания файлов
$show_all = TRUE;              #true  - показывать все файлы в каталоге, false - только скачанные

$use_quota = 'rgquota.rgquota';          # имя файла хранящего квоту (если лежит в каталоге рядом с папками пользователей, задает квоту по умолчанию)
# в каталоге пользователя назначает личную квоту для этого пользователя. содержит размер квоты в байтах (только число)
# $use_quota=false; квотирование отключено
$hard_quota = TRUE;            # true - Блокировка при превышении квоты. Иначе таймер
$procent_message = 90;          # Порог появления предупреждения о приближении к квоте в процентах

$index_plug_ext = 'index';        # Расширение индексных файлов плагинов для скачивания с обменников
$first_plug_ext = 'first';        # Расширение первой страницы плагинов
$second_plug_ext = 'second';        # Расширение второй страницы плагинов

$redir = TRUE;              #true  - поодержка редиректинга линков, false - без поодержки редиректа
$show_direct = TRUE;            #true  - показывать галку на показ прямого линка, false - не показывать
$default_checked_showlink = FALSE;    #true  - галочка на показ прямого линка выставлена; false - не выставлена;

$new_window = TRUE;            #true - показывать галку на открытие в новом окне, false - не показывать
$default_new_window = TRUE;        #true - галочка на открытие в новом окне выставлена; false - не выставлена;
$java_new_window = TRUE;          #true - открытие через javascript; false - просто новое окно;
$clear_link = TRUE;            #true - очищать URL и Referer при открытии в новом окне; false - нет;
$showautoclose = TRUE;                    #true - происходит автозакрытие окна с состоянием загрузки; false - нет;
$timeautoclose = 500;                     #по умолчанию автозакрытие через 500 мс
$showlogo = TRUE;                         #true - лого включено; false - нет;
$background = FALSE;                       #true - фон картинка; false - без картинки;
$menunew = TRUE;                         #true - новое меню; false - старое меню;
$showline = TRUE;              #true -разделительные линии из картинок; false - разделительные линии из тега <hr>

$use_js_check_mails = TRUE;        #javascript-проверка введённых ящиков на стороне пользователя; false - выключить её.
$new_method_mass_submits = TRUE;      #true - в массовой отсылке при разбиении файлов каждой части указывается ящик; false - все части каждого файла иду на указанный файлу 1 ящик.
$EmptyMail = "";              #false - ничего; строка - "ящик", указываемый для частей, которые нужно пропустить;

$archive_library = "PEAR-TAR";      #сторонняя библиотека для упаковки; false - упаковка недоступна; возможные значения :
#"PEAR-TAR"	- требует компоненты PEAR.php и Tar.php	http://pear.php.net/package/Archive_Tar
#"PCL-ZIP"	- требует компонент pclzip.lib.php		http://www.phpconcept.net/pclzip/
#эти 2 библиотеки можно скачать так-же и на http://rapidget.linux.spb.ru/addition/

$no_cache = TRUE;              #true  - Запрет кеширования страницы со скриптом браузером, проксиками;
$progressbar_via_time = 5;        #false - прорисовка бара по размеру скачанной\залитой части (размер части зависит от размера файла не пропорционально)
#число > 0, интервал между обновлениями прогрессбара

$loadcaptha_old = TRUE;          #true - грузить картинку с оригинального линка, false - картинку грузит скрипт и показывает.
$ssl_image_direct = TRUE;            #true - грузить картинки защиты с ssl линков напрямую браузером, false - грузить через скрипт (не везде работает)

$disable_timer = FALSE;          #отключение таймера получения ссылок. не гарантирует работу некоторых обменников.

$sleep_time = FALSE;              #Задержка (в микросекундах) в цикле скачивания, помогает решить часть проблем при вылете скрипта работающего через CGI
$sleep_count = FALSE;              #Число тактов между задержками.

$show_download_link = TRUE;        #true - Показывать столбец с линком, false - не показывать
$embellishment = TRUE;          #украшения таблицы списка файлов

$language = FALSE;                    # принудительное выставление языка скрипта
#  $language = false; // скрипт автоматом выберет язык
#  $language = 'ru'; // русский язык
#  $language = 'en'; // english language
#  $language = 'es'; // lengua espanol

$step_dir = FALSE;                      # включить переход по папкам! альфа версия, не рекомендуем использовать

$check_update_day = 5;                 # число дней, через которое проводить проверку обновлений на офсайте

$title_update = TRUE;            #Обновление заголовков окон при скачивании\заливке

$_max_split_buffer = 5 * 1024 * 1024;        #Обьем в байтах

$max_mailsize = 10;
$smtp_ehlo = "mail.ru";
$smpt_server = FALSE;
$smtp_port = "2525";
$smtp_login = FALSE;            #Ваш логин
$smtp_passwd = FALSE;            #Ваш пароль
$smtp_mail = TRUE && ($smpt_server !== FALSE) && ($smpt_server != "") && ($smtp_port != "") && ($smtp_port !== FALSE);
$mail_not_support = in_array("mail", $disable_function) || in_array("base64_encode", $disable_function) || in_array("chunk_split", $disable_function);

if (file_exists("build.php")) {
  include "build.php";
}

$agent = "Mozilla/5.0 (Windows NT 6.3; rv:36.0) Gecko/20100101 Firefox/36.0";
$fromaddr = "rapidget@from.ru";

if (file_exists("services.php")) {
  include "services.php";
}

$ssl_support = function_exists('stream_get_transports') ? in_array('ssl', stream_get_transports()) : TRUE;
$ssl_image_direct = (!$ssl_support || $ssl_image_direct) ? TRUE : TRUE;

$maysaveto = $workpath ? FALSE : $maysaveto;
$maysaveto = $loginpath ? FALSE : $maysaveto;

$PHP_SELF = !$PHP_SELF ? $_SERVER["PHP_SELF"] : $PHP_SELF;
$workpath = (($workpath === FALSE) || !is_dir($workpath)) ? getcwd() : $workpath;

$systemfile = array(
  "add",
  "upload",
  "plugin",
  "proxy.lst",
  "proxy.php",
  "xmail.php",
  "myconfig.php",
  "build.php",
  "services.php",
  "login.php",
  "smtp.php",
  "spbland.php",
  "base64.js",
  ".htaccess",
  "map.php",
  "license.html",
  "sendspace.php",
  "config.php",
  "cookie.php",
  "function.php",
  "index.php",
  "notlink.php",
  "files.lst",
  "PEAR.php",
  "Tar.php",
  "pclzip.lib.php",
  "index.html",
  "pack.php",
  "opera.php",
  "upload.php",
  "auth.php",
  "langpack.php",
  "line.gif",
  "bg.jpg",
  "logo.png",
  "info.php"
);
$systemfile[] = basename(trim($PHP_SELF));
if ($use_quota !== FALSE) {
  $systemfile[] = $use_quota;
}

$is_windows = (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') ? TRUE : FALSE;  // это винда? если да то это жесть

if (!@in_array('shell_exec', $disable_function) & !@in_array('escapeshellarg', $disable_function) & !$is_windows) {
  $r = trim(@shell_exec('ls ' . getcwd() . ' | grep function.php'));
  $max_4gb = $r == 'function.php' ? FALSE : FALSE;
}
else {
  $max_4gb = TRUE;
}

if (file_exists("myconfig.php")) {
  include "myconfig.php";
}

?>