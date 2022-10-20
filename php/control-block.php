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
   $prohibition = $db->query("SELECT `operation`, `access` FROM `prohibition`"   );
} catch (Exception $e) {
   echo "Помилка виконання! Зверніться до Адміністратора сайту!";
}

$r = 0;
while ($r < count($prohibition)) {
   if ($prohibition[$r]['operation'] == "marks") {
      if ($prohibition[$r]['access'] == 1) {
         echo "<div class=\"block-embargo-true\" id=\"marks\">
            Обмежити редагування оцінок
         </div>";
      } else if ($prohibition[$r]['access'] == 0) {
         echo "<div class=\"block-embargo-false\" id=\"marks\">
            Редагування оцінок обмежено!
         </div>";
      }
   } else if ($prohibition[$r]['operation'] == "visit") {
      if ($prohibition[$r]['access'] == 1) {
         echo "<div class=\"block-embargo-true\" id=\"visit\">
            Обмежити редагування пропусків
         </div>";
      } else if ($prohibition[$r]['access'] == 0) {
         echo "<div class=\"block-embargo-false\" id=\"visit\">
            Редагування пропусків обмежено!
         </div>";
      }
   }
   $r++;
}