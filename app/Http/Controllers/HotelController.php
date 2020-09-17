<?php

namespace App\Http\Controllers;

use Validator;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use Dotenv\Dotenv;

use App\Http\Controllers\Controller;

class HotelController extends Controller
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

        $this->connect(getenv('DB_DATABASE'));
    }


    public function room()
    {

        $result = $this->database->select('room', '*');

        return $this->handlers($result);
    }

    public function reservation()
    {
        $result = $this->database->select('reservation', '*');

        return $this->handlers($result);
    }

    public function reservation_filter(Request $request)
    {

        $validator =  Validator::make($request->all(), [
            'id' => 'required'
        ]);

        if ($validator->passes()) {
            //TODO Handle your data
            $result = $this->database->select('view_reservation', '*', ["id_reservation" => $request->input('id')]);

            return $this->handlers($result);
        } else {
            //TODO Handle your error
            //dd($validator->errors()->all());
            return response()->json(['message' => $validator->errors()->all(), "status" => false], 400);
        }

    }


    public function view_reservation()
    {
        $result = $this->database->select('view_reservation', '*');

        return $this->handlers($result);
    }

    public function create(Request $request)
    {

        $validator =  Validator::make($request->all(), [
            'type' => 'required',
            'checkin' => 'required',
            'checkout' => 'required',
        ]);

        $values = [];
        $values['id_room'] = $request->input('type');
        $values['checkin'] = $request->input('checkin');
        $values['checkout'] = $request->input('checkout');

        if ($validator->passes()) {
            //TODO Handle your data
            $result = $this->database->insert('reservation', $values);

            return $this->handlers($result);
        } else {
            //TODO Handle your error
            //dd($validator->errors()->all());
            return response()->json(['message' => $validator->errors()->all(), "status" => false], 400);
        }


    }

    public function edit(Request $request)
    {

        $validator =  Validator::make($request->all(), [
            'id' => 'required',
            'type' => 'required',
            'checkin' => 'required',
            'checkout' => 'required',
        ]);


        $values = [];
        $values['id_room'] = $request->input('type');
        $values['checkin'] = $request->input('checkin');
        $values['checkout'] = $request->input('checkout');

        if ($validator->passes()) {
            //TODO Handle your data
            $result =  $this->database->update('reservation', $values, ['id_reservation' => $request->input('id')]);

            return $this->handlers($result);
        } else {
            //TODO Handle your error
            //dd($validator->errors()->all());
            return response()->json(['message' => $validator->errors()->all(), "status" => false], 400);
        }
    }

    public function destroy(Request $request)
    {

        $validator =  Validator::make($request->all(), [
            'id' => 'required'
        ]);

        if ($validator->passes()) {
            //TODO Handle your data
            $result = $this->database->delete('reservation', ['id_reservation' => $request->input('id')]);

            return $this->handlers($result);
        } else {
            //TODO Handle your error
            //dd($validator->errors()->all());
            return response()->json(['message' => $validator->errors()->all(), "status" => false], 400);
        }
    }


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
