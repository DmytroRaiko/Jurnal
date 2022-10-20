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

try {
   $ban = $db->query("SELECT `access` 
      FROM `prohibition` 
      WHERE `operation` = :operation",
      [
         ':operation'  => "marks"
      ]
   );
} catch (Exception $e) {
   echo "Помилка виконання! Зверніться до Адміністратора сайту!";
}

if ($ban[0]['access'] == 1) {

   $teacher = $_POST['teacher'];  
   $descipline = $_POST['descipline'];  
   $group = $_POST['group'];  

   try {
      $sql_c_teacher = $db->query(
         "SELECT `count_teacher` AS 'c_t'
         FROM `descipline` INNER JOIN `subject` 
            ON `descipline`.`subject` = `subject`.`id`
         WHERE `descipline`.`id` = :descipline)",
         [  
            ':descipline' => $descipline
         ]
      );
   } catch (Exception $e) {
      echo "Помилка виконання! Зверніться до Адміністратора сайту!";
   }
   
   try {
      $sql = $db->query(
         "SELECT count(*) 
         FROM `descipline` INNER JOIN ( `pair` INNER JOIN `group` 
            ON `pair`.`group` = `group`.`id`) 
            ON `descipline`.`id` = `pair`.`descipline`
         WHERE `group`.`id` = :group AND `descipline`.`subject` = 
         (SELECT `subject` FROM `descipline` WHERE `descipline`.`id` = :descipline)",
         [  
            ':group'      => $group,
            ':descipline' => $descipline
         ]
      );
   } catch (Exception $e) {
      echo "Помилка виконання! Зверніться до Адміністратора сайту!";
   }

   if (($sql[0]['count(*)'] == 1 && $sql_c_teacher[0]['c_t'] == 1) 
      || ($sql[0]['count(*)'] == 2 && $sql_c_teacher[0]['c_t'] == 2)) {
      echo '<center><div style="padding-left: 3%; padding-bottom: 2%; width:auto"><p class="massage-error" style="width: auto; color:red;"><img src="../images/error.png" alt="Error!">Даний предмет викладаєтся у цієї групи</p></div></center>';
   } else {

      try {
         $sql = $db->query(
            "INSERT INTO `pair` (`group`, `descipline`)
            VALUE (:group, :descipline)",
            [  
               ':group'      => $group,
               ':descipline' => $descipline
            ]
         );
      } catch (Exception $e) {
         echo "Помилка виконання! Зверніться до Адміністратора сайту!";
      }

      echo "<script>$('#group-add-teacher').val(\"\");
      setTimeout(function() {window.location.reload();}, 1000);
      </script>"
      ;
      echo '<center><div style="padding-left: 3%; padding-bottom: 2%; width:90%"><p class="massage-error" style="width: auto; color:green;"><img src="../images/ok.png" alt="Ok!">Предмет додано!</p></div></center>';

   }
} else {
   echo "<script>alert(\"Редагування оцінок обмежено!\")</script>";
}