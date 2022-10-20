<?php

include '../db/setting.php';
require_once '../db/db.php';

$speciality = new Speciality();


$specialtyNormalized = trim($_POST['name']);

if (!empty($specialtyNormalized)) {
   $sql = $speciality->delete($specialtyNormalized);

   if ($sql === 1) echo '<div style="padding-left: 3%; padding-bottom: 2%; width:auto"><p class="massage-error" style="width: auto; color:red;"><img src="../images/error.png" alt="Error!">Спеціальності не існує!</p></div>';
   else echo '<div style="padding-left: 3%; padding-bottom: 2%; width:90%"><p class="massage-error" style="width: auto; color:green;"><img src="../images/ok.png" alt="Ok!">Спеціальність видалена!</p></div>';
}
