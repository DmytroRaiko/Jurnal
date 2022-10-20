<?php 
require_once '../db/db.php';
include '../db/setting.php';
session_start();

if ($_SESSION['name'] != "admin-site" || !isset($_SESSION['name'])){

   header('Location: ../index.php');
   exit;
}
class AdminQueries extends DataBase {
    public function __construct(){
        parent::__construct();
    }

    public function SelectUsers () {
        try {
            return parent::query("SELECT DISTINCT `nickname`,`surname`, `name`, `middle_name` FROM `user`");
        } catch (Exception $e) {
            echo "Помилка виконання! Зверніться до Адміністратора сайту!";
        }
    }

    public function SelectTeachers () {

        try {
            return parent::query("SELECT `id`,`surname`, `name`, `middle_name` FROM `teacher` ORDER BY `surname`, `name`");
        } catch (Exception $e) {
            echo "Помилка виконання! Зверніться до Адміністратора сайту!";
        }
    }

    public function SelectStudents () {
        try {
            return parent::query("SELECT `id`,`surname`, `name`, `middle_name` FROM `student` ORDER BY `surname`,`name`");
        } catch (Exception $e) {
            echo "Помилка виконання! Зверніться до Адміністратора сайту!";
        }
    }

    public function SelectStudentByID ($id) {
        try {
            return $this::query("SELECT `student`.`id` AS 'student_id',`student`.`name` AS 'student_name', `student`.`surname` AS 'student_surname',
                                       `student`.`middle_name` AS 'student_middle_name', `student`.`study_form` AS 'student_study_form', `group`.`id` AS 'group_id'
                                        FROM `student` INNER JOIN `group` ON `student`.`group` = `group`.`id` WhERE `student`.`id` = :id",
                [
                    ':id' => $id
                ]);
        } catch (Exception $e) {
            echo "Помилка виконання! Зверніться до Адміністратора сайту!";
        }
    }

