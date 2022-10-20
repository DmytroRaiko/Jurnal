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
   if (!empty($_POST['date-visit']) && !empty($_POST['pair-count']) && !empty($_POST['day-subject']) && !empty($_POST['kurator'])) {


      $date = $_POST['date-visit'];
      $count = $_POST['pair-count'];
      $kurator = $_POST['kurator'];
      $pair = htmlspecialchars(implode(",", $_POST['day-subject']));
      $pair = explode(',', $pair);

      try {
         $c = $db->query("SELECT COUNT(`date`) AS 'count'
            FROM `visiting` INNER JOIN (`student` INNER JOIN `group` ON `student`.`group`=`group`.`id`) 
               ON  `visiting`.`student`=`student`.`id`
            WHERE `group`.`kurator` = :kurator AND `date` = :date",
            [
               ':kurator'  => $kurator,
               ':date'     => $date
            ]
         );
      } catch (Exception $e) {
         echo "Помилка виконання! Зверніться до Адміністратора сайту!";
      }

      if ($c[0]['count'] == 0) {

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
         
         $r = 0;
         while ($r < count($student)) {
            $a = 0;
            while ($a < $count) {
               
               try {
                  $db->query("INSERT INTO `visiting`
                  (`student`, `cause`, `date`, `pair`, `order`) 
                  VALUES (:student, :cause, :date, :pair, :order)",
                     [
                        ':student'  => $student[$r]['id'],
                        ':cause'    => NULL,
                        ':date'     => $date,
                        ':pair'     => $pair[$a],
                        ':order'    => $a
                     ]
                  );
               } catch (Exception $e) {
                  echo "Помилка виконання! Зверніться до Адміністратора сайту!";
               }
               $a++;
            }
            $r++;
         }
      } else {
         echo '<center><div style="padding-left: 3%; padding-bottom: 2%; width:auto"><p class="massage-error" style="width: auto; color:red;"><img src="../images/error.png" alt="Error!">Неможливо додати день</p></div></center>';
      }
   } 
} else {
   echo "<script>alert(\"Редагування пропусків обмежено!\")</script>";
}