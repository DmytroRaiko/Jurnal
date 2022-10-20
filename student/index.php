<!DOCTYPE html>
<?php
require_once '../db/db.php';
include '../db/setting.php';
$db = new DataBase();
session_start();

if ($_SESSION['name'] != "student" || !isset($_SESSION['name'])) {

   header('Location: ../index.php');
   exit;
}

function mySqlQuer($db, $querty, $params)
{
   try {

      $sql = $db->query($querty, $params);
      return $sql;
   } catch (Exception $e) {

      echo "Помилка виконання! Зверніться до Адміністратора сайту!";
   }
}
?>

<html >

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <link rel="stylesheet" href="../style/style.css">
   <link rel="stylesheet" href="../style/template.css">
   <link rel="stylesheet" href="../style/style-media-query.css">
   <link rel="stylesheet" href="../style/templete-media-query.css">
   <link rel="stylesheet" href="../style/style-choose-block.css">
   <link rel="stylesheet" href="../style/style-teacher.css">
   <link rel="stylesheet" href="../style/kurator.css">
   <link rel="stylesheet" href="../style/style-choose-block-media-query.css">

   <link rel="shortcut icon" href="../images/favicon.png" type="image/x-icon">
   <script src='//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>
   <title>Електронний журнал</title>
</head>

