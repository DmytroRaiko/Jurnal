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

$pair = $_POST['pair'];

function addWhere($where, $add, $and = true) {
   if ($where) {
     if ($and) $where .= " $add";
     else $where .= " OR $add";
   }
   else $where = $add;
   return $where;
}

$where = "AND ";
if (!empty($_POST['filter-month'])) {
   $where = addWhere($where, "`mark`.`date` LIKE '".$_POST['filter-month']."%'", true);
}
if (!empty($_POST['filter-type'])) {
   $type = htmlspecialchars(implode(",", $_POST['filter-type']));
   $type = explode(',', $type);
   $x = 0;
   $where .= " AND (";
   while ($x <count($type)) {
      if ($x == 0) {
         $where = addWhere($where,"`mark`.`type` = '".$type[$x]."' ", true);
      } else {
         $where = addWhere($where,"`mark`.`type` = '".$type[$x]."' ", false);
      }
      $x++;
   } 
   $where .= ")";
}

if ($where == "AND ") {
   $where = "";
}

try {
   $sql = $db->query("SELECT DISTINCT `mark`.`type` AS 'mark_type', `mark`.`date` AS 'mark_date', `mark`.`mark_identifikator`
      FROM `mark` INNER JOIN `pair` ON `mark`.`pair` = `pair`.`id` 
      WHERE `pair`.`id` = :id ".$where."
      ORDER BY `mark`.`date`, `mark`.`type`",
      [  
         ':id' => $pair
      ]
   );
} catch (Exception $e) {
   echo "Помилка виконання! Зверніться до Адміністратора сайту!";
}

try {
   $m_s = $db->query("SELECT `subject`.`mark_system` AS 'mark_system'
   FROM `subject` INNER JOIN (`descipline` INNER JOIN `pair` 
      ON `pair`.`descipline` = `descipline`.`id`) ON `descipline`.`subject` = `subject`.`id`
   WHERE `pair`.`id`  = :id",
      [  
         ':id' => $pair
      ]
   );
} catch (Exception $e) {
   echo "Помилка виконання! Зверніться до Адміністратора сайту!";
}

$mark_system = $m_s[0]['mark_system'];

if ($mark_system == 5){
   $pattern = "[1-5]?|н/зал?|зал?|н/а?|зар?|н/зар?";
   $title_default = "1-5 або ж н/зал, зал, зар, н/зар";
} else if ($mark_system == 12) {
   $pattern = "[1-5]?|[1]?[0-2]?|н/зал?|зал?|н/а?|зар?|н/зар?";
   $title_default = "1-12 або ж н/зал, зал, зар, н/зар";
} else if ($mark_system == "зал") {
   $pattern = "н/зал?|зал?";
   $title_default = "н/зал або ж зал";
}
$pattern_default = $pattern;
$count_pair = count($sql);
echo "<table>   
   <thead style=\"background: rgb(49, 173, 211); \">
   <tr>
      <th rowspan=\"2\">№</th>
      <th rowspan=\"2\">ПІБ</th>";
      
$r = 0;
$s = 0;
while ($r < $count_pair) {
   echo "
         <th style=\"padding: 2px;\">".date("d/m", strtotime($sql[$r]['mark_date']))."</th>
      ";
      $r++;
}
echo "</tr><tr>";

