<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Models\User;

class UserTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Test sanity of User model.
     *
     * @return void
     */
    public function testSanity()
    {
        $user = factory(User::class)->create();

        $this->assertTrue($user->exists());
    }

    public function testEmailShouldBeUnique()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        $user = factory(User::class)->create();

        User::create([
            'name' => $user->name,
            'email' => $user->email,
            'password' => bcrypt('secret'),
        ]);
    }
}
