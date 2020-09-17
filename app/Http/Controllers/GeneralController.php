<?php

namespace App\Http\Controllers;

use Validator;

use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;


use tibonilab\Pdf\PdfFacade as PDF; 
use Mpdf\Mpdf;

use App\Http\Controllers\Controller;

class GeneralController extends Controller
{
    private $db;
    private $table;
    private $field;
    private $condition;

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
        $this->request = $request;
     
        list($found, $routeInfo, $params) = $request->route() ?: [false, [], []];

        $this->db = isset($params['db']) ? $params['db'] : null ;
        $this->table = isset($params['table']) ? $params['table'] : null ;
        $this->field = isset($params['field']) ? $params['field'] : null ;
        $this->condition = isset($params['condition']) ? $params['condition'] : null ;


        $this->connect($this->db);
    }
   
    /**
     *
     */
    public function all()
    {
        // DB::select("SELECT * FROM users");
        //$this->database->select("users", "*");
       
        $result = $this->database->select($this->table, '*');

        return $this->handlers($result);
    }
    
    /**
     *
     */
    public function field(Request $request)
    {
        //$table = 'tabla';

        //$field = ["campo1", "campo2"];

        $limit = $request->input('limit');
        $orderAsc = $request->input('orderAsc');
        $orderDesc = $request->input('orderDesc');

        $field = $request->input('field');
        $fields = isset($field) ?  explode(',', trim($request->input('field'))) : '*' ;


        if (isset($limit)) {
            // $condition[] = ['LIMIT' => explode(',', $limit)];
            $condition['LIMIT'] = $limit;
        }

        if (isset($orderAsc)) {
            // $condition[] = ['LIMIT' => explode(',', $limit)];
            $condition['ORDER'] = [$orderAsc => 'ASC'];
        }

        if (isset($orderDesc)) {
            // $condition[] = ['LIMIT' => explode(',', $limit)];
            $condition['ORDER'] = [$orderDesc => 'DESC'];
        }

        $result = $this->database->select($this->table, $fields, $condition);

        return $this->handlers($result);
    }

    /**
     *
     */
    public function filter(Request $request)
    {

        //$table = 'tabla';
        //$fields = ['campo1', 'campoe'];
        //$condition = ['campo1' => 'calor'];

        $limit = $request->input('limit');
        $orderAsc = $request->input('orderAsc');
        $orderDesc = $request->input('orderDesc');

        $field = $request->input('field');
        $fields = isset($field) ?  explode(',', trim($request->input('field'))) : '*' ;


        if (isset($limit)) {
            // $condition[] = ['LIMIT' => explode(',', $limit)];
            $condition['LIMIT'] = $limit;
        }

        if (isset($orderAsc)) {
            // $condition[] = ['LIMIT' => explode(',', $limit)];
            $condition['ORDER'] = [$orderAsc => 'ASC'];
        }

        if (isset($orderDesc)) {
            // $condition[] = ['LIMIT' => explode(',', $limit)];
            $condition['ORDER'] = [$orderDesc => 'DESC'];
        }

     
        if (!empty($this->field)) {
            $condition[$this->field] = $this->condition;
        }


        $result = $this->database->select($this->table, $fields, $condition);
        
        
        return $this->handlers($result);
    }

    /**
     * GUARDAR.
     *
     * Almacena los registros en la base de datos
     * devuelve json los datos procesados
     *
     * @param array $data se reciben los datos a guardar en bosque
     */
    public function create(Request $request)
    {
        $insert = $request->input('insert');
        $values = $request->input('values');
        $increment = $request->input('increment');

        if (count($values) == 1) {
            $values[0][$increment] = $this->database->max($insert, $increment) + 1;

            $result = $this->database->insert($insert, $values[0]);
        } else {
            $autoIncrement = $this->database->max($insert, $increment) + 1;
            $i = 0;
            $create = array();

            foreach ($values as $key) {
                $key[$increment] = $autoIncrement + $i;
                $create[] = $key;
                $i++;
            }

            $result = $this->database->insert($insert, $create);
        }


        return $this->handlers($result);
    }

    public function create_autoincrement(Request $request)
    {
        $insert = $request->input('insert');
        $values = $request->input('values');      

        if (count($values) == 1) {
            
            $result = $this->database->insert($insert, $values[0]);
        } else {


            $result = $this->database->insert($insert, $create);
        }


        return $this->handlers($result);
    }    


    public function create_data($request)
    {

        
        if (count($request['values']) == 1) {

            $request['values'][0][$request['increment']] = $this->database->max($request['insert'], $request['increment']) + 1;

            $result = $this->database->insert($request['insert'], $request['values'][0]);

        } else {
            $autoIncrement = $this->database->max($request['insert'], $request['increment']) + 1;
            $i = 0;
            $create = array();

            foreach ($request->values as $key) {
                $key[$request->increment] = $autoIncrement + $i;
                $create[] = $key;
                $i++;
            }

            $result = $this->database->insert($request->insert, $create);
        }


        return $this->handlers($result);
        
    } 

    /**
     * ACTUALIZAR.
     *
     * Almacena los registros en la base de datos
     * devuelve json los datos procesados
     *
     * @param array $data se reciben los datos a actualzar en bosque
     */
    public function edit(Request $request)
    {
        $update = $request->input('update');
        $set = $request->input('set');
        $where = $request->input('where');


        $validator =  Validator::make($request->all(), [
            'update' => 'required',
            'set' => 'required',
            'where' => 'required',
        ]);
     

        if ($validator->passes()) {
            //TODO Handle your data
            $result =  $this->database->update($update, $set, $where);

            return $this->handlers($result);
        } else {
            //TODO Handle your error
            //dd($validator->errors()->all());
            return response()->json(['message' => $validator->errors()->all(), "status" => false], 400);
        }
    }

    /**
     * ELIMINAR.
     *
     * Almacena los registros en la base de datos
     * devuelve json los datos procesados
     *
     * @param array $data se reciben los datos a eliminar en bosque
     */
    public function destroy(Request $request)
    {
        $delete = $request->input('delete');
        $where = $request->input('where');

        $validator =  Validator::make($request->all(), [
            'delete' => 'required',
            'where' => 'required',
        ]);
     

        if ($validator->passes()) {
            //TODO Handle your data
            $result = $this->database->delete($delete, $where);

            return $this->handlers($result);
        } else {
            //TODO Handle your error
            //dd($validator->errors()->all());
            return response()->json(['message' => $validator->errors()->all(), "status" => false], 400);
        }
    }

    /**
     * SELECT
     */

    public function select(Request $request)
    {
        $fields_input = $request->input('fields');

        $from  = $request->input('from');
        $fields = isset($fields_input)  ? $fields_input :  '*';
        $where = $request->input('where');

        $result = $this->database->select($from, $fields, $where);
        
        
        return $this->handlers($result);
    }

    /**
     * Upload
     */
    public function upload(Request $request)
    {
        if ($request->file('file')->isValid()) {
            $fileName = $request->file('file')->getClientOriginalName();
            //$request->file('photo')->move($request->input('path'));
            $request->file('file')->move($request->input('path'), $fileName);
            return response()->json(['message' => 'file is upload', "status" => true], 201);
        } else  {
            return response()->json(['message' => 'file is not upload', "status" => false], 406);
        }
    }    

    /**
     * uploadInsert
     */
    public function uploadInsert(Request $request)
    {

        $response = json_decode($request->input('insert'), true, JSON_UNESCAPED_SLASHES);        

        if ($request->file('file')->isValid()) {
            $fileName = $request->file('file')->getClientOriginalName();            
            //$request->file('file')->move($request->input('path'), $fileName);          
 
    
            $response['values'][0]['file'] = $fileName;
            $response['values'][0]['binary'] = base64_encode(file_get_contents($request->file('file')));  
            $response['values'][0]['path'] = $request->input('path');

           
            $data = file_get_contents($request->file('file'));  

            $base64 = 'data:image/' . $request->file('file')->getClientMimeType() . ';base64,' . base64_encode($data);  
            
            $this->create_data($response);

            return response()->json(['message' => 'file is upload', "status" => true], 201);
        } else  {
            return response()->json(['message' => 'file is not upload', "status" => false], 406);
        }        
        
        return response()->json([
          "response" => $response,
          "responses" => $response->insert,
          "status" => true], 200);
    }    
    
    public function createPdf() {


        $html = '<h1> hioa </h1>';
        //return PDF::load($html, 'A4', 'portrait')->show();

        $mpdf = new Mpdf();

        $html = file_get_contents('./view/certificado.php');

        //$html = utf8_encode($html);
        
        $mpdf->WriteHTML($html);
        
        return  $mpdf->Output();
    }

    public function viewPdf() {

        include './test/medoo.php';

        
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
