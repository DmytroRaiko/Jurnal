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

function estimate ($system, $mark) {
   if ($system == 12) {
      if ($mark == 12) {
         return 12;
      } else if ($mark >= 11 && $mark < 12) {
         return 11;
      } else if ($mark >= 10 && $mark < 11) {
         return 10;
      } else if ($mark >= 9 && $mark < 10) {
         return 9;
      } else if ($mark >= 8 && $mark < 9) {
         return 8;
      } else if ($mark >= 7 && $mark < 8) {
         return 7;
      } else if ($mark >= 6 && $mark < 7) {
         return 6;
      } else if ($mark >= 5 && $mark < 6) {
         return 5;
      } else if ($mark >= 4 && $mark < 5) {
         return 4;
      } else if ($mark < 4) {
         return "н/а";
      }
   } else if ($system == 5) {
      $p_s = ($mark *100) / 5;
      if ($p_s >= 90 && $p_s <= 100) {
         return 5;
      } else if ($p_s >= 75 && $p_s < 90) {
         return 4;
      } else if ($p_s >= 50 && $p_s < 75) {
         return 3;
      } else if ($p_s < 50) {
         return "н/а";
      } 
   }
}


try {
   $ban = $db->query("SELECT `access` 
      FROM `prohibition` 
      WHERE `operation` = :operation",
      [
         ':operation'  => "marks"
      ]
   );
} catch (Exception $e) {
   echo "Помилка виконання! Зверніться до Адміністратора сайту!";
}

