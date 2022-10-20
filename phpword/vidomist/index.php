<?php

require "../vendor/autoload.php";
include '../../db/setting.php';
require_once '../../db/db.php';
$db = new DataBase();

function mySqlQuer ($db, $querty, $params) {
   try {      

      $sql = $db->query($querty, $params);
      return $sql;
   } catch (Exception $e) {

      echo "Помилка виконання! Зверніться до Адміністратора сайту!";    
   }                           
}


function mark ($mark) {
   if ($mark <= 12 && $mark >= 10) {
      return 5;
   } else if ($mark < 10 && $mark >= 7) {
      return 4;
   } else if ($mark < 7 && $mark >= 4) {
      return 3;
   } else {
      return $mark;
   }
}

$phpWord = new \PhpOffice\PhpWord\PhpWord();


$kurator_zn = $_GET['kurator'];

try {
   $group_sql = $db->query("SELECT `id`
      FROM `group`
      WHERE `kurator` = :kurator",
      [
         ':kurator' => $kurator_zn
      ]
   );
 } catch (Exception $e) {
   echo "Помилка виконання! Зверніться до Адміністратора сайту!";
}

$group = $group_sql[0]['id'];
$day_start = $_GET['dayStart'];
$day_finish = $_GET['dayFinish'];

$document = $phpWord->loadTemplate('vidomist.docx');

$count_mark_subject = array(
   '0'  => 0,
   '1'  => 0,
   '2'  => 0,
   '3'  => 0,
   '4'  => 0,
   '5'  => 0,
   '6'  => 0,
   '7'  => 0,
   '8'  => 0,
   '9'  => 0,
   '10' => 0,
   '11' => 0,
   '12' => 0,
   '13' => 0
);
$sum_mark_subject = array (
   '0'  => 0,
   '1'  => 0,
   '2'  => 0,
   '3'  => 0,
   '4'  => 0,
   '5'  => 0,
   '6'  => 0,
   '7'  => 0,
   '8'  => 0,
   '9'  => 0,
   '10' => 0,
   '11' => 0,
   '12' => 0,
   '13' => 0
);
$bad_mark_subject = array (
   '0'  => 0,
   '1'  => 0,
   '2'  => 0,
   '3'  => 0,
   '4'  => 0,
   '5'  => 0,
   '6'  => 0,
   '7'  => 0,
   '8'  => 0,
   '9'  => 0,
   '10' => 0,
   '11' => 0,
   '12' => 0,
   '13' => 0
);

try {
   $group_sql = $db->query("SELECT `name`
      FROM `group`
      WHERE `id` = :group",
      [
         ':group' => $group
      ]
   );
 } catch (Exception $e) {
   echo "Помилка виконання! Зверніться до Адміністратора сайту!";
}
$group_out = $group_sql[0]['name'];
$kurs_out = mb_substr($group_sql[0]['name'], 0, 1);

try {
   $subject = $db->query("SELECT DISTINCT `subject`.`name` AS 'sname', `teacher`.`surname` AS 'surname', `teacher`.`name` AS 'name', `teacher`.`middle_name` AS 'middle_name', `pair`.`id` AS 'pair_id', `mark_system`
      FROM `subject` INNER JOIN (`descipline` INNER JOIN (`pair` INNER JOIN `mark` ON `pair`.`id`=`mark`.`pair`)
            ON `descipline`.`id`=`pair`.`descipline`) 
         ON `subject`.`id`=`descipline`.`subject`INNER JOIN `teacher` ON `descipline`.`teacher`=`teacher`.`id`
      WHERE `pair`.`group` = :group AND `mark`.`type` = 'С' 
         AND `mark`.`date` >= :start AND `mark`.`date` <= :finish
      ORDER BY `subject`.`name`",
      [
         ':group'    => $group,
         ':start'    => $day_start,
         ':finish'   => $day_finish
      ]
   );
 } catch (Exception $e) {
   echo "Помилка виконання! Зверніться до Адміністратора сайту!";
}

