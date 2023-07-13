<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WriterModel;
use App\Helpers\ApiFormatter;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class WriterController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $sort   = $request->sort?? 'writer_name';
        $sort_type = $request->sort_type?? 'asc';

        $query = WriterModel::where('is_deleted', 0);

        if(!empty($search)){
            $query->where(function ($q) use ($search){
                $q->where('writer_name', 'LIKE', '%'.$search.'%');
                $q->orWhere('writer_phone', 'LIKE', '%'.$search.'%');
                $q->orWhere('writer_email', 'LIKE', '%'.$search.'%');
            });
        }
        
        $data = $query->orderBy($sort, $sort_type)->paginate(10);

        $response = ApiFormatter::createJson(200, 'Get Data Success', $data);
        return $response;
    }

    public function store(Request $request)
    {
        $post = $request->all();
        
        $validator = Validator::make($post, [
            'writer_name' => 'required',
            'writer_email' => 'email|unique:writer',
        ]);
        
        if($validator->fails()){
            $response = $validator->errors()->all();
            $response = ApiFormatter::createJson(400, 'Input Error', $response);
            return $response;
        }
        
        $image = "";

        $writer = WriterModel::create([
            'writer_id' => Uuid::uuid1()->toString(),
            'writer_name' => $post['writer_name'],
            'writer_phone' => $post['writer_phone'],
            'writer_email' => $post['writer_email'],
            'writer_address' => $post['writer_address'],
            'writer_image' => $image
        ]);

        $response = ApiFormatter::createJson(200, 'Input Data Success', $writer);
        return $response;
    }

    public function show($id)
    {
        $writer = WriterModel::where('writer_id', $id)->first();
        
        if(empty($writer)){
            $response = ApiFormatter::createJson(400, 'Data is not founded', null);
            return $response;
        }
        
        $response = ApiFormatter::createJson(200, 'Get Detail Success', $writer);
        return $response;
    }

    public function update(Request $request, $id)
    {
        $post = $request->all();

        $validator = Validator::make($post, [
            'writer_name' => 'required',
            'writer_email' => 'email'
        ]);

        if($validator->fails()){
            $response = $validator->errors()->all();
            $response = ApiFormatter::createJson(400, 'Input Error', $response);
            return $response;
        }

        $image = "";

        $writer = WriterModel::find($id);

        if(!$writer){
            $response = ApiFormatter::createJson(404, 'Data Not Found', null);
            return $response;
        }

        $writer->writer_name = $post['writer_name'];
        $writer->writer_phone = $post['writer_phone'];
        $writer->writer_email = $post['writer_email'];
        $writer->writer_address = $post['writer_address'];
        $writer->writer_image = $image;
        
        $writer->save();

        $response = ApiFormatter::createJson(200, 'Update Data Success', $writer);
        return $response;
    }

    public function delete($id)
    {
        $writer = WriterModel::find($id);

        $writer->is_deleted = 1;
        $writer->save();

        $response = ApiFormatter::createJson(200, 'Delete Data Success', null);
        return $response;
    }
}
