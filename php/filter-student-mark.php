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


try {
   $sql_type = $db->query("SELECT DISTINCT `mark`.`type` AS 'type'
      FROM `mark` INNER JOIN `pair`
      ON `pair`.`id` = `mark`.`pair`
      WHERE `pair` = :pair
      ORDER BY `mark`.`type`",
      [  
         ':pair'     => $pair
      ]
   );
} catch (Exception $e) {
   echo "Помилка виконання! Зверніться до Адміністратора сайту!";
}
echo "
   <div>
      <label for=\"filter-month-student\" style=\"font-size: 20px;\">Період</label>
      <input type=\"month\" name=\"filter-month-student\" id=\"filter-month-student\">
   </div>
   
   <table>
";

$r = 0; 
while ($r < count($sql_type)) {

   if ($sql_type[$r]['type'] == "л/р") { $name = "Лабораторна робота";}
   else if ($sql_type[$r]['type'] == "зл/р") { $name = "Захист лабораторної роботи";}
   else if ($sql_type[$r]['type'] == "п/р") { $name = "Практична робота";}
   else if ($sql_type[$r]['type'] == "ауд/р") { $name = "Оцінка за роботу в аудиторії";}
   else if ($sql_type[$r]['type'] == "с/р") { $name = "Самостійна робота";}
   else if ($sql_type[$r]['type'] == "к/р") { $name = "Контрольна робота";}
   else if ($sql_type[$r]['type'] == "сем/р") { $name = "Семестрова контрольна робота";}
   else if ($sql_type[$r]['type'] == "т/к") { $name = "Тематичний контроль";}
   else if ($sql_type[$r]['type'] == "мон") { $name = "Монолог";}
   else if ($sql_type[$r]['type'] == "діал") { $name = "Діалог";}
   else if ($sql_type[$r]['type'] == "ауд") { $name = "Аудіювання";}
   else if ($sql_type[$r]['type'] == "чит") { $name = "Читання";}
   else if ($sql_type[$r]['type'] == "п") { $name = "Письмо";}
   else if ($sql_type[$r]['type'] == "говор") { $name = "Говоріння";}
   else if ($sql_type[$r]['type'] == "ККР") { $name = "Комплексна к/р";}
   else if ($sql_type[$r]['type'] == "ДПА") { $name = "ДПА";}
   else if ($sql_type[$r]['type'] == "тест") { $name = "Тест";}
   else if ($sql_type[$r]['type'] == "дикт") { $name = "Диктант";}
   else if ($sql_type[$r]['type'] == "вірш") { $name = "Вірш";}
   else if ($sql_type[$r]['type'] == "твір") { $name = "Твір";}
   else if ($sql_type[$r]['type'] == "поез") { $name = "Поезія";}
   else if ($sql_type[$r]['type'] == "сем") { $name = "Семінар";}
   else if ($sql_type[$r]['type'] == "доп") { $name = "Доповідь";}
   else if ($sql_type[$r]['type'] == "перез") { $name = "Перезалік";}
   else if ($sql_type[$r]['type'] == "вх/к") { $name = "Вхідний контроль";}
   else if ($sql_type[$r]['type'] == "Е") { $name = "Екзамен";}
   else if ($sql_type[$r]['type'] == "С") { $name = "Семестр";}
   else if ($sql_type[$r]['type'] == "А") { $name = "Атестація";}
   else if ($sql_type[$r]['type'] == "М") { $name = "Модуль";}
   else if ($sql_type[$r]['type'] == "Р") { $name = "Річна";}
   echo "
      <tr>
         <td style=\"min-width: 50px; border: none\"><label style=\"font-size: 20px;\">".$name."</label></td>
         <td style=\"border: none\"><input type=\"checkbox\" name=\"filter-type-student[]\" value=\"".$sql_type[$r]['type']."\" checked></td>
      </tr>
      ";
   $r++;
}
echo "</table>";
