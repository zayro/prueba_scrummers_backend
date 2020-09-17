<?php

namespace App\Http\Controllers;

use Validator;
use Log;

use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Firebase\JWT\ExpiredException;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Routing\Controller as BaseController;
use App\Http\Controllers\Controller;

//use Illuminate\Support\Facades\Log;

use zayro\src\Controllers\library\encrypt;

use Dotenv\Dotenv;

use PDO;

class AuthController extends Controller
{
    private $db;
    private $table;
    private $field;
    private $condition;

    private static $secret_key = null;
    private static $encrypt = ['HS256'];
    private static $aud = null;


    /**
     * The request instance.
     *
     * @var \Illuminate\Http\Request
     */
    private $request;
    /**
     * Create a new controller instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function __construct(Request $request)
    {
        self::$secret_key = env('JWT_SECRET');

        $this->request = $request;

        list($found, $routeInfo, $params) = $request->route() ?: [false, [], []];

        $this->db = getenv('DB_DATABASE');
        $this->table = isset($params['table']) ? $params['table'] : null ;
        $this->field = isset($params['field']) ? $params['field'] : null ;
        $this->condition = isset($params['condition']) ? $params['condition'] : null ;


        $this->connect($this->db);
    }


    public function asrguDB()
    {
        $this->db = 'astgu';
        $this->table = isset($params['table']) ? $params['table'] : null ;
        $this->field = isset($params['field']) ? $params['field'] : null ;
        $this->condition = isset($params['condition']) ? $params['condition'] : null ;


        $this->connect($this->db);
    }

    /**
     * Create a new token.
     *
     * @param  \App\User   $user
     * @return string
     */
    protected function jwt($data)
    {
        $payload = [
            'iss' => "lumen-jwt", // Issuer of the token
            'aud' => "lumen-jwt", // Issuer of the token
            'iat' => time() , // Time when JWT was issued.
            #'nbf' => time() + 1, //  Timestamp of when the token should start being considered valid.
            #'exp' => time() + ((60*60) * 4), // Expiration time 4 hours,
            'exp' => (time() +  (4 * 60 * 60)), // Expiration time 4 hours,
            'sub' => $data,
            'data' => $data
        ];

        // As you can see we are passing `JWT_SECRET` as the second parameter that will
        // be used to decode the token in the future.
        return JWT::encode($payload, env('JWT_SECRET'), 'HS256');
    }

    /**
     * Authenticate a user and return the token if the provided credentials are correct.
     *
     * @param  \App\User   $user
     * @return mixed
     */

    public function authenticateCompany(Request $request)
    {

        //Log::info('Showing user profile for user: ');

        $this->validate($this->request, [
            'user'     => 'required',
            'password'  => 'required'
        ]);
        // Find the user by email
        $from  = 'users';
        $fields = ['username'];

        $user = $request->input('user');
        $password = $request->input('password');

        $where =  [
            "username" => "$user",
            "password" => "$password"
        ];

        $sql = "SELECT * from $from where (username =  '$user' or email =  '$user' ) ";


        //$result = $this->database->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        $result = $this->database->query($sql)->fetchAll(PDO::FETCH_OBJ);

        $count = method_exists($result, 'rowCount') ? $result->rowCount() : count($result);

        foreach ($result as $row) {
            $passwordSql = $row->password;
        }

        $info = [];

        /*
        $this->medoo();

        $from  = 'precio';
        $fields = '*';
        $where =  [

        ];

        $info = $this->database->select($from, $fields, $where);
        */

        if ($count > 0) {

            // Verify the password and generate the token
            if (Hash::check($password, $passwordSql)) {
                return response()->json([
                    'success' => true,
                    'status' => true,
                    'message' => "Auth Success",
                    'result' => $result,
                    'info' => $info,
                    'token' => $this->jwt($result)
                ], 200);
            } else {

                // Bad Request response
                return response()->json([
                'error' => 'password is wrong.',
                'pass' => $password
            ], 400);
            }
        } else {
            return response()->json([
                    'status' => false,
                    'result' => $this->database->log(),
                    'message' => "Auth Denid User",
                    'count' => "$count"
                ], 401);
        }
    }


    /**
     *
     */
    public function changePasswordCompany(Request $request)
    {
        $this->validate($this->request, [
            'newPass'  => 'required',
            'user'  => 'required'
        ]);

        $newPass = Hash::make($request->input('newPass'));
        $user = $request->input('user');


        $result = $this->database->pdo->prepare("UPDATE users set
        `password` = '$newPass'
        WHERE
        username = '$user'  ");

        $result->execute();


        if ($result->rowCount() > 0) {
            return $this->handlers($result);
        } else {
            $msj['success'] = false;
            $msj['status'] = false;
            $msj['error'] = $this->database->error();
            $msj['sql'] = $this->database->log();
            return $msj;
        }
    }


    /**
     * Recovery Password
     */
    public function recoveryPasswordCompany(Request $request)
    {
        $this->validate($this->request, [
            'email'     => 'required'
        ]);

        $email = $request->input('email');

        //Log::info('Showing email: '.$email);

        $pass = bin2hex(random_bytes(6));

        $result = $this->database->pdo->prepare("SELECT * FROM users  WHERE  email = '$email'  ");

        $result->execute();

        if ($result->rowCount() > 0) {

        if (view()->exists('auth.view_recoveryPasswordCompany')) {

            //Log::info('encontro view');

            return view(
                'auth.view_recoveryPasswordCompany',
                [
                'email' => $email,
                'newpass' => $pass
                ]
            );
        } else {
            $msj['success'] = false;
            $msj['status'] = false;
            $msj['msg'] = 'view email not found';

            return $msj;
        }

        } else {
            $msj['success'] = false;
            $msj['status'] = false;
            $msj['msg'] = 'error email not found';
            $msj['error'] = $this->database->error();
            $msj['sql'] = $this->database->log();
            return $msj;
        }

        /*

        $result = $this->database->insert($insert, $create);

        if ($this->database->error()[0] != 00000) {

            $msj['error'] = $this->database->error();
            $msj['sql'] = $this->database->log();
            return $msj;
        } else {
             return view(
            'auth/view_recovery_pass_company',
            [
            'email' => $email['email']
            ]
        );
        }
        */
    }


    private static function Aud()
    {
        $aud = '';

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $aud = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $aud = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $aud = $_SERVER['REMOTE_ADDR'];
        }

        $aud .= @$_SERVER['HTTP_USER_AGENT'];
        $aud .= gethostname();

        return sha1($aud);
    }

    public static function GetData($token)
    {
        return JWT::decode(
            $token,
            env('JWT_SECRET'),
            self::$encrypt
        )->data;
    }

    public function Check(Request $request)
    {
        $token = $request->input('token');

        if (empty($token)) {
            throw new Exception("Invalid token supplied.");
        }


        /*         if($decode->aud !== self::Aud())
        {
            throw new Exception("Invalid user logged in.");
        } */

        try {
            $decode = JWT::decode(
                $token,
                env('JWT_SECRET'),
                ['HS256']
            )->data;

            return response()->json([
                'exitos' => 'token valido.',
                'data' => $decode
            ], 200);
        } catch (ExpiredException $e) {
            return response()->json([
                'error' => 'Provided token is expired.'
            ], 400);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'An error while decoding token.',
                'token' => $token,
            ], 400);
        }
    }
}
