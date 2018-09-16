<?php

namespace App\Http\Controllers\Backend\Shop;

use App\Http\Controllers\Controller;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\Shop\ShopGoodsCommentCreateRequest;
use App\Http\Requests\Shop\ShopGoodsCommentUpdateRequest;
use App\Repositories\Interfaces\ShopGoodsCommentRepository;
use App\Validators\Shop\ShopGoodsCommentValidator;

/**
 * Class ShopGoodsCommentsController.
 *
 * @package namespace App\Http\Controllers;
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
     * 商品评论列表
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $comments = $this->repository->all ();

        return json (1001, "更新成功", $comments);

    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $comment = $this->repository->find ($id);

        if (request ()->wantsJson ()) {

            return response ()->json ([
                'data' => $comment,
            ]);
        }

        return view ('comments.show', compact ('comment'));
    }



    /**
     * @param ShopGoodsCommentUpdateRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(ShopGoodsCommentUpdateRequest $request, $id)
    {
        try {

            $this->validator->with ($request->all ())->passesOrFail (ValidatorInterface::RULE_UPDATE);

            $comment = $this->repository->update ($request->all (), $id);

            $response = [
                'message' => 'ShopGoodsComment updated.',
                'data' => $comment->toArray (),
            ];

            if ($request->wantsJson ()) {

                return response ()->json ($response);
            }

            return redirect ()->back ()->with ('message', $response['message']);
        } catch (ValidatorException $e) {

            if ($request->wantsJson ()) {

                return response ()->json ([
                    'error' => true,
                    'message' => $e->getMessageBag ()
                ]);
            }

            return redirect ()->back ()->withErrors ($e->getMessageBag ())->withInput ();
        }
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
        $deleted = $this->repository->delete ($id);

        if (request ()->wantsJson ()) {

            return response ()->json ([
                'message' => 'ShopGoodsComment deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect ()->back ()->with ('message', 'ShopGoodsComment deleted.');
    }
}
