<?php

/*
    Class implementing Singleton pattern to get a cursor to the current database.
*/
class MysqlDatabase {
    private static $host = 'localhost';
    private static $db = 'realdb';
    private static $user = 'wt1_prakt';
    private static $pw = 'abcd';
    /* cursor to DB connection */
    private static $cursor = null;
    /* Singleton instance - not needed in class methods */
    private static $instance = null;
    /*
        Use this method to get access to the database connection.
    */
    public static function get_instance(){
        if(self::$instance == null){
            self::$instance = new MysqlDatabase();
        }
        return self::$instance;
    }
    /*
        Private constructor to implement Singleton. Do not use this method for instatiation!
    */
    public static function connection(){
        try{
             self::$cursor = new PDO('mysql:host='.self::$host .';dbname='.self::$db, self::$user, self::$pw);
		} 
		catch(PDOException $e){
			echo "Verbindungsaufbau gescheitert: " . $e->getMessage();
		}
    }
	private function __construct(){
		self::connection();
    }
    /*
        Do not call this method directly.
    */
	public function __destruct(){
		self::$cursor = NULL;	
    }

    public static function fetchdata(){
        try {
            self::connection();
            $pdo = self::$cursor;
            $stmt = $pdo->query("SELECT id, due_date, description, priority FROM Task");
            return json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        } catch (PDOException $e) {
            return "Datenbankfehler: " . $e->getMessage();
        }
    }
    public static function delete_task($id){
        try {
            self::connection();
            $pdo = self::$cursor;
            $sql = "DELETE FROM Task WHERE id =?";
            $stmt= $pdo->prepare($sql);
            $stmt->execute([$id]);
            if ($stmt->rowCount() > 0) {
                return  "Datensatz erfolgreich gelöscht.";
            }
        } catch (PDOException $e) {
            return "Datenbankfehler: " . $e->getMessage();
        }
    }   
}
if (isset($_GET['function']) && $_GET['function'] == 'fetchdata') {
    echo  MysqlDatabase::fetchdata();
}

if ( isset($_POST['id']) && !empty($_POST['id']) ){
    echo  MysqlDatabase::delete_task($_POST['id']);

}
?>
