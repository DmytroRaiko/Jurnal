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

$descipline = $_POST['descipline']; 

try {
   $sql = $db->query(
      "SELECT `group`.`name` AS 'name', `pair`.`id` AS 'pair', `group`.`id` AS 'group'
      FROM `descipline` INNER JOIN (`pair` INNER JOIN `group` 
        ON `pair`.`group` = `group`.`id`) 
        ON `descipline`.`id` = `pair`.`descipline`
      WHERE `descipline`.`id` = :id
      ORDER BY `group`.`name`;",
      [  
         ':id' => $descipline
      ]
   );
} catch (Exception $e) {
   echo "Помилка виконання! Зверніться до Адміністратора сайту!";
}

$r = 0;
while ($r < count($sql)) {

   echo "
   <div class=\"choose-group\" style=\"text-align: center;\">
   <div class=\"menu-delete\" id=\"".$sql[$r]['pair']."\"><img src=\"../images/delete_bin_40px.png\"  height=\"30px\" width=\"30px\" alt=\"Vertical menu\"></div>
   <p class=\"group-text\" id=\"".$sql[$r]['pair']."\">".$sql[$r]['name']."</p>
   </div>
   ";

   $r++;
}

echo "
   <div class=\"block-add\" id=\"block-add\" >
      <div id=\"line-horizontal\"></div>
      <div id=\"line-vertical\"></div>
      <div class=\"text-footer\">
         <p>Додати групу</p>
      </div>
   </div>
";
?>