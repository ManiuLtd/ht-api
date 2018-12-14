<?php

namespace App\Repositories\Taoke;

use App\Criteria\RequestCriteria;
use App\Models\Taoke\DianOrder;
use App\Validators\Taoke\DianOrderValidator;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\Interfaces\Taoke\DianOrderRepository;

/**
 * Class DianRepositoryEloquent.
 */
class DianOrderRepositoryEloquent extends BaseRepository implements DianOrderRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'transaction_id',
        'out_trade_no',
        'title'=>'like',
    ];

    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return DianOrder::class;
    }

    /**
     * @return null|string
     */
    public function validator()
    {
        return DianOrderValidator::class;
    }

    /**
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

}
