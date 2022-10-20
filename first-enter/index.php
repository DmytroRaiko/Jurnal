<?php
require_once '../db/db.php';
include '../db/setting.php';
session_start();
$db = new DataBase();
if ( $_SESSION['entery'] != "ok") {
   header('Location: ../index.php');
   exit;
} else {
?>
<!DOCTYPE html>
<html>
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <link rel="stylesheet" href="../style/style.css">
   <link rel="stylesheet" href="../style/style-media-query.css">
   <link rel="shortcut icon" href="../images/favicon.png" type="image/x-icon">
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
      <!-- header - end -->
      <!-- main - start -->
      <main>
         <div class="content">
         <div class="form-circle">
               <div class="massage">Вітаємо на нашому сайті. Оскільки Ви завітали до нас вперше, Вам потрібно створити пароль!</div>
               <div class="form-login">
                  <form action="index.php" method="post">
                     <label for="login">Логін</label>
                        <input type="text" name="login" id="login" placeholder="Іванов І І" value="<?php echo $_SESSION['nickname'];?>"    oninput="this.value = nameDelSymbol(this.value);" 
                        pattern="[A-ZА-Я ІЇЄҐ]{1}[’]?[a-zа-я іїєґ]+[’]?[a-zа-я іїєґ]+\s?[A-ZА-Яa-zа-я ІЇЄҐіїєґ.]+" title="Іванов І.І. або Лук’янов І.І." readonly required>
                     <label for="password">Пароль</label>
                     <input type="password" name="password" id="password" placeholder="Введіть новий пароль" autocomplete="off"  required>
                     <input type="password" name="password-r" id="password-r" class="" placeholder="Повторіть пароль" autocomplete="off" required>
                     <input type="submit" name="log-in" value="Змінити пароль" id="log-in">
                  </form>
                  <?php 
                  if (isset($_POST['log-in'])) {
                     if (isset($_POST['login']) && isset($_POST['password'])) {

                        $loginNormalized = trim($_POST['login']); 
                        $passwordNormalized = trim($_POST['password']); 
                        
                        if ($_POST['password'] == $_POST['password-r']) {
                           if(!empty($loginNormalized) && !empty($passwordNormalized)) {
                              $passwordHash = md5(md5($passwordNormalized).$SMT);

                              if ($_SESSION['nickname'] == $loginNormalized) {
                                 switch ($_SESSION['privileges']) {
                                    case 'admin': 
                                       try {      
                                          $sql = $db->query("UPDATE `user` SET `password_start` = :password,`site_checked` = true WHERE `nickname` = :login",
                                             [
                                                ':login'    => $loginNormalized,
                                                ':password' => $passwordHash
                                             ]
                                          );
                                                
                                       } catch (Exception $e) {
                                          echo "Помилка авторизації! Зверніться до Адміністратора сайту!";    
                                       }
                                       header('Location: ../index.php');
                                       exit;
                                    break;
                                    case 'admin_site': 
                                       try {      
                                          $sql = $db->query("UPDATE `user` SET `password_start` = :password, `site_checked` = true WHERE `nickname` = :login",
                                             [
                                                ':login'    => $loginNormalized,
                                                ':password' => $passwordHash
                                             ]
                                          );
                                                
                                       } catch (Exception $e) {
                                          echo "Помилка авторизації! Зверніться до Адміністратора сайту!";    
                                       }
                                       header('Location: ../index.php');
                                       exit;
                                    break;
                                    case 'teacher': 
                                       try {      
                                          $sql = $db->query("UPDATE `teacher` SET `password_start` = :password,`site_checked` = true WHERE `nickname` = :login",
                                             [
                                                ':login'    => $loginNormalized,
                                                ':password' => $passwordHash
                                             ]
                                          );
                                                
                                       } catch (Exception $e) {
                                          echo "Помилка авторизації! Зверніться до Адміністратора сайту!";    
                                       }
                                       header('Location: ../index.php');
                                       exit;
                                    break;
                                    case 'kurator': 
                                       try {      
                                          $sql = $db->query("UPDATE `teacher` SET `password_start`=:password,`site_checked` = true WHERE `nickname` = :login",
                                             [
                                                ':login'    => $loginNormalized,
                                                ':password' => $passwordHash
                                             ]
                                          );
                                                
                                       } catch (Exception $e) {
                                          echo "Помилка авторизації! Зверніться до Адміністратора сайту!";    
                                       }
                                       header('Location: ../index.php');
                                       exit;
                                    break;
                                    case 'student': 
                                       try {      
                                          $sql = $db->query("UPDATE `student` SET `password_start` = :password,`site_checked` = true WHERE `nickname` = :login",
                                             [
                                                ':login'    => $loginNormalized,
                                                ':password' => $passwordHash
                                             ]
                                          );
                                                
                                       } catch (Exception $e) {
                                          echo "Помилка авторизації! Зверніться до Адміністратора сайту!";    
                                       }
                                       header('Location: ../index.php');
                                       exit;
                                    break;
                                    default:
                                       echo 'Виникла помилка ідентифікації';
                                    break;
                                 }
                              } else {
                                 echo "Введіть свій старий логін!";
                              }
                           }
                           
                        } else {
                              echo '<center><div style="padding-left: 3%; padding-bottom: 2%; width:auto"><p class="massage-error" style="width: auto; color:red;"><img src="../images/error.png" alt="Error!">Паролі повинні співпадати!</p></div></center>';
                        }
                        
                     } else {
                        //
                     }
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
   <script src="../script/preloader.js"></script>
</body>
</html>


