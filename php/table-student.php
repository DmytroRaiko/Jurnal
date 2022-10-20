
<?php 
   require_once '../db/db.php';
   include '../db/setting.php';
   $db = new DataBase();
                                    
   $data = $_POST["groupValue"];
                                       
   try {      
      $sql = $db->query("SELECT `student`.`id` AS 'student_id', `student`.`name` AS 'student_name', `student`.`surname` AS 'student_surname',
       `student`.`middle_name` AS 'student_middle_name', `student`.`study_form` AS 'student_study_form', `group`.`name` AS 'group_name'
        FROM `student` INNER JOIN `group` ON `student`.`group` = `group`.`id` WhERE `group`.`id` = :group ORDER BY `student`.`surname`",
         [
            ':group' => $data
         ]
      );

   } catch (Exception $e) {
      echo "Помилка авторизації! Зверніться до Адміністратора сайту!";    
   }
                                    
   $r = 0;
   while ($r < count($sql)) {
      if ($r%2 == 0 || $r === 0) {

         echo '<tr style=" background: white;">'.
            "<td> {$sql[$r]['student_surname']}"." "."{$sql[$r]['student_name']}"." "."{$sql[$r]['student_middle_name']} </td>".
            "<td> {$sql[$r]['group_name']}</td>".
            "<td> {$sql[$r]['student_study_form']} </td>".
            "<td style=\"border-left: 1px dashed rgb(59, 128, 255)\"><a href='?red_student_id={$sql[$r]['student_id']}'>Змінити</a> </td>".
         '</tr>';

      } else if ($r%2 != 0) {

         echo '<tr>'.
            "<td style=\" background: rgb(181, 220, 255);\"> {$sql[$r]['student_surname']}"." "."{$sql[$r]['student_name']}"." "."{$sql[$r]['student_middle_name']} </td>".
            "<td style=\" background: rgb(181, 220, 255);\"> {$sql[$r]['group_name']}</td>".
            "<td style=\" background: rgb(181, 220, 255);\"> {$sql[$r]['student_study_form']} </td>".
            "<td style=\" background: rgb(181, 220, 255); border-left: 1px dashed rgb(59, 128, 255)\"><a href='?red_student_id={$sql[$r]['student_id']}'>Змінити</a> </td>".
         '</tr>';
      }
      $r++; 
   }
                                    
?>