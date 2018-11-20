<?php

namespace App\Http\Controllers\Backend\Taoke;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\Taoke\SettingCreateRequest;
use App\Http\Requests\Taoke\SettingUpdateRequest;
use App\Repositories\Interfaces\Taoke\SettingRepository;
use App\Validators\Taoke\SettingValidator;

/**
 * Class SettingsController.
 *
 * @package namespace App\Http\Controllers\Taoke;
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
        $this->validator  = $validator;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $settings = $this->repository->findWhere([
            'user_id' => getUserId(),

        ])->first();

        return json(1001, '列表获取成功', $settings);
    }

    /**
     * @param SettingUpdateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(SettingUpdateRequest $request)
    {

        try {
            $data = $request->except('token');
            $data['user_id'] = getUserId();

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $setting = $this->repository->updateOrCreate([
                'user_id' => getUserId()
            ], $data);

            return json(1001,'更新成功', $setting);
        } catch (ValidatorException $e) {
            return json(5001,$e->getMessageBag()->first());
        }
    }


}
