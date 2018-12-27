<?php   // dbFuncs0.php

  //PDO database connect
  $config['db'] = array(
      'host'      => 'localhost',
      'username'  => 'root',
      'password'  => '',
      'dbname'    => 'db_crud'
  );
    
  try {
      $db = new PDO('mysql:host=' .$config['db']['host']. ';dbname=' .$config['db']['dbname'], $config['db']['username'], $config['db']['password']);
      $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
      $db->exec("SET CHARACTER SET utf8");
  } catch(PDOException $e) {
      die('ERROR - ' . $e->getMessage() . var_dump($config));
  }

?>