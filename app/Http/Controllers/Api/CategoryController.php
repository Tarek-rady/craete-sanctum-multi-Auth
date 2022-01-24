<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Controllers\Api\ApiResponseTrait;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    use ApiResponseTrait;

    public function index()
    {
        $categories = Category::selection()->get();
        return $this->apiResponse($categories , 200 , 'تم استرجاع البيانات بنجاح');

    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
         // validation
         $validator = Validator::make($request->all(), [
            'name_ar' => 'required',
            'name_en' => 'required',
            'status' => 'nullable'

        ]);

        if($validator->fails()){
            return $this->apiResponse($validator->errors()->toJson(), 400 , 'category Not found');
        }

        $category = Category::create( $request->all());
        if($category){
           return $this->apiResponse($category , 201 , 'category Created successfully');
        }else{
            return $this->apiResponse(null , 404 , 'category Not found');
        }
    }


    public function show(Request $request)
    {
        $category = Category::selection()->where('id' , $request->id)->first();
        if($category){
            return $this->apiResponse($category , 200 , 'تم استرجاع القسم بنجاح');
        }
        return $this->apiResponse(null , 404 , 'category Not found');
    }


    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
         // validation
         $validator = Validator::make($request->all(), [
            'name_ar' => 'required',
            'name_en' => 'required',
            'status' => 'nullable'

        ]);

        if($validator->fails()){
            return $this->apiResponse($validator->errors()->toJson(), 400 , 'category Not found');
        }

        $category = Category::find($id);
        $category->update($request->all());

        if($category){
            return $this->apiResponse($category , 201 , 'category Upadated successfully');
         }else{
             return $this->apiResponse(null , 404 , 'category Not found');
         }

    }


    public function destroy($id)
    {
        $category = Category::find($id);

        if(!$category){
            return $this->apiResponse(null , 404 , 'category Not found');
        }else{
            $category = Category::destroy($id);
            return $this->apiResponse($category , 200 , 'category  deleted successfully');

        }
    }

    public function changeStatus($id , Request $request)
    {
        $category = Category::where('id' , $id)->update(['status' => $request->status]);

        if($category){
            return $this->apiResponse($category , 201 , 'نم تغير الحاله بنجاح');
        }else{
            return $this->apiResponse(null , 404 , 'category Not found');

        }
    }
}
