<?php 

final class DB implements Singleton
{
	private $connection;

    private static $instance; 

    private static $options = [
    	PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    private function __construct()
    {	
    	$settings = getSettings();

    	$dsn = $this->getDsn($settings);

        try {

            $this->connection = new PDO($dsn, $settings['user'], $settings['password'], self::$options);

        } catch (PDOException $e) {

            die('Подключение не удалось: ' . $e->getMessage());
            
        }
    }

    private function __clone()    {  } 

    private function __wakeup()   {  } 

    public static function getInstance()
    { 
        if ( empty(self::$instance) ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function getConnection():PDO
    { 
    	return $this->connection;
    }

    private function getDsn(array $settings):string
    {
    	return $settings['dbType'].":host=".$settings['host'].";dbname=".$settings['db'].";charset=".$settings['charset'];
    }
 }