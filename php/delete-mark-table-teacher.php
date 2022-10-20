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
   $ban = $db->query("SELECT `access` 
      FROM `prohibition` 
      WHERE `operation` = :operation",
      [
         ':operation'  => "marks"
      ]
   );
} catch (Exception $e) {
   echo "Помилка виконання! Зверніться до Адміністратора сайту!";
}

if ($ban[0]['access'] == 1) {

   $mark = $_POST['mark'];


   try {
      $db->query("DELETE FROM `mark`
         WHERE `mark`.`mark_identifikator` = :id",
         [  
            ':id' => $mark
         ]
      );
   } catch (Exception $e) {
      echo "Помилка виконання! Зверніться до Адміністратора сайту!";
   }

   try {
      $db->query("DELETE FROM `mark_ident` 
         WHERE `id` = :id",
         [  
            ':id' => $mark
         ]
      );
   } catch (Exception $e) {
      echo "Помилка виконання! Зверніться до Адміністратора сайту!";
   }
} else {
   echo "<script>alert(\"Редагування оцінок обмежено!\")</script>";
}