$s = 0;
while ($s < 14) {
   $subject_out = "";
   $teacher_out = "";
   if ($s < count($subject)) {
      $subject_out = $subject[$s]['sname'];
      $teacher_out =  $subject[$s]['surname']." ".mb_substr($subject[$s]['name'], 0, 1).".".mb_substr($subject[$s]['middle_name'], 0, 1).".";
   }
   $document->setValue('subject['.$s.']', htmlspecialchars($subject_out));
   $document->setValue('learnteacher['.$s.']', htmlspecialchars($teacher_out));
   $s++;
}

if ($kurs_out === 1) {
   try {
      $student = $db->query("SELECT DISTINCT `student`.`id` AS 'student_id', `student`.`surname` AS 'student_surname', 
            `student`.`name` AS 'student_name', `student`.`middle_name` AS 'student_middle_name',
            `student`.`study_form` AS 'study_form'
         FROM `student` INNER JOIN (`group` INNER JOIN `pair` ON `group`.`id`=`pair`.`group`)
            ON `student`.`group`=`group`.`id`
         WHERE `group`.`id` = :group
         ORDER BY `student`.`surname`, `student`.`name`, `student`.`middle_name`",
         [
            ':group' => $group
         ]
      );
    } catch (Exception $e) {
      echo "Помилка виконання! Зверніться до Адміністратора сайту!";
   }
   $count_student = count($student);   
   $s = 0;
   while ($s < 30) {
      $study_form_out = "";
      $student_out = "";
      $num = "";
      $bad_mark_student = 0;
      $count_mark_student = 0;
      $sum_mark_student = 0;
      if ($s < $count_student) {
         
         $num = ($s + 1).".";
         $student_out = $student[$s]['student_surname']." ".mb_substr($student[$s]['student_name'], 0, 1).".".mb_substr($student[$s]['student_middle_name'], 0, 1).".";
         $document->setValue('snm['.$s.']', htmlspecialchars($student_out));         
         if ($student[$s]['study_form'] == "контракт") {
            $study_form_out = "ко";
         } else {
            $study_form_out = "б";
         }

         $r = 0;
         while ($r < 14) {
            $mark_out = "";
            if ($r < count($subject)) {
               try {
                  $mark = $db->query("SELECT `mark`, `date`
                     FROM `mark` 
                     WHERE `type` = 'С' AND `date` >= :start
                        AND `date` <= :finish AND `pair` = :pair AND `student` = :student",
                     [
                        ':start'    => $day_start,
                        ':finish'   => $day_finish,
                        ':pair'     => $subject[$r]['pair_id'],
                        ':student'  => $student[$s]['student_id']
                     ]
                  );
                } catch (Exception $e) {
                  echo "Помилка виконання! Зверніться до Адміністратора сайту!";
               }

               try {
                  $ex = $db->query("SELECT `mark` 
                     FROM `mark` 
                     WHERE `type` = 'Е' AND `date` >= :start
                        AND `pair` = :pair AND `student` = :student",
                     [
                        ':start'    => $mark[0]['date'],
                        ':pair'     => $subject[$r]['pair_id'],
                        ':student'  => $student[$s]['student_id']
                     ]
                  );
                } catch (Exception $e) {
                  echo "Помилка виконання! Зверніться до Адміністратора сайту!";
               }

               if (count($ex) !== 0) {
                  if ($ex[0]['mark'] !== NULL) {
                     $mark_out = $ex[0]['mark'];
                  } else {
                     if (count($mark) !== 0) {
                        $mark_out = $mark[0]['mark'];
                     }
                  }
               } else {
                  if (count($mark) != 0) {
                     $mark_out = $mark[0]['mark'];
                  } else {
                     $mark_out = "";
                  }
               }
               if ($mark_out == "н/зал" || $mark_out == "н/а" || $mark_out == "н/зар") {
                  $bad_mark_student++;
                  $bad_mark_subject[$r]++;

               } else if ($mark_out != "зал" && $mark_out != "зар") {
                  $count_mark_subject[$r]++;
                  $count_mark_student++;
                  $sum_mark_subject[$r] += $mark_out;
                  $sum_mark_student += $mark_out;
               } else if ($mark_out == "зал" || $mark_out == "зар") {
                  $count_mark_subject = 0;
               }
            }
            $document->setValue('mark['.$s.']['.$r.']', htmlspecialchars($mark_out));         
            $r++;
         }

         if ($count_mark_student !== 0){
            $avg_student_out = $sum_mark_student/$count_mark_student;
            $document->setValue('ams['.$s.']', htmlspecialchars(round($avg_student_out, 2)));         
         } else {
            $document->setValue('ams['.$s.']', htmlspecialchars("0"));         
         }
      } else {
         $document->setValue('ams['.$s.']', htmlspecialchars(""));         
      }

      $r = 0;
      while ($r < 14) {
         $document->setValue('mark['.$s.']['.$r.']', "");         
         $r++;
      }

      if ($bad_mark_student === 0) {
         $document->setValue('bms['.$s.']', htmlspecialchars(""));         
      } else {
         $document->setValue('bms['.$s.']', htmlspecialchars($bad_mark_student));         
      }

      

      
      $document->setValue('num['.$s.']', htmlspecialchars($num));         
      $document->setValue('student['.$s.']', htmlspecialchars($student_out));
      $document->setValue('form['.$s.']', htmlspecialchars($study_form_out));         

      $s++;
   }  
} else {
   
   try {
      $student = $db->query("SELECT DISTINCT `student`.`id` AS 'student_id', `student`.`surname` AS 'student_surname', 
            `student`.`name` AS 'student_name', `student`.`middle_name` AS 'student_middle_name',
            `student`.`study_form` AS 'study_form'
         FROM `student` INNER JOIN (`group` INNER JOIN `pair` ON `group`.`id`=`pair`.`group`)
            ON `student`.`group`=`group`.`id`
         WHERE `group`.`id` = :group
         ORDER BY `student`.`surname`, `student`.`name`, `student`.`middle_name`",
         [
            ':group' => $group
         ]
      );
    } catch (Exception $e) {
      echo "Помилка виконання! Зверніться до Адміністратора сайту!";
   }
   $count_student = count($student);   
   $s = 0;
   while ($s < 30) {
      $study_form_out = "";
      $student_out = "";
      $num = "";
      $bad_mark_student = 0;
      $count_mark_student = 0;
      $sum_mark_student = 0;
      if ($s < $count_student) {
         
         $num = ($s + 1).".";
         $student_out = $student[$s]['student_surname']." ".mb_substr($student[$s]['student_name'], 0, 1).".".mb_substr($student[$s]['student_middle_name'], 0, 1).".";
         $document->setValue('snm['.$s.']', htmlspecialchars($student_out));         
         if ($student[$s]['study_form'] == "контракт") {
            $study_form_out = "ко";
         } else {
            $study_form_out = "б";
         }

         $r = 0;
         while ($r < 14) {
            $mark_out = "";
            if ($r < count($subject)) {
               try {
                  $mark = $db->query("SELECT `mark`, `date`
                     FROM `mark` 
                     WHERE `type` = 'С' AND `date` >= :start
                        AND `date` <= :finish AND `pair` = :pair AND `student` = :student
                     ORDER BY `date` DESC
                     LIMIT 1",
                     [
                        ':start'    => $day_start,
                        ':finish'   => $day_finish,
                        ':pair'     => $subject[$r]['pair_id'],
                        ':student'  => $student[$s]['student_id']
                     ]
                  );
                } catch (Exception $e) {
                  echo "Помилка виконання! Зверніться до Адміністратора сайту!";
               }
               if (count($mark) != 0) {
                  try {
                     $ex = $db->query("SELECT `mark` 
                        FROM `mark` 
                        WHERE `type` = 'Е' AND `date` >= :start
                           AND `pair` = :pair AND `student` = :student",
                        [
                           ':start'    => $mark[0]['date'],
                           ':pair'     => $subject[$r]['pair_id'],
                           ':student'  => $student[$s]['student_id']
                        ]
                     );
                  } catch (Exception $e) {
                     echo "Помилка виконання! Зверніться до Адміністратора сайту!";
                  }
               
               
                  if ($subject[$r]['mark_system'] == 5) {
                     if (count($ex) !== 0) {
                        if ($ex[0]['mark'] !== NULL) {
                           $mark_out = $ex[0]['mark'];
                        } else {
                           if (count($mark) !== 0) {
                              $mark_out = $mark[0]['mark'];
                           }
                        }
                     } else {
                        if (count($mark) != 0) {
                           $mark_out = $mark[0]['mark'];
                        } else {
                           $mark_out = "";
                        }
                     }
                  } else {
                     if (count($ex) !== 0) {
                        if ($ex[0]['mark'] !== NULL) {
                           $mark_out = mark($ex[0]['mark']);
                        } else {
                           if (count($mark) !== 0) {
                              $mark_out = mark($mark[0]['mark']);
                           }
                        }
                     } else {
                        if (count($mark) != 0) {
                           $mark_out = mark($mark[0]['mark']);
                        } else {
                           $mark_out = "";
                        }
                     }
                  }
               }
               if ($mark_out == "н/зал" || $mark_out == "н/а" || $mark_out == "н/зар") {
                  $bad_mark_student++;
                  $bad_mark_subject[$r]++;

               } else if ($mark_out != "зал" && $mark_out != "зар" && $mark_out != "н/зар" && $mark_out != "н/зал" && $mark_out != "н/а") {
                  $count_mark_subject[$r]++;
                  $count_mark_student++;
                  $sum_mark_subject[$r] += $mark_out;
                  $sum_mark_student += $mark_out;
               } else if ($mark_out == "зал" || $mark_out == "зар") {
                  $count_mark_subject[$r] = 0;
               }
            }
            $document->setValue('mark['.$s.']['.$r.']', htmlspecialchars($mark_out));         
            $r++;
         }

         if ($count_mark_student !== 0){
            $avg_student_out = $sum_mark_student/$count_mark_student;
            $document->setValue('ams['.$s.']', htmlspecialchars(round($avg_student_out, 2)));         
         } else {
            $document->setValue('ams['.$s.']', htmlspecialchars("0"));         
         }
      } else {
         $document->setValue('ams['.$s.']', htmlspecialchars(""));         
      }

      $r = 0;
      while ($r < 14) {
         $document->setValue('mark['.$s.']['.$r.']', "");         
         $r++;
      }

      if ($bad_mark_student === 0) {
         $document->setValue('bms['.$s.']', htmlspecialchars(""));         
      } else {
         $document->setValue('bms['.$s.']', htmlspecialchars($bad_mark_student));         
      }

      

      
      $document->setValue('num['.$s.']', htmlspecialchars($num));         
      $document->setValue('student['.$s.']', htmlspecialchars($student_out));
      $document->setValue('form['.$s.']', htmlspecialchars($study_form_out));         

      $s++;
   }
}


