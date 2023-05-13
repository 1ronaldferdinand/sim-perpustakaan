<?php

namespace App\Http\Controllers\API;

use App\Helpers\ApiFormatter;
use App\Http\Controllers\Controller;
use App\Models\BorrowModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class BorrowController extends Controller
{
    public function index()
    {
        $borrow = BorrowModel::where('borrow.is_deleted', 0)
            ->leftjoin('book', 'book.book_id', '=', 'borrow.book_id')
            ->leftjoin('member', 'member.member_id', '=', 'borrow.member_id')
            ->groupBy('borrow.member_id')
            ->orderBy('borrow.borrow_start', 'desc')
            ->get();
        $response = ApiFormatter::createJson(200, 'Get Data Success', $borrow);
        return $response;
    }

    public function borrow(Request $request)
    {
        $post = $request->all();

        $validator = Validator::make($post, [
            'book_id'=> 'required',
            'member_id'=> 'required',
            'borrow_start'=> 'required',
            'borrow_end'=> 'required'
        ]);

        if($validator->fails()){
            $errors = $validator->errors()->all();
            $response = ApiFormatter::createJson(200, 'Input Error', $errors);
            return $response;
        }

        $borrow = BorrowModel::create([
            'borrow_id'=> Uuid::uuid1()->toString(),
            'book_id'=> $post['book_id'],
            'member_id'=> $post['member_id'],
            'borrow_amount'=> $post['borrow_amount'],
            'borrow_start'=> $post['borrow_start'],
            'borrow_end'=> $post['borrow_end'],
        ]);

        $response = ApiFormatter::createJson(200, 'Input Data Success', $borrow);
        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