if ($ban[0]['access'] == 1) {

   $pair = trim($_POST['pair']);  
   $date = trim($_POST['date']);
   $type = trim($_POST['type']);
   $avg = trim($_POST['avg']);
   $mark = NULL;

   if (!empty($pair)) {
      if ($type !== "А" && $type !== "С" && $type !== "М" && $type !== "Р") {
         $avg = NULL;

         try {
            $sql = $db->query("SELECT `student`.`id` AS 'id'
               FROM `student`
               WHERE `student`.`group` = (
                  SELECT `group`.`id` 
                  FROM `group` INNER JOIN `pair` 
                  ON `group`.`id` = `pair`.`group` 
                  WHERE `pair`.`id` = :id
               )",
               [  
                  ':id' => $pair
               ]
            );
         } catch (Exception $e) {
            echo "Помилка виконання! Зверніться до Адміністратора сайту!";
         }
         
         
         try {
            $db->query("INSERT INTO `mark_ident`(`i`) VALUES (:i)",
               [
                  ':i'  => "i"
               ]
            );
         } catch (Exception $e) {
            echo "Помилка виконання! Зверніться до Адміністратора сайту!";
         }
         
         try {
            $mark_ident = $db->query("SELECT MAX(`id`) AS 'max' FROM `mark_ident`");
         } catch (Exception $e) {
            echo "Помилка виконання! Зверніться до Адміністратора сайту!";
         }
         
         $r = 0;
         
         while ($r < count($sql)) {
         
            try {
               $db->query("
                  INSERT INTO `mark`(`type`, `mark`, `pair`, `student`, `date`, `mark_identifikator`, `avg_columns`) 
                  VALUES (:type, :mark, :pair, :student, :date, :mark_identifikator, :avg_columns)
               ",
                  [  
                     ':type'                 => $type,
                     ':mark'                 => $mark,
                     ':pair'                 => $pair,
                     ':student'              => $sql[$r]['id'],
                     ':date'                 => $date,
                     ':mark_identifikator'   => $mark_ident[0]['max'],
                     ':avg_columns'          => $avg
                  ]
               );
            } catch (Exception $e) {
               echo "Помилка виконання! Зверніться до Адміністратора сайту!";
            }
            $r++;
         }

         try {
            $count_date = $db->query("SELECT COUNT(DISTINCT `date`)  AS 'COUNT'
               FROM `mark`  
               WHERE `date` >= (SELECT DISTINCT `date` FROM `mark` WHERE `mark_identifikator` = :ident) 
                  AND `mark_identifikator` != :ident",
               [
                  ':ident' => $mark_ident[0]['max']
               ]
            );
         } catch (Exception $e) {
            echo "Помилка виконання! Зверніться до Адміністратора сайту!";
         }

         if ($count_date[0]['COUNT'] > 0) {
            echo "
               <script>
                  var mark = ".$mark_ident[0]['max'].";
                  $.post(\"../php/change-avg-add-mark-table-teacher.php\", {mark})

                  .done(function(data) {
                     pair = localStorage.getItem(\"pair\");


                     var data = $('#form-table-filter').serialize();
                     data += \"&pair=\"+pair;
                              
                     $.ajax ({
                        type: \"POST\",
                        url: \"../php/mark-table-teacher.php\", 
                        data: data,
                        success: function(data) {
                           $('#block-table-mark').html(data);
                           var date = $('#pair-date').val(\"\");
                           var type = $('#pair-type').val(\"\");
                        }
                     })
                  });
               </script>
            ";
         } else {
            echo "
               <script>
               
                  pair = localStorage.getItem(\"pair\");

                  var data = $('#form-table-filter').serialize();
                  data += \"&pair=\"+pair;

                  $.ajax ({
                     type: \"POST\",
                     url: \"../php/mark-table-teacher.php\", 
                     data: data,
                     success: function(data) {
                        $('#block-table-mark').html(data);
                        var date = $('#pair-date').val(\"\");
                        var type = $('#pair-type').val(\"\");
                     }
                  });
               </script>
            ";
         }
      } else  if ($type === "А" || $type === "С" || $type === "М" || $type === "Р") {

         /** проверка на существования такой же даты */
         try {
            $pass_add_pair = $db->query("SELECT COUNT(*) 
               FROM `mark` INNER JOIN `pair` ON `pair`.`id` = `mark`.`pair`
               WHERE `pair`.`id` = :pair AND `mark`.`type` = :type AND `mark`.`date` = :date
               ",
               [
                  ':date'  => $date,
                  ':pair'  => $pair,
                  ':type'  => $type
               ]
            );
         } catch (Exception $e) {
            echo "Помилка виконання! Зверніться до Адміністратора сайту!";
         }

         if ($pass_add_pair[0]['COUNT(*)'] == 0) {
            $avg_array = explode(":", $avg);
            $condition_type = "";
            $n = 0;

            while ($n < count($avg_array)) {

               $condition_type .= "`mark`.`type` = '".$avg_array[$n]."'";
               $n++;

               if ($n != count($avg_array)) {
                  $condition_type .= " OR ";
               }
            }

            try {
               $date_start = $db->query("SELECT DISTINCT `date` FROM `mark`
                     WHERE `date` <= :date AND `pair` = :pair AND `type` = :type
                     ORDER BY `date` DESC",
                  [
                     ':date'  => $date,
                     ':pair'  => $pair,
                     ':type'  => $type
                  ]
               );
            } catch (Exception $e) {
               echo "Помилка виконання! Зверніться до Адміністратора сайту!";
            }

            $condition_date = "";
            if (count($date_start) <= 0) {
               $condition_date .= "`mark`.`date` <= :date_finish";
            } else {
               $condition_date .= "`mark`.`date` > :date_start  AND `mark`.`date` <= :date_finish";
            }
            
            try {
               $student = $db->query("SELECT `student`.`id` AS 'id' , `student`.`surname` AS 'student_surname',
                     `student`.`name` AS 'student_name'
                  FROM `student`
                  WHERE `student`.`group` = (
                     SELECT `group`.`id` 
                     FROM `group` INNER JOIN `pair` 
                     ON `group`.`id` = `pair`.`group` 
                     WHERE `pair`.`id` = :id)
                  ",
                  [  
                     ':id' => $pair
                  ]
               );
            } catch (Exception $e) {
               echo "Помилка виконання! Зверніться до Адміністратора сайту!";
            }

            try {
               $subject_mark = $db->query("SELECT `subject`.`mark_system` AS 'system'
                  FROM `subject` INNER JOIN (`descipline` INNER JOIN `pair` ON `descipline`.`id`=`pair`.`descipline`)
                     ON `descipline`.`subject`=`subject`.`id`
                  WHERE `pair`.`id` = :pair
                  ",
                  [  
                     ':pair' => $pair
                  ]
               );
            } catch (Exception $e) {
               echo "Помилка виконання! Зверніться до Адміністратора сайту!";
            }
            

            try {
               $db->query("INSERT INTO `mark_ident`(`i`) VALUES (:i)",
                  [
                     ':i'  => "i"
                  ]
               );
            } catch (Exception $e) {
               echo "Помилка виконання! Зверніться до Адміністратора сайту!";
            }
            
            try {
               $mark_ident = $db->query("SELECT MAX(`id`) AS 'max' FROM `mark_ident`");
            } catch (Exception $e) {
               echo "Помилка виконання! Зверніться до Адміністратора сайту!";
            }

            $s = 0;
            while ($s < count($student)) {
               $condition_date = "";
               if (count($date_start) <= 0) {
                  $condition_date .= "`mark`.`date` <= :date_finish";
                  try {
                     $mark_qv = $db->query("SELECT `mark`.`type` AS 'type', `mark`.`mark` AS 'mark', `mark`.`mark_identifikator` AS 'mark_ident' 
                     FROM `mark`
                     WHERE (".$condition_date.") 
                           AND (`mark`.`student` = :student) 
                           AND (`mark`.`pair` = :pair)
                           AND (".$condition_type.")
                        ",
                        [  
                           ':student'     => $student[$s]['id'],
                           ':pair'        => $pair,
                           ':date_finish' => $date
                        ]
                     );
                  } catch (Exception $e) {
                     echo "Помилка виконання! Зверніться до Адміністратора сайту!";
                  }
               } else {
                  $condition_date .= "`mark`.`date` > :date_start  AND `mark`.`date` <= :date_finish";
                  try {
                     $mark_qv = $db->query("SELECT `mark`.`type` AS 'type', `mark`.`mark` AS 'mark', `mark`.`mark_identifikator` AS 'mark_ident' 
                     FROM `mark`
                     WHERE (".$condition_date.") 
                           AND (`mark`.`student` = :student) 
                           AND (`mark`.`pair` = :pair)
                           AND (".$condition_type.")
                        ",
                        [  
                           ':student'     => $student[$s]['id'],
                           ':pair'        => $pair,
                           ':date_start'  => $date_start[0]['date'],
                           ':date_finish' => $date
                        ]
                     );
                  } catch (Exception $e) {
                     echo "Помилка виконання! Зверніться до Адміністратора сайту!";
                  }
               }

               $sum_mark = 0;
               $count_mark = 0;
               $mark_pass = 1;
               $m = 0;
               $mark = 0;
               if ($subject_mark[0]['system'] == 5) {
                  while ($m < count($mark_qv)) {
                     
                     if ($mark_qv[$m]['type'] == 'ауд/р' || $mark_qv[$m]['type'] == 'сем' 
                           || $mark_qv[$m]['type'] == 'доп' || $mark_qv[$m]['type'] == 'перез' ) {

                        if ($mark_qv[$m]['mark'] == NULL || $mark_qv[$m]['mark'] == "" || $mark_qv[$m]['mark'] == " ") {
                           
                        } else {
                           $sum_mark += $mark_qv[$m]['mark'];
                           $count_mark++;
                        }
                     } else {
                        
                        if ($mark_qv[$m]['mark'] != 'н/а' && $mark_qv[$m]['mark'] != 'н/зар' &&
                              $mark_qv[$m]['mark'] != 'зар' && $mark_qv[$m]['mark'] != 'зал' &&
                              $mark_qv[$m]['mark'] != 'н/зал') {
                           if ($mark_qv[$m]['mark'] != NULL && $mark_qv[$m]['mark'] >= 3) {

                              $sum_mark += $mark_qv[$m]['mark'];
                              $count_mark++;
                           } else if ($mark_qv[$m]['mark'] == NULL || $mark_qv[$m]['mark'] < 3){   

                              $mark = 'н/а';
                              $mark_pass = 0;
                           }
                        } else {
                           if ($mark_qv[$m]['mark'] == 'зар' || $mark_qv[$m]['mark'] == 'зал') {

                           } else if ($mark_qv[$m]['mark'] == 'н/зар' || $mark_qv[$m]['mark'] == 'н/зал' || $mark_qv[$m]['mark'] == 'н/а') {

                              $mark = 'н/а';
                              $mark_pass = 0;
                           }
                        }
                     }
                     $m++;
                  }
               } else if ($subject_mark[0]['system'] == 12) {
                  while ($m < count($mark_qv)) {
                     
                     if ($mark_qv[$m]['type'] == 'ауд/р' || $mark_qv[$m]['type'] == 'сем' 
                           || $mark_qv[$m]['type'] == 'доп' || $mark_qv[$m]['type'] == 'перез') {

                        if ($mark_qv[$m]['mark'] == NULL || $mark_qv[$m]['mark'] == "" || $mark_qv[$m]['mark'] == " ") {
                           
                        } else {
                           $sum_mark += $mark_qv[$m]['mark'];
                           $count_mark++;
                        }
                     } else {
                        
                        if ($mark_qv[$m]['mark'] != 'н/а' && $mark_qv[$m]['mark'] != 'н/зар' &&
                              $mark_qv[$m]['mark'] != 'зар' && $mark_qv[$m]['mark'] != 'зал' &&
                              $mark_qv[$m]['mark'] != 'н/зал') {
                           if ($mark_qv[$m]['mark'] != NULL && $mark_qv[$m]['mark'] >= 4) {

                              $sum_mark += $mark_qv[$m]['mark'];
                              $count_mark++;
                           } else if ($mark_qv[$m]['mark'] == NULL || $mark_qv[$m]['mark'] < 4){   

                              $mark = 'н/а';
                              $mark_pass = 0;
                           }
                        } else {
                           if ($mark_qv[$m]['mark'] == 'зар' || $mark_qv[$m]['mark'] == 'зал') {

                           } else if ($mark_qv[$m]['mark'] == 'н/зар' || $mark_qv[$m]['mark'] == 'н/зал' || $mark_qv[$m]['mark'] == 'н/а') {

                              $mark = 'н/а';
                              $mark_pass = 0;
                           }
                        }
                     }
                     $m++;
                  }
               } else if ($subject_mark[0]['system'] == 'зал'){
                  while ($m < count($mark_qv)) {
                     
                     if ($mark_qv[$m]['type'] == 'ауд/р' || $mark_qv[$m]['type'] == 'сем' 
                           || $mark_qv[$m]['type'] == 'доп' || $mark_qv[$m]['type'] == 'перез') {

                        if ($mark_qv[$m]['mark'] == NULL || $mark_qv[$m]['mark'] == "" 
                              || $mark_qv[$m]['mark'] == " " || $mark_qv[$m]['mark'] == "зар" 
                              || $mark_qv[$m]['mark'] == "зал") {
                           
                                 
                        } else if ($mark_qv[$m]['mark'] == "н/а" || $mark_qv[$m]['mark'] == "н/зар" 
                              || $mark_qv[$m]['mark'] == "н/зал"){
                           $mark_pass = 0;
                        }
                     } else {
                        
                        if ($mark_qv[$m]['mark'] == 'н/а' || $mark_qv[$m]['mark'] == 'н/зар' ||
                              $mark_qv[$m]['mark'] == 'н/зал' || $mark_qv[$m]['mark'] == NULL || $mark_qv[$m]['mark'] == "") {
                  
                              $mark_pass = 0;
                        }
                     }
                     $m++;
                  }
               }
               
            
               if ($mark_pass == 1 && count($mark_qv) >= 1 && $subject_mark[0]['system'] != 'зал') {
                  $mark = $sum_mark / $count_mark;
                  if ($subject_mark[0]['system'] == 5) {
                     $mark = estimate(5, $mark);
                  } else if ($subject_mark[0]['system'] == 12) {
                     $mark = estimate(12, $mark);
                  }
               } else if ($mark_pass == 0 && $subject_mark[0]['system'] != 'зал'){
                  $mark = "н/а";
               } else if ($mark_pass == 0 && $subject_mark[0]['system'] == 'зал'){
                  $mark = "н/а";
               } else if ($mark_pass == 1 && $subject_mark[0]['system'] == 'зал'){
                  $mark = "зал";
               }

               try {
                  $db->query("
                     INSERT INTO `mark`(`type`, `mark`, `pair`, `student`, `date`, `mark_identifikator`, `avg_columns`) 
                     VALUES (:type, :mark, :pair, :student, :date, :mark_identifikator, :avg_columns)
                  ",
                     [  
                        ':type'                 => $type,
                        ':mark'                 => $mark,
                        ':pair'                 => $pair,
                        ':student'              => $student[$s]['id'],
                        ':date'                 => $date,
                        ':mark_identifikator'   => $mark_ident[0]['max'],
                        ':avg_columns'          => $avg
                     ]
                  );
               } catch (Exception $e) {
                  echo "Помилка виконання! Зверніться до Адміністратора сайту!";
               }
               $s++;
            }

            try {
               $count_date = $db->query("SELECT COUNT(DISTINCT `date`)  AS 'COUNT'
                  FROM `mark`  
                  WHERE `date` >= (SELECT DISTINCT `date` FROM `mark` WHERE `mark_identifikator` = :ident) 
                     AND `mark_identifikator` != :ident",
                  [
                     ':ident' => $mark_ident[0]['max']
                  ]
               );
            } catch (Exception $e) {
               echo "Помилка виконання! Зверніться до Адміністратора сайту!";
            }

            if ($count_date[0]['COUNT'] > 0) {
               echo "
                  <script>
                     var mark = ".$mark_ident[0]['max']."
                     $.post(\"../php/change-avg-add-mark-table-teacher.php\", {mark})

                     .done(function(data) {
                        pair = localStorage.getItem(\"pair\");

                        var data = $('#form-table-filter').serialize();
                        data += \"&pair=\"+pair;

                        $.ajax ({
                           type: \"POST\",
                           url: \"../php/mark-table-teacher.php\", 
                           data: data,
                           success: function(data) {
                              $('#block-table-mark').html(data);
                              var date = $('#pair-date').val(\"\");
                              var type = $('#pair-type').val(\"\");
                           }
                        });
                     });
                  </script>
               ";
            } else {
               echo "
                  <script>
                     pair = localStorage.getItem(\"pair\");
                     console.log('pair: ', pair);

                     var data = $('#form-table-filter').serialize();
                     data += \"&pair=\"+pair;
                     
                     $.ajax ({
                        type: \"POST\",
                        url: \"../php/mark-table-teacher.php\", 
                        data: data,
                        success: function(data) {
                           $('#block-table-mark').html(data);
                           var date = $('#pair-date').val(\"\");
                           var type = $('#pair-type').val(\"\");
                        }
                     });
                  </script>
               ";
            }

         } else {
            echo '<center><div style="padding-left: 3%; padding-bottom: 2%; width:auto"><p class="massage-error" style="width: auto; color:red;"><img src="../images/error.png" alt="Error!">Не можливо додати пару</p></div></center>';

         }
      }
   }
} else {
   echo "<script>alert(\"Редагування оцінок обмежено!\")</script>";
}