<?php
namespace App\Services;

use App\Models\MMovie;
class MovieMasterService {

    public function __construct(){
    }

    public function add_movie_master_entity(string $title, string $genre, int $year, string $description):bool
    {
        $movie_master_entity = new MMovie;
        $movie_master_entity->title = $title;
        $movie_master_entity->genre = $genre;
        $movie_master_entity->year = $year;
        $movie_master_entity->description = $description;
        
        $result = $movie_master_entity->save();

        return $result;
    }

    public function search_movie_by_keyword(string $keyword)
    {
        $keyword_search_result = [];
        $movieMasterInfoList = MMovie::all();
        
        foreach($movieMasterInfoList as $movieMasterInfo){
            if(str_contains($movieMasterInfo->title, $keyword) || str_contains($movieMasterInfo->description, $keyword)){
                $keyword_search_result[] = [
                    'id' => $movieMasterInfo->id,
                    'title' => $movieMasterInfo->title,
                    'genre' => $movieMasterInfo->genre,
                    'year' => $movieMasterInfo->year,
                    'description' => $movieMasterInfo->description,
                ];
            }
        }

        return json_encode($keyword_search_result);
    }

    public function search_movie_by_id(int $movie_id)
    {
        $movieSearchResult = MMovie::where('id','=', $movie_id)->get()->first();
        return json_encode([
                'id' => $movieSearchResult->id,
                'title' => $movieSearchResult->title,
                'genre' => $movieSearchResult->genre,
                'year' => $movieSearchResult->year,
                'description' => $movieSearchResult->description,
            ]
        );
    }
}