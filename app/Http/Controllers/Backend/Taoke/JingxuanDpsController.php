<?php

namespace App\Http\Controllers\Backend\Taoke;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Taoke\JingxuanDp;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\Taoke\JingxuanDpCreateRequest;
use App\Http\Requests\Taoke\JingxuanDpUpdateRequest;
use App\Repositories\Interfaces\Taoke\JingxuanDpRepository;
use App\Validators\Taoke\JingxuanDpValidator;

/**
 * Class JingxuanDpsController.
 *
 * @package namespace App\Http\Controllers\Taoke;
 */
class JingxuanDpsController extends Controller
{
    /**
     * @var JingxuanDpRepository
     */
    protected $repository;

    /**
     * @var JingxuanDpValidator
     */
    protected $validator;

    /**
     * JingxuanDpsController constructor.
     *
     * @param JingxuanDpRepository $repository
     * @param JingxuanDpValidator $validator
     */
    public function __construct(JingxuanDpRepository $repository, JingxuanDpValidator $validator)
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
        $this->repository->pushCriteria(app('Prettus\Repository\Criteria\RequestCriteria'));
        $jingxuanDps = $this->repository->paginate(15);
        return json(1001,'获取成功',$jingxuanDps);
    }

    /**
     * @param JingxuanDpCreateRequest $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function store(JingxuanDpCreateRequest $request)
    {

        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);
            $data = $request->except('token');
            $data['pic_url'] = json_encode($data['pic_url']);
            $jingxuanDp = $this->repository->create($data);
            return json(1001,'创建成功',$jingxuanDp);
        } catch (ValidatorException $e) {
            return json(4001,$e->getMessageBag()->first());
        }

    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $data = $this->repository->find($id);
        return json(1001,'获取成功',$data);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param JingxuanDpUpdateRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(JingxuanDpUpdateRequest $request, $id)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);
            $data = $request->except('token');
            $data['pic_url'] = json_encode($data['pic_url']);
            $jingxuanDp = $this->repository->update($data,$id);
            return json(1001,'修改成功',$jingxuanDp);
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