$r = 0;
$s = 0;
while ($r < $count_pair) {
   $background_row = "rgb(49, 173, 211)";

   if ($sql[$r]['mark_type'] == "ДПА" || $sql[$r]['mark_type'] == "Е" || $sql[$r]['mark_type'] == "А" 
         || $sql[$r]['mark_type'] == "С"|| $sql[$r]['mark_type'] == "М" || $sql[$r]['mark_type'] == "Р") 
      $background_row = "#FFC540";
   else if ($sql[$r]['mark_type'] == "с/р" || $sql[$r]['mark_type'] == "т/к" || $sql[$r]['mark_type'] == "к/р" 
         || $sql[$r]['mark_type'] == "ККР"|| $sql[$r]['mark_type'] == "тест" || $sql[$r]['mark_type'] == "вх/к")
      $background_row = "#AEF26D";
   else
      $background_row = "rgb(49, 173, 211)";
   if ($sql[$r]['mark_type'] == "л/р") { $name = "Лабораторна робота";}
   else if ($sql[$r]['mark_type'] == "зл/р") { $name = "Захист лабораторної роботи";}
   else if ($sql[$r]['mark_type'] == "п/р") { $name = "Практична робота";}
   else if ($sql[$r]['mark_type'] == "ауд/р") { $name = "Оцінка за роботу в аудиторії";}
   else if ($sql[$r]['mark_type'] == "с/р") { $name = "Самостійна робота";}
   else if ($sql[$r]['mark_type'] == "к/р") { $name = "Контрольна робота";}
   else if ($sql[$r]['mark_type'] == "сем/р") { $name = "Семестрова контрольна робота";}
   else if ($sql[$r]['mark_type'] == "т/к") { $name = "Тематичний контроль";}
   else if ($sql[$r]['mark_type'] == "мон") { $name = "Монолог";}
   else if ($sql[$r]['mark_type'] == "діал") { $name = "Діалог";}
   else if ($sql[$r]['mark_type'] == "ауд") { $name = "Аудіювання";}
   else if ($sql[$r]['mark_type'] == "чит") { $name = "Читання";}
   else if ($sql[$r]['mark_type'] == "п") { $name = "Письмо";}
   else if ($sql[$r]['mark_type'] == "говор") { $name = "Говоріння";}
   else if ($sql[$r]['mark_type'] == "ККР") { $name = "Комплексна к/р";}
   else if ($sql[$r]['mark_type'] == "ДПА") { $name = "ДПА";}
   else if ($sql[$r]['mark_type'] == "тест") { $name = "Тест";}
   else if ($sql[$r]['mark_type'] == "дикт") { $name = "Диктант";}
   else if ($sql[$r]['mark_type'] == "вірш") { $name = "Вірш";}
   else if ($sql[$r]['mark_type'] == "твір") { $name = "Твір";}
   else if ($sql[$r]['mark_type'] == "поез") { $name = "Поезія";}
   else if ($sql[$r]['mark_type'] == "сем") { $name = "Семінар";}
   else if ($sql[$r]['mark_type'] == "доп") { $name = "Доповідь";}
   else if ($sql[$r]['mark_type'] == "перез") { $name = "Перезалік";}
   else if ($sql[$r]['mark_type'] == "вх/к") { $name = "Вхідний контроль";}
   else if ($sql[$r]['mark_type'] == "Е") { $name = "Екзамен";}
   else if ($sql[$r]['mark_type'] == "С") { $name = "Семестр";}
   else if ($sql[$r]['mark_type'] == "А") { $name = "Атестація";}
   else if ($sql[$r]['mark_type'] == "М") { $name = "Модуль";}
   else if ($sql[$r]['mark_type'] == "Р") { $name = "Річна";}
   echo "
         <th style=\"background: ".$background_row.";\" title=\"".$name."\">".$sql[$r]['mark_type']."</th>
      ";
      $r++;
}
echo "</tr></thead><tbody>";
try {
   $sql = $db->query("SELECT `student`.`id` AS 'student_id', `student`.`surname` AS 'student_surname', 
         `student`.`name` AS 'student_name', `student`.`middle_name` AS 'student_middle_name',
         `mark`.`mark` AS 'mark', `mark`.`type` AS 'mark_type', `mark`.`date` AS 'mark_date',
         `pair`.`id` AS 'pair_id', `mark`.`id` AS 'mark_id', `mark`.`mark_identifikator` AS 'mark_identifikator',
         `avg_columns`
      FROM `student` INNER JOIN ( `mark` INNER JOIN `pair` ON `mark`.`pair` = `pair`.`id`) 
         ON `student`.`id` = `mark`.`student`
      WHERE `pair`.`id` = :id ".$where."
      ORDER BY `student`.`surname`, `student`.`name`, `mark`.`date`, `mark`.`type`",
      [  
         ':id'    => $pair
      ]
   );
} catch (Exception $e) {
   echo "Помилка виконання! Зверніться до Адміністратора сайту!";
}
try {
   $sqll = $db->query("SELECT DISTINCT `student`.`id` AS 'student_id', `student`.`surname` AS 'student_surname', 
         `student`.`name` AS 'student_name', `student`.`middle_name` AS 'student_middle_name'
      FROM `student` INNER JOIN ( `mark` INNER JOIN `pair` ON `mark`.`pair` = `pair`.`id`) 
      ON `student`.`id` = `mark`.`student`
      WHERE `pair`.`id` = :id ".$where."
      ORDER BY `student`.`surname`, `student`.`name`",
      [  
         ':id'    => $pair
      ]
   );
} catch (Exception $e) {
   echo "Помилка виконання! Зверніться до Адміністратора сайту!";
}

