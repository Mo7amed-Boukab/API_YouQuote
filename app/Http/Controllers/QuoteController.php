<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quote;
use Illuminate\Support\Facades\DB;


class QuoteController extends Controller
{

    public function index()
    {
        return response()->json(Quote::all(), 200);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
         'content' => 'required|string',
         'author' => 'required|string',
        ]);

        Quote::create($data);
        return response()->json(['message' => 'New Quote is added successfully'], 201);
    }


    public function show(string $id)
    {
        $quote = Quote::findOrFail($id);
        $quote->increment('popularity');
        return response()->json($quote, 200);
    }


    public function update(Request $request, string $id)
    {
        $newData = $request->validate([
         'content' => 'required|string',
         'author' => 'required|string',
        ]);

        $quote = Quote::findOrFail($id);
        $quote->update($newData);

        return response()->json(['message' => 'The Quote is updated successfully.'], 200);
    }


    public function destroy(string $id)
    {
        $quote = Quote::findOrFail($id);
        $quote->delete();
        return response()->json(['message' => 'The Quote is deleted successfully.'], 200);
    }


    public function randomQuote($numberQuotes)
    {
        $quotes = Quote::inRandomOrder()->limit($numberQuotes)->get();
        if (empty($quotes)) {
           return response()->json(['message' => 'No quote found.'], 404);
       }
        return response()->json($quotes, 200);
    }


    public function getPopularQuotes($number)
    {
        $quotes = Quote::orderByDesc('popularity')->limit($number)->get();
        if ($quotes->isEmpty()) {
           return response()->json(['message' => 'No popular quotes found.'], 404);
        }
        return response()->json($quotes, 200);
    }
    

    public function filterByWord($numberWords){

      $quotes = DB::select("SELECT * FROM quotes WHERE (LENGTH(TRIM(content)) - LENGTH(REPLACE(content, ' ', '')) + 1) <= ?", [$numberWords]);
      if (empty($quotes)) {
         return response()->json(['message' => 'No quote found.'], 404);
      }
      return response()->json($quotes, 200);
    }

}