$s = 0; 
while ($s < 14) {
   if ($count_mark_subject[$s] === 0) {
      $document->setValue('a_s['.$s.']', htmlspecialchars(""));         
   } else {
      $avg_mark_subject = $sum_mark_subject[$s] / $count_mark_subject[$s];
      $document->setValue('a_s['.$s.']', htmlspecialchars(round($avg_mark_subject, 2)));         
   }

   if ($bad_mark_subject[$s] === 0) {
      $document->setValue('b_s['.$s.']', htmlspecialchars(""));         
   } else {
      $document->setValue('b_s['.$s.']', htmlspecialchars($bad_mark_subject[$s]));         
   }
   $s++;
}



try {
   $student = $db->query("SELECT DISTINCT `student`.`id` AS 'student_id', `student`.`surname` AS 'student_surname', 
         `student`.`name` AS 'student_name', `student`.`middle_name` AS 'student_middle_name'
      FROM `student` INNER JOIN (`group` INNER JOIN `pair` ON `group`.`id`=`pair`.`group`)
         ON `student`.`group`=`group`.`id`
      WHERE `group`.`id` = :group
      ORDER BY `student`.`surname`, `student`.`name`, `student`.`middle_name`",
      [
         ':group' => $group
      ]
   );
 } catch (Exception $e) {
   echo "Помилка виконання! Зверніться до Адміністратора сайту!";
}

