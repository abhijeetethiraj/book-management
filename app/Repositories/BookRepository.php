<?php
namespace App\Repositories;

use App\Models\Book;

class BookRepository
{
   public function findByGutendexId($gutendexId)
   {
     return Book::where('gutendex_id',$gutendexId)->first();
   }

   public function store(array $data)
   {
    return Book::create($data);
   }

   
}