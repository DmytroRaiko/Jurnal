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
   $sql = $db->query("SELECT `group`.`name` AS 'group', `group`.`id` AS 'id', 
         `teacher`.`surname` AS 'surname', `teacher`.`name` AS 'name', `teacher`.`middle_name` AS 'middle_name'
      FROM `group` INNER JOIN `teacher` ON `group`.`kurator`=`teacher`.`id`
      ORDER BY `group`.`name`"
   );
} catch (Exception $e) {
   echo "Помилка виконання! Зверніться до Адміністратора сайту!";
}

$r = 0;
while ($r < count($sql)) {

   echo "<div class=\"choose-group\" style=\"text-align: center;\">
   <p class=\"group-text\" id=\"".$sql[$r]['id']."\">".$sql[$r]['group']."</p>
   <p class=\"text-footer\" title=\"".$sql[$r]['surname']." ".$sql[$r]['name']." ".$sql[$r]['middle_name']."\">".$sql[$r]['surname']." ".mb_substr($sql[$r]['name'], 0, 1).".".mb_substr($sql[$r]['middle_name'], 0, 1).".</p>   
   </div>";
   $r++;
}
?>