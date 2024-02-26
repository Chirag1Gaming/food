<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Food;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('roles')->get();
        
        if(!empty($users)){
            foreach($users as $user){
                $image_url = url('user/profile_picture/'.$user->pic);
                $user->image_url = $image_url;
            }
        }

        return response([
            'message' => 'Users Details Retrieved Successfully',
            'status' => '200',
            'users' => $users
        ]);
    }

    public function update_profile(Request $request)
    {
        $users = User::find($request->user_id);
        if(empty($users)){
            return response([
                'status' => '400',
                'message' => 'User Not Found',
            ]);
        }

        $fileName = '';
        if ($request->hasFile('pic')) {
            $destinationPath = public_path() . '/user/profile_picture/';
            $file = $request->pic;
            $fileName = time() . '.' . $file->extension();
            $file->move($destinationPath, $fileName);
            $users->pic = $fileName;
        }

        $users->first_name = $request->first_name;
        $users->last_name = $request->last_name;
        $users->dob = date('Y-m-d', strtotime($request->dob));
        $users->contact_no = $request->contact_no;
        $users->gender = $request->gender;
        $users->save();

        return response([
            'status' => '200',
            'message' => 'User Updated Successfully',
            'user' => $users
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
        $user = User::with('roles')->find($id);
        $image_url = url('user/profile_picture/'.$user->pic);
        $user->image_url = $image_url;
        if(empty($user)){
            return response([
                'status' => '400',
                'message' => 'Record Not Fouund',
            ]);
        }else{
            return response([
                'status' => '200',
                'message' => 'User Retrived Successfully.',
                'user' => $user
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
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
        //
    }

    public function user_dashboard($id, Request $request){
        $user = User::find($id);
        $image_url = url('user/profile_picture/'.$user->pic);
        $user->image_url = $image_url;
        if(empty($user)){
            return response([
                'status' => '400',
                'message' => 'User Not Found.',
            ]);
        }
        $food_request_count = Food::where('user_id', $id)->where('type', 'request')->count();
        $food_donate_count = Food::where('user_id', $id)->where('type', 'donate')->count();
        $food_accepted_count = Food::where('user_id', $id)->where('accept_id', '!=', null)->count();
        $food_records = Food::where('user_id', $id)->take(3)->get();

        $data = [];
        $data['user'] = $user;
        $data['food_request_count'] = $food_request_count;
        $data['food_donate_count'] = $food_donate_count;
        $data['food_accepted_count'] = $food_accepted_count;
        $data['food_records'] = $food_records;

        return response([
            'status' => '200',
            'message' => 'Data Retrived Successfully.',
            'data' => $data
        ]);
    }

    public function admin_dashboard(Request $request){
        $food_request_count = Food::where('type', 'request')->count();
        $food_donate_count = Food::where('type', 'donate')->count();
        $food_accepted_count = Food::where('accept_id', '!=', null)->count();
        $food_records = Food::limit('3')->get();

        $year = date('Y');

        // Generate an array of all months in the year
        $months = [];
        for ($month = 1; $month <= 12; $month++) {
            $months[] = $month;
        }

        $userCounts = DB::table(DB::raw('(SELECT ' . implode(' as month UNION ALL SELECT ', $months) . ' as month) months'))
            ->leftJoin('users', function ($join) use ($year) {
                $join->on(DB::raw('MONTH(users.created_at)'), '=', 'months.month')
                    ->whereYear('users.created_at', $year);
            })
            ->select(DB::raw('MONTHNAME(CONCAT("2000-", months.month, "-01")) as month_name'), DB::raw('COUNT(users.id) as total'))
            ->groupBy('months.month')
            ->orderBy('months.month')
            ->get();

        $year = date('Y');

        // Generate an array of all months in the year
        $months = [];
        for ($month = 1; $month <= 12; $month++) {
            $months[] = $month;
        }
        
        $foodCounts = DB::table(DB::raw('(SELECT '. implode(' as month UNION ALL SELECT ', $months) . ' as month) months'))
        ->leftJoin('foods', function($join) use ($year) {
            $join->on(DB::raw('MONTH(foods.created_at)'), '=', 'months.month')
                ->whereYear('foods.created_at', $year);
        })
        ->select(
            DB::raw('MONTHNAME(CONCAT("2000-", months.month, "-01")) as month_name'),
            DB::raw('CAST(SUM(CASE WHEN foods.type = "donate" THEN 1 ELSE 0 END) AS UNSIGNED) as total_donation'),
            DB::raw('CAST(SUM(CASE WHEN foods.type = "request" THEN 1 ELSE 0 END) AS UNSIGNED) as total_request')
        )
        ->groupBy('months.month')
        ->orderBy('months.month')
        ->get();
    
    // $foodCounts now contains total_donation and total_request as integers

        $data = [];
        $data['food_request_count'] = $food_request_count;
        $data['food_donate_count'] = $food_donate_count;
        $data['food_accepted_count'] = $food_accepted_count;
        $data['food_records'] = $food_records;
        $data['user_graph'] = $userCounts;
        $data['food_graph'] = $foodCounts;

        return response([
            'status' => '200',
            'message' => 'Data Retrived Successfully.',
            'data' => $data
        ]);
    }
}
