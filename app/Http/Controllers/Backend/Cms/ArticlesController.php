<?php

namespace App\Http\Controllers\Backend\Cms;

use App\Http\Controllers\Controller;
use App\Validators\Cms\ArticleValidator;
use App\Http\Requests\Cms\ArticleCreateRequest;
use App\Http\Requests\Cms\ArticleUpdateRequest;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Repositories\Interfaces\Cms\ArticleRepository;

/**
 * Class ArticlesController.
 */
class ArticlesController extends Controller
{
    /**
     * @var ArticleRepository
     */
    protected $repository;

    /**
     * @var ArticleValidator
     */
    protected $validator;

    /**
     * ArticlesController constructor.
     *
     * @param ArticleRepository $repository
     * @param ArticleValidator $validator
     */
    public function __construct(ArticleRepository $repository, ArticleValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    /**
     * 文章列表.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $article = $this->repository
            ->with(['user', 'category'])
            ->paginate(request('limit', 10));

        return json(1001, '列表获取成功', $article);
    }

    /**
     * 添加文章.
     * @param ArticleCreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ArticleCreateRequest $request)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $article = $this->repository->create($request->all());

            return json(1001, '创建成功', $article);
        } catch (ValidatorException $e) {
            return json(5001, $e->getMessageBag());
        }
    }

    /**
     * 编辑文章.
     * @param ArticleUpdateRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ArticleUpdateRequest $request, $id)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $article = $this->repository->update($request->all(), $id);

            return json(1001, '更新成功', $article);
        } catch (ValidatorException $e) {
            return json(5001, $e->getMessageBag());
        }
    }

    /**
     * 删除文章.
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $this->repository->delete($id);

        return json(1001, '删除成功');
    }
}
