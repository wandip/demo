<?php
/**
 * Here we are doing Abstraction do that there will be no need to call sql function
 * @package MessedUp!
 * @subpackage  Database
 * @author  Tanmay Singhal
 * @method execSql
 * */
class Db{


    /**
     * @property $host is localhost always
     * @property $username is the username required by database
     * @property $password is the user's password
     * @property $db_name is the name of the database
     * */

    private $host       =   null;
    private $username   =   null;
    private $password   =   null;
    private $db_name    =   null;
    private $conn;


    /**
     * Here the constructor of the Abstract Class because our database is
     * dashboard if we are working on dashboard only then why take effort
     * to every time generate a new connection so now this constructor
     * will generate a new connection
     * @return $conn a PDO resource
     * */

    #Note: This is parameterized because we can then swtich between the DBs easily,
    # This server has 3 Dbs namely, whdb, wifidog, auth-wifidog
    public function __construct($host, $username, $password, $db_name)
    {
            $this->$host     = $host;
            $this->$username = $username;
            $this->$password = $password;
            $this->$db_name  = $db_name;

            try {
                $this->conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
                $this->conn->setAttribute( PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                $this->conn->exec("SET CLIENT_ENCODING TO UTF8");  //  return all sql requests as UTF-8
                return $this->conn;
            }
            catch (PDOException $err) {
                //echo "DB facing Problem";
                //$err->getMessage() . "<br/>";
             //   file_put_contents('PDOErrors.txt',$err, FILE_APPEND);  // write some details to an error-log outside public_html
                //die();  //  terminate connection
            }
        return $this->conn;
    }


    /**
     * Execute a Sql statement and return a result True or False, and if true send the data
     * @param $sql This take a valid sql statement
     * @param $result_set A 2-dimensionnal array containing result rows, NULL if empty
     * @return TRUE indicated the query went fine, FALSE something went wrong
     *
     * */
    public function execSql($sql, $params, & $result_set)
    {

        $result = $this->conn->prepare($sql);

        $result->execute($params);
        if($result == FALSE)
        {
            $result_set   = NULL;
            $return_value = FALSE;
        }
        else if($result->rowCount() == 0)
        {
            $result_set   = NULL;
            $return_value = TRUE;
        }else
        {
            $result_set   = $result->fetchAll();
            $return_value = TRUE;
        }
        return $return_value;


    }

    public function updateSql($sql, $params, & $result_set)
    {

        $result = $this->conn->prepare($sql);

        $result->execute($params);
        if($result  == FALSE)
        {
            $result_set   = NULL;
            $return_value = FALSE;
        }
        else if($result == TRUE)
        {
            $result_set   = NULL;
            $return_value = TRUE;
        }
        return $return_value;

    }


}
