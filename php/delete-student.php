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
$studentNormalized = trim($_POST['student']);

if (!empty($studentNormalized)) {

   try {
      $sql = $db->query(
         "SELECT count(*) FROM `student` where `student`.`id` = :student",
         [
            ':student'  => $studentNormalized
         ]
      );
   } catch (Exception $e) {
      echo "Помилка виконання! Зверніться до Адміністратора сайту!";
   }
   if ($sql[0]['count(*)'] > 0) {

      try {
         $sql = $db->query(
            "DELETE FROM `student` WHERE `student`.`id` = :student",
            [
               ':student'  => $studentNormalized
            ]
         );
      } catch (Exception $e) {
         echo "Помилка виконання! Зверніться до Адміністратора сайту!";
      }

      echo '<div style="padding-left: 3%; padding-bottom: 2%; width:100%"><p class="massage-error" style="width: auto; color:green;"><img src="../images/ok.png" alt="Ok!">Студент видалений!</p></div>';
   } else {
      echo '<div style="padding-left: 3%; padding-bottom: 2%; width:auto"><p class="massage-error" style="width: auto; color:red;"><img src="../images/error.png" alt="Error!">Такого студента не існує!</p></div>';
   }
}
