<?php
namespace App\Services;

use App\Models\MMovie;
use App\Models\UUser;
use App\Models\UUserFavorite;
use Exception;

class UserService {

    public function __construct(){
    }

    public function register_user(string $user_id, string $plain_text_password):bool
    {
        $user_entity = new UUser;
        $user_entity->user_id = $user_id;
        $user_entity->hashed_password = hash('sha256', $plain_text_password);        
        $result = $user_entity->save();

        return $result;
    }

    public function mark_as_favorite_movie(string $user_id, $movie_id, string $plain_text_password):bool
    {
        $uuser = UUser::where('user_id','=',$user_id)->get()->first();
        if(!is_null($uuser) && $uuser->hashed_password != hash('sha256', $plain_text_password)){
            throw new Exception('Invalid user information');
        }

        $mmovie = MMovie::where('id','=',$movie_id)->get()->first();
        if(is_null($mmovie) || $mmovie->count() == 0){
            throw new Exception("Not valid movie id");
        }

        $count = UUserFavorite::where([['u_user_id', '=', $user_id],['m_movie_id', '=', $movie_id]])->get()->count();
        if(!$count){
            $user_favorite_entity = new UUserFavorite;
            $user_favorite_entity->user_id = $user_id;;
            $user_favorite_entity->m_movie_id = $movie_id;

            $result = $user_favorite_entity->save();

            return $result;
        } else {
            return false;
        }
    }

    public function get_movies_marked_as_favorite()
    {
        $row_movie_ids = UUserFavorite::distinct()->select('m_movie_id')->get()->toArray();
        
        $movie_ids = [];
        foreach($row_movie_ids as $row_movie_id){$movie_ids[] = $row_movie_id['m_movie_id'];}
        
        $formatted_movie_list = [];
        $movies_marked_as_favorite = MMovie::select()->whereIn('m_movie.id', $movie_ids)->get();
        foreach($movies_marked_as_favorite as $movie_marked_as_favorite){
            $formatted_movie_list[] = [
                'id' => $movie_marked_as_favorite->id,
                'title' => $movie_marked_as_favorite->title,
                'genre' => $movie_marked_as_favorite->genre,
                'year' => $movie_marked_as_favorite->year,
                'description' => $movie_marked_as_favorite->description
            ];
        }

        return json_encode($formatted_movie_list);
    }

}