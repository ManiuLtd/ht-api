<?php

namespace App\Http\Controllers\Backend\Taoke;

use App\Http\Controllers\Controller;
use App\Validators\Taoke\DiyAdsValidator;
use App\Http\Requests\Taoke\DiyAdsCreateRequest;
use App\Http\Requests\Taoke\DiyAdsUpdateRequest;
use Prettus\Validator\Contracts\ValidatorInterface;
use App\Repositories\Interfaces\Taoke\DiyAdsRepository;

/**
 * Class DiyAdsController.
 */
class DiyAdsController extends Controller
{
    /**
     * @var DiyAdsRepository
     */
    protected $repository;

    /**
     * @var DiyAdsValidator
     */
    protected $validator;

    /**
     * DiyAdsController constructor.
     *
     * @param DiyAdsRepository $repository
     * @param DiyAdsValidator $validator
     */
    public function __construct(DiyAdsRepository $repository, DiyAdsValidator $validator)
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
        $diyAds = $this->repository
            ->paginate(request('limit', 10));

        return json(1001, '获取成功', $diyAds);
    }

    /**
     * @param DiyAdsCreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(DiyAdsCreateRequest $request)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $diyAds = $this->repository->create($request->all());

            return json(1001, '添加成功', $diyAds);
        } catch (\Exception $e) {
            return json(5001, $e->getMessage());
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
        $diyAds = $this->repository->find($id);

        return json(1001, '获取成功', $diyAds);
    }

    /**
     * @param DiyAdsUpdateRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(DiyAdsUpdateRequest $request, $id)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $diyAds = $this->repository->update($request->all(), $id);

            return json(1001, '修改成功', $diyAds);
        } catch (\Exception $e) {
            return json(5001, $e->getMessage());
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
