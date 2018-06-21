<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;

use App\Model\Users\User;
use App\Model\Company\Companies;

class CompanyTest extends TestCase
{
  use DatabaseMigrations;

  public function setUp(): void
   {
     parent::setUp();
     $this->artisan('migrate');
     $this->artisan('db:seed');
   }

   /**
    * A Model Testing
    * @test
    * @return void
    */
   public function user_can_create_company_with_valid_data()
   {
     $token = $this->login_user();
     //Act
     $response = $this->post('/api/company', [
       'user_id' => 1,
       'name' => 'Testing Company Name',
       'description' => 'This is testing description',
       'industry' => 'IT/Software',
       'logo' =>  UploadedFile::fake()->image('file.png', 600, 600),
       'country' => 'Indonesia',
       'address' => 'Jl. Testing 21 No. 1',
       'link_website' => 'www.testing.com',
       'culture' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry.
       Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an
       unknown printer took a galley of type and scrambled it to make a type specimen book. It
       has survived not only five centuries, but also the leap into electronic typesetting,
       remaining essentially unchanged. It was popularised in the 1960s with the release of
       Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing
       software like Aldus PageMaker including versions of Lorem Ipsum",
     ], ['HTTP_Authorization' => 'Bearer' . $token]);

     //Assert
     $response
         ->assertJsonStructure([
           "status",
           'data' => [
             'company' => [
               'id',
               'user_id',
               'name',
               'description',
               'industry',
               'logo',
               'country',
               'address',
               'link_website',
               'culture'
             ]
           ],
           'message'
         ])
         ->assertStatus(200);

     $company = Companies::findOrFail(3);
     $this->assertEquals('1', $company->user_id);
     $this->assertEquals('Testing Company Name', $company->name);
     $this->assertEquals('This is testing description', $company->description);
     $this->assertEquals('IT/Software', $company->industry);
     $this->assertNotNull($company->logo);
     $this->assertEquals('Indonesia', $company->country);
     $this->assertEquals('Jl. Testing 21 No. 1', $company->address);
     $this->assertEquals('www.testing.com', $company->link_website);
     $this->assertNotNull($company->culture);
   }

}