$s = 0; 
while ($s < 30) {
   
   if ($s < count($student)) {

      try {
         $a_p = $db->query("SELECT COUNT(`cause`) AS 'sum'
            FROM `visiting`
            WHERE `date` >= :start AND `date` <= :finish AND `student` = :student",
            [
               ':start'    => $day_start,
               ':finish'   => $day_finish,
               ':student'  => $student[$s]['student_id']
            ]
         );
       } catch (Exception $e) {
         echo "Помилка виконання! Зверніться до Адміністратора сайту!";
      }

      if (count($a_p) > 0) {
         if ($a_p[0]['sum'] == 0) {
            $document->setValue('a_p['.$s.']', htmlspecialchars("-"));   
         } else {
            $document->setValue('a_p['.$s.']', htmlspecialchars($a_p[0]['sum']*2));   
         }
      } else {
         $document->setValue('a_p['.$s.']', htmlspecialchars("-"));   
      }

      try {
         $hv = $db->query("SELECT COUNT(`cause`) AS 'sum'
            FROM `visiting`
            WHERE `date` >= :start AND `date` <= :finish AND `student` = :student AND `cause` = 'хв'",
            [
               ':start'    => $day_start,
               ':finish'   => $day_finish,
               ':student'  => $student[$s]['student_id']
            ]
         );
       } catch (Exception $e) {
         echo "Помилка виконання! Зверніться до Адміністратора сайту!";
      }

      if (count($hv) > 0) {
         if ($hv[0]['sum'] == 0) {
            $document->setValue('hv['.$s.']', htmlspecialchars("-"));   
         } else {
            $document->setValue('hv['.$s.']', htmlspecialchars($hv[0]['sum']*2));   
         }
      } else {
         $document->setValue('hv['.$s.']', htmlspecialchars("-"));   
      }

      try {
         $rosp = $db->query("SELECT COUNT(`cause`) AS 'sum'
            FROM `visiting`
            WHERE `date` >= :start AND `date` <= :finish AND `student` = :student AND `cause` = 'рз'",
            [
               ':start'    => $day_start,
               ':finish'   => $day_finish,
               ':student'  => $student[$s]['student_id']
            ]
         );
       } catch (Exception $e) {
         echo "Помилка виконання! Зверніться до Адміністратора сайту!";
      }

      if (count($rosp) > 0) {
         if ($rosp[0]['sum'] == 0) {
            $document->setValue('rosp['.$s.']', htmlspecialchars("-"));   
         } else {
            $document->setValue('rosp['.$s.']', htmlspecialchars($rosp[0]['sum']*2));   
         }
      } else {
         $document->setValue('rosp['.$s.']', htmlspecialchars("-"));   
      }

      try {
         $zai = $db->query("SELECT COUNT(`cause`) AS 'sum'
            FROM `visiting`
            WHERE `date` >= :start AND `date` <= :finish AND `student` = :student AND `cause` = 'зв'",
            [
               ':start'    => $day_start,
               ':finish'   => $day_finish,
               ':student'  => $student[$s]['student_id']
            ]
         );
       } catch (Exception $e) {
         echo "Помилка виконання! Зверніться до Адміністратора сайту!";
      }

      if (count($zai) > 0) {
         if ($zai[0]['sum'] == 0) {
            $document->setValue('zai['.$s.']', htmlspecialchars("-"));   
         } else {
            $document->setValue('zai['.$s.']', htmlspecialchars($zai[0]['sum']*2));   
         }
      } else {
         $document->setValue('zai['.$s.']', htmlspecialchars("-"));   
      }

      try {
         $p = $db->query("SELECT COUNT(`cause`) AS 'sum'
            FROM `visiting`
            WHERE `date` >= :start AND `date` <= :finish AND `student` = :student AND `cause` = 'пр'",
            [
               ':start'    => $day_start,
               ':finish'   => $day_finish,
               ':student'  => $student[$s]['student_id']
            ]
         );
       } catch (Exception $e) {
         echo "Помилка виконання! Зверніться до Адміністратора сайту!";
      }

      if (count($p) > 0) {
         if ($p[0]['sum'] == 0) {
            $document->setValue('p['.$s.']', htmlspecialchars("-"));   
         } else {
            $document->setValue('p['.$s.']', htmlspecialchars($p[0]['sum']*2));   
         }
      } else {
         $document->setValue('p['.$s.']', htmlspecialchars("-"));   
      }


   } else {
      $document->setValue('a_p['.$s.']', htmlspecialchars(""));         
      $document->setValue('hv['.$s.']', htmlspecialchars(""));         
      $document->setValue('rosp['.$s.']', htmlspecialchars(""));         
      $document->setValue('zai['.$s.']', htmlspecialchars(""));         
      $document->setValue('p['.$s.']', htmlspecialchars(""));      
   }
   $s++;
}

