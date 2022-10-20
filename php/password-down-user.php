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

$nicknameNormalized = trim($_POST['nickname']);
$passwordsNormalized = trim($_POST['passwords']);

if (!empty($nicknameNormalized) && !empty($passwordsNormalized)) {

   $passwordsHash = md5(md5($passwordsNormalized) . $SMT);

   try {
      $sql = $db->query(
         "SELECT count(*) FROM `user` where `nickname` = :nickname",
         [
            ':nickname' => $nicknameNormalized
         ]
      );
   } catch (Exception $e) {
      echo "Помилка виконання! Зверніться до Адміністратора сайту!";
   }

   if ($sql[0]['count(*)'] <= 0) {

      try {
         $sql = $db->query(
            "SELECT count(*) FROM `teacher` where `nickname` = :nickname",
            [
               ':nickname' => $nicknameNormalized
            ]
         );
      } catch (Exception $e) {
         echo "Помилка виконання! Зверніться до Адміністратора сайту!";
      }

      if ($sql[0]['count(*)'] <= 0) {

         try {
            $sql = $db->query(
               "SELECT count(*) FROM `student` where `nickname` = :nickname",
               [
                  ':nickname' => $nicknameNormalized
               ]
            );
         } catch (Exception $e) {
            echo "Помилка виконання! Зверніться до Адміністратора сайту!";
         }

         if ($sql[0]['count(*)'] <= 0) {
            echo '<div style="padding-left: 3%; padding-bottom: 2%; width:auto"><p class="massage-error" style="width: auto; color:red;"><img src="../images/error.png" alt="Error!">Користувач з  ім’ям ' . $nicknameNormalized . ' не існує!</p></div>';
         } else {

            try {
               $sql = $db->query(
                  "UPDATE `student` SET `password_start` = :passwords WHERE `nickname` = :nickname",
                  [
                     ':nickname'    => $nicknameNormalized,
                     ':passwords'   => $passwordsHash
                  ]
               );
            } catch (Exception $e) {
               echo "Помилка виконання! Зверніться до Адміністратора сайту!";
            }
            echo '<div style="padding-left: 3%; padding-bottom: 2%; width:100%"><p class="massage-error" style="width: auto; color:green;"><img src="../images/ok.png" alt="Ok!">Пароль для користувачa з ім’ям ' . $nicknameNormalized . ' змінено!</p></div>';
         }
      } else {

         try {
            $sql = $db->query(
               "UPDATE `teacher` SET `password_start` = :passwords WHERE `nickname` = :nickname",
               [
                  ':nickname'    => $nicknameNormalized,
                  ':passwords'   => $passwordsHash
               ]
            );
         } catch (Exception $e) {
            echo "Помилка виконання! Зверніться до Адміністратора сайту!";
         }
         echo '<div style="padding-left: 3%; padding-bottom: 2%; width:100%"><p class="massage-error" style="width: auto; color:green;"><img src="../images/ok.png" alt="Ok!">Пароль для користувачa з ім’ям ' . $nicknameNormalized . ' змінено!</p></div>';
      }
   } else {

      try {
         $sql = $db->query(
            "UPDATE `user` SET `password_start` = :passwords WHERE `nickname` = :nickname",
            [
               ':nickname'    => $nicknameNormalized,
               ':passwords'   => $passwordsHash
            ]
         );
      } catch (Exception $e) {
         echo "Помилка виконання! Зверніться до Адміністратора сайту!";
      }
      echo '<div style="padding-left: 3%; padding-bottom: 2%; width:100%"><p class="massage-error" style="width: auto; color:green;"><img src="../images/ok.png" alt="Ok!">Пароль для користувачa з ім’ям ' . $nicknameNormalized . ' змінено!</p></div>';
   }
}
