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

$descipline = $_POST['descipline']; 

try {
   $sql = $db->query(
      "SELECT `subject`.`name` AS 'name'
      FROM `descipline` INNER JOIN `subject`
        ON `descipline`.`subject` = `subject`.`id`
      WHERE `descipline`.`id` = :id",
      [  
         ':id' => $descipline
      ]
   );
} catch (Exception $e) {
   echo "Помилка виконання! Зверніться до Адміністратора сайту!";
}

echo $sql[0]['name'];
?>