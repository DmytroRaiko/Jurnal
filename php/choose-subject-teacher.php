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

$id = $_POST['teacher'];  
try {
   $sql = $db->query(
      "SELECT `subject`.`name` AS 'name', `subject`.`mark_system` AS 'mark_system',
         `background_subject`.`image` AS 'image', `descipline`.`id` AS 'id'
      FROM `descipline` INNER JOIN (`subject` INNER JOIN `background_subject` 
         ON `subject`.`id`=`background_subject`.`subject`) 
         ON `descipline`.`subject`=`subject`.`id` 
      WHERE `descipline`.`teacher` = :id
      ORDER BY `subject`.`mark_system`, `subject`.`name`",
      [  
         ':id' => $id
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
         <div class=\"choose-subject\" id=\"".$sql[$r]['id']."\" style=\"
               background-image: url(../images/background-subject".$q.".jpg);
               background-repeat: no-repeat;
               -webkit-background-size: cover;
               background-size: cover;\">
            <div class=\"text-footer-left\">".$sql[$r]['name']."</div>
            <div class=\"text-footer-right\">".$sql[$r]['mark_system']."</div>
         </div>
      ";
      $q++;
      if ($q > 5) $q = 0;
   } else {

      echo "
         <div class=\"choose-subject\" id=\"".$sql[$r]['id']."\" style=\"
               background-image: url(data:image/jpg;base64,".base64_encode($sql[$r]['image']).");
               background-repeat: no-repeat;
               -webkit-background-size: cover;
               background-size: cover;\">
            <div class=\"text-footer-left\">".$sql[$r]['name']."</div>
            <div class=\"text-footer-right\">".$sql[$r]['mark_system']."</div>
         </div>
      ";
   }

   $r++;
}

echo "
   <script>
      $(document).ready(function () {
         $('.choose-subject').on('click',function() {

            $('#block-subject').hide();
            $('#block-table-teacher').hide();
            $('#back-to-group').hide();
            $('#back-to-subject').show();
            $('#block-group').show();

            var descipline = this.id;
            localStorage.setItem(\"descipline\",this.id);
            var teacher = localStorage.getItem(\"id\");
            $('#descipline-id').val(descipline);

            $.post(\"../php/choose-group-teacher.php\", {descipline})
            .done(function(data) {
               $('#block-group').html(data);   
            });

            $.post(\"../php/subject-name-add-group-teacher.php\", {descipline}) 
            .done(function(data){                  
               $('#subject-name').val(data);
            }); 

            $.post(\"../php/add-group-teacher.php\", {}) 
            .done(function(data){                  
               $('#group-add-teacher').html(data);
            }); 
         })
      })
   </script>
";

?>