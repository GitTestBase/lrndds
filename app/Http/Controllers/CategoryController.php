<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;


use Illuminate\Http\Request;

class CategoryController extends Controller
{
   public function get_category()
   {
      $all_category = Category::all();
      return response()->json(['success'=>'1','response'=>$all_category]);
   }
    public function add_category(Request $request)
    {
       $validator = Validator::make($request->all(),[
            'categoryName'=>'required'
       ]);
       if($validator->fails())
       {
            return response()->json(['success'=>0,'response'=>$validator->errors()->messages()]);
       }

       $check_cat = Category::firstOrNew(['category_name'=>$request->categoryName]);
       if(!$check_cat->exists)
       {
          $check_cat->category_name = $request->categoryName;
          $check_cat->status = $request->status;
          $check_cat->save();
          return response()->json(['success' => '1', 'response' => 'Added successfully']);
       }
       else
       {
          return response()->json(['success' => '1', 'response' => 'Already exist']);
       }
       
    }
}
