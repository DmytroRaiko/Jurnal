<?php

require "../vendor/autoload.php";
include '../../db/setting.php';
require_once '../../db/db.php';
$db = new DataBase();

$p_s = array (
         '12'  => 0,
         '11'  => 0,
         '10'  => 0,
         '9'   => 0,
         '8'   => 0,
         '7'   => 0,
         '6'   => 0,
         '5'   => 0,
         '4'   => 0,
         '3'   => 0,
         '2'   => 0,
         '1'   => 0,
         '0'   => 0
      );
function mySqlQuer ($db, $querty, $params) {
   try {      

      $sql = $db->query($querty, $params);
      return $sql;
   } catch (Exception $e) {

      echo "Помилка виконання! Зверніться до Адміністратора сайту!";    
   }                           
}

function mark ($s, $m) {
   global $p_s;
   if ($s == 0) {
      if ($m == "н/а") {
         $p_s["0"] += 1;
         return "не атестовано";
      } else if ($m == 3) {
         $p_s["3"] += 1;
         return "3 (задовільно)";
      } else if ($m == 4) {
         $p_s["4"] += 1;
         return "4 (добре)";
      } else if ($m == 5) {
         $p_s["5"] += 1;
         return "5 (відмінно)";
      }
   } else if ($s == 1){
      if ($m == "0") {
         $p_s["н/а"] += 1;
         return "не атестовано";
      } else if ($m == 4) {
         $p_s["4"] += 1;
         return "4 (чотири)";
      } else if ($m == 5) {
         $p_s["5"] += 1;
         return "5 (п’ять)";
      } else if ($m == 6) {
         $p_s["6"] += 1;
         return "6 (шість)";
      } else if ($m == 7) {
         $p_s["7"] += 1;
         return "7 (сім)";
      } else if ($m == 8) {
         $p_s["8"] += 1;
         return "8 (вісім)";
      } else if ($m == 9) {
         $p_s["9"] += 1;
         return "9 (дев’ять)";
      } else if ($m == 10) {
         $p_s["10"] += 1;
         return "10 (десять)";
      } else if ($m == 11) {
         $p_s["11"] += 1;
         return "11 (одинадцять)";
      } else if ($m == 12) {
         $p_s["12"] += 1;
         return "12 (дванадцять)";
      }
   } else if ($s == 2) {
      if ($m == "зал") {
         return "залік";
      } else if ($m == "н/зал") {
         $p_s["н/а"] += 1;
         return "не залік";
      }
   }
}

$phpWord = new \PhpOffice\PhpWord\PhpWord();

$pair = $_GET['pair'];
$day_start = $_GET['dayStart'];
$day_finish = $_GET['dayFinish'];

try {
   $sql = $db->query("SELECT `subject`.`mark_system` AS 'mark_system'
      FROM `subject` INNER JOIN (`descipline` INNER JOIN `pair` ON `descipline`.`id`=`pair`.`descipline`)
         ON `subject`.`id`=`descipline`.`subject`
      WHERE `pair`.`id` = :pair",
      [
         ':pair' => $pair
      ]
   );
 } catch (Exception $e) {
   echo "Помилка виконання! Зверніться до Адміністратора сайту!";
}

try {
   $group = $db->query("SELECT `subject`.`name` AS 'sname', `teacher`.`surname` AS 'surname', `teacher`.`name` AS 'name', `teacher`.`middle_name` AS 'middle_name'
      FROM `subject` INNER JOIN (`descipline` INNER JOIN `pair` ON `descipline`.`id`=`pair`.`descipline`) 
         ON `subject`.`id`=`descipline`.`subject`INNER JOIN `teacher` ON `descipline`.`teacher`=`teacher`.`id`
      WHERE `pair`.`id` = :pair",
      [
         ':pair' => $pair
      ]
   );
 } catch (Exception $e) {
   echo "Помилка виконання! Зверніться до Адміністратора сайту!";
}
$subject_out = $group[0]['sname'];
$teacher_out = $group[0]['surname']." ".mb_substr($group[0]['name'], 0, 1).".".mb_substr($group[0]['middle_name'], 0, 1).".";
$teacher1_out = mb_substr($group[0]['name'], 0, 1).".".mb_substr($group[0]['middle_name'], 0, 1).". ".$group[0]['surname'];

