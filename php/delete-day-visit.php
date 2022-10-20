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
         ':operation'  => "visit"
      ]
   );
} catch (Exception $e) {
   echo "Помилка виконання! Зверніться до Адміністратора сайту!";
}

if ($ban[0]['access'] == 1) {

   if (!empty($_POST['date']) && !empty($_POST['kurator'])) {

      $date = $_POST['date'];
      $kurator = $_POST['kurator'];

      try {
         $student = $db->query("SELECT DISTINCT `student`.`surname` AS 'surname', `student`.`name` AS 'name',
               `student`.`middle_name` AS 'middle_name', `student`.`id` AS 'id'
            FROM `student` INNER JOIN `group` 
               ON `student`.`group`=`group`.`id`
            WHERE `group`.`kurator` = :kurator
            ORDER BY `student`.`surname`,`student`.`name`,`student`.`middle_name`",
            [
               ':kurator'  => $kurator
            ]
         );
      } catch (Exception $e) {
         echo "Помилка виконання! Зверніться до Адміністратора сайту!";
      }

      $a = 0;
      while ($a < count($student)) {
         
         try {
            $db->query("DELETE 
               FROM `visiting` 
               WHERE `date` = :date AND `student` = :student",
               [
                  ':student'  => $student[$a]['id'],
                  ':date'     => $date
               ]
            );
         } catch (Exception $e) {
            echo "Помилка виконання! Зверніться до Адміністратора сайту!";
         }
         $a++;
      }
   }
} else {
   echo "<script>alert(\"Редагування пропусків обмежено!\")</script>";
}
