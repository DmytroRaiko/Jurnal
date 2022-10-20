<?php
require_once '../db/db.php';
include '../db/setting.php';
include '../php/file-function.php';
$db = new DataBase();

function mySqlQuer ($db, $querty, $params) {
   try {      

      $sql = $db->query($querty, $params);
      return $sql;
   } catch (Exception $e) {

      echo "Помилка виконання! Зверніться до Адміністратора сайту!";    
   }                           
}

$nameNormalized = trim($_POST['name-subject-add']);
$mark_systemNormalized = trim($_POST['mark-system-subject-add']);
$count_teacher = trim($_POST['mark-count-teacher-add']);
$pictureNormalized = $_FILES['picture-subject-add'];

$check = can_upload($pictureNormalized);

if (!empty($nameNormalized) && !empty($count_teacher) && !empty($mark_systemNormalized) && $_FILES['picture-subject-add']['size'] == 0) {

   //добавление без картинки - начало
   try {
      $sql = $db->query(
         "SELECT count(*) FROM `subject` where `name` = :name AND `mark_system` = :mark_system",
         [
            ':name'        => $nameNormalized,
            ':mark_system' => $mark_systemNormalized
         ]
      );
   } catch (Exception $e) {
      echo "Помилка виконання! Зверніться до Адміністратора сайту!";
   }

   if ($sql[0]['count(*)'] <= 0) {

      try {
         $sql = $db->query(
            "INSERT INTO `subject`(`name`, `mark_system`, `count_teacher`) VALUES (:name, :mark_system, :count_teacher)",
            [
               ':name'           => $nameNormalized,
               ':mark_system'    => $mark_systemNormalized,
               ':count_teacher'  => $count_teacher
            ]
         );
      } catch (Exception $e) {
         echo "Помилка виконання! Зверніться до Адміністратора сайту!";
      }
      
      try {
         $sqll = $db->query(
            "SELECT `id` FROM `subject` WHERE `name` = :name AND `mark_system` = :mark_system",
            [
               ':name'        => $nameNormalized,
               ':mark_system' => $mark_systemNormalized
            ]
         );
      } catch (Exception $e) {
         echo "Помилка виконання! Зверніться до Адміністратора сайту!";
      }
      
      try {
         $sql = $db->query(
            "INSERT INTO `background_subject` (`subject`) VALUES (:subject)",
            [
               ':subject' => $sqll[0]['id']
            ]
         );
      } catch (Exception $e) {
         echo "Помилка виконання! Зверніться до Адміністратора сайту!";
      }

      echo '<div style="padding-left: 3%; padding-bottom: 2%; width:100%"><p class="massage-error" style="width: auto; color:green;"><img src="../images/ok.png" alt="Ok!">Предмет ' . $nameNormalized . ' додано!</p></div>';
      echo '<script>
            $(\'#name-subject-add\').val("");
            $(\'#mark-system-subject-add\').val("");
            $(\'#picture-subject-add\').val("");
            </script>';
      //добавление без картинки - конец

   } else {
      echo '<div style="padding-left: 3%; padding-bottom: 2%; width:auto"><p class="massage-error" style="width: auto; color:red;"><img src="../images/error.png" alt="Error!">Такий предмет вже існує!</p></div>';
   }
} else if (!empty($nameNormalized) && !empty($count_teacher) && !empty($mark_systemNormalized) && !empty($pictureNormalized)) {

  
   if (can_upload($_FILES['picture-subject-add']) == 1) {

      //добавление с картинкой - начало
      try {
         $sql = $db->query(
            "SELECT count(*) FROM `subject` where `name` = :name AND `mark_system` = :mark_system",
            [
               ':name'        => $nameNormalized,
               ':mark_system' => $mark_systemNormalized
            ]
         );
      } catch (Exception $e) {
         echo "Помилка виконання! Зверніться до Адміністратора сайту!";
      }
   
      if ($sql[0]['count(*)'] <= 0) {
   
         try {
            $sql = $db->query(
               "INSERT INTO `subject`(`name`, `mark_system`, `count_teacher`) VALUES (:name, :mark_system, :count_teacher)",
               [
                  ':name'        => $nameNormalized,
                  ':mark_system' => $mark_systemNormalized,
                  ':count_teacher'  => $count_teacher
               ]
            );
         } catch (Exception $e) {
            echo "Помилка виконання! Зверніться до Адміністратора сайту!";
         }
         
         //картинка - начало
         try {
            $sql = $db->query(
               "SELECT `id` FROM `subject` WHERE `name` = :name AND `mark_system` = :mark_system",
               [
                  ':name'        => $nameNormalized,
                  ':mark_system' => $mark_systemNormalized
               ]
            );
         } catch (Exception $e) {
            echo "Помилка виконання! Зверніться до Адміністратора сайту!";
         }

         $imageHash = file_get_contents($_FILES['picture-subject-add']['tmp_name']);

         try {
            $sqll = $db->query(
               "INSERT INTO `background_subject`(`subject`, `image`) VALUES (:subject, :image)",
               [
                  ':subject'  => $sql[0]['id'],
                  ':image'    => $imageHash
               ]
            );
         } catch (Exception $e) {
            echo "Помилка виконання! Зверніться до Адміністратора сайту!";
         }
         //картинка - конец

         echo '<div style="padding-left: 3%; padding-bottom: 2%; width:100%"><p class="massage-error" style="width: auto; color:green;"><img src="../images/ok.png" alt="Ok!">Предмет ' . $nameNormalized . ' додано!</p></div>';
         echo '<script>
               $(\'#name-subject-add\').val("");
               $(\'#mark-system-subject-add\').val("");
               $(\'#picture-subject-add\').val("");
               </script>';
         //добавление с картинкой - конец
      } else {
         echo '<div style="padding-left: 3%; padding-bottom: 2%; width:auto"><p class="massage-error" style="width: auto; color:red;"><img src="../images/error.png" alt="Error!">Такий предмет вже існує!</p></div>';
         echo '<script>
               $(\'#name-subject-add\').val("");
               $(\'#mark-system-subject-add\').val("");
               $(\'#picture-subject-add\').val("");
               </script>';
      }
   } else {
      echo '<div style="padding-left: 3%; padding-bottom: 2%; width:auto"><p class="massage-error" style="width: auto; color:red;"><img src="../images/error.png" alt="Error!">Помилка додавання предмета!</p></div>';
   }
}
?>