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
   $ban = $db->query("SELECT `access` 
      FROM `prohibition` 
      WHERE `operation` = :operation",
      [
         ':operation'  => "visit"
      ]
   );
} catch (Exception $e) {
   echo "Помилка виконання! Зверніться до Адміністратора сайту!";
}

if ($ban[0]['access'] == 1) {

   $id = $_POST['change-visit-id'];
   $cause = $_POST['change-visit-edit'];


   if ($cause == "" || $cause == " ") {
      $cause = NULL;
   }

   try {
      $count_student = $db->query(
         "UPDATE `visiting` 
            SET `cause`=:cause
            WHERE `id` = :id",
         [
            ':id'    => $id,
            ':cause' => $cause
         ]
      );
   } catch (Exception $e) {
      echo "Помилка виконання! Зверніться до Адміністратора сайту!";
   }


   echo "
      <script>
         var dayStart, dayFinish;
         dayStart = $('#visit-filter-start').val();
         dayFinish = $('#visit-filter-finish').val();

         var data = localStorage.getItem('id');
         data = \"id=\"+data;

         data += \"&dayStart=\" + dayStart + \"&dayFinish=\" + dayFinish;
    
         $.ajax ({
            type: \"POST\",
            url: \"../php/visit-table-kurator.php\", 
            data: data,
            success: function(data) {
               $('#block-table-visit').html(data);
            }
         });
      </script>
   ";
} else {
   echo "<script>alert(\"Редагування пропусків обмежено!\")</script>";
}
