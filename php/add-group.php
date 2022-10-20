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
   $specialtyNormalized = trim($_POST['specialty']);
   $kuratorNormalized = trim($_POST['kurator']);

   if (!empty($nameNormalized) && !empty($specialtyNormalized) && !empty($kuratorNormalized)) {

      try {      
         $sql = $db->query("SELECT count(*) FROM `group` where `name` = :name",
            [ 
               ':name' => $nameNormalized
            ]
         );
      } catch (Exception $e) {
         echo "Помилка виконання! Зверніться до Адміністратора сайту!";    
      }

      if ($sql[0]['count(*)'] <= 0) {

         try {      
            $sqll = $db->query("SELECT `nickname` FROM `teacher` WHERE `id` = :kurator",
               [
                  ':kurator' => $kuratorNormalized
               ]
            );
         } catch (Exception $e) {
            echo "Помилка виконання! Зверніться до Адміністратора сайту!";    
         }

         try {      
            $sql = $db->query("INSERT INTO `group`(`specialty`, `name`, `kurator`) VALUES (:specialty, :name, :kurator)",
               [
                  ':specialty' => $specialtyNormalized,
                  ':name'      => $nameNormalized,
                  ':kurator'  => $kuratorNormalized
               ]
            );
         } catch (Exception $e) {
            echo "Помилка виконання! Зверніться до Адміністратора сайту!";    
         }

         $nick = $sqll[0]['nickname'];
         
         try {      
            $sql = $db->query("UPDATE `teacher` SET `privileges` = 'kurator' WHERE `nickname` = :nick",
               [
                  ':nick'  => $nick
               ]
            );
         } catch (Exception $e) {
            echo "Помилка виконання! Зверніться до Адміністратора сайту!";    
         }

         echo '<div style="padding-left: 3%; padding-bottom: 2%; width:100%"><p class="massage-error" style="width: auto; color:green;"><img src="../images/ok.png" alt="Ok!">Група '.$nameNormalized.' додана!</p></div>';
      } else {
         echo '<div style="padding-left: 3%; padding-bottom: 2%; width:auto"><p class="massage-error" style="width: auto; color:red;"><img src="../images/error.png" alt="Error!">Така група існує!</p></div>';
      }
   }