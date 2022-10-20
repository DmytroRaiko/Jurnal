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


if (!empty($_POST['id'])  && !empty($_POST['dayStart']) && !empty($_POST['dayFinish'])) {
   
   $kurator = $_POST['id'];
   $day_start = $_POST['dayStart'];
   $day_finish = $_POST['dayFinish'];

   $where = "`visiting`.`date` BETWEEN :day_start AND :day_finish";
   $array_cause = array("хв", "зв", "рз", "пр");
   try {
      $count_student = $db->query("SELECT COUNT(*) 
      FROM `student` INNER JOIN `group` ON `student`.`group`=`group`.`id`
      WHERE `group`.`kurator` = :kurator",
         [
            ':kurator'  => $kurator
         ]
      );
   } catch (Exception $e) {
      echo "Помилка виконання! Зверніться до Адміністратора сайту!";
   }
   $c_s = $count_student[0]['COUNT(*)'];

   try {
      $thead = $db->query("SELECT DISTINCT `date` 
      FROM `visiting` INNER JOIN (`student` INNER JOIN `group` ON `student`.`group`=`group`.`id`) 
         ON `visiting`.`student`=`student`.`id`
      WHERE `kurator` = :kurator AND ".$where."
      ORDER BY `date`",
         [
            ':kurator'  => $kurator,
            ':day_start'   => $day_start,
            ':day_finish'  => $day_finish
         ]
      );
      } catch (Exception $e) {
      echo "Помилка виконання! Зверніться до Адміністратора сайту!";
   }

   echo "<table>   
   <thead style=\"background: rgb(49, 173, 211); \">
   <tr>
      <th>№</th>
      <th>ПІБ</th>";

   $a = 0;
   while ($a < count($thead)) {

      
      try {
         $span = $db->query("SELECT COUNT(`date`) AS 'span' 
         FROM `visiting` INNER JOIN (`student` INNER JOIN `group` 
            ON `student`.`group`=`group`.`id`) 
           ON `visiting`.`student`=`student`.`id`
         WHERE `group`.`kurator` = :kurator AND `date` = :date AND ".$where,
            [
               ':kurator'  => $kurator,
               ':date'     => $thead[$a]['date'],
               ':day_start'   => $day_start,
               ':day_finish'  => $day_finish
            ]
         );
         } catch (Exception $e) {
         echo "Помилка виконання! Зверніться до Адміністратора сайту!";
      }

      $s = 0;
      while ($s < count($span)) {
         
         $colspan = $span[$s]['span'] / $c_s;
         echo "<th colspan=\"".$colspan."\">
            ".date("d/m", strtotime($thead[$a]['date']))."
            </th>";
         $s++;
      }
      $a++;
   }
   echo "</tr></thead><tbody>";

   try {
      $student = $db->query("SELECT DISTINCT `student`.`surname` AS 'surname', `student`.`name` AS 'name',
            `student`.`middle_name` AS 'middle_name', `student`.`id` AS 'id'
         FROM `visiting` INNER JOIN (`student` INNER JOIN `group` 
            ON `student`.`group`=`group`.`id`) 
         ON `visiting`.`student`=`student`.`id`
         WHERE `group`.`kurator` = :kurator
         ORDER BY `student`.`surname`,`student`.`name`,`student`.`middle_name`",
         [
            ':kurator'  => $kurator
         ]
      );
   } catch (Exception $e) {
      echo "Помилка виконання! Зверніться до Адміністратора сайту!";
   }

   $numeric = $a = 0; 
   $k = 1;
   while ($a < count($student)) {

      $background_row = "#f2fff5";

      if ($k%2==0 ) {
         $background_row = "#f2fff5";
         $b = $background_row;
      } else if ($k%3==0) {
         $background_row = "#eeecff";
         $b = $background_row;
      } else {
         $background_row = "#ffeaea";
         $b = $background_row;
      } 

      try {
         $visit = $db->query("SELECT `student`.`surname`, `student`.`name`,
               `student`.`middle_name`, `student`.`id`,
               `visiting`.`id` AS 'id', `visiting`.`cause` AS 'cause', `visiting`.`date` AS 'date', `visiting`.`order`
            FROM `visiting` INNER JOIN (`student` INNER JOIN `group` 
               ON `student`.`group`=`group`.`id`) 
            ON `visiting`.`student`=`student`.`id`
            WHERE `group`.`kurator` = :kurator AND `visiting`.`student` = :student AND ".$where."
            ORDER BY `student`.`surname`,`student`.`name`,`student`.`middle_name`, `visiting`.`date`, `visiting`.`order`",
            [
               ':kurator'  => $kurator,
               ':student'  => $student[$a]['id'],
               ':day_start'   => $day_start,
               ':day_finish'  => $day_finish
            ]
         );
      } catch (Exception $e) {
         echo "Помилка виконання! Зверніться до Адміністратора сайту!";
      }

      $numeric = $a + 1;
      echo "<tr style=\"background: ".$background_row.";\">
         <td><center>
            ".$numeric."
         </center></td>
         <td style=\"padding-left:2px;padding-right:2px; min-width: 138px\" title=\"".$student[$a]['surname']." ".$student[$a]['name']." ".$student[$a]['middle_name']."\">
            ".$student[$a]['surname']." ".mb_substr($student[$a]['name'], 0, 1).".".mb_substr($student[$a]['middle_name'], 0, 1).".
         </td>";


      $b = 0;
      while ($b < count($visit)) {
         
         $color = "black";
         if ($visit[$b]['cause'] == "пр") {
            $color = "#9B001C";
         }

         echo "
            <td class=\"mark\" style=\"width: 30px;\">
            <form class=\"visit-change\" style=\"background: ;\"> 
               <input type=\"hidden\" name=\"change-visit-id\" id=\"change-visit-id\" value=\"".$visit[$b]['id']."\">
               <select type=\"text\" name=\"change-visit-edit\" id=\"change-visit-edit\" 
                  style=\"background: ".$background_row."; border: none; margin: 0px; font-size: 16px;
                  color: ".$color."; -webkit-appearance: none; -moz-appearance: none; appearance: none;\"
                  pattern=\"пр?|хв?|зв?|рз?\"  title=\"\" autocomplete=\"off\">
                  <option value=\"\"></option>
         ";

         $c = 0; 
         while ($c < count($array_cause)) {
            
            if ($visit[$b]['cause'] == $array_cause[$c]) {
               $selected = "selected";
            } else {
               $selected = "";
            }

            echo "
               <option value = \"".$array_cause[$c]."\" ".$selected.">".$array_cause[$c]."</option>
               ";
            $c++;
         }
         echo "
               <select>
            </form>
         </td>
         ";
         $b++;
      }
      echo "</tr>";
      $a++;
      $k++;
      $k >= 4 ? $k = 1 : $k = $k; 
   }
   echo "<tr><td style=\"border: none;\" colspan=\"2\" rowspan=\"2\"></td>";

   try {
      $thead = $db->query("SELECT DISTINCT `visiting`.`date` AS 'date', `subject`.`name` AS 'subject',               
            `visiting`.`pair` AS 'pair', `visiting`.`order`
         FROM `subject` INNER JOIN (
               `descipline` INNER JOIN (
                        `visiting` INNER JOIN (
                           `student` INNER JOIN `group` 
                           ON `student`.`group`=`group`.`id`)
                        ON `visiting`.`student`=`student`.`id`)
               ON `descipline`.`id`=`visiting`.`pair`) 
            ON `subject`.`id`=`descipline`.`subject`
         WHERE `group`.`kurator` = :kurator AND ".$where."
         ORDER BY `date`,`visiting`.`order`",
         [
            ':kurator'     => $kurator,
            ':day_start'   => $day_start,
            ':day_finish'  => $day_finish
         ]
      );
      } catch (Exception $e) {
      echo "Помилка виконання! Зверніться до Адміністратора сайту!";
   }
   
   $a = 0;
   while ($a < count($thead)) {
      
      echo "<td title=\"".$thead[$a]['subject']."\" style=\"text-align: center; background: rgb(49, 173, 211);\">
         <p style=\"border-collapse: collapse; font-family: 'Roboto Condensed', sans-serif; font-weight: normal; word-wrap: none; border: none; 
         writing-mode: vertical-rl; -webkit-transform: rotate(-180deg); -moz-transform: rotate(-180deg); -ms-transform: rotate(-180deg); 
         -o-transform: rotate(-180deg); transform: rotate(-180deg); font-size: 14px; margin-top: 2px; margin-bottom: 2px; text-overflow: ellipsis;
         overflow: hidden; word-break: break-all;\">".$thead[$a]['subject']."</p></td>";
      $a++;
   }
   echo "</tr><tr>";

   $a = 0;
   while ($a < count($thead)) {
   
      try {
         $thead = $db->query("SELECT DISTINCT `date` 
         FROM `visiting` INNER JOIN (`student` INNER JOIN `group` ON `student`.`group`=`group`.`id`) 
            ON `visiting`.`student`=`student`.`id`
         WHERE `kurator` = :kurator AND ".$where."
         ORDER BY `date`",
            [
               ':kurator'  => $kurator,
               ':day_start'   => $day_start,
               ':day_finish'  => $day_finish
            ]
         );
         } catch (Exception $e) {
         echo "Помилка виконання! Зверніться до Адміністратора сайту!";
      }
   
      $a = 0;
      while ($a < count($thead)) {
   
         
         try {
            $span = $db->query("SELECT COUNT(`date`) AS 'span' 
            FROM `visiting` INNER JOIN (`student` INNER JOIN `group` 
               ON `student`.`group`=`group`.`id`) 
              ON `visiting`.`student`=`student`.`id`
            WHERE `group`.`kurator` = :kurator AND `date` = :date AND ".$where,
               [
                  ':kurator'  => $kurator,
                  ':date'     => $thead[$a]['date'],
                  ':day_start'   => $day_start,
                  ':day_finish'  => $day_finish
               ]
            );
            } catch (Exception $e) {
            echo "Помилка виконання! Зверніться до Адміністратора сайту!";
         }
   
         $s = 0;
         while ($s < count($span)) {
            
            $colspan = $span[$s]['span'] / $c_s;
            echo "<td class=\"mark delete-day-visit\" colspan=\"".$colspan."\" id=\"".$thead[$a]['date']." ".$kurator."\"style=\"background: rgb(219, 96, 96);\">
            <img src=\"../images/delete_bin_black_40px.png\" height=\"23px\" width=\"23px\" alt=\"Вид.\" title=\"Видалити\">
               </td>";
            $s++;
         }
         $a++;
      }
   $a++;
   }
   echo "</tr></tbody></table>";
}