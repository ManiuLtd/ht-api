<?php

namespace App\Repositories\System;

use App\Models\System\Feedback;
use App\Validators\System\FeedbackValidator;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Interfaces\System\FeedbackRepository;

/**
 * Class FeedbackRepositoryEloquent.
 */
class FeedbackRepositoryEloquent extends BaseRepository implements FeedbackRepository
{
    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return Feedback::class;
    }

    /**
     * Specify Validator class name.
     *
     * @return mixed
     */
    public function validator()
    {
        return FeedbackValidator::class;
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
