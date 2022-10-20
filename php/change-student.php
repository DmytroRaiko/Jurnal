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
$surnameNormalized = trim($_POST['surname']);
$middle_nameNormalized = trim($_POST['middle_name']);
$study_formNormalized = trim($_POST['study_form']);
$groupNormalized = trim($_POST['group']);

if (
   !empty($id) && !empty($nameNormalized) && !empty($surnameNormalized) &&
   !empty($middle_nameNormalized) && !empty($study_formNormalized) && !empty($groupNormalized)
) {

   try {
      $sql = $db->query(
         "SELECT count(*) FROM `student` WHERE id = :id AND (`name` = :name AND `surname` = :surname AND
                                       `middle_name` = :middle_name AND `study_form` = :study_form AND `group` = :group)",
         [
            ':id'                => $id,
            ':name'              => $nameNormalized,
            ':surname'           => $surnameNormalized,
            ':middle_name'       => $middle_nameNormalized,
            ':study_form'        => $study_formNormalized,
            ':group'             => $groupNormalized
         ]
      );
   } catch (Exception $e) {
      echo "Помилка виконання! Зверніться до Адміністратора сайту!";
   }
   if ($sql[0]['count(*)'] > 0) {
      echo '<div style="padding-left: 3%; padding-bottom: 2%; width:auto"><p class="massage-error" style="width: auto; color:red;"><img src="../images/error.png" alt="Error!">Ви не змінили дані!</p></div>';
   } else {
      try {
         $sql = $db->query(
            "UPDATE `student` SET `surname`= :surname,`name`=:name,`middle_name`=:middle_name,
                                                `group`=:group,`study_form`=:study_form WHERE `id` = :id",
            [
               ':id'                => $id,
               ':name'              => $nameNormalized,
               ':surname'            => $surnameNormalized,
               ':middle_name'       => $middle_nameNormalized,
               ':study_form'        => $study_formNormalized,
               ':group'             => $groupNormalized
            ]
         );
         echo '<div style="padding-left: 3%; padding-bottom: 2%; width:90%"><p class="massage-error" style="width: auto; color:green;"><img src="../images/ok.png" alt="Ok!">Дані змінено!</p></div>';
      } catch (Exception $e) {
         echo "Помилка виконання! Зверніться до Адміністратора сайту!";
      }
   }
}
                                 ?>