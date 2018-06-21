<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Model\Users\User;

class UserAuthTest extends TestCase
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
  public function user_register_with_valid_data()
  {
    //Act
    $response = $this->postJson('/api/register', [
      'email' => 'kahell123@gmail.com',
      'username' => 'kahell123',
      'password' => 'secret',
      'password_confirmation' => 'secret',
      'name' => 'Helfi pangestu',
      'gender' => '1',
      'bod' => '12-01-1997',
      'country' => 'Indonesia',
      'phone_number' => '00000',
      'rank_id' => 1
    ]);

    //Assert
    $response
        ->assertExactJson([
          'status' => true,
          'data' => null,
          'message' => 'Registered Successfully!.'
        ])
        ->assertStatus(200);

    $user = User::where('username', 'kahell123')->first();
    $this->assertNotNull($user);
    $this->assertEquals('kahell123', $user->username);
    $this->assertEquals('Helfi pangestu', $user->name);
    $this->assertNotNull($user->password);
  }

  /**
   * @test
   */
  public function user_register_with_invalid_data()
  {
    //Arrange Factory
    $response = $this->postJson('/api/register', [
      'email' => 'test@test.com',
      'username' => 'test',
      'password' => 'secret',
      'password_confirmation' => 'secret',
      'name' => 'Helfi pangestu',
      'gender' => '1',
      'bod' => '12-01-1997',
      'country' => 'Indonesia',
      'phone_number' => '00000000',
      'rank_id' => 1
    ]);

    //Test user can register with invalid username
    $response = $this->postJson('/api/register', [
      'username' => 'test',
      'email' => 'test@test.com',
      'password' => 'secret',
      'password_confirmation' => 'secret',
      'name' => 'Test',
      'gender' => '1',
      'bod' => '01-11-2018',
      'country' => 'Indonesia',
      'phone_number' => '00000000',
      'rank_id' => 1
    ]);

    //Assert
    $response
      ->assertExactJson([
        'status' => false,
        'data' => null,
        'message' => 'The username has already been taken.'
      ])
      ->assertStatus(422);

    //Test user can register with invalid email
    $response = $this->postJson('/api/register', [
      'username' => 'test1',
      'email' => 'test@test.com',
      'password' => 'secret',
      'password_confirmation' => 'secret',
      'name' => 'Test',
      'gender' => '1',
      'bod' => '01-11-2018',
      'country' => 'Indonesia',
      'phone_number' => '00000000',
      'rank_id' => 1
    ]);

    //Assert
    $response
      ->assertExactJson([
        'status' => false,
        'data' => null,
        'message' => 'The email has already been taken.'
      ])
      ->assertStatus(422);

    //Test user can register with invalid phone Number
    $response = $this->postJson('/api/register', [
      'username' => 'test2',
      'email' => 'test2@test.com',
      'password' => 'secret',
      'password_confirmation' => 'secret',
      'name' => 'Test',
      'gender' => '1',
      'bod' => '01-11-2018',
      'country' => 'Indonesia',
      'phone_number' => '00000000',
      'rank_id' => 1
    ]);

    //Assert
    $response
      ->assertExactJson([
        'status' => false,
        'data' => null,
        'message' => 'Phone with number 00000000 is already used.'
      ])
      ->assertStatus(422);

  }

  /**
   * @test
   */
  public function user_can_login_with_registered_account()
  {
      //Arrange
      factory(User::class)->create([
        'email' => 'test@test.com',
        'username' => 'test',
        'password' => bcrypt('secret'),
        'name' => 'Helfi pangestu',
        'gender' => '1',
        'bod' => '12-01-1997',
        'country' => 'Indonesia',
        'phone_number' => '00000000',
        'rank_id' => 1
      ]);

      //Act
      $response = $this->postJson('/api/login', [
        'username' => 'test',
        'password' => 'secret'
      ]);

      //Assert
      $response
          ->assertStatus(200);

      //Act
      $response = $this->postJson('/api/login', [
        'username' => 'test123',
        'password' => 'secret'
      ]);

      //Assert
      $response
          ->assertExactJson([
            'status' => FALSE,
            'data' => null,
            'message' => 'Provided username and password does not match!'
          ])
          ->assertStatus(401);
  }

}
