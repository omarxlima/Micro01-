<?php

namespace Tests\Feature\Api;

use App\Models\{
    Company,
    Category
};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CompanyTest extends TestCase
{
    protected $endpoint = '/companies';
    /**
     * Get all Companies
     */
    public function test_get_all_companies(): void
    {
        Company::factory()->count(6)->create();

        $response = $this->getJson($this->endpoint);
        $response->assertJsonCount(6, 'data');
        $response->assertStatus(200);
    }

    /**
     * Error Get Single company
     */
    public function test_error_get_single_company(): void
    {
        $company = 'fake-uuid';

        $response = $this->getJson("{$this->endpoint}/{$company}");
        $response->assertStatus(404);
    }

    /**
     * Get Single company
     */
    public function test_get_single_company(): void
    {
        $company = Company::factory()->create();

        $response = $this->getJson("{$this->endpoint}/{$company->uuid}");
        $response->assertStatus(200);
    }
    /**
     * store company
     */
    public function test_validations_store_company(): void
    {

        $response = $this->postJson($this->endpoint, [
            'name' => ' ',
        ]);
        $response->assertStatus(422);
    }

    /**
     * store company
     */
    public function test_store_company(): void
    {
        $category = Category::factory()->create();
        $response = $this->postJson($this->endpoint, [
            'category_id' => $category->id,
            'name' => 'Empresa Teste',
            'email' => 'teste@teste.com',
            'whatsapp' => '988775544'
        ]);
        $response->assertStatus(201);
    }

    /**
     * update company
     */
    public function test_update_company(): void
    {
        $company = Company::factory()->create();
        $category = Category::factory()->create();


        $data = [
            'category_id' => $category->id,
            'name' => 'Empresa Update',
            'email' => 'testeupdate@teste.com',
            'whatsapp' => '9888888888'
        ];

        $response = $this->putJson("$this->endpoint/fake-company", $data);
        $response->assertStatus(404);

        $response = $this->putJson("$this->endpoint/{$company->uuid}", []);
        $response->assertStatus(422);

        $response = $this->putJson("$this->endpoint/{$company->uuid}", $data);
        $response->assertStatus(200);
    }

    /**
     * delete company
     */
    public function test_delete_company(): void
    {
        $company = Company::factory()->create();

        $response = $this->deleteJson("$this->endpoint/{fake-company}");
        $response->assertStatus(404);

        $response = $this->deleteJson("$this->endpoint/{$company->uuid}");
        $response->assertStatus(204);
    }
}
