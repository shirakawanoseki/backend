<?php

namespace Tests\Feature;

use App\Models\MMovie;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use SebastianBergmann\Type\VoidType;
use Tests\TestCase;

class MovieMasterTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_add_movie_master_entity():void
    {
        $response = $this->post('movies/add',
            [
                'title' => 'test_movie_title_001',
                'genre' => 'sports',
                'year' => 2016,
                'description' => 'test_movie_description_004'
            ]
        );
        $response->assertStatus(200);
        $response->assertJson(['result' => true]);
    }

    public function test_search_movie_master_entity_by_id():void
    {
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
                'genre' => 'Action',
                'year' => 2022,
                'description' => 'test_movie_description_003'
            ],
        );

        $response = $this->get('/movies/1');
        $response->assertStatus(200);
        $response->assertJson([
            'id' => 1,
            'title' => 'test_movie_title_001',
            'genre' => 'sports',
            'year' => 2016,
            'description' => 'test_movie_description_001'
        ]);

    }

    public function test_search_by_keyword():void
    {
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
        $response = $this->get('/movies?search=description');
        $response->assertStatus(200);
        $response->assertJson([
            [
                'id' => 1,
                'title' => 'test_movie_title_001',
                'genre' => 'sports',
                'year' => 2016,
                'description' => 'test_movie_description_001'
    
            ],
            [
                'id' => 2,
                'title' => 'test_movie_title_002',
                'genre' => 'documentary',
                'year' => 1997,
                'description' => 'test_movie_description_002'
            ]
        ]);
    }
}
