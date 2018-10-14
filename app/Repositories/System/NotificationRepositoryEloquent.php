<?php

namespace App\Repositories\System;

use App\Models\System\Notification;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Validators\System\NotificationValidator;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Interfaces\System\NotificationRepository;

/**
 * Class NotificationRepositoryEloquent.
 */
class NotificationRepositoryEloquent extends BaseRepository implements NotificationRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'member_id',
        'user_id',
        'type',
        'created_at',
    ];

    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return Notification::class;
    }

    /**
     * Specify Validator class name.
     *
     * @return mixed
     */
    public function validator()
    {
        return NotificationValidator::class;
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
