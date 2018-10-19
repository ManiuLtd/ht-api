<?php

namespace App\Http\Controllers\Backend\Taoke;

use App\Http\Controllers\Controller;
use App\Validators\Taoke\ZhuanTiValidator;
use App\Http\Requests\Taoke\ZhuanTiCreateRequest;
use App\Http\Requests\Taoke\ZhuanTiUpdateRequest;
use Illuminate\Support\Facades\Validator;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Repositories\Interfaces\Taoke\ZhuanTiRepository;

/**
 * Class ZhuanTisController.
 */
class ZhuanTisController extends Controller
{
    /**
     * @var ZhuanTiRepository
     */
    protected $repository;

    /**
     * @var ZhuanTiValidator
     */
    protected $validator;

    /**
     * ZhuanTisController constructor.
     *
     * @param ZhuanTiRepository $repository
     * @param ZhuanTiValidator $validator
     */
    public function __construct(ZhuanTiRepository $repository, ZhuanTiValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = $this->repository->paginate(request('limit', 10));

        return json(1001, '获取成功', $categories);
    }

    /**
     * @param ZhuanTiCreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ZhuanTiCreateRequest $request)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $category = $this->repository->create($request->all());

            return json(1001, '添加成功', $category);
        } catch (ValidatorException $e) {
            return json(4001, $e->getMessageBag()->first());
        }
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
        $category = $this->repository->find($id);

        return json(1001, '获取成功', $category);
    }

    /**
     * @param ZhuanTiUpdateRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ZhuanTiUpdateRequest $request, $id)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $category = $this->repository->update($request->all(), $id);

            return json(1001, '修改成功', $category);
        } catch (ValidatorException $e) {
            return json(4001, $e->getMessageBag()->first());
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
        $coupon = db('tbk_coupons')->where('cat', $id)->first();
        if ($coupon) {
            return json(4001, '禁止删除已经包含优惠券的分类');
        }
        $this->repository->delete($id);

        return json(1001, '删除成功');
    }
}