try {
   $sum_a_p = $db->query("SELECT COUNT(`cause`) AS 'sum'
      FROM `visiting` INNER JOIN (`student` INNER JOIN `group` ON `student`.`group`=`group`.`id`)
         ON `visiting`.`student`=`student`.`id`
      WHERE `date` >= :start AND `date` <= :finish AND `group`.`id` = :group ",
      [
         ':start'    => $day_start,
         ':finish'   => $day_finish,
         ':group'    => $group
      ]
   );
 } catch (Exception $e) {
   echo "Помилка виконання! Зверніться до Адміністратора сайту!";
}

if (count($sum_a_p) > 0) {
   if ($sum_a_p[0]['sum'] == 0) {
      $document->setValue('sum_a_p', htmlspecialchars("0"));   
   } else {
      $document->setValue('sum_a_p', htmlspecialchars($sum_a_p[0]['sum']*2));   
   }
} else {
   $document->setValue('sum_a_p', htmlspecialchars("0"));   
}

try {
   $sum_a_p = $db->query("SELECT COUNT(`cause`) AS 'sum'
      FROM `visiting` INNER JOIN (`student` INNER JOIN `group` ON `student`.`group`=`group`.`id`)
         ON `visiting`.`student`=`student`.`id`
      WHERE `date` >= :start AND `date` <= :finish AND `group`.`id` = :group AND `cause` = 'хв'",
      [
         ':start'    => $day_start,
         ':finish'   => $day_finish,
         ':group'    => $group
      ]
   );
 } catch (Exception $e) {
   echo "Помилка виконання! Зверніться до Адміністратора сайту!";
}

