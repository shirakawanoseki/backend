<?php

namespace Tests\Feature;

use App\Models\MMovie;
use App\Models\UUser;
use App\Models\UUserFavorite;

use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

use Tests\TestCase;

class UserTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_register_user(): void
    {
        $faker = $this->faker('ja_JP');
        $user_id = $faker->name;
        $user_password = $faker->password(10);

        $response = $this->post('/users/register',
            [
                'user_id' => $user_id,
                'password' => $user_password
            ]
        );

        $response->assertStatus(200);
        $response->assertJson(['result' => true]);
        $this->assertDatabaseHas('u_user', [
            'user_id' => $user_id,
            'hashed_password' => hash('sha256', $user_password)
        ]);

    }

    public function test_exception_is_thrown_when_invalid_password_given():void
    {
        $faker = $this->faker('ja_JP');
        $user_id = $faker->name;
        $user_password = $faker->password(10);
        $invalid_user_password = $faker->password(10);

        UUser::factory()->create(
            [
                'id' => 1,
                'user_id' => $user_id,
                'hashed_password' => hash('sha256', $user_password),
            ]
        );

        $this->expectException(Exception::class);
        $response = $this->withoutExceptionHandling()->post('/favorites',
            [
                'user_id' => $user_id,
                'm_movie_id' => 2,
                'password' => $invalid_user_password,
            ]
        );

    }

    public function  test_exception_is_thrown_when_invalid_movie_id_given():void
    {
        $faker = $this->faker('ja_JP');
        $user_id = $faker->name;
        $user_password = $faker->password(10);

        UUser::factory()->create(
            [
                'id' => 1,
                'user_id' => $user_id,
                'hashed_password' => hash('sha256', $user_password),
            ]
        );
        MMovie::factory()->create(
            [
                'id' => 1,
                'title' => 'test_movie_title_001',
                'genre' => 'sports',
                'year' => 2016,
                'description' => 'test_movie_description_001'
            ],
        );
        MMovie::factory()->create(
            [
                'id' => 2,
                'title' => 'test_movie_title_002',
                'genre' => 'documentary',
                'year' => 1997,
                'description' => 'test_movie_description_002'
            ],
        );

        $this->expectException(Exception::class);
        $response = $this->withoutExceptionHandling()->post('/favorites',
            [
                'user_id' => $user_id,
                'm_movie_id' => 3,
                'password' => $user_password,
            ]
        );

    }

    public function test_mark_as_favorite_movie():void 
    {
        $faker = $this->faker('ja_JP');

        MMovie::factory()->create(
            [
                'id' => 1,
                'title' => 'test_movie_title_001',
                'genre' => 'sports',
                'year' => 2016,
                'description' => 'test_movie_description_001'
            ],
        );
        MMovie::factory()->create(
            [
                'id' => 2,
                'title' => 'test_movie_title_002',
                'genre' => 'documentary',
                'year' => 1997,
                'description' => 'test_movie_description_002'
            ],
        );

        $user_id = 'test_user01';
        $user_password = $faker->password(10);
        UUser::factory()->create(
            [
                'id' => 1,
                'user_id' => $user_id,
                'hashed_password' => hash('sha256', $user_password),
            ]
        );
        $response = $this->post('/favorites',
            [
                'user_id' => $user_id,
                'm_movie_id' => 2,
                'password' => $user_password,
            ]
        );

        $response->assertStatus(200);
        $this->assertDatabaseHas('u_user_favorite', [
            'user_id' => $user_id,
            'm_movie_id' => 2
        ]);

    }

    public function test_get_movies_marked_as_favorite():void
    {
        $faker = $this->faker('ja_JP');

        //Setting up movie master data.
        MMovie::factory()->create(
            [
                'id' => 1,
                'title' => 'test_movie_title_001',
                'genre' => 'sports',
                'year' => 2016,
                'description' => 'test_movie_description_001'
            ],
        );
        MMovie::factory()->create(
            [
                'id' => 2,
                'title' => 'test_movie_title_002',
                'genre' => 'documentary',
                'year' => 1997,
                'description' => 'test_movie_description_002'
            ],
        );
        MMovie::factory()->create(
            [
                'id' => 3,
                'title' => 'test_movie_title_003',
                'genre' => 'action',
                'year' => 2023,
                'description' => 'test_movie_description_003'
            ],

        );

        //Setting up user registration data.
        $user_id = $faker->name;
        $user_password = $faker->password(10);
        UUser::factory()->create(
            [
                'id' => 1,
                'user_id' => $user_id,
                'hashed_password' => hash('sha256', $user_password),
            ]
        );

        //Setting up user's favorie movies data.
        UUserFavorite::factory()->create(
            [
                'id' => 1,
                'user_id' => $user_id,
                'm_movie_id' => 2
            ],
        );
        UUserFavorite::factory()->create(
            [
                'id' => 2,
                'user_id' => $user_id,
                'm_movie_id' => 3
            ],
        );

        $response = $this->get('/favorites');

        $response->assertStatus(200);
        $response->assertJson([
            [
                'id' => 2,
                'title' => 'test_movie_title_002',
                'genre' => 'documentary',
                'year' => 1997,
                'description' => 'test_movie_description_002'
            ],
            [
                'id' => 3,
                'title' => 'test_movie_title_003',
                'genre' => 'action',
                'year' => 2023,
                'description' => 'test_movie_description_003'
            ],
        ]);
        $this->assertDatabaseHas('u_user_favorite', 
            [
                'user_id' => $user_id,
                'm_movie_id' => 2
            ],
        );
        $this->assertDatabaseHas('u_user_favorite', 
            [
                'user_id' => $user_id,
                'm_movie_id' => 3
            ],
        );
   }
}
