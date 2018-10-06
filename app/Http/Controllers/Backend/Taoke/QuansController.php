<?php

namespace App\Http\Controllers\Backend\Taoke;

use App\Http\Controllers\Controller;
use App\Http\Requests\Taoke\QuanCreateRequest;
use App\Http\Requests\Taoke\QuanUpdateRequest;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Repositories\Interfaces\Taoke\QuanRepository;
use App\Validators\Taoke\QuanValidator;

/**
 * Class CategoriesController.
 *
 * @package namespace App\Http\Controllers\Taoke;
 */
class QuansController extends Controller
{
    /**
     * @var QuanRepository
     */
    protected $repository;

    /**
     * @var QuanValidator
     */
    protected $validator;

    /**
     * CategoriesController constructor.
     *
     * @param QuanRepository $repository
     * @param QuanValidator $validator
     */
    public function __construct(QuanRepository $repository, QuanValidator $validator)
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
        $quans = $this->repository
            ->with(['user','goods'])
            ->paginate(request('limit', 10));
        return json(1001,'获取成功',$quans);
    }

    /**
     * @param QuanCreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(QuanCreateRequest $request)
    {
        try {

            $this->validator->with($request->all())->passesOrFail();

            $quan = $this->repository->create($request->all());
            return json(1001,'添加成功',$quan);
        } catch (ValidatorException $e) {
            return json(4001,$e->getMessageBag()->first());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $quan = $this->repository->find($id);

        return json(1001,'获取成功',$quan);
    }


    /**
     * @param QuanUpdateRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(QuanUpdateRequest $request, $id)
    {
        try {
            $this->validator->with($request->all())->passesOrFail();

            $quan = $this->repository->update($request->all(), $id);

            return json(1001,'修改成功', $quan);

        } catch (ValidatorException $e) {

            return json(4001,$e->getMessageBag()->first());
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $this->repository->delete($id);

        return json(1001,'删除成功');
    }
}
