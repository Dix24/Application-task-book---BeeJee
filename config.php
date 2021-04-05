<?php
$dir = $_SERVER['DOCUMENT_ROOT'].'/session';
if (!file_exists($dir)){mkdir($dir);}
ini_set('session.save_path',$dir);

session_start();

$base_l = "* DB LOGIN *";
$base_p = "* DB PASSWORD *";

try {
	$PDO = new PDO('mysql:host=localhost;dbname= /* DB NAME */ ;charset=utf8', $base_l, $base_p);
} catch (PDOException $e) {
	die($e->getMessage());
}

unset($base_l,$base_p);
?>
