<?php

namespace App\Repositories\System;

use App\Events\SendSMS;
use App\Models\System\Sms;
use App\Validators\System\SmsValidator;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Interfaces\System\SmsRepository;

/**
 * Class SmsRepositoryEloquent.
 */
class SmsRepositoryEloquent extends BaseRepository implements SmsRepository
{
    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return Sms::class;
    }

    /**
     * Specify Validator class name.
     *
     * @return mixed
     */
    public function validator()
    {
        return SmsValidator::class;
    }

    /**
     * Boot up the repository, pushing criteria.
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * @return string
     */
    public function presenter()
    {
        return 'Prettus\\Repository\\Presenter\\ModelFractalPresenter';
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function sendSms()
    {
        $phone = request('phone');
        if (! preg_match("/^1[3456789]{1}\d{9}$/", $phone)) {
            return json(4001, '手机号格式不正确');
        }
        $token = rand(100000, 999999);
        event(new SendSMS($phone, env('JUHE_SMS_VERIFY_TEMPLATE_ID'), $token));
        $this->create([
            'phone' => $phone,
            'code' => $token,
        ]);

        return json(1001, '短信发送成功');
    }
}
