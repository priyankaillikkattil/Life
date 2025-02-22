<?php

namespace App\Http\Controllers;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Cache;
use App\Models\Product;
use App\Models\ProductCategory;

class ProductController extends Controller
{
    protected $productRepository;
    public function __construct(ProductRepository $productRepository) {
        $this->productRepository = $productRepository;
    }

    public function index(Request $request) {
        return Cache::remember('products', 60, function () use ($request) {
            return Product::with('categories')
                    ->whereNull('deleted_at') 
                    ->when($request->has('category_id'), function ($query) use ($request) {
                        $query->whereHas('categories', function ($q) use ($request) {
                            $q->where('category_id', $request->category_id);
                        });
                    })
                    ->paginate(10);
        });
    }
    
    
    
   

    public function store(Request $request)
    {   
        $rules = [  
            'name'              => 'required|string|min:3',
            'price'             => 'required|numeric|min:1',
            'slug'              => 'required|string|max:255|unique:products|min:3',
            'stock_quantity'    => 'required|integer|min:1',
            'images.*'          => 'image|mimes:jpeg,png,jpg|max:2048',
            'categories'        => 'required|array', 
            'categories.*'      => 'exists:categories,id',
        ];
        $validator          = Validator::make($request->all(), $rules);    
        if ($validator->fails()) { 
            return response()->json($validator->errors(), 422);
        }   
        $images = [];

        if ($request->hasFile('images')) {
            // Ensure `$request->file('images')` is an array
            $files = is_array($request->file('images')) ? $request->file('images') : [$request->file('images')];
            
            foreach ($files as $image) {
                if ($image->isValid()) { 
                    $path = $image->store('products', 'public'); 
                    $images[] = $path;
                }
            }
        }
        

        $product = Product::create(array_merge($request->all(), ['images' => $images]));
        $product->categories()->attach($request->categories);
        return response()->json($product, 201);
    }
    public function show($id) {
        return Product::with('categories')->findOrFail($id);
    }

    public function update(Request $request, $id) { 
        try {
            $rules = [
                'name'              => 'sometimes|required|string',
                'price'             => 'sometimes|required|numeric',
                'stock_quantity'    => 'sometimes|required|integer',
                'categories'        => 'sometimes|array',
                'categories.*'      => 'exists:categories,id',
                'images.*'          => 'image|mimes:jpeg,png,jpg,gif|max:2048', 
                'remove_images'     => 'sometimes|array',
            ];
    
            $validator = Validator::make($request->all(), $rules);    
            if ($validator->fails()) { 
                return response()->json($validator->errors(), 422);
            }
    
            $product        = Product::findOrFail($id);
            $existingImages = is_array($product->images) ? $product->images : json_decode($product->images, true);
            $existingImages = $existingImages ?? [];    

            if ($request->has('remove_images')) {
                foreach ($request->remove_images as $imageToRemove) {
                    $this->deleteImage($imageToRemove); 
                    $existingImages = array_values(array_diff($existingImages, [$imageToRemove])); 
                }
            }

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    if ($image->isValid()) {
                        $path = $image->store('products', 'public');
                        $existingImages[] = $path; 
                    }
                }
            }
    
            $updated = $product->update([
                'name'              => $request->name ?? $product->name,
                'price'             => $request->price ?? $product->price,
                'stock_quantity'    => $request->stock_quantity ?? $product->stock_quantity,
                'images'            => json_encode($existingImages), 
            ]);
    
            
            if ($request->has('categories')) {
                $product->categories()->sync($request->categories);
            }
    
            
            $product->refresh();
    
            return response()->json([
                'message' => 'Product updated successfully',
                'product' => $product
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    
    

    public function destroy($id) {
        try{
                $product = Product::findOrFail($id);
                ProductCategory::where('product_id', $id)->update(['deleted_at' => now()]);
                foreach (json_decode($product->images, true) ?? [] as $image) {
                    $this->deleteImage($image);
                }
                $product->delete(); 
                return response()->json(['message' => 'Product soft deleted'], 200);
        }catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
        
    }

    private function deleteImage($imagePath)
    {
        if ($imagePath && Storage::disk('public')->exists($imagePath)) {
            Storage::disk('public')->delete($imagePath); 
        }
    }
}
