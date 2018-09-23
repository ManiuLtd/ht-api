<?php

namespace App\Http\Controllers\Backend\Image;

use App\Http\Controllers\Controller;
use App\Validators\Image\BannerValidator;
use App\Http\Requests\Image\BannerCreateRequest;
use App\Http\Requests\Image\BannerUpdateRequest;
use App\Repositories\Interfaces\BannerRepository;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * Class BannersController.
 */
class BannersController extends Controller
{
    /**
     * @var BannerRepository
     */
    protected $repository;

    /**
     * @var BannerValidator
     */
    protected $validator;

    /**
     * BannersController constructor.
     *
     * @param BannerRepository $repository
     * @param BannerValidator $validator
     */
    public function __construct(BannerRepository $repository, BannerValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    /**
     * 店招列表.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $banners = $this->repository->paginate(request('limit', 10));

        return json(1001, '列表获取成功', $banners);
    }

    /**
     * 添加店招.
     * @param BannerCreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(BannerCreateRequest $request)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $banner = $this->repository->create($request->all());

            return json(1001, '创建成功', $banner);
        } catch (ValidatorException $e) {
            return json(5001, $e->getMessageBag());
        }
    }

    /**
     * 编辑店招.
     * @param BannerUpdateRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(BannerUpdateRequest $request, $id)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $banner = $this->repository->update($request->all(), $id);

            return json(1001, '更新成功', $banner);
        } catch (ValidatorException $e) {
            return json(5001, $e->getMessageBag());
        }
    }

    /**
     * 删除店招.
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $this->repository->delete($id);

        return json(1001, '删除成功');
    }
}
