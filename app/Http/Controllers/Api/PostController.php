<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\postResource;
use Illuminate\Support\Facades\Validator;


/**
 * @OA\Info(
 *   title="Your API Title",
 *   version="1.0",
 *   description="Your API Description",
 *   @OA\Contact(
 *     email="your-email@example.com",
 *     name="Your Name"
 *   )
 * )
 */
class PostController extends Controller
{
    use ApiResponseTrait;
    use ValidationTrait;

    /**
     * @OA\Get(
     *     path="/api/posts",
     *     tags={"Posts"},
     *     summary="Get all posts",
     *     description="Returns all posts",
     *     @OA\Response(response="200", description="Success"),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function index()
    {
        $posts =  PostResource::collection(post::get()); //لو هرجع جميع البيانات
        return $this->apiResponse($posts, 'ok', 200);
    }



    /**
     * @OA\Get(
     *     path="/api/post/{id}",
     *     tags={"single posts"},
     *     summary="Get a single post",
     *     description="Returns a single post by its ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the post",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=404, description="Post not found")
     * )
     */
    public function show($id)
    {

        $post = Post::find($id);

        if ($post) {
            return $this->apiResponse($post, 'ok', 200);
        }

        return $this->apiResponse(null, 'The post Not Found', 404);
    }


    public function store(Request $request)
    {

        $validator = $this->validatePostRequest($request);

        if ($validator->fails()) {
            return $this->apiResponse(null, $validator->errors(), 400);
        }


        $post = Post::create($request->all());

        if ($post) {
            return $this->apiResponse(new PostResource($post), 'The post Save', 201);
        }

        return $this->apiResponse(null, 'The post Not Save', 400);
    }

    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'body' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->apiResponse(null, $validator->errors(), 400);
        }

        $post = Post::find($id);

        if (!$post) {
            return $this->apiResponse(null, 'The post Not Found', 404);
        }

        $post->update($request->all());

        if ($post) {
            return $this->apiResponse(new PostResource($post), 'The post update', 201);
        }
    }


    public function destroy($id)
    {

        $post = Post::find($id);

        if (!$post) {
            return $this->apiResponse(null, 'The post Not Found', 404);
        }

        $post->delete($id);

        if ($post) {
            return $this->apiResponse(null, 'The post deleted', 200);
        }
    }
}
