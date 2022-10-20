<?php

class DataBase {

   private $link;

   public function __construct() {

      $this->connect();
   }

   private function connect () {
      try{
         $config = require_once 'config.php';

         $dbh = 'mysql:host='.$config['hosts'].';dbname='.$config['database'].';charset='.$config['charset'];

         $this->link = new PDO($dbh, $config['user'], $config['passwords']);
      } catch (Exception $e) {
         echo '<center><div width:100%"><p class="massage" style="width: auto;">Помилка підключення до бази даних!</p></div></center>';
      }
   }

   public function execute ($sql) {

      $sth = $this->link->prepare($sql);

      return $sth->execute();

   }

   public function query ($sql, $params = null) {

      if (!empty($params) && !is_array($params)) {
        throw new Exception("Parameters must be an array or null");
      }
    
      if (empty($params)) {
      $params = [];
      }

      $exe = $this->link->prepare($sql);

      $exe->execute($params); 

      $result = $exe->fetchAll (PDO::FETCH_ASSOC);

      if ($result === false) {
         return [];
      }

      return $result;

   }
}
?>