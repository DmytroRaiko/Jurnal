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

$group = $_POST['group'];  
try {
   $sql = $db->query(
      "SELECT `subject`.`name` AS 'subject', `subject`.`mark_system` AS 'mark_system',
            `background_subject`.`image` AS 'image', `pair`.`id` AS 'id', 
            `teacher`.`surname` AS 'surname', `teacher`.`name` AS 'name', `teacher`.`middle_name` AS 'middle_name'
      FROM `group` INNER JOIN 
         (`pair` INNER JOIN 
            (`descipline` INNER JOIN 
                  (`subject` INNER JOIN `background_subject` 
               ON `subject`.`id`=`background_subject`.`subject`) 
               ON `descipline`.`subject`=`subject`.`id` )
         ON `pair`.`descipline`=`descipline`.`id`)
      ON	`group`.`id`=`pair`.`group` INNER JOIN `teacher` ON `descipline`.`teacher`=`teacher`.`id`
      WHERE `group`.`id` = :id
      ORDER BY `subject`.`mark_system`, `subject`.`name`",
      [  
         ':id' => $group
      ]
   );
} catch (Exception $e) {
   echo "Помилка виконання! Зверніться до Адміністратора сайту!";
}

$r = 0;
$q = 0;
while ($r < count($sql)) {

   if ($sql[$r]['image'] == NULL) {

      echo "
         <div class=\"choose-subject-admin\" id=\"".$sql[$r]['id']."\" style=\"
               background-image: url(../images/background-subject".$q.".jpg);
               background-repeat: no-repeat;
               -webkit-background-size: cover;
               background-size: cover;\">
            <div class=\"text-footer-left-top\">".$sql[$r]['subject']."</div>
            <div class=\"text-footer-left-bottom\" title=\"".$sql[$r]['surname']." ".$sql[$r]['name']." ".$sql[$r]['middle_name']."\">
            <i>".$sql[$r]['surname']." ".mb_substr($sql[$r]['name'], 0, 1).".".mb_substr($sql[$r]['middle_name'], 0, 1).".</i></div>
            <div class=\"text-footer-kurator\">".$sql[$r]['mark_system']."</div>
         </div>
      ";
      $q++;
      if ($q > 5) $q = 0;
   } else {

      echo "
         <div class=\"choose-subject-admin\" id=\"".$sql[$r]['id']."\" style=\"
               background-image: url(data:image/jpg;base64,".base64_encode($sql[$r]['image']).");
               background-repeat: no-repeat;
               -webkit-background-size: cover;
               background-size: cover;\">
               <div class=\"text-footer-left-top\">".$sql[$r]['subject']."</div>
               <div class=\"text-footer-left-bottom\" title=\"".$sql[$r]['surname']." ".$sql[$r]['name']." ".$sql[$r]['middle_name']."\">
               <i>".$sql[$r]['surname']." ".mb_substr($sql[$r]['name'], 0, 1).".".mb_substr($sql[$r]['middle_name'], 0, 1).".</i></div>
               <div class=\"text-footer-kurator\">".$sql[$r]['mark_system']."</div>
         </div>
      ";
   }

   $r++;
}

?>