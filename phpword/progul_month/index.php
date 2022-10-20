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

$phpWord = new \PhpOffice\PhpWord\PhpWord();
$document = $phpWord->loadTemplate('progul_month.docx'); 
$array_month = array(
                  '1'   => 'СІЧЕНЬ',
                  '2'   => 'ЛЮТИЙ',
                  '3'   => 'БЕРЕЗЕНЬ',
                  '4'   => 'КВІТЕНЬ',
                  '5'   => 'ТРАВЕНЬ',
                  '6'   => 'ЧЕРВЕНЬ',
                  '7'   => 'ЛИПЕНЬ',
                  '8'   => 'СЕРПЕНЬ',
                  '9'   => 'ВЕРЕСЕНЬ',
                  '10'  => 'ЖОВТЕНЬ',
                  '11'  => 'ЛИСТОПАД',
                  '12'  => 'ГРУДЕНЬ'
);
$kurator = $_GET['kurator'];
$month = $_GET['date'];
/*$kurator = 10;
$month = "2020-05";*/
$document_name = "";
{
   if (strpos($month, "-01") !== false) {
      $document->setValue('month', $array_month[1]);
      $document_month = $array_month[1];
   } else if (strpos($month, "-02") !== false) {
      $document->setValue('month', $array_month[2]);
      $document_month = $array_month[2];
   } else if (strpos($month, "-03") !== false) {
      $document->setValue('month', $array_month[3]);
      $document_month = $array_month[3];
   } else if (strpos($month, "-04") !== false) {
      $document->setValue('month', $array_month[4]);
      $document_month = $array_month[4];
   } else if (strpos($month, "-05") !== false) {
      $document->setValue('month', $array_month[5]);
      $document_month = $array_month[5];
   } else if (strpos($month, "-06") !== false) {
      $document->setValue('month', $array_month[6]);
      $document_month = $array_month[6];
   } else if (strpos($month, "-07") !== false) {
      $document->setValue('month', $array_month[7]);
      $document_month = $array_month[7];
   } else if (strpos($month, "-08") !== false) {
      $document->setValue('month', $array_month[8]);
      $document_month = $array_month[8];
   } else if (strpos($month, "-09") !== false) {
      $document->setValue('month', $array_month[9]);
      $document_month = $array_month[9];
   } else if (strpos($month, "-10") !== false) {
      $document->setValue('month', $array_month[10]);
      $document_month = $array_month[10];
   } else if (strpos($month, "-11") !== false) {
      $document->setValue('month', $array_month[11]);
      $document_month = $array_month[11];
   } else if (strpos($month, "-12") !== false) {
      $document->setValue('month', $array_month[12]);
      $document_month = $array_month[12];
   }
}
try {
   $sql = $db->query("SELECT `surname`, `teacher`.`name` AS 'name', `middle_name`, `group`.`name` AS 'gname'
      FROM `teacher` INNER JOIN `group` ON `teacher`.`id`=`group`.`kurator`
      WHERE `kurator` = :kurator",
      [
         ':kurator' => $kurator
      ]
   );
} catch (Exception $e) {
   echo "Помилка виконання! Зверніться до Адміністратора сайту!";
}
$kurator_out = $sql[0]['surname']." ".mb_substr($sql[0]['name'], 0, 1).".".mb_substr($sql[0]['middle_name'], 0, 1).".";
$document->setValue('group', $sql[0]['gname']);
$document_name = $sql[0]['gname']."_".$document_month;
$document->setValue('kurator', $kurator_out);
$document->setValue('year', mb_substr($month, 0, 4)); 
$month .= "%";

