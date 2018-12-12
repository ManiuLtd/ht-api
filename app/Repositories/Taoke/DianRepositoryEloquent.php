<?php

namespace App\Repositories\Taoke;

use App\Models\Taoke\Dian;
use App\Criteria\RequestCriteria;
use App\Validators\Taoke\DianValidator;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\Interfaces\Taoke\DianRepository;

/**
 * Class DianRepositoryEloquent.
 */
class DianRepositoryEloquent extends BaseRepository implements DianRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name'=>'like',
        'tag'=>'like',
        'city'=>'like',
        'category_id',
    ];

    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return Dian::class;
    }

    /**
     * @return null|string
     */
    public function validator()
    {
        return DianValidator::class;
    }

    /**
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
