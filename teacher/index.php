<!DOCTYPE html>
<?php
require_once '../db/db.php';
include '../db/setting.php';
$db = new DataBase();
session_start();

if ($_SESSION['name'] != "teacher" || !isset($_SESSION['name'])) {

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
echo "<script>
      localStorage.setItem(\"id\"," . $_SESSION['id'] . ");
      </script>";
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
   <link rel="stylesheet" href="../style/style-choose-block-media-query.css">
   <link rel="stylesheet" href="../style/zvit.css">
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
         <div class="login"><a href="../index.php" ><img src="../images/customer_100px.png" alt="customer" title="Вихід"></a></div>
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
            <li class="filter-zone menu-item">Фільтри</li>
            <li class="add-buttom menu-item">Додати пару</li>
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
      <main>

         <div id="add-group-teacher" class="add-group-teacher order" style="display: none;">
            <div class="modal-window-block">
               <div class="modal-window">
                  <div class="header-text">Додати групу</div>
                  <div class="x">×</div>
               </div>
               <div id="content">
                  <form id="form-add-group" class="form-modal">
                     <label for="subject-name">Предмет</label>
                     <input type="hidden" id="descipline-id">
                     <input type="text" id="subject-name" readonly>
                     <label for="group-add-teacher">Група</label>
                     <select id="group-add-teacher" required>
                     </select>
                     <input type="submit" value="Додати">
                  </form>
                  <div id="message-add-group-teacher" style="width: 90%"></div>
                  <script>
                     $(document).ready(function() {
                        $('#form-add-group').submit((event) => {
                           event.preventDefault();

                           var teacher = localStorage.getItem("id");
                           var descipline = $('#descipline-id').val();
                           var group = $('#group-add-teacher').val();

                           $.post("../php/form-add-group-teacher.php", {teacher,descipline,group})

                           .done(function(data) {
                              $('#message-add-group-teacher').html(data);
                           });
                        });
                     })
                  </script>
               </div>
            </div>
         </div>

         <div id="filter-table-teacher" class="add-group-teacher order order-filter-table-teacher" style="display: none;">
            <div class="modal-window-block">
               <div class="modal-window">
                  <div class="header-text">Фільтри</div>
                  <div class="x-filter-table-teacher x">×</div>
               </div>
               <div id="content" style="
                                 justify-content: unset;"
               >
                  <form id="form-table-filter" class="form-modal" 
                           style="justify-content: unset;
                                 text-align: unset;
                                 display: block;"
                  >

                  </form>
                  <div id="filter-message"></div>
                  <script>
                     $(document).ready(function() {
                        $('body').on("change", "#form-table-filter", function(e) {
                           
                           var data = $('#form-table-filter').serialize();
                           var pair = localStorage.getItem('pair');
                           data += "&pair="+pair;

                           $.ajax({
                              type: "POST",
                              url: "../php/mark-table-teacher.php",
                              data: data,
                              success: function(data) {
                                 $('#block-table-mark').html(data);
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

         <div id="add-pair-teacher" class="add-group-teacher order order-add-pair-teacher" style="display: none;">
            <div class="modal-window-block">
               <div class="modal-window">
                  <div class="header-text">Додати пару</div>
                  <div class="x-add-pair-teacher x">×</div>
               </div>
               <div id="content">
                  <form id="form-add-pair-teacher" class="form-modal" method="POST"> 
                     <input type="hidden" id="pair-add-pair-id">
                     <label for="pair-date">Дата</label>
                        <input type="date" name="pair-date" id="pair-date" required>
                     <label for="pair-type">Тип пари</label>
                        <input type="search" name="pair-type" id="pair-type" list="list-pair-type" required>
                           <datalist id="list-pair-type">
                              <option value="л/р">Лабораторна робота</option>
                              <option value="зл/р">Захист лабораторної роботи</option>
                              <option value="п/р">Практична робота</option>
                              <option value="ауд/р">Оцінка за роботу в аудиторії (якщо оцінка відсутня, то вона не буде враховуватися в розрахунку модульної, семестрової, річної або атестації)</option>
                              <option value="с/р">Самостійна робота</option>
                              <option value='к/р'>Контрольна робота</option>
                              <option value='сем/р'>Семестрова контрольна робота</option>
                              <option value='т/к'>Тематичний контроль</option>
                              <option value='мон'>Монолог</option>
                              <option value='діал'>Діалог</option>
                              <option value='ауд'>Аудіювання</option>
                              <option value='чит'>Читання</option>
                              <option value='п'>Письмо</option>
                              <option value='говор'>Говоріння</option>
                              <option value='ККР'>Комплексна к/р</option>
                              <option value='ДПА'>ДПА</option>
                              <option value='тест'>Тест</option>
                              <option value='дикт'>Диктант</option>
                              <option value='вірш'>Вірш</option>
                              <option value='твір'>Твір</option>
                              <option value='поез'>Поезія</option>
                              <option value='сем'>Семінар (якщо оцінка відсутня, то вона не буде враховуватися в розрахунку модульної, семестрової, річної або атестації)</option>
                              <option value="доп">Доповідь (якщо оцінка відсутня, то вона не буде враховуватися в розрахунку модульної, семестрової, річної або атестації)</option>
                              <option value='перез'>Перезалік</option>
                              <option value='вх/к'>Вхідний контроль</option>
                              <option value='Е'>Екзамен</option>
                              <option value='С'>Семестр</option>
                              <option value='А'>Атестація</option>
                              <option value='М'>Модуль</option>
                              <option value='Р'>Річна</option>
                           </datalist>
                     <label id="checkbox-dropdown-container-label" style="display: none; font-size: 15px;">Оцінка буде вираховуватися середнє арифметичним тих пар, які ви оберете нижче</label>
                           <div id="checkbox-dropdown-container" style="display: none;"></div>
                     <div style="display: none">
                     <script>
                     $(document).ready(function() {
                        $('body').on("click","#custom-select", function() {
                           $("#custom-select-option-box").toggle();
                        });
                        
                        function toggleFillColor(obj) {
                           $("#custom-select-option-box").show();
                           if ($(obj).prop('checked') == true) {
                              $(obj).parent().css("background", '#c6e7ed');
                           } else {
                              $(obj).parent().css("background", '#FFF');
                           }
                        }

                        $('body').on("click", ".custom-select-option", function(e) {
                           var checkboxObj = $(this).children("input");
                           if ($(e.target).attr("class") != "custom-select-option-checkbox") {
                              if ($(checkboxObj).prop('checked') == true) {
                                 $(checkboxObj).prop('checked', false)
                              } else {
                                 $(checkboxObj).prop("checked", true);
                              }
                           }
                           toggleFillColor(checkboxObj);
                        });
                        
                        $("body").on("click",function(e) {
                           if (e.target.id != "custom-select"
                                 && $(e.target).attr("class") != "custom-select-option") {
                              $("#custom-select-option-box").hide();
                           }
                        });
                     
                        $('body').on('change', '#pair-date', event, function() {
                           event.preventDefault();

                           var type = $('#pair-type').val();
                           var date = $('#pair-date').val();

                           $('#checkbox-dropdown-container').hide();
                           $('.modal-window-block').css('height', '400px');
                           $('#checkbox-dropdown-container-label').hide();

                           if (type === "С" || type === "А" || type === "М" || type === "Р") {

                              var pair = localStorage.getItem("pair");

                              $.post("../php/avg-mark-block.php", {pair, type, date})

                              .done(function(data) {
                                 $('#checkbox-dropdown-container').html(data);

                              });
                           }
                        })

                        $('body').on('change', '#pair-type', event, function() {
                           event.preventDefault();

                           var type = $('#pair-type').val();
                           var date = $('#pair-date').val();

                           $('#checkbox-dropdown-container').hide();
                           $('.modal-window-block').css('height', '400px');
                           $('#checkbox-dropdown-container-label').hide();

                           if (type === "С" || type === "А" || type === "М" || type === "Р") {

                              var pair = localStorage.getItem("pair");

                              $.post("../php/avg-mark-block.php", {pair, type, date})

                              .done(function(data) {
                                 $('#checkbox-dropdown-container').html(data);
                              });
                           }
                        })
                     })
                     </script>
                     </div>
                     <input type="submit" value="Додати">
                  </form>
                  <div id="message-add-pair"></div>
                  <script>
                     $(document).ready(function() {
                        $('body').on('submit', '#form-add-pair-teacher', event, function() {
                           event.preventDefault();

                           var pair = $('#pair-add-pair-id').val();
                           var date = $('#pair-date').val();
                           var type = $('#pair-type').val();
                           var avgC = [];

                           $('input:checked[name=\'toys[]\']').each(function(){
                              avgC.push($(this).val());
                           });
             
                           var avg = avgC.join(':');

                           if ((type === "А" || type === "С" || type === "М" || type === "Р") 
                                 && (avg !== " " && avg !== "" && avg !== undefined && avg !== "NULL")){
                              
                              $.post("../php/add-pair-teacher.php", {pair,date,type,avg})

                              .done(function(data) {
                                 $('#message-add-pair').html(data);
                                 $.post("../php/filter.php", {pair})
                                 .done(function(data) {
                                    $('#form-table-filter').html(data);
                                 

                                 var s = new Date(),
                                       year = s.getFullYear(),
                                       month = s.getMonth()+1;

                                    if (month < 10) {
                                       month = "0"+month;
                                    }

                                    let today = year+"-"+month;
                                    
                                    $('#filter-month').val(today);
            
                                    $('#pair-add-pair-id').val(pair);

                                    var data = $('#form-table-filter').serialize();
                                    data += "&pair="+pair;

                                    $.ajax({
                                       type: "POST",
                                       url: "../php/mark-table-teacher.php", 
                                       data: data,

                                       success: function(data) {
                                          $('#block-table-mark').html(data);
                                       }
                                    });
                                 });
                              });
                           } else if (type !== "А" && type !== "С" && type !== "М" && type !== "Р") {
                              
                              $.post("../php/add-pair-teacher.php", {pair,date,type,avg})

                              .done(function(data) {
                                 $('#message-add-pair').html(data);
                                 $.post("../php/filter.php", {pair})
                                 .done(function(data) {
                                    $('#form-table-filter').html(data);
                                 

                                 var s = new Date(),
                                       year = s.getFullYear(),
                                       month = s.getMonth()+1;

                                    if (month < 10) {
                                       month = "0"+month;
                                    }

                                    let today = year+"-"+month;
                                    
                                    $('#filter-month').val(today);
            
                                    $('#pair-add-pair-id').val(pair);

                                    var data = $('#form-table-filter').serialize();
                                    data += "&pair="+pair;

                                    $.ajax({
                                       type: "POST",
                                       url: "../php/mark-table-teacher.php", 
                                       data: data,

                                       success: function(data) {
                                          $('#block-table-mark').html(data);
                                       }
                                    });
                                 });
                              });
                           }

                        });
                     })
                  </script>
               </div>
            </div>
         </div>

         <div id="zvit-teacher" class="add-group-teacher order order-zvit-teacher" style="display: none">
            <div class="modal-window-block" style="height: 300px">
               <div class="modal-window">
                  <div class="header-text">Семестровий звіт</div>
                  <div class="x-zvit-teacher x">×</div>
               </div>
               <div class="content" style="align-items: unset;">
                  <form id="form-zvit-teacher" class="form-modal" >
                     з
                     <input type="date" id="date-teacher-start" required>
                     до
                     <input type="date" id="date-teacher-finish" required>
                     <a id="create-zvit-teacher" class="buttom">Генерація</a>
                  </form>
                  <script>
                     $(window).on('load', function(event) {
                           event.preventDefault();

                           function checkDate(number) {
                              if (number < 10) {
                                 return "0" + number;
                              } else {
                                 return number;
                              }
                           }

                           var date = new Date(), year = date.getFullYear(), month = checkDate(date.getMonth()+1), day =  checkDate(date.getDate());
                           date = year + "-" + month + "-" + day;
                           
                           $('#date-teacher-start').val(date);
                           $('#date-teacher-finish').val(date);
                        })

                     $(document).ready(function() {
                        $('body').on("mousedown", "#create-zvit-teacher", function(e) {
                           e.preventDefault();
                           var dayStart = $('#date-teacher-start').val(),
                              dayFinish = $('#date-teacher-finish').val(),
                              vidomist = $('#zvit-select-type').val();
                           var pair = localStorage.getItem('pair');
                           var data = "?pair=" + pair + "&dayStart=" + dayStart + "&dayFinish=" + dayFinish;

                           $('#create-zvit-teacher').attr('href', '../phpword/template/index.php' + data);
                           

                        });
                     })
                  </script>
               </div>
            </div>
         </div>

         <div class="content">
            <div id="active-zone">
               <div id="uper-zone">
                  <div id="back-zone" style="width: 100%">
                     <div id="back-to-subject" class="back" style="display: none;"><img src="../images/back_arrow_30px.png" alt="Назад" style="margin-right: 7%">до вибору предмету</div>
                     <div id="back-to-group" class="back" style="display: none;"><img src="../images/back_arrow_30px.png" alt="Назад" style="margin-right: 7%">до вибору групи</div>
                  </div>
                  <div id="filter" style="width: 50%; display: none;">
                     <div id="filter-zone" class="back filter-zone"> Фільтри
                     </div>
                     <div id="add-buttom" class="back add-buttom"> Додати пару</div>
                  </div>
               </div>
               <div id="block-subject">
               </div>
               <script>
                     $(window).on('load', (event) => {
                        event.preventDefault();

                        var teacher = localStorage.getItem("id");
                        var data ='teacher='+teacher;

                        $.ajax({
                           type: "POST",
                           url: "../php/choose-subject-teacher.php", 
                           data: data,

                           success: function(data) {
                              $('#block-subject').html(data);
                           },
                           error: function () {
                              $('#block-subject').html("ERROR");
                           }
                        })
                     });
               </script>
               <div id="block-group" style="display: none;">
               </div>
               <script>
                  $(document).ready(function() {
                     $('body').on('click', '.group-text', function showTable(event) {
                        event.preventDefault();

                        var pair = this.id;
                        localStorage.setItem("pair", pair);
                        $.post("../php/filter.php", {pair})
            
                        .done(function(data) {
                           $('#form-table-filter').html(data);
                        

                        var s = new Date(),
                              year = s.getFullYear(),
                              month = s.getMonth()+1;

                           if (month < 10) {
                              month = "0"+month;
                           }

                           let today = year+"-"+month;
                           
                           $('#filter-month').val(today);
   
                           $('#pair-add-pair-id').val(pair);

                           var data = $('#form-table-filter').serialize();
                           data += "&pair="+pair;

                           $.ajax({
                              type: "POST",
                              url: "../php/mark-table-teacher.php", 
                              data: data,

                              success: function(data) {
                                 $('#block-table-mark').html(data);
                              }
                           });
                        });
                     })

                     $('body').on('click', '.delete-mark-teacher', function showTable(event) {
                        event.preventDefault();

                        var confir = confirm("Після видалення дані повернути неможливо. Справді бажаєте видалити пару?");

                        if (confir === true) {

                           var mark = this.id;
                           $.post("../php/change-avg-delete-mark-table-teacher.php", {mark})
                              .done(function(data) {

                              $.post("../php/delete-mark-table-teacher.php", {mark})
                              .done(function(data) {
                                    
                                 var pair = localStorage.getItem("pair");
                                 var data = $('#form-table-filter').serialize();
                                 data += "&pair="+pair;
                        
                                 $.ajax ({
                                    type: "POST",
                                    url: "../php/mark-table-teacher.php", 
                                    data: data,
                                    success: function(data) {

                                       $('#block-table-mark').html(data);
                                    }
                                 })
                              });
                           });
                        }
                     })
                     /* 
                     * - 
                     * - modal window - start
                     * - 
                     */
                  $('body').on('click', ".add-buttom", event => {
                     event.preventDefault();

                     $("#add-pair-teacher").css('display','flex');
                  });

                  $('body').on('click', ".filter-zone", event => {
                     event.preventDefault();

                     $("#filter-table-teacher").css('display','flex');
                  });
               
               })
                     /* 
                     * - 
                     * - modal window - end
                     * - 
                     */
               </script>
               <div id="block-table-teacher" style="display: none;">
                  <div id="block-table-mark"></div>
                  <div class="zvit-block" >
                        <input type="button" value="Створити звіт" id="open-modal-teacher" class="buttom">
                        <script>
                           $(document).ready(function() {
                              $('body').on('click', '#open-modal-teacher', function(event) {
                                 event.preventDefault();

                                 $('#zvit-teacher').css('display', 'block');
                              })
                           })
                        </script>
                     </div>
               </div>
               <div id="add-pair-teacher-message"></div>
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
         $('body').on('change', '.mark-change', event, function() {
            event.preventDefault();
            var data = $(this).serialize();

            $.ajax({
               type: "POST",
               url: "../php/change-mark-table-teacher.php",
               data: data,
               success: function(data) {
                  $('#add-pair-teacher-message').html(data);
               },
               error: function() {
                  alert('error handing here');
               }
            });
         })
      
         $('body').on('submit', '.mark-change', function(event) {
            event.preventDefault();

            var data = $(this).serialize();
            $.ajax({
               type: "POST",
               url: "../php/change-mark-table-teacher.php",
               data: data,
               success: function(data) {
                  
                  var pair = localStorage.getItem("pair");
                  var data = $('#form-table-filter').serialize();
                  data += "&pair="+pair;
                        
                  $.ajax({
                     type: "POST",
                     url: "../php/mark-table-teacher.php", 
                     data: data,

                     success: function(data) {
                        $('#block-table-mark').html(data);
                     }
                  })
               },
               error: function() {
                  alert('error handing here');
               }
            });
         })

         $('body').on('click', '.block-add', function() {
            $('#add-group-teacher').show();
         });

         $('body').on('click', '.menu-delete', function() {
            var pair = this.id;

            var confir = confirm("Після видалення зникне вся інформація зв'язана з цією групою! Справді бажаєте видалити групу?");

            if (confir === true) {
               $.post("../php/delete-group-teacher.php", {pair})

               .done(function(data) {
                  setTimeout(function() {
                     window.location.reload();
                  }, 1000);
               });
            }
         });


         $('body').on('click', '.group-text', function(event) {
            var target = event.target;
            var modal = target.closest('.menu-group');

            if (target !== modal) {
               
               $('#block-group').hide();
               $('#block-subject').hide();
               $('#back-to-subject').show();
               $('#back-to-group').show();
               $('#block-table-teacher').show();
               if ($(window).width() > 992) {
                  $('#back-zone').css('width', '50%');
                  $('#filter').show();
               }
            }
         })
      })
   </script>
   <script src="../script/preloader.js"></script>
   <script src="../script/script-teacher.js"></script>

</body>

</html>