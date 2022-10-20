<?php
require_once ('./db/db.php');
include './db/setting.php';
session_start();
session_unset();
$db = new DataBase();
?>
<!DOCTYPE html>
<html>
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <link rel="stylesheet" href="./style/style.css">
   <link rel="stylesheet" href="./style/style-media-query.css">
   <link rel="shortcut icon" href="./images/favicon.png" type="image/x-icon">
   <script src='//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>
   <title>Електронний журнал</title>
   <style>
      p a:hover {
         text-decoration: none;
         color: blue;
      }
   </style>
</head>
<body>
   <div class="all">
<!--      <div id="page-preloader" class="preloader">-->
<!--         <div class="loader"></div>-->
<!--      </div>-->
      <!-- header - start -->
      <header id="first">
         <div class="logo"><a href="https://mk.sumdu.edu.ua/" target="_blank"><img src="./images/logo.png" alt="logo" title="На головну МК СумДУ"></a></div>
         <div class="name">ЕЛЕКТРОННИЙ ЖУРНАЛ</div>
         <div class="login"><a href="./index.php" ><img src="./images/customer_100px.png" alt="customer" title="Вихід"></a></div>
      </header>
      <!-- header - end -->
      <!-- main - start -->
      <script>
         'use strict';

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
      </script>
                          
      <main>
         <div class="content">
         <div class="form-circle">
               <div class="massage"></div>
               <div class="form-login">
                  <form action="index.php" method="post">
                  <script src="./script/regular.js"></script>
                     <label for="login">Логін</label>
                        <input type="text" name="login" id="login" placeholder="Іванов І. І." autocomplete="off" oninput="this.value = nameDelSymbol(this.value);" 
                        pattern="[A-ZА-Я ІЇЄҐ]{1}[’]?[a-zа-я іїєґ]+[’]?[a-zа-я іїєґ]+\s?[A-ZА-Яa-zа-я ІЇЄҐіїєґ.]+" title="Іванов І.І. або Лук’янов І.І." required>
                     <label for="password">Пароль</label>
                        <input type="password" name="password" id="password" placeholder="********" autocomplete="off" required>
                     <input type="submit" name="log-in" value="Увійти" id="log-in">
                     <p><a href="https://t.me/mk_journal_bot"style="cursor: pointer;">Дізнатися логін</a></p>
                  </form>
                  <script>
                     $(window).on('load', function(event) {
                        event.preventDefault();
                        $('#login').focus();
                     })
                  </script>
                  <?php 
                  if (isset($_POST['log-in'])) {

                     if ( isset($_POST['login']) && isset($_POST['password']) ) {
                     
                        $loginNormalized = trim($_POST['login']); 
                        $passwordNormalized = trim($_POST['password']); 

                        if(!empty($loginNormalized) && !empty($passwordNormalized)) {
                           $passwordHash = md5(md5($passwordNormalized).$SMT);

                           echo $passwordHash;
                           try {      
                              $sql = $db->query("SELECT `id`, nickname, site_checked, privileges FROM user WHERE nickname = :login AND password_start = :password",
                                 [
                                    ':login' => $loginNormalized,
                                    ':password' => $passwordHash
                                 ]
                              );
                                    
                           } catch (Exception $e) {
                              echo "Помилка авторизації! Зверніться до Адміністратора сайту!";  
                           }
                        
                        
                           try {      
                              $sqll = $db->query("SELECT count(*) FROM user WHERE nickname = :login AND password_start = :password",
                                 [
                                    ':login' => $loginNormalized,
                                    ':password' => $passwordHash
                                 ]
                              );
                                    
                           } catch (Exception $e) {
                              echo "Помилка авторизації! Зверніться до Адміністратора сайту!";
                           }

                           if ($sqll[0]['count(*)'] <= 0) {
                              
                              try {      
                                 $sql = $db->query("SELECT `id`, `teacher`.`nickname`, `teacher`.`site_checked`, `teacher`.`privileges` FROM `teacher` WHERE `teacher`.`nickname` = :login and `teacher`.`password_start` = :password",
                                    [
                                       ':login' => $loginNormalized,
                                       ':password' => $passwordHash
                                    ]
                                 );
                                       
                              } catch (Exception $e) {
                                 echo "Помилка авторизації! Зверніться до Адміністратора сайту!";  
                              }
                              
                              try {      
                                 $sqll = $db->query("SELECT count(*) FROM `teacher` WHERE `teacher`.`nickname` = :login and `teacher`.`password_start` = :password",
                                    [
                                       ':login' => $loginNormalized,
                                       ':password' => $passwordHash
                                    ]
                                 );
                                       
                              } catch (Exception $e) {
                                 echo "Помилка авторизації! Зверніться до Адміністратора сайту!";  
                              }
                              if ($sqll[0]['count(*)'] <= 0) {

                                 try {      
                                    $sql = $db->query("SELECT `id`, `student`.`nickname`, `student`.`site_checked`, `student`.`privileges` FROM `student` WHERE `student`.`nickname` = :login and `student`.`password_start` = :password",
                                       [
                                          ':login' => $loginNormalized,
                                          ':password' => $passwordHash
                                       ]
                                    );
                                          
                                 } catch (Exception $e) {
                                    echo "Помилка авторизації! Зверніться до Адміністратора сайту!";  
                                 }
                                 
                                 try {      
                                    $sqll = $db->query("SELECT count(*) FROM `student` WHERE `student`.`nickname` = :login and `student`.`password_start` = :password",
                                       [
                                          ':login' => $loginNormalized,
                                          ':password' => $passwordHash
                                       ]
                                    );
                                          
                                 } catch (Exception $e) {
                                    echo "Помилка авторизації! Зверніться до Адміністратора сайту!";
                                 }

                                 if ($sqll[0]['count(*)'] <= 0) {
                                    session_unset();
                                    echo '<br><center><div><p class="massage">Такого користувача не існує, перевірте введені дані!</p></div></center>';
                                 } else {
                                    if ($sql[0]['site_checked'] == false) {
                                       $_SESSION['nickname'] = $loginNormalized;
                                       $_SESSION['privileges'] = $sql[0]['privileges'];
                                       $_SESSION['entery'] = 'ok';
                                       header('Location: ./first-enter/index.php');
                                    } else {
                                       switch ($sql[0]['privileges']) {
                                          case 'student': 
                                             $_SESSION['name'] = "student";
                                             $_SESSION['id'] = $sql[0]['id'];
                                             header('Location: ./student/');
                                             exit;
                                          break;
                                          default:
                                             session_unset();
                                             echo '<br><center><div><p class="massage">Такого користувача не існує, перевірте введені дані!</p></div></center>';
                                          break;
                                       }
                                    }  
                                 }
                              } else {
                                 if ($sql[0]['site_checked'] == false) {
                                    $_SESSION['nickname'] = $loginNormalized;
                                    $_SESSION['privileges'] = $sql[0]['privileges'];
                                    $_SESSION['entery'] = 'ok';
                                    header('Location: ./first-enter/index.php');
                                 } else {
                                    switch ($sql[0]['privileges']) {
                                       case 'teacher': 
                                          $_SESSION['name'] = "teacher";
                                          $_SESSION['id'] = $sql[0]['id'];
                                          header('Location: ./teacher/');
                                          exit;
                                       break;
                                       case 'kurator': 
                                          $_SESSION['name'] = "kurator";
                                          $_SESSION['id'] = $sql[0]['id'];
                                          header('Location: ./kurator/');
                                          exit;
                                       break;
                                       default:
                                          session_unset();
                                          echo '<br><center><div><p class="massage">Такого користувача не існує, перевірте введені дані!</p></div></center>';
                                       break;
                                    }
                                 }
                              }
                           } else {
                              if ($sql[0]['site_checked'] == false) {
                                 $_SESSION['nickname'] = $loginNormalized;
                                 $_SESSION['privileges'] = $sql[0]['privileges'];
                                 $_SESSION['entery'] = 'ok';
                                 header('Location: ./first-enter/index.php');
                              } else {
                                 switch ($sql[0]['privileges']) {
                                    
                                    case 'admin': 
                                       $_SESSION['name'] = "admin-college";
                                       header('Location: ./admin-college/');
                                       exit;
                                    break;
                                    case 'admin_site': 
                                       $_SESSION['name'] = "admin-site";
                                       header('Location: ./admin-site/');
                                       exit;
                                    break;
                                    default:
                                       session_unset();
                                       echo '<br><center><div><p class="massage">Такого користувача не існує, перевірте введені дані!</p></div></center>';
                                    break;
                                 }
                              }
                           }
                        }
                     } else {
                        //
                     }
                  }
                  ?>
               </div>
            </div>
         </div>
      </main>
      
      <!-- main - end -->
      <!-- footer - start -->
      <footer>
         <div class="footerear">
            <div class="ather">&copy; RaiDInc</div>
            <div class="year">2020</div>
         </div>
      </footer>
   </div>
   <!-- footer - end -->
<!--   <script src="./script/preloader.js"></script>-->
</body>
</html>