<body>
   <div class="all">
      <div id="page-preloader" class="preloader">
         <div class="loader"></div>
      </div>
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
            <li class="jurnal menu-item">Журнал</li>
            <li class="visit menu-item">Відвідування</li>
            <li class="my-group menu-item"><a href="http://schedule.mksumdu.info/" target="_blank">Розклад</a></li>
            <li class="filter-zone-my-mark menu-item f" style="background-color: rgb(49, 173, 240)">Фільтри</li>
            <li class="filter-zone-visit menu-item f" style="display: none; background-color: rgb(49, 173, 240)">Фільтри</li>
            <li class="menu-item avtorise" id="avtorise"><a style="border: none; " href="../index.php">Вихід<img src="../images/exit_32px.png" style="vertical-align: middle;" alt="Exit"></a></li>
         </div>
      </header>
      <?php
      echo "<script>
            localStorage.setItem(\"id\",".$_SESSION['id'].");
            </script>";
      ?>
      <!-- header - end -->
      <!-- main - start -->
      <div id="filter-table-student" class="add-group-teacher order order-filter-table-student" style="display: none;">
            <div class="modal-window-block">
               <div class="modal-window">
                  <div class="header-text">Фільтри</div>
                  <div class="x-filter-table-student x">×</div>
               </div>
               <div class="content" style="
                                 justify-content: unset;"
               >
                  <form id="form-filter-mark-student" class="form-modal" 
                           style="justify-content: unset;
                                 text-align: unset;
                                 display: block;"
                  >

                  </form>
                  <div id="filter-message"></div>
                  <script>
                     $(document).ready(function() {
                        $('body').on("change", "#form-filter-mark-student", function(e) {
                           
                           var data = $('#form-filter-mark-student').serialize();
                           var pair = localStorage.getItem('pair');
                           var id = localStorage.getItem('id');
                           data += "&pair="+pair+"&id="+id;

                           $.ajax({
                              type: "POST",
                              url: "../php/mark-table-student.php",
                              data: data,
                              success: function(data) {
                                 $('#block-table-mark-student').html(data);
                              },
                              error: function() {
                                 alert('error handing here');
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

                           var data = localStorage.getItem('id');
                           data = "id="+data;

                           dayStart = $('#visit-filter-start').val();
                           dayFinish = $('#visit-filter-finish').val();
                           data += "&dayStart=" + dayStart + "&dayFinish=" + dayFinish;
                           
                           $.ajax({
                              type: "POST",
                              url: "../php/visit-table-student.php",
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
      
      <main>
         
         
         <div class="content">
            <div id="menu-sidebar">
               <div>   
                  <ol>
                     <li class="menu-item-sidebar jurnal" id="jurnal"><div>Журнал</div><img src="../images/menu-png/jurnal.png" alt="jurnal"></li>
                     <li class="menu-item-sidebar visit" id="visit"><div>Відвідування</div><img src="../images/menu-png/visit.png" alt="visiting"></li>
                     <a class="a menu-item-sidebar" href="https://mk.sumdu.edu.ua/" target="_blank"><div>Розклад</div><img src="../images/menu-png/jurnal.png" alt="jurnal"></a>
                  </ol>
                  <div>
                     
                  </div>
               </div>
            </div>

            <div id="active-zone">
               <div id="jurnal-zone" class="block-active-zone">
                  <div id="uper-zone">
                     <div id="back-zone">
                        <div id="back-to-subject" class="back" style="display: none;"><img src="../images/back_arrow_30px.png" alt="Назад" style="margin-right: 7%">до вибору предмету</div>
                     </div>
                     <div id="filter" style="width: 50%; display: none;">
                        <div id="filter-zone" class="back filter-zone">Фільтри</div>
                     </div>
                  </div>
                  <div id="block-subject">
                  </div>
                  <script>
                        $(window).on('load', (event) => {
                           event.preventDefault();

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

                           $('.filter-zone').show();
                           $('.add-buttom').show();

                           var student = localStorage.getItem("id");
                           var data ='student=' + student;

                           $.ajax({
                              type: "POST",
                              url: "../php/choose-subject-student.php", 
                              data: data,

                              success: function(data) {
                                 $('#block-subject').html(data);
                              },
                              error: function () {
                                 $('#block-subject').html("ERROR");
                              }
                           })
                        });
                     $(document).ready(function() {

                        $('body').on('click', ".filter-zone-my-mark", event => {
                           event.preventDefault();
                           $("#filter-table-teacher").css('display','flex');
                           $('#top-menu').css('max-height', '0px');
                           $('#top-menu').css('font-size', '0px');
                        });
                  })
                  </script>
                  <div id="block-table-student" style="display: none;">
                     <div id="block-table-mark-student"></div>
                  </div>
                  <div id="add-pair-teacher-message"></div>
               </div>

               <div id="visit-zone" class="block-active-zone" style="display: none">
                  <div id="uper-zone-visit" style="margin-bottom: 5px; width: 100%;">
                     <div id="filter-visit" id="filter" style="width: 100%;">
                        <div id="filter-zone-visit" class="back filter-zone-visit"> Фільтри</div>
                     </div>
                  </div>
                  <div id="block-table-kurator" >
                     <div id="block-table-visit"></div>
                  </div>
                  <script>

                     $(window).on('load', function(event) {
                        event.preventDefault();

                        var dayStart, dayFinish, year, month, day;

                        var data = localStorage.getItem('id');
                        data = "id="+data;
                        dayStart = $('#visit-filter-start').val();
                        dayFinish = $('#visit-filter-finish').val();
                        data += "&dayStart=" + dayStart + "&dayFinish=" + dayFinish;
                        
                        
                        $.ajax({
                           type: "POST",
                           url: "../php/visit-table-student.php",
                           data: data,
                           success: function (data) {
                              $('#block-table-visit').html(data);
                           }
                        });
                     })

                     $('body').on('click', ".filter-zone-visit", event => {
                        event.preventDefault();
                        $("#filter-table-visit").css('display','flex');
                        $('#top-menu').css('max-height', '0px');
                        $('#top-menu').css('font-size', '0px');
                     });
                  </script>
               </div>

            </div>
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

   <script>
      $(document).ready(function() {
         $('body').on('click', '.jurnal', event, function() {
            event.preventDefault();
            
            $('#block-subject').css("display","flex");
            $('#block-table-student').hide();
            $('#back-to-subject').hide();
            $('#filter').hide();
            $('.f').hide();
            $('.filter-zone-my-mark').show();
         })

         $('body').on('click', '#back-to-subject', event, function() {
            event.preventDefault();
            
            $('#block-subject').css("display","flex");
            $('#block-table-student').hide();
            $('#back-to-subject').hide();
            $('#filter').hide();
            $('.f').hide();
            $('.filter-zone-my-mark').show();
         })

         $('body').on('click', '.visit', event, function() {
            event.preventDefault();

            $('.f').hide();
            $('.filter-zone-visit').show();
            $('.add-buttom-day').show();
            $('.add-buttom-visit').show();
            if ($(window).width() <= 992) {
               $('#uper-zone-visit').hide();
            }
         })

         $('body').on('click', '.my-group', event, function() {
            
            
         })
      })
   </script>
   <script src="../script/preloader.js"></script>
   <script src="../script/script-student.js"></script>
   <script>
      $(document).ready(function() {
         $('body').on('click', '#filter-zone', event, function() {
            event.preventDefault();
            
            $('#filter-table-student').css("display","block");
         })

         $('body').on('click', '.filter-zone-my-mark', event, function() {
            event.preventDefault();
            
            $('#filter-table-student').css("display","block");
         })
      })
   </script>
</body>

</html>