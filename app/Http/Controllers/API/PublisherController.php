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
    public function index()
    {
        $publishers = PublisherModel::where('is_deleted', 0)->orderBy('publisher_name', 'asc')->get();
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
        $publisher = PublisherModel::where('publisher_id', $id)->first();
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

        $publisher->is_deleted = 1;
        $publisher->save();

        $response = ApiFormatter::createJson(200, 'Delete Data Success', null);
        return $response;
    }
}
