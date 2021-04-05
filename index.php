<?php
// 01.04 +5:36; 02.04 +6:56; 05.04 +1:10 (13:42)
include 'config.php'; // Подключаем БД
include 'firewall.php'; // Подключаем зачистку данных

$l = ''; $p = ''; $insert_id = ''; $prototype = ''; $URL = '/';

// Дополнительно зачищаем переменную
function trimming($string = ''){ return htmlspecialchars(strip_tags(trim($string)),ENT_QUOTES); }

// Вернет TRUE в случае успешной авторизации или FALSE в случае неудачи
function authorization($login,$password){
  if($login == '' and $password == ''){ return null; }

  $password = md5($password);

  $user_dostup[] = array('login' => 'admin', 'password' => md5('123'));
  $key = array_search($login, array_column($user_dostup, 'login'));
  if($user_dostup[$key]['login']==strtolower($login) and $user_dostup[$key]['password']==$password)
  { return true; } else { unset($_SESSION['login'],$_SESSION['password']); return false; }
}

// Отслеживаем аутентификацию пользователя
if(isset($_SESSION["login"]) and isset($_SESSION["password"])){ $l = trimming($_SESSION['login']); $p = trimming($_SESSION['password']); }

// Авторизуем пользователя
if(isset($_POST["login"]) and isset($_POST["password"])){
    $_SESSION['login']    = trimming($_POST["login"]);
    $_SESSION['password'] = trimming($_POST["password"]);

    header("Location: ".$URL);
}

// Деавторизуем пользователя
if(isset($_POST["quit"])){ unset($_SESSION['login'],$_SESSION['password']); header("Location: ".$URL); }

// Добавляем новую задачу
if(isset($_SESSION['message'])){ $insert_id = trimming($_SESSION['message']); unset($_SESSION['message']); }
if(isset($_POST["name"]) and isset($_POST["email"]) and isset($_POST["message"])){
  $data = [
      'name'    => trimming($_POST["name"]),
      'email'   => trimming($_POST["email"]),
      'message' => trimming($_POST["message"])
  ];

  $sql = "INSERT INTO `message` (`name`, `email`, `message`) VALUES (:name, :email, :message); ";
  $PDO->prepare($sql)->execute($data);
  $insert_id = $PDO->lastInsertId();
  $_SESSION['message'] = $insert_id;
  header("Location: ".$URL);
}

// Загружаем задачи
$sorting = "";
if(!isset($_SESSION['sorting'])){ $_SESSION['sorting'] = ''; }
if(isset($_GET['sorting'])){ $_SESSION['sorting'] = trimming($_GET['sorting']); }

if($_SESSION['sorting'] == 'sortingNaAs'){ $sorting .= " `message`.`name` ASC "; }
if($_SESSION['sorting'] == 'sortingNaDe'){ $sorting .= " `message`.`name` DESC "; }
if($_SESSION['sorting'] == 'sortingEmAs'){ $sorting .= " `message`.`email` ASC "; }
if($_SESSION['sorting'] == 'sortingEmDe'){ $sorting .= " `message`.`email` DESC "; }
if($_SESSION['sorting'] == 'sortingStAs'){ $sorting .= " `message`.`status` ASC "; }
if($_SESSION['sorting'] == 'sortingStDe'){ $sorting .= " `message`.`status` DESC "; }

if(strlen($sorting) > 0){ $sorting = " ORDER BY ".$sorting; }

$limit = " LIMIT 0, 3 ";
if(isset($_GET['page'])){ $num = trimming($_GET['page'])*3; $limit = " LIMIT ".$num.", 3 "; }

$message = [];
$sql = $PDO->query("SELECT * FROM `message` ".$sorting." ".$limit."; ");
$sql->setFetchMode(PDO::FETCH_ASSOC);
while($row = $sql->fetch()) {
  $message[] = array('id' => $row['id'], 'status' => $row['status'], 'name' => $row['name'], 'email' => $row['email'], 'message' => $row['message'], 'date_create' => $row['date_create'], 'date_change' => $row['date_change']);
}
$sql = null;

// Считаем пагинацию
$sql = $PDO->query("SELECT COUNT(*) AS col FROM `message`; ");
$count = $sql->fetch(PDO::FETCH_ASSOC);

// Редактируем задачи от имени администратора
if(authorization($l,$p) === true){
  if(isset($_POST["edit_id"])){
    if(isset($_POST["edit_status"])){ $status = 'Завершенная'; } else { $status = 'Новая'; }

    $data = [
        'id'      => trimming($_POST["edit_id"]),
        'status'  => trimming($status),
        'message' => trimming($_POST["edit_message"])
    ];

    $sql = "UPDATE `message` SET `message`.`status`=:status, `message`.`message`=:message WHERE `message`.`id`=:id; ";
    $PDO->prepare($sql)->execute($data);
    header("Location: ".$URL);
  }
}

// Обрабатываем информацию о запрашваемой страницы
$URL = trimming($_SERVER['REQUEST_URI']);
$URL = str_replace("/", "", $URL);

$prototype = 'not_found';
// Обрабатываем условия отслеживания определенных URL адресов
if($URL == '' or $URL == 'added_message' or count($_GET) > 0){
  $page_title = 'Приложение-задачник'; // Мета-теги страницы
  $prototype  = 'template'; // Имя шаблона для подключения страницы
} else {
  $page_title = 'Ошибка 404. Страница не найдена';
  $prototype  = 'not_found';
}

$prototype = strtolower($prototype).'.php';
include trimming($prototype); // Кол-во шаблонов-прототипов не ограничено и подключается автоматически в соответствии с описанными свойствами страницы

$PDO = null;
unset($PDO);
?>
