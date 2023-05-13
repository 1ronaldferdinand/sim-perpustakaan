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
    public function index()
    {
        $books = BookModel::where('is_deleted', 0)->orderBy('book_name', 'asc')->get();
        $response = ApiFormatter::createJson(200, 'Get Data Success', $books);
        return $response;
    }

    public function store(Request $request)
    {
        $post = $request->all();

        $validator = Validator::make($post, [
            'book_name'=> 'required',
            'isbn'=> 'unique:book',
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

        $image = "";

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
        $book = BookModel::where('book_id', $id)->first();
        $response = ApiFormatter::createJson(200, 'Get Detail Success', $book);
        return $response;
    }

    public function update(Request $request, $id)
    {
        $post = $request->all();

        $validator = Validator::make($post, [
            'book_name'=> 'required',
            'isbn'=> 'unique:book',
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
        $book->is_deleted = 1;
        $book->save();

        $response = ApiFormatter::createJson(200, 'Delete Data Success', null);
        return $response;
    }
}
