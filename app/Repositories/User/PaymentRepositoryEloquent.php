<?php

namespace App\Repositories\User;

use App\Models\User\Payment;
use App\Criteria\RequestCriteria;
use App\Validators\User\PaymentValidator;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\Interfaces\User\PaymentRepository;

/**
 * Class PaymentRepositoryEloquent.
 */
class PaymentRepositoryEloquent extends BaseRepository implements PaymentRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'out_trade_no' => 'like',
        'user_id',
        'user_id',
        'type',
        'status',
        'created_at',
    ];

    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return Payment::class;
    }

    /**
     * Specify Validator class name.
     *
     * @return mixed
     */
    public function validator()
    {
        return PaymentValidator::class;
    }

    /**
     * @throws \Prettus\Repository\Exceptions\RepositoryException
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
