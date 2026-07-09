<?php

namespace App\Http\Controllers\Api;
use App\Services\GutendexService;
use App\Http\Controllers\Controller;
use App\Services\BookService;
use Illuminate\Http\Request;

class BookController extends Controller
{
  protected GutendexService $gutendexService;
  protected BookService $bookService;

  public function __construct
  (
     GutendexService  $gutendexService,
     BookService  $bookService
  ){
    $this->gutendexService= $gutendexService;
    $this->bookService = $bookService;
  }

  public function index()
  {
    $books = $this->gutendexService->getBooks();
    return response()->json($books); 
  }

  public function show($id)
  {
    $book = $this->gutendexService->getBookByGutendexId($id);
    return response()->json($book);
  }

  public function search(Request $request)
  {
    $query = $request->query('q');
    $book = $this->gutendexService->search($query);
    return response()->json($book);
  }

    public function store(Request $request)
    {
        $data = $request->validate([
            'gutendex_id' => 'required|integer',
        ]);

        $book = $this->bookService->store($data['gutendex_id']);

        return response()->json($book, 201);
    }
}
