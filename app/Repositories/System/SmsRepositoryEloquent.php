<?php

namespace App\Repositories\System;

use App\Models\System\Sms;
use App\Repositories\Interfaces\System\SmsRepository;
use App\Validators\System\SmsValidator;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;

/**
 * Class SmsRepositoryEloquent
 * @package App\Repositories\System
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
}
