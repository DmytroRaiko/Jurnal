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



   $desciplineNormalized = trim($_POST['teacher']);

   if (!empty($desciplineNormalized)) {

      try {      
         $sql = $db->query("SELECT count(*) FROM `descipline` where `descipline`.`id` = :descipline",
            [
               ':descipline'  => $desciplineNormalized
            ]
         );
      } catch (Exception $e) {
         echo "Помилка виконання! Зверніться до Адміністратора сайту!";    
      }
      if ($sql[0]['count(*)'] > 0) {

         try {      
            $sql = $db->query("DELETE FROM `descipline` WHERE `descipline`.`id` = :descipline",
               [
                  ':descipline'  => $desciplineNormalized
               ]
            );
         } catch (Exception $e) {
            echo "Помилка виконання! Зверніться до Адміністратора сайту!";    
         }

         echo '<div style="padding-left: 3%; padding-bottom: 2%; width:100%"><p class="massage-error" style="width: auto; color:green;"><img src="../images/ok.png" alt="Ok!">Дисципліну видалений!</p></div>';
      } else {
         echo '<div style="padding-left: 3%; padding-bottom: 2%; width:auto"><p class="massage-error" style="width: auto; color:red;"><img src="../images/error.png" alt="Error!">Такої дисципліни не існує!</p></div>';
      }
   }