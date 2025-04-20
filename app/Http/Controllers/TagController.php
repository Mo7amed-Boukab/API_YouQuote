<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Http\Requests\StoreTagRequest;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index()
    {
        return response()->json(Tag::all(), 200);
    }

    public function store(StoreTagRequest $request)
    {     
        $tag = Tag::create( $request->validated());
        return response()->json(['message' => 'Tag added successfully', 'tag_name' => $tag], 201);
    }
} 