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
$nicknameNormalized = trim($_POST['nickname']);
$passwordsNormalized = trim($_POST['passwords']);
$privilegeNormalized = trim($_POST['privilege']);


if (
   !empty($nicknameNormalized) && !empty($passwordsNormalized) && !empty($privilegeNormalized) && !empty($nameNormalized)
   && !empty($surnameNormalized) && !empty($middle_nameNormalized)
) {

   $passwordsHash = md5(md5($passwordsNormalized) . $SMT);

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
      echo '<div style="padding-left: 3%; padding-bottom: 2%; width:auto"><p class="massage-error" style="width: auto; color:red;"><img src="../images/error.png" alt="Error!">Користувач ' . $surnameNormalized . ' ' . $nameNormalized . ' ' . $middle_nameNormalized . ' існує!</p></div>';
   } else {

      $quer = "SELECT count(*) FROM `user` where `nickname` = :nickname";
      $params = [
         ':nickname'  => $nicknameNormalized
      ];
      $sql_user_log = mySqlQuer($db, $quer, $params);

      $quer = "SELECT count(*) FROM `teacher` where `nickname` = :nickname";
      $params = [
         ':nickname'  => $nicknameNormalized
      ];
      $sql_teacher_log = mySqlQuer($db, $quer, $params);

      $quer = "SELECT count(*) FROM `student` where `nickname` = :nickname";
      $params = [
         ':nickname'  => $nicknameNormalized
      ];
      $sql_student_log = mySqlQuer($db, $quer, $params);

      if ($sql_user_log[0]['count(*)'] > 0 || $sql_teacher_log[0]['count(*)'] > 0 || $sql_student_log[0]['count(*)'] > 0) {

         echo '<div style="padding-left: 3%; padding-bottom: 2%; width:100%"><p class="massage-error" style="width: auto; color:green;"><img src="../images/ok.png" alt="Ok!">Користувач з логіном ' . $nicknameNormalized . ' є в базі, змініть логін!</p></div>';
      } else {

         $sql = $db->query(
            "INSERT INTO `user`(`surname`, `name`, `middle_name`, `nickname`, `password_start`, `privileges`) 
                                                            VALUES (:surname ,:name ,:middle_name ,:nickname, :passwords, :privilege)",
            [
               ':surname' => $surnameNormalized,
               ':name' => $nameNormalized,
               ':middle_name' => $middle_nameNormalized,
               ':nickname' => $nicknameNormalized,
               ':passwords' => $passwordsHash,
               ':privilege' => $privilegeNormalized
            ]
         );
         echo '<div style="padding-left: 3%; padding-bottom: 2%; width:100%"><p class="massage-error" style="width: auto; color:green;"><img src="../images/ok.png" alt="Ok!">Користувач ' . $surnameNormalized . ' ' . $nameNormalized . ' ' . $middle_nameNormalized . ' з логіном ' . $nicknameNormalized . ' додано!</p></div>';
      }
   }
} else if (
   empty($nicknameNormalized) && !empty($passwordsNormalized) && !empty($privilegeNormalized) && !empty($nameNormalized)
   && !empty($surnameNormalized) && !empty($middle_nameNormalized)
) {

   $passwordsHash = md5(md5($passwordsNormalized) . $SMT);

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

      echo '<div style="padding-left: 3%; padding-bottom: 2%; width:auto"><p class="massage-error" style="width: auto; color:red;"><img src="../images/error.png" alt="Error!">Користувач ' . $surnameNormalized . ' ' . $nameNormalized . ' ' . $middle_nameNormalized . ' існує!</p></div>';
   } else {

      $end_k = 1;
      $end_w = 1;
      $login = $surnameNormalized . ' ' . mb_substr($nameNormalized, 0, $end_w) . '.' . mb_substr($middle_nameNormalized, 0, $end_k) . '.';
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

               $login = $surnameNormalized . ' ' . mb_substr($nameNormalized, 0, $end_w) . '.' . mb_substr($middle_nameNormalized, 0, $end_k) . '.';
               $end_w++;
            } else if ($end_k <= strlen($middle_nameNormalized)) {

               $login = $surnameNormalized . ' ' . mb_substr($nameNormalized, 0, $end_w) . '.' . mb_substr($middle_nameNormalized, 0, $end_k) . '.';
               $end_k++;
            } else {
               $rand = rand(1, 150);
               $login = $surnameNormalized . ' ' . mb_substr($nameNormalized, 0, $end_w) . '.' . mb_substr($middle_nameNormalized, 0, $end_k) . '.N' . $rand;
            }
         }

         $sql = $db->query(
            "INSERT INTO `user`(`surname`, `name`, `middle_name`, `nickname`, `password_start`, `privileges`) 
                                                            VALUES (:surname ,:name ,:middle_name ,:nickname, :passwords, :privilege)",
            [
               ':surname' => $surnameNormalized,
               ':name' => $nameNormalized,
               ':middle_name' => $middle_nameNormalized,
               ':nickname' => $login,
               ':passwords' => $passwordsHash,
               ':privilege' => $privilegeNormalized
            ]
         );
         echo '<div style="padding-left: 3%; padding-bottom: 2%; width:100%"><p class="massage-error" style="width: auto; color:green;"><img src="../images/ok.png" alt="Ok!">Користувач ' . $surnameNormalized . ' ' . $nameNormalized . ' ' . $middle_nameNormalized . ' з логіном ' . $login . ' додано!</p></div>';
      } else {

         $sql = $db->query(
            "INSERT INTO `user`(`surname`, `name`, `middle_name`, `nickname`, `password_start`, `privileges`) 
                                                            VALUES (:surname ,:name ,:middle_name ,:nickname, :passwords, :privilege)",
            [
               ':surname' => $surnameNormalized,
               ':name' => $nameNormalized,
               ':middle_name' => $middle_nameNormalized,
               ':nickname' => $login,
               ':passwords' => $passwordsHash,
               ':privilege' => $privilegeNormalized
            ]
         );
         echo '<div style="padding-left: 3%; padding-bottom: 2%; width:100%"><p class="massage-error" style="width: auto; color:green;"><img src="../images/ok.png" alt="Ok!">Користувач ' . $surnameNormalized . ' ' . $nameNormalized . ' ' . $middle_nameNormalized . ' з логіном ' . $login . ' додано!</p></div>';
      }
   }
}
?>