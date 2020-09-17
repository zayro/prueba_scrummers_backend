<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Medoo\Medoo;
use PDO;

class Controller extends BaseController
{
    public $database;
    public $connectest;
        
    public function __construct()
    {
    }

    public function connect($db)
    {
        $database_type = env('DB_CONNECTION');
        $database_name = ($db == null) ? env('DB_DATABASE') : $db;
        $user = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        $port = env('DB_PORT');
        $server = env('DB_HOST');
                   
        // realiza autenticacion con parametros
        $default = array(
                  // required
                  'database_type' => "$database_type",
                  'database_name' => "$database_name",
                  'server' => "$server",
                  'username' => "$user",
                  'password' => "$password",
    
                  // [optional]
                  'charset' => 'utf8',
                  'port' => $port,
    
                  // [optional] Enable logging (Logging is disabled by default for better performance)
                  'logging' => true,
    
                  // [optional] driver_option for connection, read more from http://www.php.net/manual/en/pdo.setattribute.php
                  'option' => array(
                    PDO::ATTR_CASE => PDO::CASE_NATURAL, PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC,
                  ),
    
                  // [optional] Medoo will execute those commands after connected to the database for initialization
                  'command' => array(
                    'SET SQL_MODE=ANSI_QUOTES',
                ),
                  );
    
        $this->database = new Medoo($default);
    }

    /**
     * MANEJADOR.
     *
     * Almacena los registros en la base de datos
     * devuelve json los datos procesados
     *
     * @param array $data se reciben los datos a eliminar en bosque
     */
    public function handlers($result)
    {
        if ($this->database->error()[0] != 00000) {
            $msj['success'] = false;
            $msj['status'] = false;
            $msj['error'] = $this->database->error();
            $msj['sql'] = $this->database->log();
        } else {
            $msj['success'] = true;
            $msj['status'] = true;
            //$msj['sql'] = $this->database->log();
            $msj['count'] = method_exists($result, 'rowCount') ? $result->rowCount() : count($result);
            $msj['message'] = 'Proceso Enviado';
            $msj['data'] = $result;
        }

        return $msj;
    }
        
}
