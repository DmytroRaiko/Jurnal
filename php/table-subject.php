<?php 
   require_once '../db/db.php';
   include '../db/setting.php';
   $db = new DataBase();
                                    
   $name = '%'.$_POST["name"].'%';
                                       
   try {      
      $sql = $db->query("SELECT `subject`.`id` AS 'subject_id', `subject`.`name` AS 'subject_name', 
            `subject`.`mark_system` AS 'subject_mark_system', `background_subject`.`image` AS 'image_background' 
            FROM `subject` INNER JOIN `background_subject` ON `subject`.`id` = `background_subject`.`subject`
            WHERE `subject`.`name` LIKE :name ORDER BY `subject`.`name`",
         [
            ':name' => $name
         ]
      );

   } catch (Exception $e) {
      echo "Помилка авторизації! Зверніться до Адміністратора сайту!";    
   }
                                    
   $r = 0;
   while ($r < count($sql)) {
      if ($r%2 == 0 || $r === 0) {

         echo '<tr style=" background: white;">'.
            "<td> {$sql[$r]['subject_name']}</td>".
            "<td> {$sql[$r]['subject_mark_system']} </td>".
            "<td><img style=\"width: 150px; heigth:150px;\" src=\"data:image/jpeg;base64, ".base64_encode($sql[$r]['image_background'])."\" alt=\"\"></td>".
            "<td style=\"border-left: 1px dashed rgb(59, 128, 255)\"><a href='?red_subject_id={$sql[$r]['subject_id']}'>Змінити</a> </td>".
         '</tr>';

      } else if ($r%2 != 0) {

         echo '<tr>'.
            "<td style=\" background: rgb(181, 220, 255);\"> {$sql[$r]['subject_name']}</td>".
            "<td style=\" background: rgb(181, 220, 255);\"> {$sql[$r]['subject_mark_system']} </td>".
            "<td style=\" background: rgb(181, 220, 255);\"><img style=\"width: 150px; heigth:150px;\" src=\"data:image/jpeg;base64, ".base64_encode($sql[$r]['image_background'])."\" alt=\"\"></td>".
            "<td style=\" background: rgb(181, 220, 255); border-left: 1px dashed rgb(59, 128, 255)\"><a href='?red_subject_id={$sql[$r]['subject_id']}'>Змінити</a> </td>".
         '</tr>';
      }
      $r++; 
   }
                                    
?>