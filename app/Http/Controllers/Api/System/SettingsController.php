<?php

namespace App\Http\Controllers\Api\System;

use App\Http\Controllers\Controller;
use App\Validators\System\SettingValidator;
use App\Http\Requests\System\SettingUpdateRequest;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
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

    // TODO 获取设置信息
    public function index()
    {
        $setting = $this->repository->find(1);dd($setting);
    }

}
