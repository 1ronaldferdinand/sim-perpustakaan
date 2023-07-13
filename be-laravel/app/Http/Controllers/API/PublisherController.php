<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PublisherModel;
use App\Helpers\ApiFormatter;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class PublisherController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $sort   = $request->sort?? 'publisher_name';
        $sort_type = $request->sort_type?? 'asc';

        $query = PublisherModel::where('is_deleted', 0);

        if(!empty($search)){
            $query->where(function ($q) use ($search){
                $q->where('publisher_name', 'LIKE', '%'.$search.'%');
                $q->orWhere('publisher_phone', 'LIKE', '%'.$search.'%');
                $q->orWhere('publisher_email', 'LIKE', '%'.$search.'%');
                $q->orWhere('publisher_city', 'LIKE', '%'.$search.'%');
            });
        }
        
        $publishers = $query->orderBy($sort, $sort_type)->paginate(10);
        
        $response = ApiFormatter::createJson(200, 'Get Data Success', $publishers);
        return $response;
    }

    public function store(Request $request)
    {
        $post = $request->all();
        
        $validator = Validator::make($post, [
            'publisher_name' => 'required',
            'publisher_email' => 'email|unique:publisher',
            'publisher_phone' => 'required',
            'publisher_city' => 'required',
            'publisher_address' => 'required'
        ]);
        
        if($validator->fails()){
            $response = $validator->errors()->all();
            $response = ApiFormatter::createJson(400, 'Input Error', $response);
            return $response;
        }
        
        $image = "";

        $publishers = PublisherModel::create([
            'publisher_id' => Uuid::uuid1()->toString(),
            'publisher_name' => $post['publisher_name'],
            'publisher_phone' => $post['publisher_phone'],
            'publisher_email' => $post['publisher_email'],
            'publisher_city' => $post['publisher_city'],
            'publisher_address' => $post['publisher_address'],
            'publisher_image' => $image
        ]);

        $response = ApiFormatter::createJson(200, 'Input Data Success', $publishers);
        return $response;
    }

    public function show($id)
    {
        $publisher = PublisherModel::where('publisher_id', $id)->where('is_deleted', 0)->first();

        if(empty($publisher)){
            return ApiFormatter::createJson(404, 'Publisher not found');
        }

        $response = ApiFormatter::createJson(200, 'Get Detail Success', $publisher);
        return $response;
    }

    public function update(Request $request, $id)
    {
        $post = $request->all();

        $validator = Validator::make($post, [
            'publisher_name' => 'required',
            'publisher_email' => 'email|unique:publisher',
            'publisher_phone' => 'required',
            'publisher_city' => 'required',
            'publisher_address' => 'required'
        ]);

        if($validator->fails()){
            $response = $validator->errors()->all();
            $response = ApiFormatter::createJson(400, 'Input Error', $response);
            return $response;
        }

        $image = "";

        $publisher = PublisherModel::find($id);

        if(!$publisher){
            $response = ApiFormatter::createJson(404, 'Data Not Found', null);
            return $response;
        }

        $publisher->publisher_name = $post['publisher_name'];
        $publisher->publisher_phone = $post['publisher_phone'];
        $publisher->publisher_email = $post['publisher_email'];
        $publisher->publisher_city = $post['publisher_city'];
        $publisher->publisher_address = $post['publisher_address'];
        $publisher->publisher_image = $image;
        
        $publisher->save();

        $response = ApiFormatter::createJson(200, 'Update Data Success', $publisher);
        return $response;
    }

    public function delete($id)
    {
        $publisher = PublisherModel::find($id);

        if(!$publisher){
            return ApiFormatter::createJson(404, 'Publisher not found');
        }

        $publisher->is_deleted = 1;
        $publisher->save();

        $response = ApiFormatter::createJson(200, 'Delete Data Success', null);
        return $response;
    }
}
