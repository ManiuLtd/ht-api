<?php

namespace App\Http\Controllers\Backend\Shop;

use App\Http\Controllers\Controller;
use App\Validators\Shop\ShopGoodsCommentValidator;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\Shop\ShopGoodsCommentUpdateRequest;
use App\Repositories\Interfaces\ShopGoodsCommentRepository;

/**
 * Class ShopGoodsCommentsController.
 */
class ShopGoodsCommentsController extends Controller
{
    /**
     * @var ShopGoodsCommentRepository
     */
    protected $repository;

    /**
     * @var ShopGoodsCommentValidator
     */
    protected $validator;

    /**
     * ShopGoodsCommentsController constructor.
     *
     * @param ShopGoodsCommentRepository $repository
     * @param ShopGoodsCommentValidator $validator
     */
    public function __construct(ShopGoodsCommentRepository $repository, ShopGoodsCommentValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    /**
     * 商品评论列表.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $comments = $this->repository->with(['member', 'order', 'goods'])->all();

        return json(1001, '列表获取成功', $comments);
    }

    /**
     * 编辑评论.
     * @param ShopGoodsCommentUpdateRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ShopGoodsCommentUpdateRequest $request, $id)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $couponCategory = $this->repository->update($request->all(), $id);

            return json(1001, '更新成功', $couponCategory);
        } catch (ValidatorException $e) {
            return json(5001, $e->getMessageBag());
        }
    }

    /**
     * 评论详情.
     *
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $comment = $this->repository->with(['member', 'order', 'goods'])->find($id);

        return json(1001, '详情获取成功', $comment);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deleted = $this->repository->delete($id);

        if (request()->wantsJson()) {
            return response()->json([
                'message' => 'ShopGoodsComment deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'ShopGoodsComment deleted.');
    }
}
