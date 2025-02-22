<?php

namespace App\Repositories;
use App\Models\Product;

class ProductRepository {
    public function getAllProducts($filters = []) {
        return Product::when(isset($filters['category']), function ($query) use ($filters) {
            $query->whereHas('categories', function ($q) use ($filters) {
                $q->where('name', $filters['category']);
            });
        })->paginate(10);
    }

    public function findProduct($id) {
        return Product::findOrFail($id);
    }

    public function createProduct($data) {
        return Product::create($data);
    }

    public function updateProduct($id, $data) {
        $product = Product::findOrFail($id);
        $product->update($data);
        return $product;
    }

    public function deleteProduct($id) {
        return Product::findOrFail($id)->delete();
    }
}