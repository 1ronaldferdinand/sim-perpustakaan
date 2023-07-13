<?php

namespace App\Http\Controllers\API;

use App\Helpers\ApiFormatter;
use App\Http\Controllers\Controller;
use App\Models\BookModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Publisher;
use Ramsey\Uuid\Uuid;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $search    = $request->search;
        $sort      = $request->sort?? 'book_name';
        $sort_type = $request->sort_type?? 'asc';

        $query     = BookModel::where('book.is_deleted', 0)
                                ->leftjoin('publisher', 'publisher.publisher_id', '=', 'book.publisher_id')
                                ->leftjoin('writer', 'writer.writer_id', '=', 'book.writer_id');

        if(!empty($query)){
            $query->where(function ($q) use ($search){
                $q->where('book_name', 'LIKE', '%'.$search.'%');
                $q->orWhere('book_type', 'LIKE', '%'.$search.'%');
                $q->orWhere('publisher_name', 'LIKE', '%'.$search.'%');
                $q->orWhere('writer_name', 'LIKE', '%'.$search.'%');
            });
        }

        $books     = $query->orderBy($sort, $sort_type)->paginate(10); 
        
        $response = ApiFormatter::createJson(200, 'Get Data Success', $books);
        return $response;
    }

    public function store(Request $request)
    {
        $post = $request->all();

        $validator = Validator::make($post, [
            'book_name'=> 'required',
            'isbn'=> 'required|unique:book',
            'publisher_id'=> 'required',
            'writer_id'=> 'required',
            'book_type'=> 'required',
            'book_publish_city'=> 'required',
            'book_publish_date'=> 'required',
            'book_print_date'=> 'required',
        ]);

        if($validator->fails()){
            $errors = $validator->errors()->all();
            $response = ApiFormatter::createJson(400, 'Input Error', $errors);
            return $response;
        };

        $book = BookModel::create([
            'book_id' => Uuid::uuid1()->toString(),
            'isbn' => $post['isbn'],
            'book_name' => $post['book_name'],
            'publisher_id' => $post['publisher_id'],
            'writer_id' => $post['writer_id'],
            'book_type' => $post['book_type'],
            'book_stock' => $post['book_stock'],
            'book_price' => $post['book_price'],
            'book_size' => $post['book_size'],
            'book_publish_city' => $post['book_publish_city'],
            'book_publish_date' => $post['book_publish_date'],
            'book_print_date' => $post['book_print_date'],
        ]);

        $response = ApiFormatter::createJson(200, 'Input Data Success', $book);
        return $response;
    }

    public function show($id)
    {
        $book = BookModel::where('book_id', $id)->where('is_deleted', 0)->first();
        if(!$book){
            $response = ApiFormatter::createJson(404, 'Data Not Found', null);
            return $response;
        }

        $response = ApiFormatter::createJson(200, 'Get Detail Success', $book);
        return $response;
    }

    public function update(Request $request, $id)
    {
        $post = $request->all();

        $validator = Validator::make($post, [
            'book_name'=> 'required',
            'isbn'=> 'required',
            'publisher_id'=> 'required',
            'writer_id'=> 'required',
            'book_type'=> 'required',
            'book_publish_city'=> 'required',
            'book_publish_date'=> 'required',
            'book_print_date'=> 'required',
        ]);

        if($validator->fails()){
            $response = $validator->errors()->all();
            $response = ApiFormatter::createJson(400, 'Input Error', $response);
            return $response;
        }

        $image = "";

        $book = BookModel::find($id);

        if(!$book){
            $response = ApiFormatter::createJson(404, 'Data Not Found', null);
            return $response;
        }

        $book->isbn = $post['isbn'];
        $book->book_name = $post['book_name'];
        $book->publisher_id = $post['publisher_id'];
        $book->writer_id = $post['writer_id'];
        $book->book_type = $post['book_type'];
        $book->book_stock = $post['book_stock'];
        $book->book_price = $post['book_price'];
        $book->book_size = $post['book_size'];
        $book->book_publish_city = $post['book_publish_city'];
        $book->book_publish_date = $post['book_publish_date'];
        $book->book_print_date = $post['book_print_date'];

        $book->save();

        $response = ApiFormatter::createJson(200, 'Update Data Success', $book);
        return $response;
    }

    public function delete($id)
    {
        $book = BookModel::where('book_id', $id)->first();
        if(!$book){
            $response = ApiFormatter::createJson(404, 'Data Not Found', null);
            return $response;
        }

        $book->is_deleted = 1;
        $book->save();

        $response = ApiFormatter::createJson(200, 'Delete Data Success', null);
        return $response;
    }
}
