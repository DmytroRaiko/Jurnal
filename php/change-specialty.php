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


$id = trim($_POST['id']);
$nameNormalized = trim($_POST['name']);
$numberNormalized = trim($_POST['number']);
$department_headNormalized = trim($_POST['department_head']);

if (!empty($id) && !empty($nameNormalized) && !empty($numberNormalized) && !empty($department_headNormalized)) {

   try {
      $sql = $db->query(
         "SELECT count(*) FROM `specialty` WHERE id = :id AND (`name` = :name AND `spec_number` = :number AND `department_head` = :department_head)",
         [
            ':id'                => $id,
            ':name'              => $nameNormalized,
            ':number'            => $numberNormalized,
            ':department_head'   => $department_headNormalized
         ]
      );
   } catch (Exception $e) {
      echo "Помилка виконання! Зверніться до Адміністратора сайту!";
   }
   if ($sql[0]['count(*)'] > 0) {
      echo '<div style="padding-left: 3%; padding-bottom: 2%; width:auto"><p class="massage-error" style="width: auto; color:red;"><img src="../images/error.png" alt="Error!">Змініть дані!</p></div>';
   } else {
      
      try {
         $sql = $db->query(
            "SELECT count(*) FROM `specialty` WHERE (`name` = :name OR `spec_number` = :number) AND `id` != :id",
            [
               ':name'              => $nameNormalized,
               ':number'            => $numberNormalized,
               ':id'                => $id
            ]
         );
      } catch (Exception $e) {
         echo "Помилка виконання! Зверніться до Адміністратора сайту!";
      }

      if ($sql[0]['count(*)'] == 0) {
         try {
            $sql = $db->query(
               "UPDATE `specialty` SET `name` = :name, `spec_number` = :number, `department_head` = :department_head WHERE `id` = :id",
               [
                  ':name'              => $nameNormalized,
                  ':number'            => $numberNormalized,
                  ':department_head'   => $department_headNormalized,
                  ':id'                => $id
               ]
            );
         } catch (Exception $e) {
            echo "Помилка виконання! Зверніться до Адміністратора сайту!";
         }
         echo '<div style="padding-left: 3%; padding-bottom: 2%; width:90%"><p class="massage-error" style="width: auto; color:green;"><img src="../images/ok.png" alt="Ok!">Дані змінено!</p></div>';
      } else {
         echo '<div style="padding-left: 3%; padding-bottom: 2%; width:auto"><p class="massage-error" style="width: auto; color:red;"><img src="../images/error.png" alt="Error!">Номер або назва группи вже використовується!</p></div>';
      }
   }
}
?>