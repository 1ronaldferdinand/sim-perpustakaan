<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MemberModel;
use App\Helpers\ApiFormatter;
use Illuminate\Support\Facades\Validator;
use Member;
use Ramsey\Uuid\Uuid;

class MemberController extends Controller
{
    public function index()
    {
        $members = MemberModel::where('is_deleted', 0)->orderyBy('member_name', 'asc')->get();
        $response = ApiFormatter::createJson(200, 'Get Data Success', $members);
        return $response;
    }

    public function store(Request $request)
    {
        $post = $request->all();

        $validator = Validator::make($post, [
            'member_name'=> 'required',
            'member_email'=> 'email|unique:member'
        ]);

        if($validator->fails()){
            $errors = $validator->errors()->all();
            $response = ApiFormatter::createJson(400, 'Input Error', $errors);
            return $response;
        };

        $image = "";

        $member = MemberModel::create([
            'member_id' => Uuid::uuid1()->toString(),
            'member_name' => $post['member_name'],
            'member_phone' => $post['member_phone'],
            'member_email' => $post['member_email'],
            'member_address' => $post['member_address'],
            'member_image' => $image
        ]);

        $response = ApiFormatter::createJson(200, 'Input Data Success', $member);
        return $response;
    }

    public function show($id)
    {
        $member = MemberModel::where('member_id', $id)->first();
        $response = ApiFormatter::createJson(200, 'Get Detail Success', $member);
        return $response;
    }

    public function update(Request $request, $id)
    {
        $post = $request->all();

        $validator = Validator::make($post, [
            'member_name' => 'required',
            'member_email' => 'email'
        ]);

        if($validator->fails()){
            $response = $validator->errors()->all();
            $response = ApiFormatter::createJson(400, 'Input Error', $response);
            return $response;
        }

        $image = "";

        $member = MemberModel::find($id);

        if(!$member){
            $response = ApiFormatter::createJson(404, 'Data Not Found', null);
            return $response;
        }

        $member->member_name = $post['member_name'];
        $member->member_phone = $post['member_phone'];
        $member->member_email = $post['member_email'];
        $member->member_address = $post['member_address'];
        $member->member_image = $image;
        
        $member->save();

        $response = ApiFormatter::createJson(200, 'Update Data Success', $member);
        return $response;
    }

    public function destroy($id)
    {
        $member = MemberModel::where('member_id', $id)->first();
        $member->is_deleted = 1;
        $member->save();

        $response = ApiFormatter::createJson(200, 'Delete Data Success', null);
        return $response;
    }
}
