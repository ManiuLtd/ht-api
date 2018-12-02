<?php

namespace App\Http\Controllers\Api\System;

use App\Http\Controllers\Controller;
use App\Validators\System\SettingValidator;
use App\Repositories\Interfaces\System\SettingRepository;

/**
 * 系统设置.
 *
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
     * 获取设置信息.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $setting = $this->repository->firstOrNew();

        return json('1001', '设置信息', $setting);
    }

    public function xieyi()
    {
        $setting = $this->repository->firstOrNew('xieyi');

        return json('1001', '设置信息', $setting);
    }
}
