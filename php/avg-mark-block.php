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

$pair = trim($_POST['pair']);
$type = trim($_POST['type']);
$date = trim($_POST['date']);

if (!empty($type) && !empty($date)) {

   echo "<script>
      $('#checkbox-dropdown-container').show();
      $('.modal-window-block').css('height', '480px');
      $('#checkbox-dropdown-container-label').show();</script>";

   try {
      $date_start = $db->query(
         "SELECT DISTINCT `date` FROM `mark`
               WHERE `date` <= :date AND `pair` = :pair AND `type` = :type
               ORDER BY `date` DESC",
         [
            ':date'  => $date,
            ':pair'  => $pair,
            ':type'  => $type
         ]
      );
   } catch (Exception $e) {
      echo "Помилка виконання! Зверніться до Адміністратора сайту!";
   }

   $condition_date = "";
   if (count($date_start) <= 0) {
      $condition_date .= "`mark`.`date` <= :date_finish";
      try {
         $sql = $db->query("SELECT DISTINCT `mark`.`type` AS 'mark_type'
         FROM `mark` INNER JOIN `pair` ON `mark`.`pair` = `pair`.`id` 
         WHERE `pair`.`id` = :id AND `mark`.`type` != :type AND 
            (".$condition_date.")",
            [  
               ':id'    => $pair,
               ':type'  => $type,
               ':date_finish' => $date
            ]
         );
      } catch (Exception $e) {
         echo "Помилка виконання! Зверніться до Адміністратора сайту!";
      }
   } else {
      $condition_date .= "`mark`.`date` > :date_start  AND `mark`.`date` <= :date_finish";
      $date_s = $date_start[0]['date'];
      try {
         $sql = $db->query("SELECT DISTINCT `mark`.`type` AS 'mark_type'
         FROM `mark` INNER JOIN `pair` ON `mark`.`pair` = `pair`.`id` 
         WHERE `pair`.`id` = :id AND `mark`.`type` != :type AND 
            (".$condition_date.")",
            [  
               ':id'    => $pair,
               ':type'  => $type,
               ':date_start'  => $date_s,
               ':date_finish' => $date
            ]
         );
      } catch (Exception $e) {
         echo "Помилка виконання! Зверніться до Адміністратора сайту!";
      }
   }

   

   echo "<div id=\"fromCustomSelect\">
            <div>
               <div class=\"custom-select\" id=\"custom-select\">Оцінка </div>
               <div id=\"custom-select-option-box\">";

   $r = 0; 
   while ($r < count($sql)){
      
      echo "
         <div class=\"custom-select-option\">
            <input onchange=\"toggleFillColor(this);\"
            class=\"custom-select-option-checkbox\" type=\"checkbox\"
               name=\"toys[]\" value=\"".$sql[$r]['mark_type']."\"> ".$sql[$r]['mark_type']."
         </div>";
      $r++;
   }

   echo "
               </div>
            </div>
         </div>";
}
