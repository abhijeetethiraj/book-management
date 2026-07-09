<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
   protected $fillable = [
    'gutendex_id',
    'title',
    'author',
    'language',
    'cover',
   ];

   public function borrowRecords()
   {
    return $this->hashMany(BorrowRecord::class);
   }
}
