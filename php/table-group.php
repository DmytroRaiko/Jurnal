<?php 
   require_once '../db/db.php';
   include '../db/setting.php';
   $db = new DataBase();
                                    
   $data = $_POST["groupValues"].'%';
                                       
   try {      
      $sql = $db->query("SELECT `group`.`id` AS 'group_id',`group`.`name` AS 'group_name', `specialty`.`spec_number` AS 'specialty_spec_number',
            `teacher`.`surname` AS 'teacher_surname', `teacher`.`name` AS 'teacher_name', `teacher`.`middle_name` AS 'teacher_middle_name'
            FROM `specialty` INNER JOIN (`group` INNER JOIN `teacher` ON `group`.`kurator` = `teacher`.`id`) ON `specialty`.`id` = `group`.`specialty`
            WHERE `group`.`name` LIKE :group ORDER BY `group`.`name`",
         [
            ':group' => $data
         ]
      );

   } catch (Exception $e) {
      echo "Помилка авторизації! Зверніться до Адміністратора сайту!";    
   }

   try {      
      $sqll = $db->query("SELECT `group`.`id` AS 'group_id',`group`.`name` AS 'group_name', `teacher`.`surname` AS 'teacher_surname', 
            `teacher`.`name` AS 'teacher_name', `teacher`.`middle_name` AS 'teacher_middle_name'
            FROM `group` INNER JOIN `teacher` ON `group`.`kurator` = `teacher`.`id`
            WHERE `group`.`name` LIKE :group AND `group`.`specialty` IS NULL 
            ORDER BY `group`.`name`",
         [
            ':group' => $data
         ]
      );

   } catch (Exception $e) {
      echo "Помилка авторизації! Зверніться до Адміністратора сайту!";    
   }

   try {      
      $sqlll = $db->query("SELECT `group`.`id` AS 'group_id',`group`.`name` AS 'group_name', `specialty`.`spec_number` AS 'specialty_spec_number'
            FROM `specialty` INNER JOIN `group` ON `specialty`.`id` = `group`.`specialty`
            WHERE `group`.`name` LIKE :group AND `group`.`kurator` IS NULL 
            ORDER BY `group`.`name`",
         [
            ':group' => $data
         ]
      );

   } catch (Exception $e) {
      echo "Помилка авторизації! Зверніться до Адміністратора сайту!";    
   }
      
   try {      
      $sqllll = $db->query("SELECT `group`.`id` AS 'group_id',`group`.`name` AS 'group_name' 
            FROM `group` 
            WHERE `group`.`name` LIKE :group AND `group`.`kurator` IS NULL AND `group`.`specialty` IS NULL 
            ORDER BY `group`.`name`",
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
            "<td> {$sql[$r]['group_name']}</td>".
            "<td> {$sql[$r]['specialty_spec_number']} </td>".
            "<td> {$sql[$r]['teacher_surname']}"." "."{$sql[$r]['teacher_name']}"." "."{$sql[$r]['teacher_middle_name']} </td>".
            "<td style=\"border-left: 1px dashed rgb(59, 128, 255)\"><a href='?red_group_id={$sql[$r]['group_id']}'>Змінити</a> </td>".
         '</tr>';

      } else if ($r%2 != 0) {

         echo '<tr>'.
            "<td style=\" background: rgb(181, 220, 255);\"> {$sql[$r]['group_name']}</td>".
            "<td style=\" background: rgb(181, 220, 255);\"> {$sql[$r]['specialty_spec_number']} </td>".
            "<td style=\" background: rgb(181, 220, 255);\"> {$sql[$r]['teacher_surname']}"." "."{$sql[$r]['teacher_name']}"." "."{$sql[$r]['teacher_middle_name']} </td>".
            "<td style=\" background: rgb(181, 220, 255); border-left: 1px dashed rgb(59, 128, 255)\"><a href='?red_group_id={$sql[$r]['group_id']}'>Змінити</a> </td>".
         '</tr>';
      }
      $r++; 
   }

   $rl = 0;
   while ($rl < count($sqll)) {

      if ($r%2 == 0 || $r === 0) {

         echo '<tr style=" background: white;">'.
            "<td> {$sqll[$rl]['group_name']}</td>".
            "<td> <i>NULL<i></td>".
            "<td> {$sqll[$rl]['teacher_surname']}"." "."{$sqll[$rl]['teacher_name']}"." "."{$sqll[$rl]['teacher_middle_name']} </td>".
            "<td style=\"border-left: 1px dashed rgb(59, 128, 255)\"><a href='?red_group_id={$sqll[$rl]['group_id']}'>Змінити</a> </td>".
         '</tr>';

      } else if ($r%2 != 0) {

         echo '<tr>'.
            "<td style=\" background: rgb(181, 220, 255);\"> {$sqll[$rl]['group_name']}</td>".
            "<td style=\" background: rgb(181, 220, 255);\"> <i>NULL<i> </td>".
            "<td style=\" background: rgb(181, 220, 255);\"> {$sqll[$rl]['teacher_surname']}"." "."{$sqll[$rl]['teacher_name']}"." "."{$sqll[$rl]['teacher_middle_name']} </td>".
            "<td style=\" background: rgb(181, 220, 255); border-left: 1px dashed rgb(59, 128, 255)\"><a href='?red_group_id={$sqll[$rl]['group_id']}'>Змінити</a> </td>".
         '</tr>';
      }
      $r++; 
      $rl++;
   }
   
   $rll = 0;
   while ($rll < count($sqlll)) {

      if ($r%2 == 0 || $r === 0) {

         echo '<tr style=" background: white;">'.
            "<td> {$sqlll[$rll]['group_name']}</td>".
            "<td> {$sqlll[$rll]['specialty_spec_number']}</td>".
            "<td> <i>NULL<i> </td>".
            "<td style=\"border-left: 1px dashed rgb(59, 128, 255)\"><a href='?red_group_id={$sqlll[$rll]['group_id']}'>Змінити</a> </td>".
         '</tr>';

      } else if ($r%2 != 0) {

         echo '<tr>'.
            "<td style=\" background: rgb(181, 220, 255);\"> {$sqlll[$rll]['group_name']}</td>".
            "<td style=\" background: rgb(181, 220, 255);\"> {$sqlll[$rll]['specialty_spec_number']} </td>".
            "<td style=\" background: rgb(181, 220, 255);\"> <i>NULL<i> </td>".
            "<td style=\" background: rgb(181, 220, 255); border-left: 1px dashed rgb(59, 128, 255)\"><a href='?red_group_id={$sqlll[$rll]['group_id']}'>Змінити</a> </td>".
         '</tr>';
      }
      $r++; 
      $rll++;
   }
        
   $rlll = 0;
   while ($rlll < count($sqllll)) {

      if ($r%2 == 0 || $r === 0) {

         echo '<tr style=" background: white;">'.
            "<td> {$sqllll[$rlll]['group_name']}</td>".
            "<td> <i>NULL<i> </td>".
            "<td> <i>NULL<i> </td>".
            "<td style=\"border-left: 1px dashed rgb(59, 128, 255)\"><a href='?red_group_id={$sqllll[$rlll]['group_id']}'>Змінити</a> </td>".
         '</tr>';

      } else if ($r%2 != 0) {

         echo '<tr>'.
            "<td style=\" background: rgb(181, 220, 255);\"> {$sqllll[$rlll]['group_name']}</td>".
            "<td style=\" background: rgb(181, 220, 255);\"> <i>NULL<i> </td>".
            "<td style=\" background: rgb(181, 220, 255);\"> <i>NULL<i> </td>".
            "<td style=\" background: rgb(181, 220, 255); border-left: 1px dashed rgb(59, 128, 255)\"><a href='?red_group_id={$sqllll[$rlll]['group_id']}'>Змінити</a> </td>".
         '</tr>';
      }
      $r++; 
      $rlll++;
   }
?>