$r = 0;
$p = 0;
$k = 1;
while ($r < count($sqll)) {
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

   
   echo "<tr style=\"background: ".$background_row.";\">";
   $number = $r + 1;
   echo "<td><center>".$number."</center></td>";
   echo "<td style=\"padding-left:2px;padding-right:2px; min-width: 138px\" title=\"".$sqll[$r]['student_surname']." ".$sqll[$r]['student_name']." ".$sqll[$r]['student_middle_name']."\">"
   .$sqll[$r]['student_surname']." ".mb_substr($sqll[$r]['student_name'], 0, 1).".".mb_substr($sqll[$r]['student_middle_name'], 0, 1).".</td>";

   while ($p < count($sql)) {

         $background_row = $b;

         if ($sql[$p]['mark_type'] == "ДПА" || $sql[$p]['mark_type'] == "Е" || $sql[$p]['mark_type'] == "А" 
               || $sql[$p]['mark_type'] == "С"|| $sql[$p]['mark_type'] == "М" || $sql[$p]['mark_type'] == "Р") 
            $background_row = "#FFC540";
         else if ($sql[$p]['mark_type'] == "с/р" || $sql[$p]['mark_type'] == "т/к" || $sql[$p]['mark_type'] == "к/р" 
               || $sql[$p]['mark_type'] == "ККР"|| $sql[$p]['mark_type'] == "тест" || $sql[$p]['mark_type'] == "вх/к")
            $background_row = "#AEF26D";
         else {
            $background_row = $background_row;
         }

         if ($mark_system == 5){
            if (($sql[$p]['mark'] < 3 || $sql[$p]['mark'] == "н/а" || $sql[$p]['mark'] == "н/зал" || $sql[$p]['mark'] == "н/зар") && $sql[$p]['mark'] != "") {
               $color = "#9B001C";
            } else {
               $color = "black";
            }
         } else if ($mark_system == 12) {
            if (($sql[$p]['mark'] < 4 || $sql[$p]['mark'] == "н/а" || $sql[$p]['mark'] == "н/зал" || $sql[$p]['mark'] == "н/зар") && $sql[$p]['mark'] != "") {
               $color = "#9B001C";
            } else {
               $color = "black";
            }
         } else if ($mark_system == "зал") {
            if (($sql[$p]['mark'] == "н/а" || $sql[$p]['mark'] == "н/зал" || $sql[$p]['mark'] == "н/зар") && $sql[$p]['mark'] != "") {
               $color = "#9B001C";
            } else {
               $color = "black";
            }
         }
         
         if ($sql[$p]['mark_type'] == "А" || $sql[$p]['mark_type'] == "С"|| $sql[$p]['mark_type'] == "М" || $sql[$p]['mark_type'] == "Р") {
            $readonly = "readonly";
            $pattern = "";
            $avg_array = explode(":", $sql[$p]['avg_columns']);
            $title = implode(",", $avg_array);
         } else {
            $pattern = $pattern_default;
            $readonly = "";
            $title = $title_default;
         }
         
         echo "<td class=\"mark\" style=\"background: ".$background_row.";\">
            <form class=\"mark-change\" style=\"background: ".$background_row.";\"> 
               <input type=\"hidden\" name=\"change-student-id\" id=\"change-student-id\" value=\"".$sql[$p]['student_id']."\"/>
               <input type=\"hidden\" name=\"change-mark-id\" id=\"change-mark-id\" value=\"".$sql[$p]['mark_id']."\">
               <input type=\"text\" name=\"change-mark-edit\" id=\"change-mark-edit\" size=\"3\" 
                  style=\"background: ".$background_row."; color: ".$color."; border: none;\"
                  pattern=\"".$pattern."\" value=\"".$sql[$p]['mark']."\" title=\"".$title."\" autocomplete=\"off\" ".$readonly.">
            </form>
         </td>";

      $p++;
      if($p%$count_pair == 0){
         break;
      }
   }
   echo "</tr>";
   $r++;
   $k++;
   $k >= 4 ? $k = 1 : $k = $k; 
}

echo "<tr style=\"border: none\">
   <td style=\"border: none\"></td>
   <td style=\"border: none\"></td>";
$r = 0;
while ($r < count($sql)) {

   echo "<td 
   class=\"mark delete-mark-teacher\" id=\"".$sql[$r]['mark_identifikator']."\"
   style=\"background: rgb(219, 96, 96);\">
   <img src=\"../images/delete_bin_black_40px.png\" height=\"23px\" width=\"23px\" alt=\"Вид.\" title=\"Видалити\"></td>";
   $r++;
   if($r%$count_pair == 0){
      break;
   }
   
}
echo "</tr></tbody></table>";


?>