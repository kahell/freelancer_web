<?php

namespace Tests;
use App\Model\Users\User;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function login_user()
    {
        factory(User::class, 1)->create([
          'email' => 'test@test.com',
          'username' => 'test',
          'password' => bcrypt('secret'),
          'name' => 'Test Name',
          'gender' => '1',
          'bod' => '12-01-1997',
          'country' => 'Indonesia',
          'phone_number' => '+6283873767000',
          'rank_id' => 1
        ]);

        $response = $this->postJson('/api/login', ['username' => 'test','password' => 'secret']);

        $token = $response->json()['data'];
        return $token['token'];
    }
}
