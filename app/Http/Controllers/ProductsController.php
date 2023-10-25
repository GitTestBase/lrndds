<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function index(Request $request)
    {
        $get_prod = Product::all();
        return response()->json(['success'=>1,'response'=>$get_prod]);
    }
    public function products (Request $request)
    {
        $cat_id = $request->categoryId;
        $validator = Validator::make($request->all(),[
            'productName' => 'required',
            'categoryId' => 'required',
        ]);
        if($validator->fails())
        {
            return response()->json(['success'=>0,'response'=>$validator->errors()->messages()]);
        }

        $check_prod = Product::firstOrNew(['category_id'=>$request->categoryId,'prod_name'=>$request->productName]);
        if(!$check_prod->exists)
        {
            $check_prod->prod_name = $request->productName;
            $check_prod->category_id = $request->categoryId;
            $check_prod->save();

            return response()->json(['success'=>1,'response'=>'Added successfully']);
        }
        else
        {
            return response()->json(['success'=>1,'response'=>'already exists']);
        }
       
    }
}
