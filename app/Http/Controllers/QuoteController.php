<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuoteRequest;
use App\Http\Requests\UpdateQuoteRequest;
use App\Models\Quote;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class QuoteController extends Controller 
{

    public function index()
    {
        return response()->json(Quote::all(), 200);
    }

    public function store(StoreQuoteRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        
        $quote = Quote::create($data);
        
        if ($request->has('tags')) {
            $tagIds = [];
            foreach ($request->tags as $tag) {
                if (isset($tag['id'])) {
                    $tagIds[] = $tag['id'];
                } elseif (isset($tag['name'])) {
                    $newTag = Tag::create(['name' => $tag['name']]);
                    $tagIds[] = $newTag->id;
                }
            }
            $quote->tags()->attach($tagIds);
        }
        
        return response()->json([
            'message' => 'New Quote added successfully',
            'Quote' => $quote->load('tags')
        ], 201);
    }


    public function show(Quote $quote)
    {
        $quote->increment('popularity');
        return response()->json(['Quote' =>$quote], 200);
    }


    public function update(UpdateQuoteRequest $request, Quote $quote)
    {
        $newData = $request->validated();
        $quote->update($newData);

        return response()->json(['message' => 'The Quote is updated successfully.', 'quote' => $newData], 200);
    }


    public function destroy(Quote $quote)
    {
        $quote->delete();
        return response()->json(['message' => 'The Quote is deleted successfully.'], 200);
    }


    public function randomQuote($numberQuotes)
    {
        $quotes = Quote::inRandomOrder()->limit($numberQuotes)->get();
        if (empty($quotes)) {
           return response()->json(['message' => 'No quote found.'], 404);
       }
        return response()->json(['quotes'=> $quotes], 200);
    }


    public function getPopularQuotes($number)
    {
        $quotes = Quote::orderByDesc('popularity')->limit($number)->get();
        if ($quotes->isEmpty()) {
           return response()->json(['message' => 'No popular quotes found.'], 404);
        }
        return response()->json(['quotes' => $quotes], 200);
    }


    public function filterByWord($numberWords){

      $quotes = DB::select("SELECT * FROM quotes WHERE (LENGTH(TRIM(content)) - LENGTH(REPLACE(content, ' ', '')) + 1) <= ?", [$numberWords]);
      if (empty($quotes)) {
         return response()->json(['message' => 'No quote found.'], 404);
      }
      return response()->json(['quotes' => $quotes], 200);
    }

    public function addToFavorites(Quote $quote)
    {
        $user = Auth::user();
        
        if ($user->favorites()->where('quote_id', $quote->id)->exists()) {
            return response()->json(['message' => 'Quote already in favorites'], 400);
        }
        
        $user->favorites()->attach($quote->id);
        
        return response()->json([
            'message' => 'Quote added to favorites successfully',
            'favorites_count' => $quote->favorites()->count()
        ], 201);
    }
    
    public function removeFromFavorites(Quote $quote)
    {
        $user = Auth::user();
        
        if (!$user->favorites()->where('quote_id', $quote->id)->exists()) {
            return response()->json(['message' => 'Quote not in favorites'], 404);
        }
        
        $user->favorites()->detach($quote->id);
        
        return response()->json([
            'message' => 'Quote removed from favorites successfully',
            'favorites_count' => $quote->favorites()->count()
        ]);
    }
    
    public function like(Quote $quote)
    {
        $user = Auth::user();
        
        if ($user->likes()->where('quote_id', $quote->id)->exists()) {
            return response()->json(['message' => 'Quote already liked'], 400);
        }
        
        $user->likes()->attach($quote->id);
        
        return response()->json([
            'message' => 'Quote liked successfully',
            'likes_count' => $quote->likes()->count()
        ], 201);
    }
    
    public function unlike(Quote $quote)
    {
        $user = Auth::user();
        
        if (!$user->likes()->where('quote_id', $quote->id)->exists()) {
            return response()->json(['message' => 'Quote not liked'], 404);
        }
        
        $user->likes()->detach($quote->id);
        
        return response()->json([
            'message' => 'Quote unliked successfully',
            'likes_count' => $quote->likes()->count()
        ]);
    }

    public function getFavorites()
    {
        $user = Auth::user();
        $favorites = $user->favorites()->get();
        return response()->json([
            'favorites' => $favorites
        ], 200);
    }

}
