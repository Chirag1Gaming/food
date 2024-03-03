<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Review;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reviews = Review::with('user')->get();

        return response([
            'status' => 200,
            'message' => 'Data Retrieved Successfully.',
            'data' => $reviews,
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
        $input = $request->all();
        if(!empty($request->id)){
            $review = Review::find($request->id);

            if(empty($review)){
                return response([
                    'status' => 400,
                    'message' => 'Review Not Found',
                ]);
            }
        }else{
            $review = new Review();
        }

        $review->user_id = $request->user_id;
        $review->title = $request->title;
        $review->text = $request->text;
        $review->rating = $request->rating;
        $review->is_approved = 0;
        $review->save();

        if(!empty($request->id)){
            $message = 'Review Updated Successfully.';
        }else{
            $message = 'Review Added Successfully.';
        }

        return response([
            'status' => 200,
            'message' => 'Review Added Successfully.',
            'data' => $review,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $review = Review::with('user')->find($id);

        if(empty($review)){
            return response([
                'status' => 400,
                'message' => 'Review not found',
            ]);
        }else{
            return response([
                'status' => 200,
                'message' => 'Review Retrieved Successfully.',
                'data' => $review
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
        $review = Review::find($id);

        if(empty($review)){
            return response([
                'status' => 400,
                'message' => 'Review not found'
            ]);
        }

        $review->delete();

        return responnse([
            'status' => 200,
            'message' => 'Review Deleted Successfully',
            'data' => $review
        ]);
    }

    public function approveReview(Request $request){
        $review = Review::find($id);

        if(empty($review)){
            return response([
                'status' => 400,
                'message' => 'Review not found'
            ]);
        }

        $review->is_approved = 1;
        $review->save();

        return responnse([
            'status' => 200,
            'message' => 'Review Approved Successfully',
            'data' => $review
        ]);
    }

    public function rejectReview(Request $request){
        $review = Review::find($id);

        if(empty($review)){
            return response([
                'status' => 400,
                'message' => 'Review not found'
            ]);
        }

        $review->is_approved = 0;
        $review->save();

        return responnse([
            'status' => 200,
            'message' => 'Review Reject Successfully',
            'data' => $review
        ]);
    }
}

