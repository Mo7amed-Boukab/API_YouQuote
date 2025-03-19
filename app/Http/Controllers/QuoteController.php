<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuoteRequest;
use App\Http\Requests\UpdateQuoteRequest;
use App\Models\Quote;
use App\Models\User;
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
        Quote::create($data);
        return response()->json(['message' => 'New Quote added successfully', 'Quote' => $data], 201);
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

}
