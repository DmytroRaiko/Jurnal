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

   $student_id = trim($_POST['change-student-id']);
   $mark_id = trim($_POST['change-mark-id']);
   $mark = trim($_POST['change-mark-edit']);

   if (!empty($mark_id)) {

      try {
         $sql = $db->query("SELECT `type` FROM `mark` WHERE `id` = :id",
            [  
               ':id' => $mark_id
            ]
         );
      } catch (Exception $e) {
         echo "Помилка виконання! Зверніться до Адміністратора сайту!";
      }
   }

   if ($sql[0]['type'] !== 'М' && $sql[0]['type'] !== 'С' && $sql[0]['type'] !== 'Р' && $sql[0]['type'] !== 'А') {

      if (!empty($student_id) && !empty($mark_id) && !empty($mark) 
            && ($mark === '1' || $mark === '2' || $mark === '3' || $mark === '4' || $mark === '5' || $mark === '6' 
            || $mark === '7' || $mark === '8' || $mark === '9' || $mark === '10' || $mark === '11' || $mark === '12' 
            || $mark === 'зал' || $mark === 'н/зал' || $mark === 'зар' || $mark === 'н/зар' || $mark === 'н/а')) {

         try {
            $sql = $db->query("SELECT `subject`.`mark_system` AS 'mark_system'
            FROM `subject` INNER JOIN (
                  `descipline` INNER JOIN (
                     `pair` INNER JOIN `mark`
                     ON `mark`.`pair` = `pair`.`id`)
                  ON `pair`.`descipline` = `descipline`.`id`)
               ON `descipline`.`subject` = `subject`.`id`
            WHERE `mark`.`id` = :id",
               [  
                  ':id' => $mark_id
               ]
            );
         } catch (Exception $e) {
            echo "Помилка виконання! Зверніться до Адміністратора сайту!";
         }

         if ($sql[0]['mark_system'] == 5 && (($mark > 0 && $mark < 6) || $mark == "н/зал"
                  || $mark == "зал" || $mark == "н/а" || $mark == "зар" || $mark == "н/зар")) {

            try {
               $db->query("UPDATE `mark` SET `mark`=:mark
               WHERE `id`=:id",
                  [  
                     ':mark'  => $mark,
                     ':id'    => $mark_id
                  ]
               );
            } catch (Exception $e) {
               echo "Помилка виконання! Зверніться до Адміністратора сайту!";
            }
            
         } else if ($sql[0]['mark_system'] == 12 && (($mark > 0 && $mark < 13) || $mark == "н/зал"
                  || $mark == "зал" || $mark == "н/а" || $mark == "зар" || $mark == "н/зар")) {
         
            try {
               $db->query("UPDATE `mark` SET `mark`=:mark
               WHERE `id`=:id",
                  [  
                     ':mark'  => $mark,
                     ':id'    => $mark_id
                  ]
               );
            } catch (Exception $e) {
               echo "Помилка виконання! Зверніться до Адміністратора сайту!";
            }
         } else if ($sql[0]['mark_system'] == "зал" && ($mark == "н/зал"
                  || $mark == "зал" || $mark == "н/а" || $mark == "зар" || $mark == "н/зар")) {
         
            try {
               $db->query("UPDATE `mark` SET `mark`=:mark
               WHERE `id`=:id",
                  [  
                     ':mark'  => $mark,
                     ':id'    => $mark_id
                  ]
               );
            } catch (Exception $e) {
               echo "Помилка виконання! Зверніться до Адміністратора сайту!";
            }
         }
      } else if (($mark === NULL || $mark === "") && (!empty($student_id) && !empty($mark_id))){

         try {
            $db->query("UPDATE `mark` SET `mark`=:mark
               WHERE `id`=:id",
               [  
                  ':mark'  => NULL,
                  ':id'    => $mark_id
               ]
            );
         } catch (Exception $e) {
            echo "Помилка виконання! Зверніться до Адміністратора сайту!";
         }
      }

      echo "
         <script>
            var student = ".$student_id.";
            var mark = ".$mark_id.";

            $.post(\"../php/change-avg-mark-table-teacher.php\", {student, mark})

            .done(function(data) {
               $('#add-pair-teacher-message').html(data);
            });
         </script>
      ";
   }
} else {
   echo "<script>alert(\"Редагування оцінок обмежено!\");

      var pair = localStorage.getItem(\"pair\");
      var data = $('#form-table-filter').serialize();
      data += \"&pair=\"+pair;

      $.ajax ({
         type: \"POST\",
         url: \"../php/mark-table-teacher.php\", 
         data: data,
         success: function(data) {
            $('#block-table-mark').html(data);
         }
      });
   </script>";
}