    public function SelectFullSubjectInfo () {
        try {
            return $this::query("SELECT `teacher`.`surname` AS 'teacher_surname', `teacher`.`name` AS 'teacher_name', `teacher`.`middle_name` AS 'teacher_middle_name', 
                                                `descipline`.`id` AS 'descipline_id', `subject`.`name` AS 'subject_name', `subject`.`mark_system` AS 'subject_mark_system'
                                                FROM `teacher` INNER JOIN (`descipline` INNER JOIN `subject` ON `descipline`.`subject`=`subject`.`id`) 
                                                ON `teacher`.`id`=`descipline`.`teacher`;");
        } catch (Exception $e) {
            echo "Помилка виконання! Зверніться до Адміністратора сайту!";
        }
    }

    public function SelectSpecialitiesInIDRange () {
        try {
            return $this::query("SELECT `name`, `id`, `spec_number` FROM `specialty` WHERE `id` IN(SELECT min(`id`) FROM `specialty` GROUP BY `name`)");
        } catch (Exception $e) {
            echo "Помилка виконання! Зверніться до Адміністратора сайту!";
        }
    }

    public function SelectSpecialities () {
        try {
            return $this::query("SELECT `name`, `id`, `spec_number` FROM `specialty` ORDER BY `name`");
        } catch (Exception $e) {
            echo "Помилка виконання! Зверніться до Адміністратора сайту!";
        }
    }

    public function SelectSpecialityByID ($id) {
        try {
            return $this::query("SELECT  `id`, `name`,`spec_number`, `department_head` FROM `specialty` WHERE `id` = :id",
                [
                    ':id' => $id
                ]);
        } catch (Exception $e) {
            echo "Помилка виконання! Зверніться до Адміністратора сайту!";
        }
    }

    public function SelectGroups () {
        try {
            return $this::query("SELECT `name`, `id` FROM `group` ORDER BY `name`");
        } catch (Exception $e) {
            echo "Помилка виконання! Зверніться до Адміністратора сайту!";
        }
    }

    public function SelectGroupByID ($id) {
        try {
            return $this::query("SELECT `id`, `specialty`, `name`, `kurator` FROM `group` WHERE `id` = :id",
                [
                    ':id' => $id
                ]
            );
        } catch (Exception $e) {
            echo "Помилка виконання! Зверніться до Адміністратора сайту!";
        }
    }

    public function SelectSubjects () {
        try {
            return $this::query("SELECT `id`, `name`, `mark_system` FROM `subject`");
        } catch (Exception $e) {
            echo "Помилка виконання! Зверніться до Адміністратора сайту!";
        }
    }

    public function SelectSubjectByID ($id) {
        try {
            return $this::query("SELECT `subject`.`id` AS 'subject_id', `subject`.`name` AS 'subject_name',
                                          `subject`.`mark_system` AS 'subject_mark_system', `background_subject`.`id` AS 'id_background',
                                          `background_subject`.`image` AS 'image_background' 
                                          FROM `subject` INNER JOIN `background_subject` ON `subject`.`id` = `background_subject`.`subject`
                                          WHERE `subject`.`id` = :id ORDER BY `subject`.`name`",
                [
                    ':id' => $id
              ]
            );
        } catch (Exception $e) {
            echo "Помилка виконання! Зверніться до Адміністратора сайту!";
        }
    }

    public function SelectInfoForEditTableSpeciality(): array
    {
        try {
            return [
                $this::query("SELECT `specialty`.`id` AS 'specialty_id', `specialty`.`name` AS 'specialty_name',
                      `specialty`.`spec_number` AS 'specialty_spec_number'
                      FROM `specialty` 
                      WHERE `specialty`.`department_head` IS NULL
                      ORDER BY `specialty`.`name`"),
                $this::query("SELECT `specialty`.`id` AS 'specialty_id', `specialty`.`name` AS 'specialty_name',
                      `specialty`.`spec_number` AS 'specialty_spec_number',  `teacher`.`surname` AS 'teacher_surname', `teacher`.`name` AS 'teacher_name', `teacher`.`middle_name` AS 'teacher_middle_name'
                      FROM `specialty` INNER JOIN `teacher` ON `specialty`.`department_head` = `teacher`.`id`
                      ORDER BY `specialty`.`name`")
            ];
        } catch (Exception $e) {
            echo "Помилка виконання! Зверніться до Адміністратора сайту!";
            return [];
        }
    }
}

$adminQuery = new AdminQueries();
?>
<!DOCTYPE html>
<html lang="ua">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <link rel="stylesheet" href="../style/style.css">
   <link rel="stylesheet" href="../style/template.css">
   <link rel="stylesheet" href="../style/admin-cite.css">
   <link rel="stylesheet" href="../style/style-media-query.css">
   <link rel="stylesheet" href="../style/templete-media-query.css">
   <link rel="shortcut icon" href="../images/favicon.png" type="image/x-icon">
   <link rel="stylesheet" href="../style/style-choose-block.css">
   <link rel="stylesheet" href="../style/style-teacher.css">
   <link rel="stylesheet" href="../style/kurator.css">
   <link rel="stylesheet" href="../style/style-choose-block-media-query.css">
   <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
   <title>Електронний журнал</title>
</head>
<body>
   <div class="all">
<!--      <div id="page-preloader" class="preloader">-->
<!--         <div class="loader"></div>-->
<!--      </div>-->
      <!-- header - start -->
      <header id="first">
         <div class="logo"><a href="https://mk.sumdu.edu.ua/" target="_blank"><img src="../images/logo.png" alt="logo" title="На головну МК СумДУ"></a></div>
         <div class="name">ЕЛЕКТРОННИЙ ЖУРНАЛ</div>
         <div class="login"><a href="../index.php"><img src="../images/customer_100px.png" alt="customer" title="Вихід"></a></div>
      </header>

      <header id="second">
         <div class="logo"><a href="https://mk.sumdu.edu.ua/" target="_blank"><img src="../images/logo.png" alt="logo" title="На головну МК СумДУ"></a></div>
         <input type="checkbox" id="check-menu">
            <div class="name">ЕЛЕКТРОННИЙ ЖУРНАЛ</div>
         <label for="check-menu" class="burger">
            <div class="burger-line first"></div>
            <div class="burger-line second"></div>
            <div class="burger-line third"></div>
            <div class="burger-line fourth"></div>
         </label>
         <div id="top-menu">
            <li class="menu-item user" id="top-user">Користувачі</li>
            <li class="menu-item db-student" id="top-db-student">DataBase "Студент"</li>
            <li class="menu-item db-teacher" id="top-db-teacher">DataBase "Викладач"</li>
            <li class="menu-item jurnal" id="top-jurnal">Журнал</li>
            <li class="menu-item visit" id="top-visit">Відвідування</li>
            <li class="menu-item block-control" id="top-block-control">Контроль ведення журналів</li>
            <li class="filter-zone-mark-admin menu-item f" style="background-color: rgb(49, 173, 240)">Фільтри</li>
            <li class="filter-zone-visit menu-item f" style="display: none; background-color: rgb(49, 173, 240)">Фільтри</li>            
            <li class="menu-item avtorise" id="avtorise"><a style="border: none; " href="../index.php">Вихід <img src="../images/exit_32px.png" style="vertical-align: middle;" alt="Exit"></a></li>
         </div>
      </header>

      <script>
         'use strict';
         $(document).ready(function() {
            $('#avtorise').on('click', () => {
               localStorage.clear();
            });
         })

         const nameDelSymbol = (text) => {   

            text = text.split('');

            for (let i = 0; i < text.length; i++) {
               if (text[i] == "'" || text[i] == ["`"] || text[i] == '"' || text[i] == "^" || text[i] == "*" || text[i] == "~") {
         
                  text[i] = "’";
               }
            }

            text = text.join('');

            return text;
         };

         const nameSpecialty = (text) => {   

            text = text.split('');

            for (let i = 0; i < text.length; i++) {
               if (text[i] == "'" || text[i] == ["`"] || text[i] == "^" || text[i] == "*" || text[i] == "~") {

                  text[i] = "’";
               }
               if (text[i] == '"') {
                  text[i] = "«»";
               }
            }

            text = text.join('');

            return text;
         };
      </script>

      <!-- header - end -->
      <!-- main - start -->

      <div id="filter-table-mark-admin" class="add-group-teacher order order-filter-table-mark-admin" style="display: none;">
            <div class="modal-window-block">
               <div class="modal-window">
                  <div class="header-text">Фільтри</div>
                  <div class="x-filter-table-mark-admin x">×</div>
               </div>
               <div class="content" style="
                                 justify-content: unset;"
               >
                  <form id="form-filter-mark-admin" class="form-modal" 
                           style="justify-content: unset;
                                 text-align: unset;
                                 display: block;"
                  >

                  </form>
                  <div id="filter-message"></div>
                  <script>
                     $(document).ready(function() {
                        $('body').on("change", '#form-filter-mark-admin', function(e) {
                           
                           var data = $('#form-filter-mark-admin').serialize();
                           var pair = localStorage.getItem('pair');
                           data += "&pair="+pair;

                           $.ajax({
                              type: "POST",
                              url: "../php/mark-table-kurator.php", 
                              data: data,

                              success: function(data) {
                                 $('#block-table-mark-admin').html(data);
                              }
                           });
                        });
                     })
                  </script>
               </div>
            </div>
      </div>

      <div id="filter-table-visit" class="add-group-teacher order order-filter-table-visit" style="display: none;">
            <div class="modal-window-block" style="height: 200px;">
               <div class="modal-window">
                  <div class="header-text">Фільтри</div>
                  <div class="x-filter-table-visit x">×</div>
               </div>
               <div class="content" style="
                                 justify-content: unset;"
               >
                  <form id="form-table-filter-visit" class="form-modal">
                     від
                     <input type="date" name="visit-filter-start" id="visit-filter-start">
                     до
                     <input type="date" name="visit-filter-finish" id="visit-filter-finish">

                  </form>
                  <div id="filter-message-visit"></div>
                  <script>
                     $(document).ready(function() {
                        $('body').on("change", "#form-table-filter-visit", function(e) {
                           e.preventDefault();

                           var dayStart, dayFinish, year, month, day;

                           var data = localStorage.getItem('group');
                           data = "group="+data;

                           dayStart = $('#visit-filter-start').val();
                           dayFinish = $('#visit-filter-finish').val();
                           data += "&dayStart=" + dayStart + "&dayFinish=" + dayFinish;

                           $.ajax({
                              type: "POST",
                              url: "../php/visit-table-admin.php",
                              data: data,
                              success: function (data) {
                                 $('#block-table-visit').html(data);
                              }
                           });
                        });
                     })
                  </script>
               </div>
            </div>
      </div>
      <main>
         <div class="content">
            <div id="menu-sidebar">
               <ol>
                  <li class="menu-item-sidebar user" id="user"><div>Користувачі</div><img src="../images/menu-png/user.png" alt="user"></li>
                  <li class="menu-item-sidebar db-student" id="db-student"><div>DataBase "Студент"</div><img src="../images/menu-png/teacher_student.png" alt="db-student"></li>
                  <li class="menu-item-sidebar db-teacher" id="db-teacher"><div>DataBase "Викладач"</div><img src="../images/menu-png/teacher_student.png" alt="db-teacher"></li>
                  <li class="menu-item-sidebar jurnal" id="jurnal"><div>Журнал</div><img src="../images/menu-png/jurnal.png" alt="jurnal"></li>
                  <li class="menu-item-sidebar visit" id="visit"><div>Відвідування</div><img src="../images/menu-png/visit.png" alt="visiting"></li>
                  <li class="menu-item-sidebar block-control" id="block-control"><div>Контроль ведення журналів</div><img src="../images/menu-png/block.png" alt="blocks"></li>
               </ol>
            </div>
            <div id="active-zone">
               <div id="user-zone" class="block-active-zone">
                  <div style="width:100%; text-align: center;"><h2 style="width:100%"><p class="block-text" style="width:100%">Адміни</p></h2></div><hr>
                  <!--              -->
                  <div class="c">
                        <h3><p class="block-text">Додати адміна</p></h3><hr>
                        <div class="zone">
                           
                        
                           <form action="index.php" method="post" id="add-user-active">
                              <label>ПІБ</label>
                              <div>
                                 <input type="text" name="surname" id="surname-user-add" class="active-input" placeholder="Прізвище" oninput="this.value = nameDelSymbol(this.value);" 
                                          pattern="[A-ZА-Я ІЇЄҐ]{1}[’]?[a-zа-я іїєґ]+[’]?[a-zа-я іїєґ]+" title="Іванов або Лук’янов" required>
                                 <input type="text" name="name" id="name-user-add" class="active-input" placeholder="Ім’я" oninput="this.value = nameDelSymbol(this.value);" 
                                          pattern="[A-ZА-Я ІЇЄҐ]{1}[’]?[a-zа-я іїєґ]+[’]?[a-zа-я іїєґ]+" title="Іван або Лук’ян" required>
                                 <input type="text" name="middle-name" id="middle-name-user-add" class="active-input" placeholder="По батькові" oninput="this.value = nameDelSymbol(this.value);" 
                                          pattern="[A-ZА-Я ІЇЄҐ]{1}[’]?[a-zа-я іїєґ]+[’]?[a-zа-я іїєґ]+" title="Іванович або Лук’янович" required>
                              </div>

                              <div><label style="margin-right: 0;" for="nickname" class="active-label">Login</label><sup style="color: skyblue">*поле не обо'вязкове</sup></div>
                                 <input type="text" name="nickname" id="nickname-user-add" class="active-input" oninput="this.value = nameDelSymbol(this.value);" 
                                          pattern="[A-ZА-Я ІЇЄҐ]{1}[’]?[a-zа-я іїєґ]+[’]?[a-zа-я іїєґ]+\s?[A-ZА-Яa-zа-я ІЇЄҐіїєґ.]+" title="Іванов І.І. або Лук’янов І.І." placeholder="Іванов І І">

                              <label for="passwords" class="active-label">Пароль</label>   
                                 <input type="text" name="passwords" id="passwords-user-add" class="active-input" required>
                              <label for="privilege" class="active-label">Тип користувача</label>   
                                 <select name="privilege" id="privilege-user-add" class="active-input" required>
                                    <option></option>
                                    <option value="admin_site">Адміністратор сайту</option>
                                    <option value="admin">Адміністрація коледжу</option>
                                 </select>
                              <input type="submit" value="Додати" name="add" id="add">
                           </form>

                           <div id="add-user-messange" style="width: 90%"></div>

                           <script>
                           $(document).ready(function () {
                              $('#add-user-active').on('submit', function(event) {
                                 event.preventDefault(); 

                                 var surname = $('#surname-user-add').val();
                                 var name = $('#name-user-add').val();
                                 var middle_name = $('#middle-name-user-add').val();
                                 var nickname = $('#nickname-user-add').val();
                                 var passwords = $('#passwords-user-add').val();
                                 var privilege = $('#privilege-user-add').val();
                                 
                                 $.post("../php/add-user.php",{surname, name, middle_name, nickname, passwords, privilege}) 

                                 .done(function(data){
                                    
                                    $('#add-user-messange').html(data);

                                    $('#surname-user-add').val("");
                                    $('#name-user-add').val("");
                                    $('#middle-name-user-add').val("");
                                    $('#nickname-user-add').val("");
                                    $('#passwords-user-add').val("");
                                    $('#privilege-user-add').val("");
                                 });
                              })
                           })
                           </script>
                           
                        </div>
                  </div>
                  <!--              -->
                  <div class="c">
                     <h3><p class="block-text">Видалити адміна</p></h3><hr>
                     <div class="zone">
                        <form action="index.php" method="post" id="delete-user-active">
                           <label for="nickname" class="active-label">Логін користувача</label>
                              <input type="search" name="nickname" id="nickname-user-delete" class="active-input" list="nicknames-list" oninput="this.value = nameDelSymbol(this.value);" 
                                       pattern="[A-ZА-Я ІЇЄҐ]{1}[’]?[a-zа-я іїєґ]+[’]?[a-zа-я іїєґ]+\s?[A-ZА-Яa-zа-я ІЇЄҐіїєґ.]+" title="Іванов І.І. або Лук’янов І.І." placeholder="Іванов Іван Іванович" required>
                              <datalist id="nicknames-list">
                              <?php
                                $sql = $adminQuery->SelectUsers();
                                $r = 0;
                                 while ($r < count($sql)) {
                                    echo "<option value = '".$sql[$r]['nickname']."'>".$sql[$r]['surname']." ".$sql[$r]['name']." ".$sql[$r]['middle_name']."</option>";
                                    $r++;
                                 }

                              ?>
                              </datalist>
                           <input type="submit" value="Видалити" name="delete" id="delete">
                        </form>
                           
                        <div id="delete-user-messange" style="width: 90%"></div>

                        <script>
                           $(document).ready(function () {
                              $('#delete-user-active').submit( (event) => {
                                 event.preventDefault(); 

                                 var nickname = $('#nickname-user-delete').val();

                                 $.post("../php/delete-user.php",{nickname}) 
                                  
                                 .done(function(data){
                                    
                                    $('#delete-user-messange').html(data);
                                    
                                    $('#nickname-user-delete').val("");
                                    
                                 });
                              
                              })
                           })
                        </script>



                     </div>
                  </div>
                  <!--              -->
                  <div class="c">
                     <h3><p class="block-text">Змінити пароль користувача</p></h3><hr>
                     <div class="zone">
                        <form action="index.php" method="post" id="change-user-active">
                           <label for="nickname" class="active-label">Логін користувача</label>
                              <input type="search" name="nickname" id="nickname-user-pass" class="active-input" list="nickname-list-pass" oninput="this.value = nameDelSymbol(this.value);" 
                                 pattern="[A-ZА-Я ІЇЄҐ]{1}[’]?[a-zа-я іїєґ]+[’]?[a-zа-я іїєґ]+\s{1}[A-ZА-Яa-zа-я ІЇЄҐіїєґ.]+" title="Іванов І.І. або Лук’янов І.І." placeholder="Іванов І І" required>
                              <datalist id="nickname-list-pass">
                              <?php
                                $sql = $adminQuery->SelectUsers();

                                 $r = 0;
                                 while ($r < count($sql)) {
                                    echo "<option value = '".$sql[$r]['nickname']."'>".$sql[$r]['surname']." ".$sql[$r]['name']." ".$sql[$r]['middle_name']."</option>";                                    
                                    $r++;
                                 }

                                    $sql = $adminQuery->SelectTeachers();
                                 $r = 0;
                                 while ($r < count($sql)) {
                                    echo "<option value = '".$sql[$r]['nickname']."'>".$sql[$r]['surname']." ".$sql[$r]['name']." ".$sql[$r]['middle_name']."</option>";                                    
                                    $r++;
                                 }

                                 try {      
                                    $sql = $adminQuery->SelectStudents();
                                 } catch (Exception $e) {
                                    echo "Помилка виконання! Зверніться до Адміністратора сайту!";    
                                 }
                                 $r = 0;
                                 while ($r < count($sql)) {
                                    echo "<option value = '".$sql[$r]['nickname']."'>".$sql[$r]['surname']." ".$sql[$r]['name']." ".$sql[$r]['middle_name']."</option>";                                    
                                    $r++;
                                 }
                              ?>
                              </datalist>
                           <label for="passwords" class="active-label">Вкажіть пароль</label>
                              <input type="text" name="passwords" id="passwords-user-pass" required>
                           <input type="submit" value="Змінити" name="change" id="change">
                        </form>

                        <div id="pass-down-user-messange" style="width: 90%"></div>

                        <script>
                           $(document).ready(function () {
                              $('#change-user-active').submit( (event) => {
                                 event.preventDefault(); 

                                 var nickname = $('#nickname-user-pass').val();
                                 var passwords = $('#passwords-user-pass').val();
                                 
                                 $.post("../php/password-down-user.php",{nickname, passwords}) 

                                 .done(function(data){
                                    
                                    $('#pass-down-user-messange').html(data);

                                    $('#nickname-user-pass').val("");
                                    $('#passwords-user-pass').val("");
                                 });
                              
                              })
                           })
                           </script>
                        


                     </div>
                  </div>
               </div>
               <div id="db-student-zone" class="block-active-zone">
                  <center style="width:100%"><h2 style="width:100%"><p class="block-text" style="width:100%">Групи</p></h2></center><hr>
                  <div class="c">
                     <h3><p class="block-text">Додати групу</p></h3><hr>
                     <div class="zone">
                        <form action="index.php" method="post" id="add-group-active">
                           <label for="name" class="active-label">Назва групи</label>
                              <input type="text" name="name" id="name-group-add" class="active-input" oninput="this.value = nameDelSymbol(this.value);" 
                                       pattern="[0-9]{1,3}[-]?[a-zа-я іїєґ]{1,4}" title="110-і" required>
                           <label for="specialty" class="active-label">Спеціальність</label>  
                              <input type="search" name="specialty" id="specialty-group-add" class="active-input" list="specialty-list" oninput="this.value = nameDelSymbol(this.value);" 
                                       pattern="[0-9]+" title="Надсилати тільки цифри, але вводити можна назву" required>
                              <datalist id="specialty-list">
                                 <?php
                                $sql = $adminQuery->SelectSpecialitiesInIDRange();
                                 
                                 $r = 0;
                                 while ($r < count($sql)) {
                                    echo "<option value='".$sql[$r]['id']."'>".$sql[$r]['spec_number']." - ".$sql[$r]['name']."</option>";
                                    $r++;
                                 }
                                 ?>
                              </datalist>
                           <label for="kurator" class="active-label">Куратор</label>   
                              <input type="search" name="kurator" id="kurator-group-add" class="active-input" list="kurator-list" oninput="this.value = nameDelSymbol(this.value);" 
                                       pattern="[0-9]+" title="Надсилати тільки цифри, але вводити можна назву" required>
                              <datalist id="kurator-list">
                                 <?php

                                 $sql = $adminQuery->SelectTeachers();
                                 
                                 $r = 0;
                                 while ($r < count($sql)) {
                                    echo "<option value='".$sql[$r]['id']."'>".$sql[$r]['surname']." ".$sql[$r]['name']." ".$sql[$r]['middle_name']."</option>";
                                    $r++;
                                 }
                                 ?>
                              </datalist>
                           <input type="submit" value="Додати" name="add-group" id="add-group">
                           
                        </form>

                        <div id="add-group-messange" style="width: 90%"></div>

                           <script>
                           $(document).ready(function () {
                              $('#add-group-active').submit( (event) => {
                                 event.preventDefault(); 

                                 var name = $('#name-group-add').val();
                                 var specialty = $('#specialty-group-add').val();
                                 var kurator = $('#kurator-group-add').val();
                                 
                                 $.post("../php/add-group.php",{name, specialty, kurator}) 

                                 .done(function(data){
                                    
                                    $('#add-group-messange').html(data);

                                    $('#name-user-add').val("");
                                    $('#specialty-group-add').val("");
                                    $('#kurator-group-add').val("");
                                 });
                              })
                           })
                           </script>
                     </div>
                  </div>
                  <!--     -->   
                  <div class="c">
                     <h3><p class="block-text">Видалити групу</p></h3><hr>
                     <div class="zone">
                        <form action="index.php" method="post" id="delete-group-active">
                           <label for="group" class="active-label">Група</label>
                              <input type="search" name="group" id="group-delete" class="active-input" 
                                       list="group-list" oninput="this.value = nameDelSymbol(this.value);" 
                                       pattern="[0-9]+" title="Надсилати тільки цифри, але вводити можна назву" required>
                              <datalist id="group-list">
                                 <?php

                                 $sql = $adminQuery->SelectGroups();
                                 
                                 $r = 0;
                                 while ($r < count($sql)) {
                                    echo "<option value='".$sql[$r]['id']."'>".$sql[$r]['name']."</option>";
                                    $r++;
                                 }

                                 ?>
                              </datalist>
                           <input type="submit" value="Видалити" name="delete-group" id="delete-group">
                        </form>

                        <div id="delete-group-messange" style="width: 90%"></div>

                        <script>
                           $(document).ready(function () {
                              $('#delete-group-active').submit( (event) => {
                                 event.preventDefault(); 

                                 var group = $('#group-delete').val();
                                 
                                 $.post("../php/delete-group.php",{group}) 

                                 .done(function(data){
                                    
                                    $('#delete-group-messange').html(data);

                                    $('#group-delete').val("");
                                 });
                              })
                           })
                           </script>
                     </div>
                  </div>
                  <div class="c">
                     <h3><p class="block-text">Редагування</p></h3><hr>
                     <div class="zone">
                        <div class="change-list">
                              <?php
                                 if (isset($_GET['red_group_id'])) {
                                   $sqll = $adminQuery->SelectGroupByID($_GET['red_group_id']);
                                 }
                              ?>
                           <form action="./index.php" method="post" class="form-change" id="change-group-active">
                              <input type="hidden" name="id-group-change" id="id-group-change" value="<?php if(isset($_GET['red_group_id'])) echo $sqll[0]['id']; else echo '';?>">
                              <label for="name" class="active-label">Назва групи</label>
                                 <input type="text" name="name" id="name-group-change" class="active-input" oninput="this.value = nameDelSymbol(this.value);" 
                                          pattern="[0-9]{1,3}[-]?[a-zа-я іїєґ]{1,4}" title="110-і"
                                          value="<?php if(isset($_GET['red_group_id'])) echo $sqll[0]['name']; else echo '';?>" required>
                              <label for="specialty" class="active-label">Спеціальність</label>  
                                 <input type="search" name="specialty" id="specialty-group-change" class="active-input" list="specialty-list-ch" oninput="this.value = nameDelSymbol(this.value);" 
                                          pattern="[0-9]+" title="Надсилати тільки цифри, але вводити можна назву"
                                          value="<?php if(isset($_GET['red_group_id'])) echo $sqll[0]['specialty']; else echo '';?>" required>
                                 <datalist id="specialty-list-ch">
                                    <?php

                                    $sql = $adminQuery->SelectSpecialities();
                                    
                                    $r = 0;
                                    while ($r < count($sql)) {
                                       echo "<option value='".$sql[$r]['id']."'>".$sql[0]['spec_number']." - ".$sql[$r]['name']."</option>";
                                       $r++;
                                    }
                                    ?>
                                 </datalist>
                              <label for="kurator" class="active-label">Куратор</label>   
                                 <input type="search" name="kurator" id="kurator-group-change" class="active-input" list="kurator-list-ch" oninput="this.value = nameDelSymbol(this.value);" 
                                          pattern="[0-9]+" title="Надсилати тільки цифри, але вводити можна назву"
                                          value="<?php if(isset($_GET['red_group_id'])) echo $sqll[0]['kurator']; else echo '';?>" required>
                                 <datalist id="kurator-list-ch">
                                    <?php

                                    $sql = $adminQuery->SelectTeachers();

                                    $r = 0;
                                    while ($r < count($sql)) {
                                       echo "<option value='".$sql[$r]['id']."'>".$sql[$r]['surname']." ".$sql[$r]['name']." ".$sql[$r]['middle_name']."</option>";
                                       $r++;
                                    }
                                    ?>
                                 </datalist>
                              <input type="submit" value="Змінити" name="change-group" id="change-group">
                           </form>
                           
                           <div id="change-group-messange" style="width: 90%"></div>

                           <script>
                           $(document).ready(function () {
                              $('#change-group-active').submit( (event) => {
                                 event.preventDefault(); 

                                 var id = $('#id-group-change').val();
                                 var name = $('#name-group-change').val();
                                 var specialty = $('#specialty-group-change').val();
                                 var kurator = $('#kurator-group-change').val();
                                 
                                 $.post("../php/change-group.php",{id, name, specialty, kurator}) 

                                 .done(function(data){
                                    console.log('data: ', data);
                                    
                                    $('#change-group-messange').html(data);

                                    $('#id-group-change').val("");
                                    $('#name-group-change').val("");
                                    $('#specialty-group-change').val("");
                                    $('#kurator-group-change').val("");
                                 });
                              })
                           })
                           </script>
                           <hr>
                           <label for="viewgroupsch">Оберіть курс</label>
                              <select name="viewgroupsch" id="viewgroupsch">
                                 <option></option>
                                 <option value="1">Перший</option>
                                 <option value="2">Другий</option>
                                 <option value="3">Третій</option>
                                 <option value="4">Четвертий</option>
                              </select>
                           <div class="table-change">
                              <table class="table-change-group table" style="border: 1px solid rgb(59, 128, 255) ">
                                 <thead>
                                    <tr>
                                       <th style="border:none;" data-type="text-short">Назва групи</span></th>
                                       <th style="border:none;" data-type="text-short">Спеціальність</span></th>
                                       <th style="border:none;" data-type="text-long">Куратор</span></th>
                                       <th style="border:none;" data-type="text-short">Редагування</span></th>
                                    </tr>
                                 </thead>
                                 <tbody class="tbody-group">
                                 
                                    <script>
                                       $(document).ready(function () {$('#viewgroupsch').change(function() {var groupValues = $('#viewgroupsch').val();$('.tbody-group').empty();$.post("../php/table-group.php",{groupValues}) .done(function(data){$('.table-change-group').append(data);});})})
                                    </script>
                                 </tbody>
                              </table>
                           </div>
                           <div class="block-vorry-buttom" style="background: #9B001B; padding-top: 6px; margin-top: 6px;">
                              <label>Перевести групи на наступний курс:</label>
                                 <input type="button" value="Перевести" id="group-perenos" name="group-perenos">
                                 <script>
                                    $(document).ready(function () {
                                       $('body').on('click', '#group-perenos', function (event) {
                                          event.preventDefault();

                                          var confir = confirm("Після перенесення данні повернути буде НЕМОЖЛИВО! Бажаєте продовжити?");

                                          if (confir === true) {
                                             $.ajax({
                                                type: "POST",
                                                url: "../php/perenos-yrear-group.php", 
                                                success: function(data) {
                                                   alert("Групи перенесено!");
                                                }
                                             });
                                          }
                                       })
                                    })
                                 </script>
                           </div>
                        </div>
                     </div>
                  </div>
                  <center style="width:100%"><h2 style="width:100%"><p class="block-text" style="width:100%">Студенти</p></h2></center><hr>
                  <div class="c">
                     <h3><p class="block-text">Додати студента</p></h3><hr>
                     <div class="zone">
                        <form action="index.php" method="post" id="add-student-active">
                        <label>ПІБ</label>
                              <div>
                                 <input type="text" name="surname" id="surname-student-add" class="active-input" placeholder="Прізвище" oninput="this.value = nameDelSymbol(this.value);" 
                                          pattern="[A-ZА-Я ІЇЄҐ]{1}[’]?[a-zа-я іїєґ]+[’]?[a-zа-я іїєґ]+" title="Іванов або Лук’янов" required>
                                 <input type="text" name="name" id="name-student-add" class="active-input" placeholder="Ім’я" oninput="this.value = nameDelSymbol(this.value);" 
                                          pattern="[A-ZА-Я ІЇЄҐ]{1}[’]?[a-zа-я іїєґ]+[’]?[a-zа-я іїєґ]+" title="Іван або Лук’ян" required>
                                 <input type="text" name="middle-name" id="middle-name-student-add" class="active-input" placeholder="По батькові" oninput="this.value = nameDelSymbol(this.value);" 
                                          pattern="[A-ZА-Я ІЇЄҐ]{1}[’]?[a-zа-я іїєґ]+[’]?[a-zа-я іїєґ]+" title="Іванович або Лук’янович" required>
                              </div>
                           <label for="passwords" class="active-label">Пароль</label>   
                              <input type="text" name="passwords" id="passwords-student-add" class="active-input" required>
                           <label for="study-form" class="active-label">Форма навчання</label>   
                              <select name="study-form" id="study-form-student-add" class="active-input" required>
                                 <option></option>
                                 <option value="бюджет">Бюджет</option>
                                 <option value="контракт">Контракт</option>
                              </select>
                           <label for="group" class="active-label">Група</label>
                              <input type="search" name="group" id="group-student-add" class="active-input" list="group-list-stud-add" oninput="this.value = nameDelSymbol(this.value);" required>
                              <datalist id="group-list-stud-add">
                                 <?php
                                 $sql = $adminQuery->SelectGroups();

                                 $r = 0;
                                 while ($r < count($sql)) {
                                    echo "<option value='".$sql[$r]['id']."'>".$sql[$r]['name']."</option>";
                                    $r++;
                                 }

                                 ?>
                              </datalist>
                           <input type="submit" value="Додати" name="add-student" id="add-student">
                           
                        </form>

                        <div id="add-student-messange" style="width: 90%"></div>

                        <script>
                           $(document).ready(function () {
                              $('#add-student-active').submit( (event) => {
                                 event.preventDefault(); 

                                 var name = $('#name-student-add').val();
                                 var surname = $('#surname-student-add').val();
                                 var middle_name = $('#middle-name-student-add').val();
                                 var passwords = $('#passwords-student-add').val();
                                 var study_form = $('#study-form-student-add').val();
                                 var group = $('#group-student-add').val();

                                 
                                 $.post("../php/add-student.php",{name, surname, middle_name, passwords, study_form, group}) 

                                 .done(function(data){
                                    
                                    $('#add-student-messange').html(data);

                                    $('#name-student-add').val("");
                                    $('#surname-student-add').val("");
                                    $('#middle-name-student-add').val("");
                                    $('#passwords-student-add').val("");
                                    $('#study-form-student-add').val("");
                                    $('#group-student-add').val("");
                                 });
                              })
                           })
                        </script>
                       
                     </div>
                  </div>
                  <!--     -->
                  <div class="c">
                     <h3><p class="block-text">Видалити студента</p></h3><hr>
                     <div class="zone">
                        <form action="index.php" method="post" id="delete-student-active">
                           <label for="id-student-delete" class="active-label">Студент</label>   
                              <input type="search" name="id-student-delete" id="id-student-delete" class="active-input" list="student-list-del" oninput="this.value = nameDelSymbol(this.value);" 
                                       pattern="[0-9]+" title="Надсилати тільки цифри, але вводити можна прізвище, імя, по батькові" required>
                              <datalist id="student-list-del">
                                 <?php

                                 $sql = $adminQuery->SelectStudents();

                                 $r = 0;
                                 while ($r < count($sql)) {
                                    echo "<option value=".$sql[$r]['id'].">".$sql[$r]['surname']." ".$sql[$r]['name']." ".$sql[$r]['middle_name']."</option>";
                                    $r++;
                                 }
                                 ?>
                              </datalist>
                           
                           <input type="submit" value="Видалити" name="delete-student" id="delete-student">
                           
                        </form>

                        <div id="delete-student-messange" style="width: 90%"></div>

                           <script>
                              $(document).ready(function () {
                                 $('#delete-student-active').submit( (event) => {
                                    event.preventDefault(); 

                                    var student = $('#id-student-delete').val();

                                    
                                    $.post("../php/delete-student.php",{student}) 

                                    .done(function(data){
                                       
                                       $('#delete-student-messange').html(data);

                                       $('#name-student-delete').val("");
                                    });
                                 })
                              })
                           </script>
                     </div>
                  </div>
                  <!--     -->
                  <div class="c">
                     <h3><p class="block-text">Редагування</p></h3><hr>
                     <div class="zone">
                        <div class="change-list">
                              <?php
                                 if (isset($_GET['red_student_id'])) {
                                    try {      
                                       $sql = $adminQuery->SelectStudentByID($_GET['red_student_id']);
                                    } catch (Exception $e) {
                                       echo "Помилка виконання! Зверніться до Адміністратора сайту!";    
                                    }
                                 }
                              ?>
                           <form action="./index.php" method="post" class="form-change" id="change-student-active">
                              <input type="hidden" name="id-student-change" id="id-student-change" value="<?php if(isset($_GET['red_student_id'])) echo $sql[0]['student_id']; else echo '';?>">
                              <label>ПІБ</label>
                                 <div>
                                    <input type="text" name="surname" id="surname-student-change" class="active-input" placeholder="Прізвище" oninput="this.value = nameDelSymbol(this.value);" 
                                             pattern="[A-ZА-Я ІЇЄҐ]{1}[’]?[a-zа-я іїєґ]+[’]?[a-zа-я іїєґ]+" title="Іванов або Лук’янов"
                                             value="<?php if (isset($_GET['red_student_id'])) echo $sql[0]['student_surname']; else echo '';?>" required>
                                    <input type="text" name="name" id="name-student-change" class="active-input" placeholder="Ім’я" oninput="this.value = nameDelSymbol(this.value);" 
                                             pattern="[A-ZА-Я ІЇЄҐ]{1}[’]?[a-zа-я іїєґ]+[’]?[a-zа-я іїєґ]+" title="Іван або Лук’ян"
                                             value="<?php if (isset($_GET['red_student_id'])) echo $sql[0]['student_name']; else echo '';?>" required>
                                    <input type="text" name="middle-name" id="middle-name-student-change" class="active-input" placeholder="По батькові" oninput="this.value = nameDelSymbol(this.value);" 
                                             pattern="[A-ZА-Я ІЇЄҐ]{1}[’]?[a-zа-я іїєґ]+[’]?[a-zа-я іїєґ]+" title="Іванович або Лук’янович"
                                             value="<?php if (isset($_GET['red_student_id'])) echo $sql[0]['student_middle_name']; else echo '';?>" required>
                                 </div>
                              <label for="study-form" class="active-label">Форма навчання</label>   
                                 <input type="search" name="study-form" id="study-form-student-change" class="active-input"
                                    value="<?php if (isset($_GET['red_student_id'])) echo $sql[0]['student_study_form']; else echo '';?>" list="change-group-list"
                                    oninput="this.value = nameDelSymbol(this.value);" pattern="бюджет|контракт" title="'бюджет' або 'контракт'" required>
                                    <datalist id="change-group-list">
                                       <option></option>
                                       <option value="бюджет"></option>
                                       <option value="контракт"></option>
                                    </datalist>
                                 </select>
                              <label for="group" class="active-label">Група</label>
                                 <input type="search" name="group" id="group-student-change" class="active-input" list="group-list-stud-add" oninput="this.value = nameDelSymbol(this.value);"
                                 value="<?php if (isset($_GET['red_student_id'])) echo $sql[0]['group_id']; else echo '';?>" required>
                                 <datalist id="group-list-stud-add">
                                    <?php
                                    $sql = $adminQuery->SelectGroups();

                                    $r = 0;
                                    while ($r < count($sql)) {
                                       echo "<option value='".$sql[$r]['id']."'>".$sql[$r]['name']."</option>";
                                       $r++;
                                    }

                                    ?>
                                 </datalist>
                              <input type="submit" value="Змінити" name="change-student" id="change-student">
                           </form>

                           <div id="change-student-messange" style="width: 90%"></div>

                           <script>
                              $(document).ready(function () {
                                 $('#change-student-active').submit( (event) => {
                                    event.preventDefault(); 

                                    var id = $('#id-student-change').val();
                                    var name = $('#name-student-change').val();
                                    var surname = $('#surname-student-change').val();
                                    var middle_name = $('#middle-name-student-change').val();
                                    var study_form = $('#study-form-student-change').val();
                                    var group = $('#group-student-change').val();

                                    
                                    $.post("../php/change-student.php",{id, name, surname, middle_name, study_form,group}) 

                                    .done(function(data){
                                       
                                       $('#change-student-messange').html(data);

                                       $('id-student-change').val("");
                                       $('#name-student-add').val("");
                                       $('#middle-name-student-add').val("");
                                       $('#passwords-student-add').val("");
                                       $('#study-form-student-add').val("");
                                       $('#group-student-add').val("");
                                    });
                                 })
                              })
                           </script>
   	                     <hr>
                           <label for="viewgroups">Оберіть групу</label>
                                       <input type="search" name="viewgroups" id="viewgroups" list="view-group">
                                          <datalist id="view-group">
                                             <?php
                                                $sql = $adminQuery->SelectGroups();
            
                                                $r = 0;
                                                while ($r < count($sql)) {
                                                   echo "<option value='".$sql[$r]['id']."'>".$sql[$r]['name']."</option>";
                                                   $r++;
                                                }
                                             ?>
                                          </datalist>
                           <div class="table-change">
                              <table class="table-change-student table" style="border: 1px solid rgb(59, 128, 255) ">
                                 <thead>
                                    <tr>
                                       <th style="border:none;" data-type="text-long">ПІБ</span></th>
                                       <th style="border:none;" data-type="text-short">Група</span></th>
                                       <th style="border:none;" data-type="text-long">Форма навчання</span></th>
                                       <th style="border:none;" data-type="text-short">Редагування</span></th>
                                    </tr>
                                 </thead>
                                 <tbody class="tbody-student"> 
                                 <script>$(document).ready(function () {$('#viewgroups').change(function() {var groupValue = $('#viewgroups').val();$('.tbody-student').empty();$.post("../php/table-student.php",{groupValue}) .done(function(data){$('.tbody-student').html(data);});})})</script>
                                 </tbody>
                              </table>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div id="db-teacher-zone" class="block-active-zone">
                  <center style="width:100%"><h2 style="width:100%"><p class="block-text" style="width:100%">Предмети</p></h2></center><hr>
                  <div class="c">
                     <h3><p class="block-text">Додати предмет</p></h3><hr>
                     <div class="zone">
                        <form action="index.php" method="post" id="add-subject-active" enctype="multipart/form-data">
                           <label for="name-subject-add" class="active-label">Назва предмету</label>
                              <input type="text" name="name-subject-add" id="name-subject-add" class="active-input" oninput="this.value = nameDelSymbol(this.value);" 
                                 pattern="[A-ZА-Я ІЇЄҐ]{1}[’]?[a-zа-я іїєґ-,.()]+[’]?[a-zа-я іїєґ-,.()]+\s?[A-ZА-Яa-zа-я ІЇЄҐіїєґ,.-()]+" title="Об’єктно-орієнтоване програмування" required>
                           <label for="mark-system-subject-add" class="active-label">Система оцінювання</label>   
                              <select name="mark-system-subject-add" id="mark-system-subject-add" class="active-input" required>
                                 <option></option>
                                 <option value="5">5</option>
                                 <option value="12">12</option>
                                 <option value="зал">зал</option>
                              </select>
                           <label for="mark-count-teacher-add" class="active-label">Можлива кількість викладачів</label>   
                              <select name="mark-count-teacher-add" id="mark-count-teacher-add" class="active-input" required>
                                 <option value="1" selected>1</option>
                                 <option value="2">2</option>
                              </select>
                           <label for="picture-subject-add">Фон<sup style="color: skyblue">*поле не обо'вязкове</sup></label>
                              <input type="file" name="picture-subject-add" id="picture-subject-add" accept=".png, .jpg, .jpeg">
                           <input type="submit" value="Додати" name="add-subject" id="add-subject">
                        </form>
                        <div id="add-subject-messange" style="width: 90%"></div>

                           <script>
                              $(document).ready(function (e) {
                                 $('#add-subject-active').on('submit',(function(e) {
                                    e.preventDefault(); 

                                    var formData = new FormData($('#add-subject-active')[0]);
                                    //console.log('formData: ', formData);
                      
                                    $.ajax({
                                          type:'POST', 
                                          url: '../php/add-subject.php',
                                          data: formData, 
                                          cache: false, 
                                          
                                          contentType: false,
                                          processData: false,
                                          success: function(data){                  

                                             $('#add-subject-messange').html(data);
                                          },

                                          error: function(data){

                                             console.log(data);
                                          }
                                    });
                                 })); 
                              });
                           </script>
                     </div>
                  </div>
                  <!--     -->
                  <div class="c">
                     <h3><p class="block-text">Видалити предмет</p></h3><hr>
                     <div class="zone">
                        <form action="index.php" method="post" id="delete-subject-active">
                           <label for="name" class="active-label">Предмет</label>   
                              <input type="search" name="name" id="name-subject-delete" class="active-input" list="name-list" oninput="this.value = nameDelSymbol(this.value);" 
                                       pattern="[0-9]+" title="Надсилати тільки цифри, але вводити можна назву" required>
                              <datalist id="name-list">
                                 <?php
                                   $sql = $adminQuery->SelectSubjects();

                                    $r = 0;
                                    while ($r < count($sql)) {
                                       echo "<option value='".$sql[$r]['id']."'>".$sql[$r]['name']." - ".$sql[$r]['mark_system']."<option>";
                                       $r++;
                                    }

                                 ?>
                              </datalist>
                           <input type="submit" value="Видалити" name="delete-subject" id="delete-subject">
                        </form>

                        <div id="delete-subject-messange" style="width: 80%"></div>

                           <script>
                              $(document).ready(function () {
                                 $('#delete-subject-active').submit( (event) => {
                                    event.preventDefault(); 

                                    var name = $('#name-subject-delete').val();

                                    
                                    $.post("../php/delete-subject.php",{name}) 

                                    .done(function(data){
                                       
                                       $('#delete-subject-messange').html(data);

                                       $('#name-subject-delete').val("");
                                    });
                                 })
                              })
                           </script>
                     </div>
                  </div>
                  <div class="c">
                     <h3><p class="block-text">Редагування</p></h3><hr>
                     <div class="zone">
                        <div class="change-list">
                              <?php
                                 if (isset($_GET['red_subject_id'])) {
                                       $sqll = $adminQuery->SelectSubjectByID($_GET['red_subject_id']);
                                 }
                              ?>
                           <form action="./index.php" method="post" class="form-change" id="change-subject-active">
                              <input type="hidden" name="id-subject-change" id="id-subject-change" value="<?php if(isset($_GET['red_subject_id'])) echo $sqll[0]['subject_id']; else echo '';?>">
                              <label for="name-subject-change" class="active-label">Назва предмету</label>
                                 <input type="text" name="name-subject-change" id="name-subject-change" class="active-input"
                                 value="<?php if(isset($_GET['red_subject_id'])) echo $sqll[0]['subject_name']; else echo '';?>"
                                 pattern="[A-ZА-Я ІЇЄҐ]{1}[’]?[a-zа-я іїєґ-,.()]+[’]?[a-zа-я іїєґ-,.()]+\s?[A-ZА-Яa-zа-я ІЇЄҐіїєґ,.-()]+" title="Об’єктно-орієнтоване програмування" required>
                              <label for="mark-system-subject-change" class="active-label">Система оцінювання</label>   
                                 <input type="text" name="mark-system-subject-change" id="mark-system-subject-change" class="active-input" list="type-subject-change"
                                          value="<?php if(isset($_GET['red_subject_id'])) echo $sqll[0]['subject_mark_system']; else echo '';?>" 
                                          pattern="5?|12?|зал?" title="5 або 12 або 'зал'" required>
                                    <datalist id="type-subject-change">
                                       <option value="5">5</option>
                                       <option value="12">12</option>
                                       <option value="зал">зал</option>
                                    </datalist>
                              <input type="hidden" name="id-fon-change" value="<?php if(isset($_GET['red_subject_id'])) echo $sqll[0]['id_background']; else echo '';?>">
                              
                              <label for="picture-subject-change">Фон
                              <?php
                                 if (isset($_GET['red_subject_id'])) {
                                    if ($sqll[0]['image_background'] == NULL) {
                                       echo '<sup style="color: skyblue">*ви можете встановити фон!</sup>';
                                    } else {
                                       echo '<sup style="color: skyblue">*ви можете змінити фон!</sup>';
                                    }
                                 }
                              ?>
                              </label>
                                 <input type="file" name="picture-subject-change" id="picture-subject-change" accept=".png, .jpg, .jpeg" title="Дозволяються розширення .png, .jpg, .jpeg та розмір картинки не більше ніж 4 Мб">
                              <input type="submit" value="Змінити" name="change-subject" id="change-subject">
                           </form>
                           
                           <div id="change-subject-messange" style="width: 90%;"></div>

                           <script>
                              $(document).ready(function () {
                                 $('#change-subject-active').on('submit',(function(e) {
                                    e.preventDefault();

                                    var formData = new FormData($('#change-subject-active')[0]);
                                    console.log('formData: ', formData);
                                    

                                    $.ajax({
                                          type:'POST', 
                                          url: '../php/change-subject.php',
                                          data: formData, 
                                          cache: false, 
                                          
                                          contentType: false,
                                          processData: false,
                                          success:function(data){                  

                                             $('#change-subject-messange').html(data);
                                          },

                                          error: function(data){

                                             console.log("с ошибкой", data);
                                          }
                                    });
                                 }))
                              })
                           </script>
                           <hr>
                           <label for="show-subject">Предмет</label>
                              <input type="text" name="show-subject" id="show-subject" class="active-input" oninput="this.value = nameDelSymbol(this.value);" 
                                 pattern="[A-ZА-Я ІЇЄҐ]{1}[’]?[a-zа-я іїєґ-,.()]+[’]?[a-zа-я іїєґ-,.()]+\s?[A-ZА-Яa-zа-я ІЇЄҐіїєґ,.-()]+" title="Об’єктно-орієнтоване програмування" required>
                           <div class="table-change">
                              <table class="table-change-subject table" style="border: 1px solid rgb(59, 128, 255) ">
                                 <thead>
                                    <tr>
                                       <th style="border:none;" data-type="text-short">Назва предмету</span></th>
                                       <th style="border:none;" data-type="text-short">Система оцінювання</span></th>
                                       <th style="border:none;" data-type="text-long">Фон</span></th>
                                       <th style="border:none;" data-type="text-short">Редагування</span></th>
                                    </tr>
                                 </thead>
                                 <tbody class="tbody-subject">
                                    <script>
                                       $(document).ready(function () {
                                          $('#show-subject').on('input', function() {
                                             var name = $('#show-subject').val();

                                             $('.tbody-subject').empty();

                                             if (name.length != 0) {

                                                $.post("../php/table-subject.php",{name}) 
                                                .done(function(data){
                                                   $('.table-change-subject').append(data);
                                                });
                                             } else {
                                                $('.tbody-subject').empty();
                                             }
                                          })
                                       })
                                    </script>
                                 </tbody>
                              </table>
                           
                           </div>
                        </div>
                     </div>
                  </div>
                  <center style="width:100%"><h2 style="width:100%"><p class="block-text" style="width:100%">Викладачі</p></h2></center><hr>
                  <div class="c">
                     <h3><p class="block-text">Додати викладача</p></h3><hr>
                     <div class="zone">
                        <form method="post" id="add-teacher-active">
                        <label>ПІБ</label>
                              <div>
                                 <input type="text" name="surname" id="surname-teacher-add" class="active-input" placeholder="Прізвище" oninput="this.value = nameDelSymbol(this.value);" 
                                          pattern="[A-ZА-Я ІЇЄҐ]{1}[’]?[a-zа-я іїєґ]+[’]?[a-zа-я іїєґ]+" title="Іванов або Лук’янов" required>
                                 <input type="text" name="name" id="name-teacher-add" class="active-input" placeholder="Ім’я" oninput="this.value = nameDelSymbol(this.value);" 
                                          pattern="[A-ZА-Я ІЇЄҐ]{1}[’]?[a-zа-я іїєґ]+[’]?[a-zа-я іїєґ]+" title="Іван або Лук’ян" required>
                                 <input type="text" name="middle-name" id="middle-name-teacher-add" class="active-input" placeholder="По батькові" oninput="this.value = nameDelSymbol(this.value);" 
                                          pattern="[A-ZА-Я ІЇЄҐ]{1}[’]?[a-zа-я іїєґ]+[’]?[a-zа-я іїєґ]+" title="Іванович або Лук’янович" required>
                              </div>
                           <label for="passwords" class="active-label">Пароль</label>   
                              <input type="text" name="passwords" id="passwords-teacher-add" class="active-input" required>
                           <input type="submit" value="Додати">
                           
                        </form>

                        <div id="add-teacher-messange" style="width: 90%"></div>
                        
                        <script>
                        $(document).ready(function () {
                           $('#add-teacher-active').submit( (event) => {
                              event.preventDefault(); 

                              var surname = $('#surname-teacher-add').val();
                              var name = $('#name-teacher-add').val();
                              var middle_name = $('#middle-name-teacher-add').val();
                              var passwords = $('#passwords-teacher-add').val();
                              
                              $.post("../php/add-teacher.php",{surname, name, middle_name, passwords}) 

                              .done(function(data){
                                 
                                 $('#add-teacher-messange').html(data);

                                 $('#surname-teacher-add').val("");
                                 $('#name-teacher-add').val("");
                                 $('#middle-name-teacher-add').val("");
                                 $('#passwords-teacher-add').val("");
                              });
                           
                           })
                        })
                        </script>
                     </div>
                  </div>
                  <!--     -->
                  <div class="c">
                     <h3><p class="block-text">Видалити викладача</p></h3><hr>
                     <div class="zone">
                        <form action="index.php" method="post" id="delete-teacher-active">
                           <label for="teacher" class="active-label">Викладач</label>   
                              <input type="search" name="teacher" id="name-teacher-delete" class="active-input" list="-list-del" oninput="this.value = nameDelSymbol(this.value);" 
                                       pattern="[0-9]+" title="Надсилати тільки цифри, але вводити можна назву" required>
                              <datalist id="-list-del">
                                 <?php

                                 $sql = $adminQuery->SelectTeachers();

                                 $r = 0;
                                 while ($r < count($sql)) {
                                    echo "<option value=".$sql[$r]['id'].">".$sql[$r]['surname']." ".$sql[$r]['name']." ".$sql[$r]['middle_name']."</option>";
                                    $r++;
                                 }
                                 ?>
                              </datalist>
                           
                           <input type="submit" value="Видалити" name="delete-teacher" id="delete-teacher">
                           
                        </form>

                        <div id="delete-teacher-messange" style="width: 90%"></div>

                           <script>
                              $(document).ready(function () {
                                 $('#delete-teacher-active').submit( (event) => {
                                    event.preventDefault(); 

                                    var teacher = $('#name-teacher-delete').val();

                                    
                                    $.post("../php/delete-teacher.php",{teacher}) 

                                    .done(function(data){
                                       
                                       $('#delete-teacher-messange').html(data);

                                       $('#name-teacher-delete').val("");
                                    });
                                 })
                              })
                           </script>
                     </div>
                  </div>
                  <center style="width:100%"><h2 style="width:100%"><p class="block-text" style="width:100%">Дисципліни</p></h2></center><hr>
                  <div class="c">
                     <h3><p class="block-text">Додати дисципліну</p></h3><hr>
                     <div class="zone">
                        <form action="index.php" method="post" id="add-discipline-active">
                           <label for="teacher">Викладач</label>
                              <input type="search" name="teacher" id="teacher-discipline-add" class="active-input" list="discipline-teacher-list-end" oninput="this.value = nameDelSymbol(this.value);" 
                                       pattern="[0-9]+" title="Надсилати тільки цифри, але вводити можна назву" required>
                                 <datalist id="discipline-teacher-list-end">
                                    <?php
                                   $sql = $adminQuery->SelectTeachers();

                                    $r = 0;
                                    while ($r < count($sql)) {
                                       echo "<option value='".$sql[$r]['id']."'>".$sql[$r]['surname']." ".$sql[$r]['name']." ".$sql[$r]['middle_name']."<option>";
                                       $r++;
                                    }
                                    ?>

                              </datalist>
                           <label for="subject">Предмет</label>
                              <input type="search" name="subject" id="subject-discipline-add" class="active-input" list="discipline-subject-list-end" oninput="this.value = nameDelSymbol(this.value);" 
                                       pattern="[0-9]+" title="Надсилати тільки цифри, але вводити можна назву" required>
                                 <datalist id="discipline-subject-list-end">
                                    <?php
                                    try {      
                                       $sql = $adminQuery->SelectSubjects();
                                    } catch (Exception $e) {
                                       echo "Помилка виконання! Зверніться до Адміністратора сайту!";    
                                    }

                                    $r = 0;
                                    while ($r < count($sql)) {
                                       echo "<option value='".$sql[$r]['id']."'>".$sql[$r]['name']." (".$sql[$r]['mark_system']." бальна)<option>";
                                       $r++;
                                    }
                                    ?>

                              </datalist>
                           <input type="submit" value="Додати" name="add-discipline" id="add-discipline">
                           
                        </form>

                        
                        <div id="add-discipline-messange" style="width: 90%"></div>
                        
                        <script>
                        $(document).ready(function () {
                           $('#add-discipline-active').submit( (event) => {
                              event.preventDefault(); 

                              var teacher = $('#teacher-discipline-add').val();
                              var subject = $('#subject-discipline-add').val();
                              
                              $.post("../php/add-discipline.php",{teacher, subject}) 

                              .done(function(data){
                                 
                                 $('#add-discipline-messange').html(data);

                                 $('#teacher-discipline-add').val("");
                                 $('#subject-discipline-add').val("");
                              });
                           })
                        })
                        </script>
                     </div>
                  </div>
                  <!--     -->
                  <div class="c">
                     <h3><p class="block-text">Видалити дисципліну</p></h3><hr>
                     <div class="zone">
                        <form action="index.php" method="post" id="delete-discipline-active">
                           <label for="discipline" class="active-label">Дисципліна</label>   
                              <input type="search" name="discipline" id="discipline-discipline-delete" class="active-input" list="discipline-list-del" oninput="this.value = nameDelSymbol(this.value);" 
                                       pattern="[0-9]+" title="Надсилати тільки цифри, але вводити можна назву" required>
                              <datalist id="discipline-list-del">
                                 <?php

                                $sql = $adminQuery->SelectFullSubjectInfo();

                                 $r = 0;
                                 while ($r < count($sql)) {
                                    echo "<option value=".$sql[$r]['descipline_id'].">".$sql[$r]['teacher_surname']." ".$sql[$r]['teacher_name']." ".$sql[$r]['teacher_middle_name']." - ".
                                    $sql[$r]['subject_name']." (".$sql[$r]['subject_mark_system']." бальна)</option>";
                                    $r++;
                                 }
                                 ?>
                              </datalist>
                                                        
                           <input type="submit" value="Видалити" name="delete-discipline" id="delete-discipline">
                           
                        </form>

                        
                        <div id="delete-discipline-messange" style="width: 90%"></div>
                        
                        <script>
                        $(document).ready(function () {
                           $('#delete-discipline-active').submit( (event) => {
                              event.preventDefault(); 

                              var teacher = $('#discipline-discipline-delete').val();
                              
                              $.post("../php/delete-descipline.php",{teacher}) 

                              .done(function(data){
                                 
                                 $('#delete-discipline-messange').html(data);

                                 $('#teacher-discipline-delete').val("");
                              });
                           
                           })
                        })
                        </script>

                       
                     </div>
                  </div>
                  <center style="width:100%"><h2 style="width:100%"><p class="block-text" style="width:100%">Спеціальності</p></h2></center><hr>
                  <div class="c">
                     <h3><p class="block-text">Додати спеціальність</p></h3><hr>
                     <div class="zone">
                        <form action="index.php" method="post" id="add-specialty-active">
                           <label for="name" class="active-label">Назва спеціальності</label>
                              <input type="text" name="name" id="name-specialty-add" class="active-input" oninput="this.value = nameSpecialty(this.value);" 
                                 pattern="[A-ZА-Я ІЇЄҐ]{1}[’]?[a-zа-я іїєґ-,.()]+[’]?[a-zа-я іїєґ-,.()]+\s?[A-ZА-Яa-zа-я ІЇЄҐіїєґ,.-()]+" title="Комп’ютерні науки" required>
                           <label for="number" class="active-label">Номер спеціальності</label>   
                              <input type="text" name="number" id="number-specialty-add" class="active-input" oninput="this.value = nameDelSymbol(this.value);" 
                                 pattern="[0-9 ()]+" title="122" required>
                           <label for="department-head" class="active-label">Зав. відділення</label>   
                              <input type="search" name="department-head" id="department-head-specialty-add" class="active-input" list="department-head-list-end" oninput="this.value = nameDelSymbol(this.value);" 
                                       pattern="[0-9]+" title="Надсилати тільки цифри, але вводити можна прізвище та ініціали" required>
                              <datalist id="department-head-list-end">
                                 <?php

                                $sql = $adminQuery->SelectTeachers();

                                 $r = 0;
                                 while ($r < count($sql)) {
                                    echo "<option value='".$sql[$r]['id']."'>".$sql[$r]['surname']." ".$sql[$r]['name']." ".$sql[$r]['middle_name']."</option>";
                                    $r++;
                                 }

                                 ?>
                              </datalist>
                           <input type="submit" value="Додати" name="add-specialty" id="add-specialty">
                        </form>

                        
                        <div id="add-specialty-messange" style="width: 90%"></div>
                        
                        <script>
                        $(document).ready(function () {
                           $('#add-specialty-active').submit( (event) => {
                              event.preventDefault();

                               const name = $('#name-specialty-add').val();
                               const number = $('#number-specialty-add').val();
                               const department_head = $('#department-head-specialty-add').val();

                               $.post("../php/add-specialty.php",{name, number,department_head})

                              .done(function(data){
                                 
                                 $('#add-specialty-messange').html(data);

                                 $('#name-specialty-add').val("");
                                 $('#number-specialty-add').val("");
                                 $('#department-head-specialty-add').val("");

                              });
                           
                           })
                        })
                        </script>

                        
                     </div>
                  </div>
                  <!--     -->
                  <div class="c">
                     <h3><p class="block-text">Видалити спеціальність</p></h3><hr>
                     <div class="zone">
                        <form action="index.php" method="post" id="delete-specialty-active">
                           <label for="specialty" class="active-label">Cпеціальність</label>   
                              <input type="search" name="specialty" id="specialty-specialty-delete" class="active-input" list="department-head-list-end-s" oninput="this.value = nameDelSymbol(this.value);" 
                                       pattern="[0-9]+" title="Надсилати тільки цифри, але вводити можна назву" required>
                              <datalist id="department-head-list-end-s">
                                 <?php
                                    $sql = $adminQuery->SelectSpecialities();

                                    $r = 0;
                                    while ($r < count($sql)) {
                                       echo "<option value='".$sql[$r]['id']."'>".$sql[$r]['spec_number']." - ".$sql[$r]['name']."</option>";
                                       $r++;
                                    }
                                 ?>
                              </datalist>
                           <input type="submit" value="Видалити" name="delete-specialty" id="delete-specialty">
                           
                        </form>

                        <div id="delete-specialty-messange" style="width: 90%"></div>

                        <script>
                           $(document).ready(function () {
                              $('#delete-specialty-active').submit( (event) => {
                                 event.preventDefault(); 

                                 var name = $('#specialty-specialty-delete').val();
                                 
                                 $.post("../php/delete-specialty.php",{name}) 

                                 .done(function(data){
                                    
                                    $('#delete-specialty-messange').html(data);

                                    $('#specialty-specialty-delete').val("");

                                 });
                              
                              })
                           })
                           </script>

                        
                     </div>
                  </div>
                  <div class="c">
                     <h3><p class="block-text">Редагування</p></h3><hr>
                     <div class="zone">
                        <div class="change-list">
                              <?php
                                if (isset($_GET['red_spec_id'])) {
                                   $sql = $adminQuery->SelectSpecialityByID($_GET['red_spec_id']);
                                 }
                              ?>
                           <form action="./index.php" method="post" class="form-change" id="change-specialty-active">
                              <input type="hidden" name="id-specialty-change" id="id-specialty-change" value="<?php if(isset($_GET['red_spec_id'])) echo $sql[0]['id']; else echo '';?>">
                              <label for="name" class="active-label">Назва спеціальності</label>
                                 <input type="text" name="name" id="name-specialty-change" class="active-input" oninput="this.value = nameSpecialty(this.value);" 
                                    pattern="[A-ZА-Я ІЇЄҐ]{1}[’]?[a-zа-я іїєґ-,.()]+[’]?[a-zа-я іїєґ-,.()]+\s?[A-ZА-Яa-zа-я ІЇЄҐіїєґ,.-()]+" title="Комп’ютерні науки"
                                    value="<?php if(isset($_GET['red_spec_id'])) echo $sql[0]['name']; else echo '';?>" required>
                              <label for="number-change" class="active-label">Номер спеціальності</label>   
                                 <input type="text" name="number-change" id="number-specialty-change" class="active-input" oninput="this.value = nameDelSymbol(this.value);" 
                                    pattern="[0-9 ()]+" title="122" value="<?php if(isset($_GET['red_spec_id'])) echo $sql[0]['spec_number']; else echo '';?>" required>
                              <label for="department-head" class="active-label">Зав. відділення</label>   
                                 <input type="search" name="department-head-change" id="department-head-specialty-change" class="active-input" list="department-head-list-chang" oninput="this.value = nameDelSymbol(this.value);" 
                                          pattern="[0-9]+" title="Надсилати тільки цифри, але вводити можна прізвище та ініціали" 
                                          value="<?php if(isset($_GET['red_spec_id'])) echo $sql[0]['department_head']; else echo '';?>" required>
                                 <datalist id="department-head-list-chang">
                                    <?php

                                   $sql = $adminQuery->SelectTeachers();

                                    $r = 0;
                                    while ($r < count($sql)) {
                                       echo "<option value='".$sql[$r]['id']."'>".$sql[$r]['surname']." ".$sql[$r]['name']." ".$sql[$r]['middle_name']."</option>";
                                       $r++;
                                    }

                                    ?>
                                 </datalist>
                              <input type="submit" value="Змінити" name="change-specialty" id="change-specialty">
                           </form>

                           <div id="change-specialty-messange" style="width: 90%"></div>
                        
                           <script>
                              $(document).ready(function () {
                                 $('#change-specialty-active').submit( (event) => {
                                    event.preventDefault(); 

                                    var id = $('#id-specialty-change').val();
                                    var name = $('#name-specialty-change').val();
                                    var number = $('#number-specialty-change').val();
                                    var department_head = $('#department-head-specialty-change').val();
                                    
                                    $.post("../php/change-specialty.php",{id, name, number,department_head}) 

                                    .done(function(data){
                                       
                                       $('#change-specialty-messange').html(data);

                                       $('#id-specialty-change').val("");
                                       $('#name-specialty-add').val("");
                                       $('#number-specialty-add').val("");
                                       $('#department-head-specialty-add').val("");

                                    });
                                 
                                 })
                              })
                           </script>

                           <hr style="margin-bottom:  15px;">
                           <div class="table-change">
                              <table class="table-change-specialty table" style="border: 1px solid rgb(59, 128, 255) ">
                                 <thead>
                                    <tr>
                                       <th style="border:none;" data-type="text-long">Назва</span></th>
                                       <th style="border:none;" data-type="text-short">Номер</span></th>
                                       <th style="border:none;" data-type="text-long">Зам. відділення</span></th>
                                       <th style="border:none;" data-type="text-short">Редагування</span></th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    <?php
                                        [$sql, $sqll] = $adminQuery->SelectInfoForEditTableSpeciality();

                                       $r = 0;
                                       while ($r < count($sql)) {
                                          if ($r%2 == 0 || $r === 0) {

                                             echo '<tr style=" background: white;">'.
                                                "<td> {$sql[$r]['specialty_name']} </td>".
                                                "<td> {$sql[$r]['specialty_spec_number']}</td>".
                                                "<td> <i>NULL</i> </td>".
                                                "<td style=\"border-left: 1px dashed rgb(59, 128, 255)\"><a href='?red_spec_id={$sql[$r]['specialty_id']}'>Змінити</a> </td>".
                                             '</tr>';

                                          } else if ($r%2 != 0) {

                                             echo '<tr>'.
                                                "<td style=\" background: rgb(181, 220, 255);\"> {$sql[$r]['specialty_name']} </td>".
                                                "<td style=\" background: rgb(181, 220, 255);\"> {$sql[$r]['specialty_spec_number']}</td>".
                                                "<td style=\" background: rgb(181, 220, 255);\"> {$sql[$r]['teacher_surname']}"." "."{$sql[$r]['teacher_name']}"." "."{$sql[$r]['teacher_middle_name']} </td>".
                                                "<td style=\" background: rgb(181, 220, 255);\"> <i>NULL</i> </td>".
                                                "<td style=\" background: rgb(181, 220, 255); border-left: 1px dashed rgb(59, 128, 255)\"><a href='?red_spec_id={$sql[$r]['specialty_id']}'>Змінити</a> </td>".
                                             '</tr>';
                                          }
                                          $r++; 
                                       }

                                       $rl = 0;
                                       while ($rl < count($sqll)) {
                                          if ($r%2 == 0 || $r === 0) {

                                             echo '<tr style=" background: white;">'.
                                                "<td> {$sqll[$rl]['specialty_name']} </td>".
                                                "<td> {$sqll[$rl]['specialty_spec_number']}</td>".
                                                "<td>".$sqll[$r]['teacher_surname']." ".mb_substr($sqll[$r]['teacher_name'], 0, 1).".".mb_substr($sqll[$r]['teacher_middle_name'], 0, 1).".</td>".
                                                "<td style=\"border-left: 1px dashed rgb(59, 128, 255)\"><a href='?red_spec_id={$sqll[$rl]['specialty_id']}'>Змінити</a> </td>".
                                             '</tr>';

                                          } else if ($r%2 != 0) {

                                             echo '<tr>'.
                                                "<td style=\" background: rgb(181, 220, 255);\"> {$sqll[$rl]['specialty_name']} </td>".
                                                "<td style=\" background: rgb(181, 220, 255);\"> {$sqll[$rl]['specialty_spec_number']}</td>".
                                                "<td style=\" background: rgb(181, 220, 255);\">".$sqll[$r]['teacher_surname']." ".mb_substr($sqll[$r]['teacher_name'], 0, 1).".".mb_substr($sqll[$r]['teacher_middle_name'], 0, 1).".</td>".
                                                "<td style=\" background: rgb(181, 220, 255); border-left: 1px dashed rgb(59, 128, 255)\"><a href='?red_spec_id={$sqll[$rl]['specialty_id']}'>Змінити</a> </td>".
                                             '</tr>';
                                          }
                                          $rl++; 
                                          $r++;
                                       }
                                    ?>
                                 </tbody>
                              </table>
                           </div>
                           <div class="block-vorry-buttom" style="background: #9B001B; padding-top: 6px; margin-top: 6px;">
                              <label>Очистити таблиці (пари, оцінки, відвідування) бази данних</label>
                                 <input type="button" value="Очистити" id="db-clear" name="db-clear">
                              <script>
                                 $(document).ready(function () {
                                    $('body').on('click', '#db-clear', function (event) {
                                       event.preventDefault();

                                       var confir = confirm("Після очистки таблиць данні повернути буде НЕМОЖЛИВО! Бажаєте продовжити?");

                                       if (confir === true) {
                                          $.ajax({
                                             type: "POST",
                                             url: "../php/clear-db.php", 
                                             success: function(data) {
                                                alert("Таблиці очищено");
                                             }
                                          });
                                       }
                                    })
                                 })
                              </script>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div id="jurnal-zone" class="block-active-zone" style="display: none">
                  <div id="uper-zone">
                     <div id="back-zone" style="width: 70%">
                        <div id="back-to-group-mark" class="back" style="display: none;"><img src="../images/back_arrow_30px.png" alt="Назад" style="margin-right: 7%">до вибору групи</div>
                        <div id="back-to-subject" class="back" style="display: none;"><img src="../images/back_arrow_30px.png" alt="Назад" style="margin-right: 7%">до вибору предмету</div>
                     </div>
                     <div id="filter" style="width: 30%; display: none;">
                        <div id="filter-zone-mark-admin" class="back filter-zone-mark-admin">Фільтри</div>
                     </div>
                  </div>
                  <div id="block-group"></div>
                  <script>
                        $(window).on('load', (event) => {
                           $('.filter-zone-mark-admin').css("display","none");
                           event.preventDefault();
                           $.ajax({
                              type: "POST",
                              url: "../php/choose-group-admin.php", 
                              success: function(data) {
                                 $('#block-group').html(data);
                              },
                              error: function (data) {
                                 $('#block-group').html("ERROR");
                              }
                           })
                        });

                        $(document).ready(function() {
                           $('body').on('click', ".group-text", function(event) {
                              event.preventDefault();

                              $('#back-to-group-mark').show();
                              $('#back-zone').css("width", "100%");
                              $('#block-group').hide();
                              $('#block-subject').show();   

                              var group = this.id;
                              var data = "group="+group;
                              $.ajax({
                                 type: "POST",
                                 url: "../php/choose-subject-admin.php", 
                                 data: data,
                                 success: function(data) {
                                    $('#block-subject').html(data);
                                 },
                                 error: function (data) {
                                    $('#block-subject').html("ERROR");
                                 }
                              })
                           });
                        });
                     </script>
                  <div id="block-subject"></div>
                  <script>
                     $(document).ready(function () {
                        $('body').on('click', '.choose-subject-admin', function(event) {

                           $('#block-group-kurator').hide();
                           $('#back-to-subject').show();
                           $('#block-table-admin').css('display', 'block');
                           if ($(window).width() >= 992) {
                              $('#back-zone').css("width", "70%");
                              $('#filter').show();
                           }
                           $('#block-subject').hide();
                           $('.filter-zone-mark-admin').css("display","block");

                           var pair = this.id;
                           localStorage.setItem("pair", pair);
                           
                           $.post("../php/filter-my-group.php", {pair})
                              
                           .done(function(data) {
                              $('#form-filter-mark-admin').html(data);
                                          

                              var s = new Date(),
                                 year = s.getFullYear(),
                                 month = s.getMonth()+1;

                              if (month < 10) {
                                 month = "0" + month;
                              }

                              let today = year+"-"+month;
                                             
                              $('#filter-month-my-group').val(today);
                     

                              var data = $('#form-filter-mark-admin').serialize();
                              data += "&pair="+pair;

                              $.ajax({
                                 type: "POST",
                                 url: "../php/mark-table-kurator.php", 
                                 data: data,

                                 success: function(data) {
                                    $('#block-table-mark-admin').html(data);
                                 }
                              });
                           });
                        })

                        $('body').on('click', ".filter-zone-my-mark", event => {
                           event.preventDefault();
                           $("#filter-table-teacher").css('display','flex');
                           $('#top-menu').css('max-height', '0px');
                           $('#top-menu').css('font-size', '0px');
                        });
                     })
                  </script>
                  <div id="block-table-admin" style="display: none;">
                     <div id="block-table-mark-admin"></div>
                  </div>
                  <div id="add-pair-teacher-message"></div>
               </div>

               <div id="visit-zone" class="block-active-zone" style="display: none">
                  <div id="uper-zone-visit" style="margin-bottom: 5px;">
                     <div id="back-zone-visit" style="width: 100%">
                        <div id="back-to-group-mark" class="back" ><img src="../images/back_arrow_30px.png" alt="Назад" style="margin-right: 7%">до вибору групи</div>
                     </div>
                     <div id="filter-visit" id="filter" style="width: 50%;">
                        <div id="filter-zone-visit" class="back filter-zone-visit"> Фільтри</div>
                     </div>
                  </div>
                  <div id="block-group-visit"></div>
                  <script>
                     $(window).on('load', function(event) {
                        event.preventDefault();

                        var dayStart, dayFinish, year, month, day;

                        function checkDate (number) {
                              if (number < 10) {
                                 return "0" + number;
                              } else {
                                 return number;
                              }
                           }

                        var dayStart, dayFinish, year, month, day;
                        dayStart = new Date(new Date().setDate(new Date().getDate()-7));
                        year = dayStart.getFullYear();
                        month = checkDate(dayStart.getMonth() + 1);
                        day = checkDate(dayStart.getDate());
                        dayStart = year + "-" + month + "-" + day;

                        dayFinish = new Date();
                        year = dayFinish.getFullYear();
                        month = checkDate(dayFinish.getMonth() + 1);
                        day = checkDate(dayFinish.getDate());
                        dayFinish = year + "-" + month + "-" + day;
                           
                        $('#visit-filter-start').val(dayStart);
                        $('#visit-filter-finish').val(dayFinish);

                        $('#visit-filter-start').attr("max",dayFinish);
                        $('#visit-filter-finish').attr("max",dayFinish);
                        $('#date-visit').attr("max", dayFinish);
                        
                        $.ajax({
                           type: "POST",
                           url: "../php/choose-group-admin-visit.php",
                           success: function (data) {
                              $('#block-group-visit').html(data);
                           }
                        });
                     })
                  </script>
                  <div id="block-table">
                     <div id="block-table-visit"></div>
                  </div>
                  <script>
                     $('body').on('click', ".group-text-visit", function(event) {
                        event.preventDefault();

                        $('#block-group-visit').css("display","none");
                        $('#back-zone-visit').css("display","flex");
                        if ($(window).width() > 992) {
                           $('#back-zone-visit').css("width","50%");
                           $('#filter-visit').css("display","flex");
                        }
                        $('.filter-zone-visit').css("display","block");
                        $('#block-table').css("display","block");

                        var data, dayStart, dayFinish, group = this.id;
                        var data = "group="+group

                        localStorage.setItem('group',group);
                        dayStart = $('#visit-filter-start').val();
                        dayFinish = $('#visit-filter-finish').val();
                        
                        data += "&dayStart=" + dayStart + "&dayFinish=" + dayFinish;


                        $.ajax({
                           type: "POST",
                           url: "../php/visit-table-admin.php",
                           data: data,
                           success: function (data) {
                              $('#block-table-visit').html(data);
                           }
                        });
                     });

                     $('body').on('click', ".add-buttom-visit", event => {
                        event.preventDefault();
                        $("#add-day-kurator").css('display','flex');
                        $('#top-menu').css('max-height', '0px');
                        $('#top-menu').css('font-size', '0px');
                     });

                     $('body').on('click', ".filter-zone-visit", event => {
                        event.preventDefault();
                        $("#filter-table-visit").css('display','flex');
                        $('#top-menu').css('max-height', '0px');
                        $('#top-menu').css('font-size', '0px');
                     });
                  </script>
               </div>

               <div id="block-control-zone" class="block-active-zone" style="display: none">
                  
               </div>
               <script>
                  $(window).on('load', function (event) {
                     event.preventDefault();

                     $.ajax({
                        type: "POST",
                        url: "../php/control-block.php",
                        success: function (data) {
                           $('#block-control-zone').html(data);
                        }
                     });
                  })
                  $(document).ready(function () {
                     $('body').on('click', '.block-embargo-true', function(event) {
                        event.preventDefault();

                        var data = this.id;
                        data = "v=0&operation=" + data + "&access=" + 0 + "&password=NULL";
                        
                        $.ajax({
                           type: "POST",
                           url: "../php/control-block-do.php",
                           data: data,
                           success: function (data) {
                              $.ajax({
                                 type: "POST",
                                 url: "../php/control-block.php",
                                 success: function (data) {
                                    $('#block-control-zone').html(data);
                                 }
                              });
                           }
                        });
                     })

                     $('body').on('click', '.block-embargo-false', function(event) {
                        event.preventDefault();

                        var data = this.id;
                        data = "v=0&operation=" + data + "&access=" + 1 + "&password=NULL";
                        
                        $.ajax({
                           type: "POST",
                           url: "../php/control-block-do.php",
                           data: data,
                           success: function (data) {
                              $.ajax({
                                 type: "POST",
                                 url: "../php/control-block.php",
                                 success: function (data) {
                                    $('#block-control-zone').html(data);
                                 }
                              });
                           }
                        });
                     })
                  })
               </script>
            </div>
            
            <script> 
            $('form').submit( function () {
               $('#-list-del').load('index.php #-list-del');
               $('#nicknames-list').load('index.php #nicknames-list');
               $('#nickname-list-pass').load('index.php #nickname-list-pass');
               $('#specialty-list').load('index.php #specialty-list');
               $('#kurator-list').load('index.php #kurator-list');
               $('#group-list').load('index.php #group-list');
               $('#specialty-list-ch').load('index.php #specialty-list-ch');
               $('#kurator-list-ch').load('index.php #kurator-list-ch');
               $('#change-group-list').load('index.php #change-group-list');
               $('#group-list-stud-add').load('index.php #group-list-stud-add');
               $('#view-group').load('index.php #view-group');
               $('#name-list').load('index.php #name-list');
               $('#discipline-teacher-list-end').load('index.php #discipline-teacher-list-end');
               $('#discipline-subject-list-end').load('index.php #discipline-subject-list-end');
               $('#discipline-list-del').load('index.php #discipline-list-del');
               $('#department-head-list-end').load('index.php #department-head-list-end');
               $('#department-head-list-end-s').load('index.php #department-head-list-end-s');
               $('#department-head-list-chang').load('index.php #department-head-list-chang');
               $('#student-list-del').load('index.php #student-list-del');
            });
            </script>
         </div>
      </main>
      <!-- main - end -->
      <!-- footer - start -->
      <!--<footer>
         <div class="footerear">
            <div class="ather">&copy; RaiDInc</div>
            <div class="year">2020</div>      
         </div>
      </footer>-->
   </div>
   <!-- footer - end -->
   <script src="../script/preloader.js"></script>
   <script src="../script/script-admin-site.js"></script>
   <script>
      $(document).ready(function() {
         $('body').click('click', '.jurnal', event, function() {
            event.preventDefault();
            console.log('here');
            
            $('#block-table-admin').hide();
            $('#back-to-subject').hide();
            $('#back-to-group-mark').hide();
            $('#filter').hide();
            $('.f').hide();
            $('.filter-zone-my-mark').show();
            $('#block-group').show();
            $('#block-subject').hide();
         })

         $('body').on('click', '#back-to-group-mark', event, function() {
            event.preventDefault();
            
            $('#block-subject').hide();
            $('#block-table-admin').css("display","none");
            $('#back-zone').css("width", "100%");
            $('#back-to-subject').hide();
            $('#filter').hide();
            $('#block-group').show();
            $('#back-to-group-mark').hide();
            $('.f').hide();
            $('.filter-zone-my-mark').show();
         })

         $('body').on('click', '#back-to-subject', event, function() {
            event.preventDefault();
            
            $('#block-subject').css("display","flex");
            $('#block-table-admin').css("display","none");
            $('#back-zone').css("width", "100%");
            $('#back-to-subject').hide();
            $('#filter').hide();
            $('.f').hide();
            $('.filter-zone-my-mark').show();
         })

         $('body').on('click', '#back-to-group-mark', event, function() {
            event.preventDefault();
            
            $('.f').hide();
            $('#block-group-visit').css("display","flex");
            $('#back-zone-visit').css("display","none");
            $('#filter-visit').css("display","none");
            $('#block-table').css("display","none");
         })

         $('body').on('click', '.visit', function(event) {
            event.preventDefault();

            $('.f').hide();
            $('#block-group-visit').css("display","flex");
            $('#back-zone-visit').css("display","none");
            $('#filter-visit').css("display","none");
            $('#block-table').css("display","none");

         })

         $('body').on('click', '.my-group', event, function() {
            
            
         })
      })
   </script>
   <script src="../script/script-college.js"></script>
   <script>
      $(document).ready(function() {
         /*$('body').on('click', '#filter-zone', event, function() {
            event.preventDefault();
            
            $('#filter-table-student').css("display","block");
         })*/

         $('body').on('click', '.filter-zone-mark-admin', event, function() {
            event.preventDefault();
            
            $('#filter-table-mark-admin').css("display","block");
            $('#top-menu').css("max-height",'0px');
            $('#top-menu').css("font-size",'0px');
         })

         $('body').on('click', '.block-control', event, function() {
            event.preventDefault();
            
            $('.f').hide();
         })
      })
   </script>
</body>
</html>