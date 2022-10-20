<?php

class DataBase {

   private $host, $database, $charset, $user, $password;

    /**
     * @var PDO
     */
    private $link;


    public function __construct() {
       $config = require_once 'config.php';

       $this->host = $config['hosts'];
       $this->database = $config['database'];
       $this->charset = $config['charset'];
       $this->user = $config['user'];
       $this->password = $config['passwords'];

       $this->connect();
   }

   private function connect () {
      try {
//          print_r($this);
         $dbh = 'mysql:host='.$this->host.';dbname='.$this->database.';charset='.$this->charset;
         $this->link = new PDO($dbh, $this->user, $this->password);
      } catch (Exception $e) {
         echo '<center><div width:100%"><p class="massage" style="width: auto;">Помилка підключення до бази даних!</p></div></center>';
      }
   }

   public function execute ($sql): bool
   {
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

      if (!$this->link) { $this->connect();}
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