<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\MovieMasterService;
use Illuminate\Support\Facades\DB;

class MMovieController extends Controller
{

    /**
     * @OA\Post(
     *      path="/movies/add",
     *      operationId="Add movies master information",
     *      tags={"Movie Master"},
     *      summary="Add movies master information",
     *      description="Add movies master information",
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  type="object",
     *                  @OA\Property(
     *                      property="title",
     *                      type="string",
     *                      description="Title of movie to add",
     *                  ),
     *                  @OA\Property(
     *                      property="genre",
     *                      type="string",
     *                      description="Gnenre of movie to add",
     *                  ),
     *                  @OA\Property(
     *                      property="year",
     *                      type="int",
     *                      description="Production year of movie to add",
     *                  ),
     *                  @OA\Property(
     *                      property="description",
     *                      type="string",
     *                      description="Detailed description of movie to add",
     *                  ),
     *              ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Response at normal termination",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="result",
     *                  type="boolean",
     *                  description="Execution result",
     *              ),
     *          )
     *      ),
     *      @OA\Response(response=500, description="Internal Server Error"),
     *  )
     */
    public function add_movie_master_entity(Request $request)
    {
        $title = $request->title;
        $genre = $request->genre;
        $year = $request->year;
        $description = $request->description;
        $movieMasterService = new MovieMasterService();

        $result = DB::transaction(function () use ($movieMasterService, $title, $genre, $year, $description) {
            $result = $movieMasterService->add_movie_master_entity($title, $genre, $year, $description);
            return $result;
        });

        return response()->json(['result' => $result]);
    }

    /**
     * @OA\Get(
     *      path="/movies",
     *      operationId="Search movies by keyword",
     *      tags={"Movie Master"},
     *      summary="Search movies by keyword",
     *      description="Search movies by keyword",
     *      @OA\Parameter(
     *          name="keyword",
     *          description="Keyword to search for movies!",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Response at normal termination",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="id",
     *                  type="int",
     *                  description="Movie ID",
     *              ),
     *              @OA\Property(
     *                  property="title",
     *                  type="string",
     *                  description="Title of movie",
     *              ),
     *              @OA\Property(
     *                  property="genre",
     *                  type="string",
     *                  description="Genre of movie",
     *              ),
     *              @OA\Property(
     *                  property="year",
     *                  type="int",
     *                  description="Production year of movie",
     *              ),
     *              @OA\Property(
     *                  property="description",
     *                  type="string",
     *                  description="Detailed deacription of movie",
     *              ),
     *          ),
     *      ),
     *      @OA\Response(response=500, description="Internal Server Error"),
     *  )
     */
    public function search_movie_by_keyword(Request $request)
    {
        $keyword = $request->search;
        $movieMasterService = new MovieMasterService();
        return $movieMasterService->search_movie_by_keyword($keyword);
    }

    /**
     * @OA\Get(
     *      path="/movies/{id}",
     *      operationId="Search movies by Movie ID",
     *      tags={"Movie Master"},
     *      summary="Search movies by Movie ID",
     *      description="Search movies by Movie ID",
     *      @OA\Parameter(
     *          name="id",
     *          description="Movie ID to search for movies!",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type="int"
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Response at normal termination",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="id",
     *                  type="int",
     *                  description="Movie ID",
     *              ),
     *              @OA\Property(
     *                  property="title",
     *                  type="string",
     *                  description="Title of movie",
     *              ),
     *              @OA\Property(
     *                  property="genre",
     *                  type="string",
     *                  description="Genre of movie",
     *              ),
     *              @OA\Property(
     *                  property="year",
     *                  type="int",
     *                  description="Production year of movie",
     *              ),
     *              @OA\Property(
     *                  property="description",
     *                  type="string",
     *                  description="Detailed deacription of movie",
     *              ),
     *          ),
     *      ),
     *      @OA\Response(response=500, description="Internal Server Error"),
     *  )
     */
    public function search_movie_by_id(Request $request)
    {
        $movie_id = $request->movie_id;
        $movieMasterService = new MovieMasterService();
        return $movieMasterService->search_movie_by_id($movie_id);
    }
}