<!DOCTYPE html>
<?php
require_once '../db/db.php';
include '../db/setting.php';
$db = new DataBase();
session_start();

if ($_SESSION['name'] != "admin-college" || !isset($_SESSION['name'])) {

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
            <li class="menu-item jurnal" id="top-jurnal">Журнал</li>
            <li class="menu-item visit" id="top-visit">Відвідування</li>
            <li class="block-control menu-item"id="top-block-control">Контроль ведення журналів</li>
            <li class="filter-zone-mark-admin menu-item f" style="background-color: rgb(49, 173, 240)">Фільтри</li>
            <li class="filter-zone-visit menu-item f" style="display: none; background-color: rgb(49, 173, 240)">Фільтри</li>
            <li class="menu-item avtorise" id="avtorise"><a style="border: none; " href="../index.php">Вихід<img src="../images/exit_32px.png" style="vertical-align: middle;" alt="Exit"></a></li>
         </div>
      </header>
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
                     <li class="menu-item-sidebar block-control" id="block-control"><div>Контроль ведення журналів</div><img src="../images/menu-png/block.png" alt="blocks"></li>
                  </ol>
                  <div>
                     
                  </div>
               </div>
            </div>

            <div id="active-zone">

               <div id="jurnal-zone" class="block-active-zone" >
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
                        var password = prompt("Створіть пароль:");
                        
                        if (password !== null){
                           data = "v=1&operation=" + data + "&access=" + 0 + "&password=" + password;

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
                        }
                     })

                     $('body').on('click', '.block-embargo-false', function(event) {
                        event.preventDefault();

                        var data = this.id;
                        var password = prompt("Введіть пароль:");
                        
                        if (password !== null){
                           data = "v=1&operation=" + data + "&access=" + 1 + "&password=" + password;

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
                        }
                     })
                  })
               </script>
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
   <script src="../script/preloader.js"></script>
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