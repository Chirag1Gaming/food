<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Food;
use App\Models\FoodPics;
use DB;
use App\Events\NoticationEvent;

class FoodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function get_food_waste_history(){
        $foods = Food::select('foods.id', 'foods.type', 'foods.title', 'foods.text', 'foods.quantity', 'foods.location', 'foods.expired', DB::raw("CONCAT(users.first_name, ' ', users.last_name) AS user_name"), DB::raw("CONCAT(acceptors.first_name, ' ', acceptors.last_name) AS accept_name"))
            ->leftJoin('users', 'foods.user_id', '=', 'users.id')
            ->leftJoin('users as acceptors', 'foods.accept_id', '=', 'acceptors.id')
            ->with('pics')
            ->get();


        return response([
            'status' => 200,
            'message' => 'Data Retrieved Successfully',
            'data' => $foods
        ]);
    }

    public function get_food_request_list(){
        $foods = Food::select('foods.id', 'foods.type', 'foods.title', 'foods.text', 'foods.quantity', 'foods.location', 'foods.expired', DB::raw("CONCAT(users.first_name, ' ', users.last_name) AS user_name"), DB::raw("CONCAT(acceptors.first_name, ' ', acceptors.last_name) AS accept_name"))
            ->leftJoin('users', 'foods.user_id', '=', 'users.id')
            ->leftJoin('users as acceptors', 'foods.accept_id', '=', 'acceptors.id')
            ->where('type', 'request')
            ->with('pics')
            ->get();


        return response([
            'status' => 200,
            'message' => 'Data Retrieved Successfully',
            'data' => $foods
        ]);
    }

    public function get_my_food_request_list($user_id){
        $foods = Food::select('foods.id', 'foods.type', 'foods.title', 'foods.text', 'foods.quantity', 'foods.location', 'foods.expired', DB::raw("CONCAT(users.first_name, ' ', users.last_name) AS user_name"), DB::raw("CONCAT(acceptors.first_name, ' ', acceptors.last_name) AS accept_name"))
            ->leftJoin('users', 'foods.user_id', '=', 'users.id')
            ->leftJoin('users as acceptors', 'foods.accept_id', '=', 'acceptors.id')
            ->where('type', 'request')
            ->where('user_id', $user_id)
            ->with('pics')
            ->get();


        return response([
            'status' => 200,
            'message' => 'Data Retrieved Successfully',
            'data' => $foods
        ]);
    }

    public function get_food_donate_list(){
        $foods = Food::select('foods.id', 'foods.type', 'foods.title', 'foods.text', 'foods.quantity', 'foods.location', 'foods.expired', DB::raw("CONCAT(users.first_name, ' ', users.last_name) AS user_name"), DB::raw("CONCAT(acceptors.first_name, ' ', acceptors.last_name) AS accept_name"))
            ->leftJoin('users', 'foods.user_id', '=', 'users.id')
            ->leftJoin('users as acceptors', 'foods.accept_id', '=', 'acceptors.id')
            ->where('type', 'donate')
            ->with('pics')
            ->get();

        return response([
            'status' => 200,
            'message' => 'Data Retrieved Successfully',
            'data' => $foods
        ]);
    }

    public function get_my_food_donate_list($user_id){
        $foods = Food::select('foods.id', 'foods.type', 'foods.title', 'foods.text', 'foods.quantity', 'foods.location', 'foods.expired', DB::raw("CONCAT(users.first_name, ' ', users.last_name) AS user_name"), DB::raw("CONCAT(acceptors.first_name, ' ', acceptors.last_name) AS accept_name"))
            ->leftJoin('users', 'foods.user_id', '=', 'users.id')
            ->leftJoin('users as acceptors', 'foods.accept_id', '=', 'acceptors.id')
            ->where('type', 'donate')
            ->where('user_id', $user_id)
            ->with('pics')
            ->get();

        return response([
            'status' => 200,
            'message' => 'Data Retrieved Successfully',
            'data' => $foods
        ]);
    }

    public function get_food_history(){
        $foods = Food::select('foods.id', 'foods.type', 'foods.title', 'foods.text', 'foods.quantity', 'foods.location', 'foods.expired', DB::raw("CONCAT(users.first_name, ' ', users.last_name) AS user_name"), DB::raw("CONCAT(acceptors.first_name, ' ', acceptors.last_name) AS accept_name"))
            ->leftJoin('users', 'foods.user_id', '=', 'users.id')
            ->leftJoin('users as acceptors', 'foods.accept_id', '=', 'acceptors.id')
            ->with('pics')
            ->get();

        return response([
            'status' => 200,
            'message' => 'Data Retrieved Successfully',
            'data' => $foods
        ]);
    }

    public function create_edit_food(Request $request){
        $input_data = $request->all();
        if(!empty($input_data['id'])){
            $id = $input_data['id'];
            $food = Food::find($id);
            if(empty($food)){
                return response([
                    'status' => '400',
                    'message' => 'Record Not Fouund',
                ]);
            }
        }else{
            $food = new Food;
        }

        $food->user_id = $request->user_id;
        $food->title = $request->title;
        $food->type = $request->type;
        $food->text = $request->text;
        $food->quantity = $request->quantity;
        $food->location = $request->location;
        $food->expired = 0;
        $food->save();

        $fileName = '';
        if ($request->hasFile('pic')) {
            $food->pics()->delete();
            $files = $request->pic;
            if(!empty($files) && is_array($files) && count($files) > 0){
                foreach($files as $file){
                    $destinationPath = public_path() . '/foods/';
                    $fileName = time() . '.' . $file->extension();
                    $file->move($destinationPath, $fileName);
                    $food_pic = new FoodPics();
                    $food_pic->user_id = $request->user_id;
                    $food_pic->food_id = $food->id;
                    $food_pic->name = $fileName;
                    $food_pic->path = $fileName;
                    $food_pic->save();
                }
            }
        }

        $notification_message = '';
        if($request->type == 'donate'){
            $message = 'Food Donate Added Sucessfully';
            $notification_message = "Food Donation is added.";
        }else{
            $message = 'Food Request Added Sucessfully';
            $notification_message = "Food Request is added.";
        }
        event(new NoticationEvent($notification_message));

        return response([
            'status' => 200,
            'message' => $message,
            'data' => $food,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $input_data = $request->all();
        $food = new Food;
        $food->user_id = $request->user_id;
        $food->title = $request->title;
        $food->type = $request->type;
        $food->text = $request->text;
        $food->quantity = $request->quantity;
        $food->location = $request->location;
        $food->expired = 0;
        $food->save();
        $notification_message = "Food Request is added.";
        event(new NoticationEvent($notification_message));

        return response([
            'status' => 200,
            'message' => 'Food Request Added Sucessfully',
            'data' => $food,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $food = Food::with('user')->with('pics')->find($id);
        if(empty($food)){
            return response([
                'status' => '400',
                'message' => 'Record Not Fouund',
            ]);
        }else{
            return response([
                'status' => '200',
                'message' => 'Food Record Retrived Successfully.',
                'food' => $food
            ]);
        }
    }

    public function update_donate_food(Request $request){
        $input_data = $request->all();
        $food = Food::find($id);
        if(empty($food)){
            return response([
                'status' => 400,
                'message' => 'Donation Not Found',
            ]);
        }
        $food->user_id = $request->user_id;
        $food->title = $request->title;
        $food->type = $request->type;
        $food->text = $request->text;
        $food->quantity = $request->quantity;
        $food->location = $request->location;
        $food->expired = 0;
        $food->save();
        $notification_message = "Food Donate is added.";
        event(new NoticationEvent($notification_message));

        return response([
            'status' => 200,
            'message' => 'Food Donnate Added Sucessfully',
            'data' => $food,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, string $id)
    {
        $input_data = $request->all();
        $food = Food::find($id);
        if(empty($food)){
            return response([
                'status' => 400,
                'message' => 'Request Not Found',
            ]);
        }
        $food->user_id = $request->user_id;
        $food->title = $request->title;
        $food->type = $request->type;
        $food->text = $request->text;
        $food->quantity = $request->quantity;
        $food->location = $request->location;
        $food->expired = 0;
        $food->save();

        $notification_message = "Food Request is added.";
        event(new NoticationEvent($notification_message));

        return response([
            'status' => 200,
            'message' => 'Food Request Updated Sucessfully',
            'data' => $food,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $food = Food::find($id);
        if(!empty($food)){
            $food->delete();
            if($food->type == 'donate'){
                $message = 'Food Donation Deleted Successfully';
            }else{
                $message = 'Food Request Deleted Successfully';
            }
            return response([
                'status' => 200,
                'message' => $message,
                'data' => $food,
            ]);
        }else{
            return response([
                'status' => 400,
                'message' => 'Record Not Found',
            ]);
        }
    }

    public function accept_food_request(Request $request){
        $food = Food::find($request->food_id);
        if(empty($food)){
            return response([
                'status' => '400',
                'message' => 'Request Not Found'
            ]);
        }

        $food->accept_id = $request->user_id;
        $food->save();

        return response([
            'status' => '200',
            'message' => 'Request Accepted Suucessfully',
        ]);
    }

    public function donee_found(string $id){
        $user = User::with('roles')->find($id);
        if(empty($user)){
            return response([
                'status' => '400',
                'message' => 'Record Not Fouund',
            ]);
        }else{
            return response([
                'status' => '200',
                'message' => 'Donee Retrived Successfully.',
                'user' => $user
            ]);
        }
    }
}
