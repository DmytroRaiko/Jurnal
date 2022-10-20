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

$nameNormalized = trim($_POST['name']);
$numberNormalized = trim($_POST['number']);
$department_headNormalized = trim($_POST['department_head']);

if (!empty($nameNormalized) && !empty($numberNormalized) && !empty($department_headNormalized)) {

   try {
      $sql = $db->query(
         "SELECT count(*) FROM `specialty` where `name` = :name OR `spec_number` = :number",
         [
            ':name'     => $nameNormalized,
            ':number'   => $numberNormalized
         ]
      );
   } catch (Exception $e) {
      echo "Помилка виконання! Зверніться до Адміністратора сайту!";
   }

   if ($sql[0]['count(*)'] <= 0) {

      try {
         $sql = $db->query(
            "INSERT INTO `specialty`(`name`, `spec_number`, `department_head`) VALUES (:name, :number, :department_head)",
            [
               ':name'              => $nameNormalized,
               ':number'            => $numberNormalized,
               ':department_head'   => $department_headNormalized
            ]
         );
      } catch (Exception $e) {
         echo "Помилка виконання! Зверніться до Адміністратора сайту!";
      }

      echo '<div style="padding-left: 3%; padding-bottom: 2%; width:100%"><p class="massage-error" style="width: auto; color:green;"><img src="../images/ok.png" alt="Ok!">Спецільність ' . $numberNormalized . ' додано!</p></div>';
   } else {
      echo '<div style="padding-left: 3%; padding-bottom: 2%; width:auto"><p class="massage-error" style="width: auto; color:red;"><img src="../images/error.png" alt="Error!">Спеціальність ' . $numberNormalized . ' вже існує!</p></div>';
   }
}
?>