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

$teacherNormalized = trim($_POST['teacher']);
$subjectNormalized = trim($_POST['subject']);

if (!empty($teacherNormalized) && !empty($subjectNormalized)) {

   try {
      $sql = $db->query(
         "SELECT count(*) FROM `descipline` WHERE `descipline`.`teacher` = :teacher AND `descipline`.`subject` = :subject",
         [
            ':teacher'  => $teacherNormalized,
            ':subject'  => $subjectNormalized
         ]
      );
   } catch (Exception $e) {
      echo "Помилка виконання! Зверніться до Адміністратора сайту!";
   }

   if ($sql[0]['count(*)'] <= 0) {


      try {
         $sql = $db->query(
            "INSERT INTO `descipline`(`teacher`, `subject`) VALUES (:teacher, :subject)",
            [
               ':teacher'  => $teacherNormalized,
               ':subject'  => $subjectNormalized
            ]
         );
      } catch (Exception $e) {
         echo "Помилка виконання! Зверніться до Адміністратора сайту!";
      }
      echo '<div style="padding-left: 3%; padding-bottom: 2%; width:100%"><p class="massage-error" style="width: auto; color:green;"><img src="../images/ok.png" alt="Ok!">Дисципліна додана!</p></div>';
   } else {
      echo '<div style="padding-left: 3%; padding-bottom: 2%; width:auto"><p class="massage-error" style="width: auto; color:red;"><img src="../images/error.png" alt="Error!">Дана дисципліна існує!</p></div>';
   }
}
?>