if (count($sum_a_p) > 0) {
   if ($sum_a_p[0]['sum'] == 0) {
      $document->setValue('sum_hv', htmlspecialchars("0"));   
   } else {
      $document->setValue('sum_hv', htmlspecialchars($sum_a_p[0]['sum']*2));   
   }
} else {
   $document->setValue('sum_hv', htmlspecialchars("0"));   
}


try {
   $sum_a_p = $db->query("SELECT COUNT(`cause`) AS 'sum'
      FROM `visiting` INNER JOIN (`student` INNER JOIN `group` ON `student`.`group`=`group`.`id`)
         ON `visiting`.`student`=`student`.`id`
      WHERE `date` >= :start AND `date` <= :finish AND `group`.`id` = :group AND `cause` = 'рз'",
      [
         ':start'    => $day_start,
         ':finish'   => $day_finish,
         ':group'    => $group
      ]
   );
 } catch (Exception $e) {
   echo "Помилка виконання! Зверніться до Адміністратора сайту!";
}

if (count($sum_a_p) > 0) {
   if ($sum_a_p[0]['sum'] == 0) {
      $document->setValue('sum_rosp', htmlspecialchars("0"));   
   } else {
      $document->setValue('sum_rosp', htmlspecialchars($sum_a_p[0]['sum']*2));   
   }
} else {
   $document->setValue('sum_rosp', htmlspecialchars("0"));   
}


