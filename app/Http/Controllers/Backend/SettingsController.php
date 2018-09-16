<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\SettingUpdateRequest;
use App\Repositories\Interfaces\SettingRepository;
use App\Validators\SettingValidator;

/**
 * 系统设置
 *
 * Class SettingsController.
 *
 * @package namespace App\Http\Controllers;
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
     * 设置信息
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $settings = $this->repository->firstOrCreate ();

        return json (1001, '列表获取成功', $settings);

    }

    /**
     * 更新设置
     * @param SettingUpdateRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(SettingUpdateRequest $request, $id)
    {
        try {

            $this->validator->with ($request->all ())->passesOrFail (ValidatorInterface::RULE_UPDATE);

            $setting = $this->repository->update ($request->all (), $id);

            return json (1001, "更新成功", $setting);

        } catch (ValidatorException $e) {

            return json (5001, $e->getMessageBag ());

        }
    }

}

