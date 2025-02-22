<?php

namespace App\Http\Controllers;
use App\Models\Category;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Cache;

class CategoryController extends Controller
{
    /**      Get All Categories      **/
    public function index()
    {
        return response()->json(Category::all());
    }

    /**    Create a New Category       **/
    public function store(Request $request)
    {
        $request->merge([
            'name' => filter_var($request->name, FILTER_SANITIZE_STRING),
        ]);
        $rules  = [
            'name' => 'required|string|unique:categories|max:255'
        ];
        $validator          = Validator::make($request->all(), $rules);    
        if ($validator->fails()) { 
            return response()->json($validator->errors(), 422);
        }  
        $category = Category::create([
            'name' => $request->name
        ]);

        return response()->json($category, 201);
    }

    // ** Get a Single Category**
    public function show($id)
    {
        $category = Category::findOrFail($id);
        return response()->json($category);
    }

    /** Update Category **/
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $request->merge([
            'name' => filter_var($request->name, FILTER_SANITIZE_STRING),
        ]);
        $request->validate([
            'name' => 'required|string|unique:categories,name,' . $id . '|max:255'
        ]);

        $category->update(['name' => $request->name]);

        return response()->json($category);
    }

    // ** Delete Category**
    public function destroy($id)
    {
        Category::findOrFail($id)->delete();

        return response()->json(['message' => 'Category deleted'], 204);
    }
}
