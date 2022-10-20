<?php

include '../db/setting.php';
require_once '../db/db.php';
$db = new DataBase();

try {
   $db->query("TRUNCATE TABLE `visiting`");
} catch (Exception $e) {
   echo "Помилка виконання! Зверніться до Адміністратора сайту!";
}

try {
   $db->query("TRUNCATE TABLE `pair`");
} catch (Exception $e) {
   echo "Помилка виконання! Зверніться до Адміністратора сайту!";
}

try {
   $db->query("TRUNCATE TABLE `mark_ident`");
} catch (Exception $e) {
   echo "Помилка виконання! Зверніться до Адміністратора сайту!";
}

try {
   $db->query("TRUNCATE TABLE `mark`");
} catch (Exception $e) {
   echo "Помилка виконання! Зверніться до Адміністратора сайту!";
}