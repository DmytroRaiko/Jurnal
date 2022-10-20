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
   $sql = $db->query(
      "SELECT `id`, `name`
      FROM `group` 
      ORDER BY `name`;");
} catch (Exception $e) {
   echo "Помилка виконання! Зверніться до Адміністратора сайту!";
}

echo "<option></option>";

$r = 0;
while ($r < count($sql)) {

   echo "<option value=".$sql[$r]['id'].">".$sql[$r]['name']."</option>";
   $r++;
   
}
?>