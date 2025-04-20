<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Quote extends Model
{
   use HasFactory;
   
   protected $fillable = ['id','content', 'author', 'popularity','user_id'];

   public function user()
   {
       return $this->belongsTo(User::class);
   }

   public function categories()
   {
       return $this->belongsToMany(Category::class);
   }

   public function tags()
   {
       return $this->belongsToMany(Tag::class);
   }

   public function likes()
   {
       return $this->hasMany(Like::class);
   }

   public function favorites()
   {
       return $this->hasMany(Favorite::class);
   }

   public function isLikedBy(User $user)
   {
       return $this->likes()->where('user_id', $user->id)->exists();
   }

   public function isFavoritedBy(User $user)
   {
       return $this->favorites()->where('user_id', $user->id)->exists();
   }
}
