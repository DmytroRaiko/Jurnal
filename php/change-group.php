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
$specialtyNormalized = trim($_POST['specialty']);
$kuratorNormalized = trim($_POST['kurator']);


if (!empty($id) && !empty($nameNormalized) && !empty($specialtyNormalized) && !empty($kuratorNormalized)) {

   try {
      $teacher = $db->query(
         "SELECT `kurator` FROM `group` WHERE `id`=:id",
         [
            ':id' => $id
         ]
      );
   } catch (Exception $e) {
      echo "Помилка виконання! Зверніться до Адміністратора сайту!";
   }

   $teacher = $teacher[0]['kurator'];

   try {
      $sql = $db->query(
         "SELECT count(*) FROM `group` WHERE id = :id AND (`name` = :name AND `specialty` = :specialty AND `kurator` = :kurator)",
         [
            ':id'          => $id,
            ':name'        => $nameNormalized,
            ':specialty'   => $specialtyNormalized,
            ':kurator'     => $kuratorNormalized
         ]
      );
   } catch (Exception $e) {
      echo "Помилка виконання! Зверніться до Адміністратора сайту!";
   }

   if ($sql[0]['count(*)'] > 0) {
      echo '<div style="padding-left: 3%; padding-bottom: 2%; width:auto"><p class="massage-error" style="width: auto; color:red;"><img src="../images/error.png" alt="Error!">Ви не змінили дані!</p></div>';
   } else {

      try {
         $sql = $db->query("SELECT COUNT(*) FROM `group` WHERE `kurator` = :kurator",
            [
               ':kurator'     => $teacher
            ]
         );
      } catch (Exception $e) {
         echo "Помилка виконання! Зверніться до Адміністратора сайту!";
      }

      if ($sql[0]['COUNT(*)'] == 1 ) {
         try {
            $db->query(
               "UPDATE `group` SET `name` = :name, `specialty` = :specialty, `kurator` = :kurator
                                                   WHERE `id` = :id",
               [
                  ':id'          => $id,
                  ':name'        => $nameNormalized,
                  ':specialty'   => $specialtyNormalized,
                  ':kurator'     => $kuratorNormalized
               ]
            );
         } catch (Exception $e) {
            echo "Помилка виконання! Зверніться до Адміністратора сайту!";
         }

         try {
            $db->query(
               "UPDATE `teacher` SET `privileges` = :privileges WHERE id = :kurator",
               [
                  ':privileges'  => "teacher",
                  ':kurator'     => $teacher
               ]
            );
         } catch (Exception $e) {
            echo "Помилка виконання! Зверніться до Адміністратора сайту!";
         }

         try {
            $db->query(
               "UPDATE `teacher` SET `privileges` = :privileges WHERE id = :kurator",
               [
                  ':privileges'  => "kurator",
                  ':kurator'     => $kuratorNormalized
               ]
            );
         } catch (Exception $e) {
            echo "Помилка виконання! Зверніться до Адміністратора сайту!";
         }
         echo '<div style="padding-left: 3%; padding-bottom: 2%; width:90%"><p class="massage-error" style="width: auto; color:green;"><img src="../images/ok.png" alt="Ok!">Дані змінено!</p></div>';

      } else {
         try {
            $db->query(
               "UPDATE `group` SET `name` = :name, `specialty` = :specialty, `kurator` = :kurator
                                                   WHERE `id` = :id",
               [
                  ':id'          => $id,
                  ':name'        => $nameNormalized,
                  ':specialty'   => $specialtyNormalized,
                  ':kurator'     => $kuratorNormalized
               ]
            );
         } catch (Exception $e) {
            echo "Помилка виконання! Зверніться до Адміністратора сайту!";
         }

         try {
            $db->query(
               "UPDATE `teacher` SET `privileges` = :privileges WHERE id = :kurator",
               [
                  ':privileges'  => "kurator",
                  ':kurator'     => $kuratorNormalized
               ]
            );
            echo '<div style="padding-left: 3%; padding-bottom: 2%; width:90%"><p class="massage-error" style="width: auto; color:green;"><img src="../images/ok.png" alt="Ok!">Дані змінено!</p></div>';
         } catch (Exception $e) {
            echo "Помилка виконання! Зверніться до Адміністратора сайту!";
         }
         echo '<div style="padding-left: 3%; padding-bottom: 2%; width:90%"><p class="massage-error" style="width: auto; color:green;"><img src="../images/ok.png" alt="Ok!">Дані змінено!</p></div>';

      }
   }
}