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

$v = $_POST['v'];
$operation = $_POST['operation'];
$access = $_POST['access'];
$password = $_POST['password'];

if ($v == 0) {
   if ($access == 1) {
      try {
         $db->query("UPDATE `prohibition`
            SET `access` = :access, `password` = :password
            WHERE `operation` = :operation",
            [
               ':access'      => $access,
               ':password'    => NULL,
               ':operation'   => $operation
            ]
         );
      } catch (Exception $e) {
         echo "Помилка виконання! Зверніться до Адміністратора сайту!";
      }
   } else {
      try {
         $db->query("UPDATE `prohibition`
            SET `access` = :access, `password` = :password
            WHERE `operation` = :operation",
            [
               ':access'      => $access,
               ':password'    => NULL,
               ':operation'   => $operation
            ]
         );
      } catch (Exception $e) {
         echo "Помилка виконання! Зверніться до Адміністратора сайту!";
      }
   }
} else if ($v == 1) {
   $password = md5($password);
   if ($access == 1) {

      try {
         $sql = $db->query("SELECT `password` 
            FROM `prohibition`
            WHERE `operation` = :operation",
            [
               ':operation'   => $operation
            ]
         );
      } catch (Exception $e) {
         echo "Помилка виконання! Зверніться до Адміністратора сайту!";
      }

      if ($password == $sql[0]['password']) {
         try {
            $db->query("UPDATE `prohibition`
               SET `access` = :access, `password` = :password
               WHERE `operation` = :operation",
               [
                  ':access'      => $access,
                  ':password'    => NULL,
                  ':operation'   => $operation
               ]
            );
         } catch (Exception $e) {
            echo "Помилка виконання! Зверніться до Адміністратора сайту!";
         }
      }
   } else {
      try {
         $db->query("UPDATE `prohibition`
            SET `access` = :access, `password` = :password
            WHERE `operation` = :operation",
            [
               ':access'      => $access,
               ':password'    => $password,
               ':operation'   => $operation
            ]
         );
      } catch (Exception $e) {
         echo "Помилка виконання! Зверніться до Адміністратора сайту!";
      }
   }
}