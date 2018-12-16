<?php

namespace App\Http\Controllers\Backend\Taoke;

use App\Http\Controllers\Controller;
use App\Validators\Taoke\SettingValidator;
use App\Http\Requests\Taoke\SettingUpdateRequest;
use Prettus\Validator\Contracts\ValidatorInterface;
use App\Repositories\Interfaces\Taoke\SettingRepository;

/**
 * Class SettingsController.
 */
class SettingsController extends Controller
{
    /**
     * @var SettingRepository
     */
    protected $repository;

    /**
     * @var SettingValidator
     */
    protected $validator;

    /**
     * SettingsController constructor.
     *
     * @param SettingRepository $repository
     * @param SettingValidator $validator
     */
    public function __construct(SettingRepository $repository, SettingValidator $validator)
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
        $settings = $this->repository->firstOrCreate([
            'user_id' => getUserId(),
        ]);

        return json(1001, '列表获取成功', $settings);
    }

    /**
     * @param SettingUpdateRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(SettingUpdateRequest $request, $id)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $settingModel = $this->repository->find($id);

            if ($settingModel->user_id != getUserId()) {
                throw  new  \Exception('请勿恶意修改参数');
            }

            $setting = $this->repository->update($request->all(), $id);

            return json(1001, '更新成功', $setting);
        } catch (\Exception $e) {
            return json(5001, $e->getMessage());
        }
    }
}