try {
   $sum_a_p = $db->query("SELECT COUNT(`cause`) AS 'sum'
      FROM `visiting` INNER JOIN (`student` INNER JOIN `group` ON `student`.`group`=`group`.`id`)
         ON `visiting`.`student`=`student`.`id`
      WHERE `date` >= :start AND `date` <= :finish AND `group`.`id` = :group AND `cause` = 'зв'",
      [
         ':start'    => $day_start,
         ':finish'   => $day_finish,
         ':group'    => $group
      ]
   );
 } catch (Exception $e) {
   echo "Помилка виконання! Зверніться до Адміністратора сайту!";
}

if (count($sum_a_p) > 0) {
   if ($sum_a_p[0]['sum'] == 0) {
      $document->setValue('sum_zai', htmlspecialchars("0"));   
   } else {
      $document->setValue('sum_zai', htmlspecialchars($sum_a_p[0]['sum']*2));   
   }
} else {
   $document->setValue('sum_zai', htmlspecialchars("0"));   
}

try {
   $sum_a_p = $db->query("SELECT COUNT(`cause`) AS 'sum'
      FROM `visiting` INNER JOIN (`student` INNER JOIN `group` ON `student`.`group`=`group`.`id`)
         ON `visiting`.`student`=`student`.`id`
      WHERE `date` >= :start AND `date` <= :finish AND `group`.`id` = :group AND `cause` = 'пр'",
      [
         ':start'    => $day_start,
         ':finish'   => $day_finish,
         ':group'    => $group
      ]
   );
 } catch (Exception $e) {
   echo "Помилка виконання! Зверніться до Адміністратора сайту!";
}

if (count($sum_a_p) > 0) {
   if ($sum_a_p[0]['sum'] == 0) {
      $document->setValue('sum_p', htmlspecialchars("0"));   
   } else {
      $document->setValue('sum_p', htmlspecialchars($sum_a_p[0]['sum']*2));   
   }
} else {
   $document->setValue('sum_p', htmlspecialchars("0"));   
}

try {
   $group_sql = $db->query("SELECT `name`
      FROM `group` 
      WHERE `group`.`id` = :group",
      [
         ':group'    => $group
      ]
   );
 } catch (Exception $e) {
   echo "Помилка виконання! Зверніться до Адміністратора сайту!";
}
$document->setValue('group', htmlspecialchars($group_sql[0]['name'])); 
$document->setValue('kurs', htmlspecialchars(mb_substr($group_sql[0]['name'], 0, 1)));

try {
   $spec = $db->query("SELECT `specialty`.`name` AS 'sname', `spec_number`, 
         `teacher`.`surname` AS 'surname', `teacher`.`name` AS 'name', `teacher`.`middle_name` AS 'middle_name'
      FROM `group` INNER JOIN (`specialty` INNER JOIN `teacher` ON `specialty`.`department_head`=`teacher`.`id`)
         ON `group`.`specialty`=`specialty`.`id`
      WHERE `group`.`id` = :group",
      [
         ':group' => $group
      ]
   );
 } catch (Exception $e) {
   echo "Помилка виконання! Зверніться до Адміністратора сайту!";
}
$document->setValue('spec', htmlspecialchars($spec[0]['spec_number']." «".$spec[0]['sname']."»"));
$dep = mb_substr($spec[0]['name'], 0, 1).".".mb_substr($spec[0]['middle_name'], 0, 1).". ".$spec[0]['surname'];
$document->setValue('department_head', htmlspecialchars($dep)); 

try {
   $spec = $db->query("SELECT `teacher`.`name` AS 'name', `surname`, `middle_name`
      FROM `group` INNER JOIN `teacher` ON `group`.`kurator`=`teacher`.`id`
      WHERE `group`.`id` = :group",
      [
         ':group'    => $group
      ]
   );
 } catch (Exception $e) {
   echo "Помилка виконання! Зверніться до Адміністратора сайту!";
}

$kurator = mb_substr($spec[0]['name'], 0, 1).".".mb_substr($spec[0]['middle_name'], 0, 1).". ".$spec[0]['surname'];
$document->setValue('kurator', htmlspecialchars($kurator)); 

$document_name = "Семестрова_відомість_".$group_sql[0]['name']."_".$day_finish;

header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
header('Content-Disposition: attachment;filename="'.$document_name.'.doc"');
header('Cache-Control: max-age=0');

$document->saveAs('php://output');