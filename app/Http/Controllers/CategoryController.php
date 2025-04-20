<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;

class CategoryController extends Controller
{

    public function index()
    {
        return response()->json(Category::all(), 200);
    }

    public function store(StoreCategoryRequest $request)
    {  
        $category = Category::create($request->validated());
        return response()->json(['message' => 'Categorie added successfully', 'categorie_name' => $category], 201);
    }

} 