<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleCollectionResource;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ArticleController extends BaseController
{
    /**
     * Display a listing of the articles.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $now = Carbon::now();
            $articles = Article::where('expired_at', '>', $now)->get();

            return $this->sendResponse(ArticleCollectionResource::collection($articles), 'Articles retrieved successfully.');
        } catch (Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }
    }

    /**
     * Store a newly created article in database.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'title'         => 'required|max:255',
            'author_name'   => 'required|max:255',
            'text'          => 'required',
            'published_at'  => 'required|date_format:Y-m-d H:i:s',
            'expired_at'    => 'required|date_format:Y-m-d H:i:s'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', (array)$validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $article = Article::create($input);

        return $this->sendResponse(new ArticleResource($article), 'Article created successfully.');
    }

    /**
     * Display the specified article.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        $article = Article::find($id);

        if (is_null($article)) {
            return $this->sendError('Article not found.');
        }

        return $this->sendResponse(new ArticleResource($article), 'Article retrieved successfully.');
    }

    /**
     * Update the specified article in database.
     *
     * @param Request $request
     * @param Article $article
     * @return JsonResponse
     */
    public function update(Request $request, Article $article): JsonResponse
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'title'         => 'required',
            'author_name'   => 'required',
            'text'          => 'required',
            'published_at'  => 'required',
            'expired_at'    => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', (array)$validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $article->title = $input['title'];
        $article->author_name = $input['author_name'];
        $article->text = $input['text'];
        $article->published_at = $input['published_at'];
        $article->expired_at = $input['expired_at'];
        $article->save();

        return $this->sendResponse(new ArticleResource($article), 'Article updated successfully.');
    }

    /**
     * Remove the specified article from database.
     *
     * @param Article $article
     * @return JsonResponse
     */
    public function destroy(Article $article): JsonResponse
    {
        $article->delete();

        return $this->sendResponse([], 'Article deleted successfully.');
    }
}
