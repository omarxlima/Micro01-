<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    protected $endpoint = '/categories';
    /**
     * Get all Categoriies
     */
    public function test_get_all_categories(): void
    {
        Category::factory()->count(6)->create();

        $response = $this->getJson($this->endpoint);
        $response->assertJsonCount(6, 'data');
        $response->assertStatus(200);
    }

    /**
     * Error Get Single Category
     */
    public function test_error_get_single_category(): void
    {
        $category = 'fake-url';

        $response = $this->getJson("{$this->endpoint}/{$category}");
        $response->assertStatus(404);


    }

     /**
     * Get Single Category
     */
    public function test_get_single_category(): void
    {
        $category = Category::factory()->create();

        $response = $this->getJson("{$this->endpoint}/{$category->url}");
        $response->assertStatus(200);


    }
     /**
     * store Category
     */
    public function test_validations_store_category(): void
    {

        $response = $this->postJson($this->endpoint, [
            'title' => ' ',
            'description' => ' '
        ]);
        $response->dump();
        $response->assertStatus(422);
    }

     /**
     * store Category
     */
    public function test_store_category(): void
    {

        $response = $this->postJson($this->endpoint, [
            'title' => 'Categoria Teste',
            'description' => 'Descrição de teste categoria',
        ]);
        $response->assertStatus(201);
    }

     /**
     * update Category
     */
    public function test_update_category(): void
    {
        $category = Category::factory()->create();

        $data = [
            'title' => 'Titulo Updated',
            'description' => 'Description Updated',
        ];

        $response = $this->putJson("$this->endpoint/fake-category", $data);
        $response->assertStatus(404);

        $response = $this->putJson("$this->endpoint/{$category->url}", []);
        $response->assertStatus(422);

        $response = $this->putJson("$this->endpoint/{$category->url}", $data);
        $response->assertStatus(200);

    }

       /**
     * delete Category
     */
    public function test_delete_category(): void
    {
        $category = Category::factory()->create();

        $response = $this->deleteJson("$this->endpoint/{fake-category}");
        $response->assertStatus(404);

        $response = $this->deleteJson("$this->endpoint/{$category->url}");
        $response->assertStatus(204);

    }
}




