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



$specialtyNormalized = trim($_POST['name']);

if (!empty($specialtyNormalized)) {

   try {
      $sql = $db->query(
         "SELECT count(*) FROM `specialty` where `specialty`.`id` = :specialty",
         [
            ':specialty'  => $specialtyNormalized
         ]
      );
   } catch (Exception $e) {
      echo "Помилка виконання! Зверніться до Адміністратора сайту!";
   }

   if ($sql[0]['count(*)'] <= 0) {
      echo '<div style="padding-left: 3%; padding-bottom: 2%; width:auto"><p class="massage-error" style="width: auto; color:red;"><img src="../images/error.png" alt="Error!">Спеціальності не існує!</p></div>';
   } else {
      try {
         $sql = $db->query(
            "DELETE FROM `specialty` WHERE `specialty`.`id` = :specialty",
            [
               ':specialty'  => $specialtyNormalized
            ]
         );
      } catch (Exception $e) {
         echo "Помилка виконання! Зверніться до Адміністратора сайту!";
      }

      echo '<div style="padding-left: 3%; padding-bottom: 2%; width:90%"><p class="massage-error" style="width: auto; color:green;"><img src="../images/ok.png" alt="Ok!">Спеціальність видалена!</p></div>';
   }
}
                        ?>