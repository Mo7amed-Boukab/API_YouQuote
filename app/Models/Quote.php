<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Quote extends Model
{
   use HasFactory;
   protected $fillable = ['id','content', 'author'];

   public function user()
   {
       return $this->belongsTo(User::class);
   }
}