try {
   $group = $db->query("SELECT `specialty`.`name` AS 'name', `spec_number`
      FROM `specialty` INNER JOIN (`group` INNER JOIN `pair` ON `group`.`id`=`pair`.`group`)
         ON `specialty`.`id`=`group`.`specialty`
      WHERE `pair`.`id` = :pair",
      [
         ':pair' => $pair
      ]
   );
 } catch (Exception $e) {
   echo "Помилка виконання! Зверніться до Адміністратора сайту!";
}
$spec_out = $group[0]['spec_number']." «".$group[0]['name']."»";

try {
   $group = $db->query("SELECT `group`.`name` AS 'name'
      FROM `student` INNER JOIN (`group` INNER JOIN `pair` ON `group`.`id`=`pair`.`group`)
         ON `student`.`group`=`group`.`id`
      WHERE `pair`.`id` = :pair",
      [
         ':pair' => $pair
      ]
   );
 } catch (Exception $e) {
   echo "Помилка виконання! Зверніться до Адміністратора сайту!";
}
$group_out = $group[0]['name'];
$kurs_out = mb_substr($group[0]['name'], 0, 1);
if ($sql[0]['mark_system'] == 5) {
   $document = $phpWord->loadTemplate('five.docx'); 

   try {
      $student = $db->query("SELECT `student`.`id` AS 'student_id', `student`.`surname` AS 'student_surname', 
            `student`.`name` AS 'student_name', `student`.`middle_name` AS 'student_middle_name'
         FROM `student` INNER JOIN (`group` INNER JOIN `pair` ON `group`.`id`=`pair`.`group`)
            ON `student`.`group`=`group`.`id`
         WHERE `pair`.`id` = :pair
         ORDER BY `student`.`surname`, `student`.`name`, `student`.`middle_name`",
         [
            ':pair' => $pair
         ]
      );
   } catch (Exception $e) {
      echo "Помилка виконання! Зверніться до Адміністратора сайту!";
   }
   $count_student = count($student);

   $s = 0;
   while ($s < 31) {
      $student_out = "";
      $num = "";
      $mark_out = "";
      if ($s < count($student)) {
         try {
            $mark = $db->query("SELECT `mark`.`mark` AS 'mark', `mark`.`date` AS 'date'
               FROM `pair` INNER JOIN `mark` ON `pair`.`id`=`mark`.`pair`
               WHERE `pair`.`id` = :pair AND `mark`.`student` = :student AND `mark`.`date` >= :start AND `mark`.`date` <= :finish AND `mark`.`type` = 'С'
               ORDER BY `mark`.`date` DESC
               LIMIT 1",
               [
                  ':pair'     => $pair,
                  ':student'  => $student[$s]['student_id'],
                  ':start'    => $day_start,
                  ':finish'   => $day_finish
               ]
            );
          } catch (Exception $e) {
            echo "Помилка виконання! Зверніться до Адміністратора сайту!";
         } 
         if (count($mark) != 0) {
            try {
               $ex = $db->query("SELECT `mark`.`mark` AS 'mark'
                  FROM `pair` INNER JOIN `mark` ON `pair`.`id`=`mark`.`pair`
                  WHERE `pair`.`id` = :pair AND `mark`.`student` = :student AND `mark`.`date` >= :date AND `mark`.`type` = 'Е'
                  ORDER BY `mark`.`date` DESC
                  LIMIT 1",
                  [
                     ':pair'     => $pair,
                     ':student'  => $student[$s]['student_id'],
                     ':date'     => $mark[0]['date']
                  ]
               );
            } catch (Exception $e) {
               echo "Помилка виконання! Зверніться до Адміністратора сайту!";
            } 

            if (count($ex) !== 0) {
               if ($ex[0]['mark'] !== NULL) {
                  $mark_out = mark(0, $ex[0]['mark']);
               } else {
                  if (count($mark) !== 0) {
                     $mark_out = mark(0, $mark[0]['mark']);
                  }
               }
            } else {
               if (count($mark) !== 0) {
                  $mark_out = mark(0, $mark[0]['mark']);
               }
            }
            $num = ($s + 1).".";
            $student_out = $student[$s]['student_surname']." ".mb_substr($student[$s]['student_name'], 0, 1).".".mb_substr($student[$s]['student_middle_name'], 0, 1).".";
         }
      }
      $document->setValue('snm['.$s.']', htmlspecialchars($student_out));         
      $document->setValue('num['.$s.']', htmlspecialchars($num));         
      $document->setValue('snm['.$s.']', htmlspecialchars($student_out));
      $document->setValue('mark['.$s.']', htmlspecialchars($mark_out));         

      $s++;
   }
} else if ($sql[0]['mark_system'] == 12) {
   $document = $phpWord->loadTemplate('twelve.docx');

   try {
      $student = $db->query("SELECT `student`.`id` AS 'student_id', `student`.`surname` AS 'student_surname', 
            `student`.`name` AS 'student_name', `student`.`middle_name` AS 'student_middle_name'
         FROM `student` INNER JOIN (`group` INNER JOIN `pair` ON `group`.`id`=`pair`.`group`)
            ON `student`.`group`=`group`.`id`
         WHERE `pair`.`id` = :pair
         ORDER BY `student`.`surname`, `student`.`name`, `student`.`middle_name`",
         [
            ':pair' => $pair
         ]
      );
   } catch (Exception $e) {
      echo "Помилка виконання! Зверніться до Адміністратора сайту!";
   }
   $count_student = count($student);

   $s = 0;
   while ($s < 31) {
      $student_out = "";
      $num = "";
      $mark_out = "";
      if ($s < count($student)) {
         try {
            $mark = $db->query("SELECT `mark`.`mark` AS 'mark', `mark`.`date` AS 'date'
               FROM `pair` INNER JOIN `mark` ON `pair`.`id`=`mark`.`pair`
               WHERE `pair`.`id` = :pair AND `mark`.`student` = :student AND `mark`.`date` >= :start AND `mark`.`date` <= :finish AND `mark`.`type` = 'С'
               ORDER BY `mark`.`date` DESC
               LIMIT 1",
               [
                  ':pair'     => $pair,
                  ':student'  => $student[$s]['student_id'],
                  ':start'    => $day_start,
                  ':finish'   => $day_finish
               ]
            );
          } catch (Exception $e) {
            echo "Помилка виконання! Зверніться до Адміністратора сайту!";
         } 

         try {
            $ex = $db->query("SELECT `mark`.`mark` AS 'mark'
               FROM `pair` INNER JOIN `mark` ON `pair`.`id`=`mark`.`pair`
               WHERE `pair`.`id` = :pair AND `mark`.`student` = :student AND `mark`.`date` >= :date AND `mark`.`type` = 'Е'
               ORDER BY `mark`.`date` DESC
               LIMIT 1",
               [
                  ':pair'     => $pair,
                  ':student'  => $student[$s]['student_id'],
                  ':date'     => $mark[0]['date']
               ]
            );
          } catch (Exception $e) {
            echo "Помилка виконання! Зверніться до Адміністратора сайту!";
         } 

         if (count($ex) !== 0) {
            if ($ex[0]['mark'] !== NULL) {
               $mark_out = mark(1, $ex[0]['mark']);
            } else {
               if (count($mark) !== 0) {
                  $mark_out = mark(1, $mark[0]['mark']);
               }
            }
         } else {
            if (count($mark) !== 0) {
               $mark_out = mark(1, $mark[0]['mark']);
            }
         }
         $num = ($s + 1).".";
         $student_out = $student[$s]['student_surname']." ".mb_substr($student[$s]['student_name'], 0, 1).".".mb_substr($student[$s]['student_middle_name'], 0, 1).".";
      }
      $document->setValue('snm['.$s.']', htmlspecialchars($student_out));         
      $document->setValue('num['.$s.']', htmlspecialchars($num));         
      $document->setValue('snm['.$s.']', htmlspecialchars($student_out));
      $document->setValue('mark['.$s.']', htmlspecialchars($mark_out));         

      $s++;
   } 
} else {
   $document = $phpWord->loadTemplate('twelve.docx'); 

   try {
      $student = $db->query("SELECT `student`.`id` AS 'student_id', `student`.`surname` AS 'student_surname', 
            `student`.`name` AS 'student_name', `student`.`middle_name` AS 'student_middle_name'
         FROM `student` INNER JOIN (`group` INNER JOIN `pair` ON `group`.`id`=`pair`.`group`)
            ON `student`.`group`=`group`.`id`
         WHERE `pair`.`id` = :pair
         ORDER BY `student`.`surname`, `student`.`name`, `student`.`middle_name`",
         [
            ':pair' => $pair
         ]
      );
   } catch (Exception $e) {
      echo "Помилка виконання! Зверніться до Адміністратора сайту!";
   }
   $count_student = count($student);

   $s = 0;
   while ($s < 31) {
      $student_out = "";
      $num = "";
      $mark_out = "";
      if ($s < count($student)) {
         try {
            $mark = $db->query("SELECT `mark`.`mark` AS 'mark', `mark`.`date` AS 'date'
               FROM `pair` INNER JOIN `mark` ON `pair`.`id`=`mark`.`pair`
               WHERE `pair`.`id` = :pair AND `mark`.`student` = :student AND `mark`.`date` >= :start AND `mark`.`date` <= :finish AND `mark`.`type` = 'С'
               ORDER BY `mark`.`date` DESC
               LIMIT 1",
               [
                  ':pair'     => $pair,
                  ':student'  => $student[$s]['student_id'],
                  ':start'    => $day_start,
                  ':finish'   => $day_finish
               ]
            );
          } catch (Exception $e) {
            echo "Помилка виконання! Зверніться до Адміністратора сайту!";
         } 

         try {
            $ex = $db->query("SELECT `mark`.`mark` AS 'mark'
               FROM `pair` INNER JOIN `mark` ON `pair`.`id`=`mark`.`pair`
               WHERE `pair`.`id` = :pair AND `mark`.`student` = :student AND `mark`.`date` >= :date AND `mark`.`type` = 'Е'
               ORDER BY `mark`.`date` DESC
               LIMIT 1",
               [
                  ':pair'     => $pair,
                  ':student'  => $student[$s]['student_id'],
                  ':date'     => $mark[0]['date']
               ]
            );
          } catch (Exception $e) {
            echo "Помилка виконання! Зверніться до Адміністратора сайту!";
         } 

         if (count($ex) !== 0) {
            if ($ex[0]['mark'] !== NULL) {
               $mark_out = mark(2, $ex[0]['mark']);
            } else {
               if (count($mark) !== 0) {
                  $mark_out = mark(2, $mark[0]['mark']);
               }
            }
         } else {
            if (count($mark) !== 0) {
               $mark_out = mark(2, $mark[0]['mark']);
            }
         }
         $num = ($s + 1).".";
         $student_out = $student[$s]['student_surname']." ".mb_substr($student[$s]['student_name'], 0, 1).".".mb_substr($student[$s]['student_middle_name'], 0, 1).".";
      }
      $document->setValue('snm['.$s.']', htmlspecialchars($student_out));         
      $document->setValue('num['.$s.']', htmlspecialchars($num));         
      $document->setValue('snm['.$s.']', htmlspecialchars($student_out));
      $document->setValue('mark['.$s.']', htmlspecialchars($mark_out));         

      $s++;
   }
   
}

$s = 0;
while ($s < 13) {
   if ($p_s[$s] == 0) {
      $p_s_out = "";
      $persent = "";
   } else {
      $p_s_out = $p_s[$s];
      $persent = round(($p_s[$s] * 100) / $count_student, 2);
   }
   $document->setValue('c['.$s.']', htmlspecialchars($p_s_out));
   $document->setValue('p['.$s.']', htmlspecialchars($persent));
   $s++;
}

$document->setValue('subject', htmlspecialchars($subject_out));
$document_name = $subject_out."_".$group_out."_".$day_finish;
$document->setValue('special', htmlspecialchars($spec_out));
$document->setValue('group', htmlspecialchars($group_out)); 
$document->setValue('kurs', htmlspecialchars($kurs_out)); 
$document->setValue('teacher', htmlspecialchars($teacher_out)); 
$document->setValue('teacher1', htmlspecialchars($teacher1_out)); 

header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
header('Content-Disposition: attachment;filename="'.$document_name.'.doc"');
header('Cache-Control: max-age=0');

$document->saveAs('php://output');
