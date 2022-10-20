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
                           
                              $nameNormalized = trim($_POST['name']);
                              $surnameNormalized = trim($_POST['surname']);
                              $middle_nameNormalized = trim($_POST['middle_name']);
                              $passwordsNormalized = trim($_POST['passwords']);
                              $study_formNormalized = trim($_POST['study_form']);
                              $groupNormalized = trim($_POST['group']);  

                              if (!empty($nameNormalized) && !empty($surnameNormalized) && !empty($middle_nameNormalized) && !empty($passwordsNormalized) 
                                             && !empty($study_formNormalized) && !empty($groupNormalized)) {

                                 $passwordsHash = md5(md5($passwordsNormalized).$SMT);

                                 $quer = "SELECT count(*) FROM `user` WHERE `surname` = :surname AND `name` = :name AND `middle_name` = :middle_name";
                                 $params = [
                                             ':surname'     => $surnameNormalized,
                                             ':name'        => $nameNormalized,
                                             ':middle_name' => $middle_nameNormalized
                                          ];

                                 $sql_user = mySqlQuer($db, $quer, $params);
                                    
                                 $quer = "SELECT count(*) FROM `teacher` WHERE `surname` = :surname AND `name` = :name AND `middle_name` = :middle_name";
                                 $params = [
                                             ':surname'     => $surnameNormalized,
                                             ':name'        => $nameNormalized,
                                             ':middle_name' => $middle_nameNormalized
                                          ];

                                 $sql_teacher = mySqlQuer($db, $quer, $params);

                                 $quer = "SELECT count(*) FROM `student` WHERE `surname` = :surname AND `name` = :name AND `middle_name` = :middle_name";
                                 $params = [
                                             ':surname'     => $surnameNormalized,
                                             ':name'        => $nameNormalized,
                                             ':middle_name' => $middle_nameNormalized
                                          ];

                                 $sql_student = mySqlQuer($db, $quer, $params);

                                 if ($sql_user[0]['count(*)'] > 0 || $sql_teacher[0]['count(*)'] > 0 || $sql_student[0]['count(*)'] > 0) {
                                    echo '<div style="padding-left: 3%; padding-bottom: 2%; width:auto"><p class="massage-error" style="width: auto; color:red;"><img src="../images/error.png" alt="Error!">В базі є '.$surnameNormalized.' '.$nameNormalized.' '.$middle_nameNormalized.'</p></div>';
                                 } else {

                                    $end_w = 1;
                                    $end_k = 1;
                                    $login = $surnameNormalized.' '.mb_substr($nameNormalized, 0, $end_w).'.'.mb_substr($middle_nameNormalized, 0, $end_k).'.';
                                    $end_w++;
                                    $end_k++;

                                    $quer = "SELECT count(*) FROM `user` where `nickname` = :nickname";
                                    $params = [
                                                ':nickname'  => $login
                                             ];
                                    $sql_user_log = mySqlQuer($db, $quer, $params);
                                    
                                    $quer = "SELECT count(*) FROM `teacher` where `nickname` = :nickname";
                                    $params = [
                                                ':nickname'  => $login
                                             ];
                                    $sql_teacher_log = mySqlQuer($db, $quer, $params);
                                    
                                    $quer = "SELECT count(*) FROM `student` where `nickname` = :nickname";
                                    $params = [
                                                ':nickname'  => $login
                                             ];
                                    $sql_student_log = mySqlQuer($db, $quer, $params);
                                    
                                    if ($sql_user_log[0]['count(*)'] > 0 || $sql_teacher_log[0]['count(*)'] > 0 || $sql_student_log[0]['count(*)'] > 0) {

                                       
                                       while ($sql_user_log[0]['count(*)'] > 0 || $sql_teacher_log[0]['count(*)'] > 0 || $sql_student_log[0]['count(*)'] > 0) {
                                          
                                             if ($end_w <= strlen($nameNormalized)) {

                                                $login = $surnameNormalized.' '.mb_substr($nameNormalized, 0, $end_w).'.'.mb_substr($middle_nameNormalized, 0, $end_k).'.';
                                                $end_w++;

                                             } else if ($end_k <= strlen($middle_nameNormalized)) {

                                                $login = $surnameNormalized.' '.mb_substr($nameNormalized, 0, $end_w).'.'.mb_substr($middle_nameNormalized, 0, $end_k).'.';
                                                $end_k++;
                                                
                                             } else {
                                                $rand = rand(1,150);
                                                $login = $surnameNormalized.' '.mb_substr($nameNormalized, 0, $end_w).'.'.mb_substr($middle_nameNormalized, 0, $end_k).'.N'.$rand;
                                             }
                                              

                                             $quer = "SELECT count(*) FROM `user` where `nickname` = :nickname";
                                             $params = [
                                                         ':nickname'  => $login
                                                      ];
                                             $sql_user_log = mySqlQuer($db, $quer, $params);

                                             $quer = "SELECT count(*) FROM `teacher` where `nickname` = :nickname";
                                             $params = [
                                                         ':nickname'  => $login
                                                      ];
                                             $sql_teacher_log = mySqlQuer($db, $quer, $params);

                                             $quer = "SELECT count(*) FROM `student` where `nickname` = :nickname";
                                             $params = [
                                                         ':nickname'  => $login
                                                      ];
                                             $sql_student_log = mySqlQuer($db, $quer, $params);

                                             
                                       }

                                       try {      
                                          $sql = $db->query("INSERT INTO `student`(`surname`, `name`, `middle_name`, `nickname`, `group`, `password_start`, `privileges`, `study_form`)
                                                            VALUES (:surname, :name, :middle_name, :nickname, :group, :passwords, :priviledy, :study_form)",
                                             [
                                                ':surname'     => $surnameNormalized,
                                                ':name'        => $nameNormalized,
                                                ':middle_name' => $middle_nameNormalized,
                                                ':nickname'    => $login,
                                                ':group'       => $groupNormalized,
                                                ':passwords'   => $passwordsHash,
                                                ':priviledy'   => 'student',
                                                ':study_form'  => $study_formNormalized
                                             ]
                                          );
                                       } catch (Exception $e) {
                                          echo "Помилка виконання! Зверніться до Адміністратора сайту!";    
                                       }
                                       echo '<div style="paddin g-left: 3%; padding-bottom: 2%; width:100%"><p class="massage-error" style="width: auto; color:green;"><img src="../images/ok.png" alt="Ok!">Студент '.$surnameNormalized.' '.$nameNormalized.' '.$middle_nameNormalized.' з логіном '.$login.' доданий!</p></div>';
                                       
                                    } else {

                                       try {      
                                          $sql = $db->query("INSERT INTO `student`(`surname`, `name`, `middle_name`, `nickname`, `group`, `password_start`, `privileges`, `study_form`)
                                                            VALUES (:surname, :name, :middle_name, :nickname, :group, :passwords, :priviledy, :study_form)",
                                             [
                                                ':surname'     => $surnameNormalized,
                                                ':name'        => $nameNormalized,
                                                ':middle_name' => $middle_nameNormalized,
                                                ':nickname'    => $login,
                                                ':group'       => $groupNormalized,
                                                ':passwords'   => $passwordsHash,
                                                ':priviledy'   => 'student',
                                                ':study_form'  => $study_formNormalized
                                             ]
                                          );
                                       } catch (Exception $e) {
                                          echo "Помилка виконання! Зверніться до Адміністратора сайту!";    
                                       }
   
                                       echo '<div style="paddin g-left: 3%; padding-bottom: 2%; width:100%"><p class="massage-error" style="width: auto; color:green;"><img src="../images/ok.png" alt="Ok!">Студент '.$surnameNormalized.' '.$nameNormalized.' '.$middle_nameNormalized.' з логіном '.$login.' доданий!</p></div>';
                                       
                                    }
                                 }
                              }
                        ?>