<?php

namespace App\Http\Controllers\Backend\Taoke;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Validators\Taoke\CouponValidator;
use App\Http\Requests\Taoke\CouponCreateRequest;
use App\Http\Requests\Taoke\CouponUpdateRequest;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Repositories\Interfaces\Taoke\CouponRepository;

/**
 * Class CouponsController.
 */
class CouponsController extends Controller
{
    /**
     * @var CouponRepository
     */
    protected $repository;

    /**
     * @var CouponValidator
     */
    protected $validator;

    /**
     * CouponsController constructor.
     *
     * @param CouponRepository $repository
     * @param CouponValidator $validator
     */
    public function __construct(CouponRepository $repository, CouponValidator $validator)
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
        $coupons = $this->repository->paginate(request('limit', 10));

        return json(1001, '获取成功', $coupons);
    }

    /**
     * @param CouponCreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CouponCreateRequest $request)
    {
        try {
            $this->validator->with($request->all())->passesOrFail();

            $coupon = $this->repository->create($request->all());

            return json(1001, '添加成功', $coupon);
        } catch (ValidatorException $e) {
            return json(4001, $e->getMessageBag()->first());
        }
    }


    /**
     * @param CouponUpdateRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(CouponUpdateRequest $request, $id)
    {
        try {
            $this->validator->with($request->all())->passesOrFail();

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
        $this->repository->delete($id);

        return json(1001, '删除成功');
    }
}
