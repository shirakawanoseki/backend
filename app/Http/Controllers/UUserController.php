<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UUserController extends Controller
{
    /**
     * @OA\Post(
     *      path="/users/register",
     *      operationId="Register user",
     *      tags={"User"},
     *      summary="Register user",
     *      description="Register user",
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  type="object",
     *                  @OA\Property(
     *                      property="user_id",
     *                      type="string",
     *                      description="ID of user to add",
     *                  ),
     *                  @OA\Property(
     *                      property="password",
     *                      type="string",
     *                      description="User's password",
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
    public function register_user_entity(Request $request)
    {
        $user_id = $request->user_id;
        $plain_text_passowrd = $request->password;
        
        $userService = new UserService();
        $result = DB::transaction(function () use ($userService, $user_id, $plain_text_passowrd) {
            $result = $userService->register_user($user_id, $plain_text_passowrd);
            return $result;
        });

        return response()->json(['result' => $result]);
    }

    /**
     * @OA\Post(
     *      path="/favorites",
     *      operationId="Check movie as user's favorite",
     *      tags={"User"},
     *      summary="Mark movie as user's favorite",
     *      description="Mark movie as user's favorite",
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  type="object",
     *                  @OA\Property(
     *                      property="user_id",
     *                      type="string",
     *                      description="User ID",
     *                  ),
     *                  @OA\Property(
     *                      property="m_movie_id",
     *                      type="string",
     *                      description="Movie ID",
     *                  ),
     *                  @OA\Property(
     *                      property="password",
     *                      type="string",
     *                      description="User's password",
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
    public function mark_as_favorite_movie(Request $request)
    {
        $user_id = $request->user_id;
        $m_movie_id = $request->m_movie_id;
        $password = $request->password;

        $userService = new UserService();
        $result = DB::transaction(function () use ($userService, $user_id, $m_movie_id, $password) {
            $result = $userService->mark_as_favorite_movie($user_id, $m_movie_id, $password);
            return $result;
        });

        return response()->json(['result' => $result]);

    }

    /**
     * @OA\Get(
     *      path="/favorites",
     *      operationId="Get movies favorited by user",
     *      tags={"User"},
     *      summary="Get movies favorited by user",
     *      description="Get movies favorited by user",
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
    public function get_movies_marked_as_favorite(Request $request){
        $userService = new UserService();
        return $userService->get_movies_marked_as_favorite();
    }
}
