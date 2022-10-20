<?php
include '../db/setting.php';
require_once '../db/db.php';
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


$id_subjectNormalized = trim($_POST['id-subject-change']);
$nameNormalized = trim($_POST['name-subject-change']);
$mark_systemNormalized = trim($_POST['mark-system-subject-change']);
$id_pictureNormalized = trim($_POST['id-fon-change']);
$pictureNormalized = $_FILES['picture-subject-change'];

if (!empty($id_subjectNormalized) && !empty($nameNormalized) && !empty($mark_systemNormalized) && $_FILES['picture-subject-change']['size'] == 0) {

   //изменение без картинки - начало 

   try {
      $sql = $db->query(
         "UPDATE `subject` SET `name`= :name,`mark_system`= :mark_system WHERE `id` = :id",
         [  
            ':name'        => $nameNormalized,
            ':mark_system' => $mark_systemNormalized,
            ':id'          => $id_subjectNormalized
         ]
      );
   } catch (Exception $e) {
      echo "Помилка виконання! Зверніться до Адміністратора сайту!";
   }
   
   try {
      $sql = $db->query(
         "UPDATE `background_subject` SET `image` = :picture WHERE `id` = :id",
         [  
            ':picture'  => NULL,
            ':id'       => $id_pictureNormalized
         ]
      );
   } catch (Exception $e) {
      echo "Помилка виконання! Зверніться до Адміністратора сайту!";
   }

   echo '<div style="padding-left: 3%; padding-bottom: 2%; width:100%"><p class="massage-error" style="width: auto; color:green;"><img src="../images/ok.png" alt="Ok!">Фон предмету ' . $nameNormalized . ' видалено!</p></div>';
   echo '<script>
         $(\'#id-subject-change\').val("");
         $(\'#name-subject-change\').val("");
         $(\'#mark-system-subject-change\').val("");
         $(\'#id-fon-change\').val();
         $(\'#picture-subject-change\').val();
         </script>';
   //изменение без картинки - конец

} else if (!empty($id_subjectNormalized) && !empty($nameNormalized) && !empty($mark_systemNormalized) && !empty($pictureNormalized)) {

   if (can_upload($_FILES['picture-subject-change']) == 1) {
      
      //изменение с картинкой - начало
      $imageHash = file_get_contents($_FILES['picture-subject-change']['tmp_name']);

      try {
         $sql = $db->query(
            "UPDATE `subject` SET `name`= :name,`mark_system`= :mark_system WHERE `id` = :id",
            [  
               ':name'        => $nameNormalized,
               ':mark_system' => $mark_systemNormalized,
               ':id'          => $id_subjectNormalized
            ]
         );
      } catch (Exception $e) {
         echo "Помилка виконання! Зверніться до Адміністратора сайту!";
      }

      try {
         $sqll = $db->query(
            "UPDATE `background_subject` SET `image` = :picture WHERE `id` = :id",
            [  
               ':picture'  => $imageHash,
               ':id'       => $id_pictureNormalized
            ]
         );
      } catch (Exception $e) {
         echo "Помилка виконання! Зверніться до Адміністратора сайту!";
      }
         
      echo '<div style="padding-left: 3%; padding-bottom: 2%; width:100%"><p class="massage-error" style="width: auto; color:green;"><img src="../images/ok.png" alt="Ok!">Фон предмету ' . $nameNormalized . ' змінено!</p></div>';
      echo '<script>
         $(\'#id-subject-change\').val("");
         $(\'#name-subject-change\').val("");
         $(\'#mark-system-subject-change\').val("");
         $(\'#id-fon-change\').val("");
         $(\'#picture-subject-change\').val("");
         </script>';
      //изменение с картинкой - конец
   } else {
      echo '<div style="padding-left: 3%; padding-bottom: 2%; width:auto"><p class="massage-error" style="width: auto; color:red;"><img src="../images/error.png" alt="Error!">Помилка зміни фону предмета!<br>
      Дозволяються розширення .png, .jpg, .jpeg та розмір картинки не більше ніж 4 Мб</p></div>';
   }
}
