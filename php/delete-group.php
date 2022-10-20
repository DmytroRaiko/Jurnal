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

$groupNormalized = trim($_POST['group']);

if (!empty($groupNormalized)) {

   try {
      $sql = $db->query(
         "SELECT count(*) FROM `group` where `id` = :group",
         [
            ':group' => $groupNormalized
         ]
      );
   } catch (Exception $e) {
      echo "Помилка виконання! Зверніться до Адміністратора сайту!";
   }

   if ($sql[0]['count(*)'] > 0) {


      try {
         $sql = $db->query(
            "SELECT count(*) FROM `group` WHERE `kurator` = (SELECT `kurator` FROM `group` WHERE `id`= :group)",
            [
               ':group' => $groupNormalized
            ]
         );
      } catch (Exception $e) {
         echo "Помилка виконання! Зверніться до Адміністратора сайту!";
      }
      
      if ($sql[0]['count(*)'] == 1) {

         try {
            $sql = $db->query(
               "UPDATE `teacher` SET `privileges`= :privileges WHERE `id`= (SELECT `kurator` FROM `group` WHERE `id`= :group)",
               [
                  ':privileges'  => 'teacher',
                  ':group'       => $groupNormalized
               ]
            );
         } catch (Exception $e) {
            echo "Помилка виконання! Зверніться до Адміністратора сайту!";
         }
      }

      try {
         $sql = $db->query(
            "DELETE FROM `group` WHERE `group`.`id` = :group",
            [
               ':group' => $groupNormalized
            ]
         );
      } catch (Exception $e) {
         echo "Помилка виконання! Зверніться до Адміністратора сайту!";
      }

      

      echo '<div style="padding-left: 3%; padding-bottom: 2%; width:100%"><p class="massage-error" style="width: auto; color:green;"><img src="../images/ok.png" alt="Ok!">Група ' . $groupNormalized . ' видалена!</p></div>';
   } else {
      echo '<div style="padding-left: 3%; padding-bottom: 2%; width:auto"><p class="massage-error" style="width: auto; color:red;"><img src="../images/error.png" alt="Error!">Такої групи не існує!</p></div>';
   }
}