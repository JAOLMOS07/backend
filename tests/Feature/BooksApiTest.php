<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Book;
class BooksApiTest extends TestCase
{
    use RefreshDatabase;

    
    function test_can_get_all_books()
    {
        $books = Book::factory(4)->create();
        
        $response = $this->getJson(route('books.index'));
        $response->assertJsonFragment([
            'title' => $books[0]->title
        ]);
    }

    function test_can_get_one_book()
    {
        $book = Book::factory()->create();

        $response = $this->getJson(route('books.show',$book));
        $response->assertJsonFragment([
            'title' => $book->title
        ]);
    }

    function test_can_create_one_book()
    {
        
        $response = $this->postJson(route('books.store',[]));
        $response->assertJsonValidationErrorFor('title');

        $response = $this->postJson(route('books.store',['title'=>'My new book']));
        $response->assertJsonFragment([
            'title' => 'My new book'
        ]);

        $this->assertDatabaseHas('books',[
            'title' => 'My new book'
        ]);
    }
    function test_can_update_one_book()
    {
        $book = Book::factory()->create();

        $response = $this->patchJson(route('books.update',$book),[]);
        $response->assertJsonValidationErrorFor('title');

        $response = $this->patchJson(route('books.update',$book),['title'=>'Update book']);
        $response->assertJsonFragment([
            'title' => 'Update book'
        ]);

        $this->assertDatabaseHas('books',[
            'title' => 'Update book'
        ]);
    }
    function test_can_delete_one_book()
    {
        $book = Book::factory()->create();

        $this->deleteJson(route('books.destroy',$book));

        $this->assertDatabaseCount('books',0);
    }

}
