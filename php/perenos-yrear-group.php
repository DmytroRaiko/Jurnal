<?php

include '../db/setting.php';
require_once '../db/db.php';
$db = new DataBase();

function mySqlQuer ($db, $querty, $params) {
   try {      

      $sql = $db->query($querty, $params);
      return $sql;
   } catch (Exception $e) {

      echo "Помилка виконання! Зверніться до Адміністратора сайту!";    
   }
}

try {
   $group = $db->query("SELECT `id`, `specialty`, `name`, `kurator` 
      FROM `group`
      ORDER BY `name`");
} catch (Exception $e) {
   echo "Помилка виконання! Зверніться до Адміністратора сайту!";
}
print_r($group);
echo "<br>";

$a = 0;
while ($a < count($group)) {
   
   $array_group = str_split($group[$a]['name']);
   $array_group[0] = $array_group[0]+1;
   echo implode($array_group)."<br>";
   try {
      $db->query("UPDATE `group`
         SET `name` = :name
         WHERE `id` = :id",
         [
            ':name'    => implode($array_group),
            ':id'      => $group[$a]['id']
         ]   
      );
   } catch (Exception $e) {
      echo "Помилка виконання! Зверніться до Адміністратора сайту!";
   }
   $a++;
}

try {
   $teacher = $db->query("SELECT `kurator` FROM 
      `group` WHERE `group`.`name` LIKE :name",
      [
         ':name'  => "5%"
      ]   
   );
} catch (Exception $e) {
   echo "Помилка виконання! Зверніться до Адміністратора сайту!";
}
$r = 0;
while($r < count($teacher)) {

   try {
      $db->query("UPDATE `teacher`
         SET `privileges` = :privileges WHERE `id` = :id",
         [
            ':privileges' => 'teacher',
            ':id'       => $teacher[$r]
         ]   
      );
   } catch (Exception $e) {
      echo "Помилка виконання! Зверніться до Адміністратора сайту!";
   }   
   $r++;
}

try {
   $db->query("DELETE FROM 
      `group` WHERE `name` LIKE :name",
      [
         ':name'  => "5%"
      ]   
   );
} catch (Exception $e) {
   echo "Помилка виконання! Зверніться до Адміністратора сайту!";
}