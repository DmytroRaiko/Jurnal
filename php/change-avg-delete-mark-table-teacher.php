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

   $ident = $_POST['mark'];

   try {
      $sql_mark = $db->query("SELECT `id`, `student` 
         FROM `mark` 
         WHERE `mark_identifikator` = :ident",
         [  
            ':ident'     => $ident
         ]
      );
   } catch (Exception $e) {
      echo "Помилка виконання! Зверніться до Адміністратора сайту!";
   }

   $k = 0;
   while ($k < count($sql_mark)) {
         
         $student_id = $sql_mark[$k]['student'];
         $mark_id = $sql_mark[$k]['id'];

         try {
            $sql_pair = $db->query("SELECT `pair`.`id` AS 'id'
               FROM `pair` INNER JOIN `mark`
                  ON `pair`.`id`=`mark`.`pair`
               WHERE `mark`.`id` = :mark ",
               [  
                  ':mark'     => $mark_id
               ]
            );
         } catch (Exception $e) {
            echo "Помилка виконання! Зверніться до Адміністратора сайту!";
         }

         $pair = $sql_pair[0]['id'];

         try {
            $sql_avg = $db->query("SELECT `date`, `id`, `avg_columns`, `type`
               FROM `mark` 
               WHERE `date` >= (SELECT `date` FROM `mark` WHERE `id` = :mark) AND
                  `student` = :student AND
                  (`type` = 'М' OR `type` = 'А' OR `type` = 'Р' OR `type` = 'С')
                  AND `pair` = :pair",
               [  
                  ':mark'     => $mark_id,
                  ':student'  => $student_id,
                  ':pair'     => $pair
               ]
            );
         } catch (Exception $e) {
            echo "Помилка виконання! Зверніться до Адміністратора сайту!";
         }

         $a = 0;
         while ($a < count($sql_avg)) {

            $avg_array = explode(":", $sql_avg[$a]['avg_columns']);
            $condition_type = "";
            $date = $sql_avg[$a]['date'];
         /** тип */
            $n = 0;
            while ($n < count($avg_array)) {

               $condition_type .= "`mark`.`type` = '".$avg_array[$n]."'";
               $n++;

               if ($n != count($avg_array)) {
                  $condition_type .= " OR ";
               }
            }
         /** */
         /** дата */
            try {
               $date_start = $db->query("SELECT DISTINCT `date` FROM `mark`
                     WHERE `date` <= :date AND `pair` = :pair AND `type` = :type 
                     AND `id` != :mark AND `student` = :student AND `mark_identifikator` != :ident
                     ORDER BY `date` DESC ",
                  [
                     ':date'     => $sql_avg[$a]['date'],
                     ':pair'     => $pair,
                     ':type'     => $sql_avg[$a]['type'],
                     ':mark'     => $sql_avg[$a]['id'],
                     ':student'  => $student_id,
                     ':ident'    => $ident
                  ]
               );
            } catch (Exception $e) {
               echo "Помилка виконання! Зверніться до Адміністратора сайту!";
            }

            $condition_date = "";
         /** */
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

            if (count($date_start) <= 0) {
               $condition_date = "`mark`.`date` <= :date_finish";
               try {
                  $mark_qv = $db->query("SELECT `mark`.`type` AS 'type', `mark`.`mark` AS 'mark', `mark`.`mark_identifikator` AS 'mark_ident' 
                  FROM `mark`
                  WHERE (".$condition_date.") 
                        AND (`mark`.`student` = :student) 
                        AND (`mark`.`pair` = :pair)
                        AND (".$condition_type.")
                        AND (`mark_identifikator` != :ident)
                     ",
                     [  
                        ':student'     => $student_id,
                        ':pair'        => $pair,
                        ':date_finish' => $date,
                        ':ident'       => $ident
                     ]
                  );
               } catch (Exception $e) {
                  echo "Помилка виконання! Зверніться до Адміністратора сайту!";
               }
            } else {
               $condition_date = "`mark`.`date` > :date_start  AND `mark`.`date` <= :date_finish";
               try {
                  $mark_qv = $db->query("SELECT `mark`.`type` AS 'type', `mark`.`mark` AS 'mark', `mark`.`mark_identifikator` AS 'mark_ident' 
                  FROM `mark`
                  WHERE (".$condition_date.") 
                        AND (`mark`.`student` = :student) 
                        AND (`mark`.`pair` = :pair)
                        AND (".$condition_type.")
                        AND (`mark_identifikator` != :ident)
                     ",
                     [  
                        ':student'     => $student_id,
                        ':pair'        => $pair,
                        ':date_start'  => $date_start[0]['date'],
                        ':date_finish' => $date,
                        ':ident'       => $ident
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
                  UPDATE `mark` 
                  SET `mark`= :mark
                  WHERE `id` = :mark_id
               ",
                  [  
                     ':mark'     => $mark,
                     ':mark_id'  => $sql_avg[$a]['id']
                  ]
               );
            } catch (Exception $e) {
               echo "Помилка виконання! Зверніться до Адміністратора сайту!";
            }

            $a++;
         }
         $k++;
   }
} else {
   echo "<script>alert(\"Редагування оцінок обмежено!\")</script>";
}