try {
   $sql = $db->query("SELECT COUNT(`cause`) AS 'count'
      FROM `visiting` INNER JOIN 
         (`student` INNER JOIN `group` ON `student`.`group`=`group`.`id`)
         ON `visiting`.`student`=`student`.`id`
      WHERE `kurator` = :kurator AND `date` LIKE :date",
      [
         ':kurator' => $kurator,
         ':date'    => $month
      ]
   );
} catch (Exception $e) {
   echo "Помилка виконання! Зверніться до Адміністратора сайту!";
}
$document->setValue('count_all', $sql[0]['count']*2);
try {
   $hv = $db->query("SELECT COUNT(`cause`) AS 'count'
      FROM `visiting` INNER JOIN 
         (`student` INNER JOIN `group` ON `student`.`group`=`group`.`id`)
         ON `visiting`.`student`=`student`.`id`
      WHERE `kurator` = :kurator AND `date` LIKE :date AND `cause` = 'хв'",
      [
         ':kurator' => $kurator,
         ':date'    => $month
      ]
   );
} catch (Exception $e) {
   echo "Помилка виконання! Зверніться до Адміністратора сайту!";
}
$document->setValue('count_hv', $hv[0]['count']*2);
try {
   $zai = $db->query("SELECT COUNT(`cause`) AS 'count'
      FROM `visiting` INNER JOIN 
         (`student` INNER JOIN `group` ON `student`.`group`=`group`.`id`)
         ON `visiting`.`student`=`student`.`id`
      WHERE `kurator` = :kurator AND `date` LIKE :date AND `cause` = 'зв'",
      [
         ':kurator' => $kurator,
         ':date'    => $month
      ]
   );
} catch (Exception $e) {
   echo "Помилка виконання! Зверніться до Адміністратора сайту!";
}
$document->setValue('count_zai', $zai[0]['count']*2);
try {
   $rosp = $db->query("SELECT COUNT(`cause`) AS 'count'
      FROM `visiting` INNER JOIN 
         (`student` INNER JOIN `group` ON `student`.`group`=`group`.`id`)
         ON `visiting`.`student`=`student`.`id`
      WHERE `kurator` = :kurator AND `date` LIKE :date AND `cause` = 'рз'",
      [
         ':kurator' => $kurator,
         ':date'    => $month
      ]
   );
} catch (Exception $e) {
   echo "Помилка виконання! Зверніться до Адміністратора сайту!";
}
$document->setValue('count_rosp', $rosp[0]['count']*2);
$document->setValue('count_pp', ($hv[0]['count']+$zai[0]['count']+$rosp[0]['count'])*2);
try {
   $pr = $db->query("SELECT COUNT(`cause`) AS 'count'
      FROM `visiting` INNER JOIN 
         (`student` INNER JOIN `group` ON `student`.`group`=`group`.`id`)
         ON `visiting`.`student`=`student`.`id`
      WHERE `kurator` = :kurator AND `date` LIKE :date AND `cause` = 'пр'",
      [
         ':kurator' => $kurator,
         ':date'    => $month
      ]
   );
} catch (Exception $e) {
   echo "Помилка виконання! Зверніться до Адміністратора сайту!";
}
$document->setValue('count_pr', $pr[0]['count']*2);
try {
   $student = $db->query("SELECT DISTINCT `student`.`id` AS 'id', `surname`, `student`.`name` AS 'name', `middle_name`
      FROM `visiting` INNER JOIN 
         (`student` INNER JOIN `group` ON `student`.`group`=`group`.`id`)
         ON `visiting`.`student`=`student`.`id`
      WHERE `kurator` = :kurator
      ORDER BY `student`.`surname`, `student`.`name`, `student`.`middle_name`",
      [
         ':kurator' => $kurator
      ]
   );
} catch (Exception $e) {
   echo "Помилка виконання! Зверніться до Адміністратора сайту!";
}
$a = 0;
$num = 1;  
$section = "";
while ($a < count($student)) {

   $snm = $student[$a]['surname']." ".mb_substr($student[$a]['name'], 0, 1).".".mb_substr($student[$a]['middle_name'], 0, 1).".";
   try {
      $count_pr = $db->query("SELECT COUNT(`cause`) AS 'count' 
         FROM `visiting`
         WHERE `date` LIKE :date AND `cause` = 'пр' AND `student` = :student",
         [
            ':student' => $student[$a]['id'],
            ':date'    => $month
         ]
      );
   } catch (Exception $e) {
      echo "Помилка виконання! Зверніться до Адміністратора сайту!";
   }
   
   if ($count_pr[0]['count'] >= 5 ) {
      
      $section .= $num.".   ".$snm."<w:br/>";
      $num ++;
      
   }
   $a++;
}
$document->setValue('spisok', $section);

header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
header('Content-Disposition: attachment;filename="'.$document_name.'.doc"');
header('Cache-Control: max-age=0');

$document->saveAs('php://output');