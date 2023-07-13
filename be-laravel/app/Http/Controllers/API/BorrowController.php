<?php

namespace App\Http\Controllers\API;

use App\Helpers\ApiFormatter;
use App\Http\Controllers\Controller;
use App\Models\BookModel;
use App\Models\BorrowModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class BorrowController extends Controller
{
    public function index(Request $request)
    {
        $search    = $request->search;
        $sort_type = $request->sort_type;
        $sort_name = $request->sort;

        $query     = BorrowModel::Select('*')
            ->where('is_deleted', 0)
            ->with('member', 'book');

        if(!empty($search)){
            $query->where(function ($q) use ($search){
                $q->orWhere('status', 'LIKE', '%'.$search.'%');
                $q->orWhere('member.member_name', 'LIKE', '%'.$search.'%');
                $q->orWhere('member.member_phone', 'LIKE', '%'.$search.'%');
                $q->orWhere('member.member_email', 'LIKE', '%'.$search.'%');
                $q->orWhere('book.book_name', 'LIKE', '%'.$search.'%');
                $q->orWhere('book.book_type', 'LIKE', '%'.$search.'%');
            });
        }

        if($sort_name != null | $sort_type != null){
            $columns        = Schema::getColumnListing('borrow'); // Mendapatkan daftar kolom dari tabel borrow
            $typeOfSorting  = ['asc', 'ASC', 'desc', 'DESC']; 

            if (in_array($sort_name, $columns) & in_array($sort_type, $typeOfSorting)) {
                $query->orderByRaw("`$sort_name` $sort_type");
            } else {
                // Nama kolom yang diberikan tidak valid, lakukan tindakan yang sesuai, misalnya beri nilai default
                $query->orderBy('borrow_start', 'desc');
            }
        } else {
            $query->orderBy('borrow_start', 'desc');
        }

        $borrow = $query->get();

        return ApiFormatter::createJson(200, 'Get Data Success', $borrow);
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
            return ApiFormatter::createJson(400, 'Input Error', $errors);
        }

        $today = Carbon::now();
        if($post['borrow_end'] <= $today){
            return ApiFormatter::createJson(400, 'Input Error', 'Borrow end date must be after this day');
        }

        $books = BookModel::Where('book_id', '=', $post['book_id'])->where('is_deleted', 0)->first();

        if(empty($books)){
            return ApiFormatter::createJson(400, 'Input Error', 'Book not found');
        }

        if($books->book_stock < $post['borrow_amount']){
            return ApiFormatter::createJson(400, 'Input Error', 'Book stocks is not enough');
        }
        
        $borrow = BorrowModel::create([
            'borrow_id'=> Uuid::uuid1()->toString(),
            'book_id'=> $post['book_id'],
            'member_id'=> $post['member_id'],
            'borrow_amount'=> $post['borrow_amount'],
            'borrow_start'=> $post['borrow_start'],
            'borrow_end'=> $post['borrow_end'],
        ]);

        $books->update([
            'book_stock' => $books->book_stock - $borrow->borrow_amount,
        ]);

        return ApiFormatter::createJson(201, 'Input Data Success', $borrow);
    }

    public function show($id)
    {
        $borrow = BorrowModel::where('borrow_id', $id)
                            ->where('is_deleted', 0)
                            ->with('member', 'book')
                            ->first();

        if($borrow == null){
            return ApiFormatter::createJson(404, 'Data is not found');
        }

        return ApiFormatter::createJson(200, 'Get Detail Success', $borrow);
    }

    public function update(Request $request, $id)
    {
        $borrow = BorrowModel::where('borrow_id', $id)->where('is_deleted', 0)->first();

        if($borrow == null){
            return ApiFormatter::createJson(404, 'Data is not found');
        }

        $post = $request->all();

        $validator = Validator::make($post, [
            'book_id'=> 'required',
            'member_id'=> 'required',
            'borrow_start'=> 'required',
            'borrow_end'=> 'required'
        ]);

        if($validator->fails()){
            $errors = $validator->errors()->all();
            return ApiFormatter::createJson(400, 'Input Error', $errors);
        }

        $today = Carbon::now();

        if($post['borrow_end'] <= $today){
            return ApiFormatter::createJson(400, 'Input Error', 'Borrow end date must be after this day');
        }

        $books = BookModel::Where('book_id', '=', $post['book_id'])->where('is_deleted', 0)->first();

        if(empty($books)){
            return ApiFormatter::createJson(400, 'Input Error', 'Book not found');
        }
        
        if($borrow->borrow_amount != $post['borrow_amount']){ 
            $oldAmount = $borrow->amount;

            if($books->book_stock < $post['borrow_amount']){
                return ApiFormatter::createJson(400, 'Input Error', 'Book stock is not enough');
            }

            $books->update([
                'book_stock' => ($books->book_stock + $oldAmount) - $post['borrow_amount'],
            ]);
        }

        $borrow->book_id = $post['book_id'];
        $borrow->member_id = $post['member_id'];
        $borrow->borrow_amount = $post['borrow_amount'];
        $borrow->borrow_start = $post['borrow_start'];
        $borrow->borrow_end = $post['borrow_end'];
        $borrow->save();

        return ApiFormatter::createJson(200, 'Update data success', $borrow);
    }

    public function return($id){
        $borrow = BorrowModel::where('borrow_id', $id)->first();
        if($borrow == null){
            return ApiFormatter::createJson(404, 'Data is not found');
        }

        $borrow->status = "return";
        $borrow->save();
        
        $books = BookModel::where('book_id', $borrow->book_id)->first();
        $books->update([
            'book_stock' => $books->book_stock + $borrow->borrow_amount,
        ]); 

        return ApiFormatter::createJson(200, 'Return Data Success', null);
    }

    public function delete($id)
    {
        $borrow = BorrowModel::where('borrow_id', $id)->where('is_deleted', 0)->first();
        if($borrow == null){
            return ApiFormatter::createJson(404, 'Data is not found');
        }
        
        $books = BookModel::where('book_id', $borrow->book_id)->first();
        $books->update([
            'book_stock' => $books->book_stock + $borrow->borrow_amount,
        ]); 
        
        $borrow->is_deleted = 1;
        $borrow->save();

        return ApiFormatter::createJson(200, 'Delete Data Success', null);
    }
}
