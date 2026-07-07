<?php

namespace App\Http\Controllers\Api;
use App\Services\GutendexService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BookController extends Controller
{
  protected GutendexService $gutendexService;

  public function __construct
  (
     GutendexService  $gutendexService
  ){
    $this->gutendexService= $gutendexService;
  }

  public function index()
  {
    $books = $this->gutendexService->getBooks();
    return response()->json($books); 
  }

  public function show($id)
  {
    $book = $this->gutendexService->show($id);
    return response()->json($book);
  }

  public function search(Request $request)
  {
    $query = $request->query('q');
    $book = $this->gutendexService->search($query);
    return response()->json($book);
  }
}
