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

$kurator = $_POST['id'];
$count = $_POST['count'];

try {
   $sql = $db->query(
      "SELECT `subject`.`name` AS 'subject', `descipline`.`id` AS 'id'
      FROM `group` INNER JOIN (`pair` INNER JOIN (`descipline` INNER JOIN `subject` 
                     ON `descipline`.`subject` = `subject`.`id`)
                 ON `pair`.`descipline` = `descipline`.`id`)
            ON `group`.`id` = `pair`.`group`
      WHERE `group`.`kurator` = :kurator",
      [
         ':kurator'  => $kurator
      ]
   );
} catch (Exception $e) {
   echo "Помилка виконання! Зверніться до Адміністратора сайту!";
}

$r = 0;
while ($r < $count) {
   echo "<select name=\"day-subject[]\" required>
            <option></option>";
   
   $c = 0;
   while ($c < count($sql)) {

      echo "<option value=".$sql[$c]['id'].">".$sql[$c]['subject']."</option>";
      $c++;
   }
   echo "</select>";
   $r++;
}