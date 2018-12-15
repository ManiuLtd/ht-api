<?php

namespace App\Http\Controllers\Backend\Taoke;

use App\Http\Controllers\Controller;
use App\Validators\Taoke\EntranceCategoryValidator;
use App\Http\Requests\Taoke\EntranceCategoryCreateRequest;
use App\Http\Requests\Taoke\EntranceCategoryUpdateRequest;
use Prettus\Validator\Contracts\ValidatorInterface;
use App\Repositories\Interfaces\Taoke\EntranceCategoryRepository;

/**
 * Class EntrancesController.
 */
class EntranceCategoriesController extends Controller
{
    /**
     * @var EntranceCategoryRepository
     */
    protected $repository;

    /**
     * @var EntranceCategoryValidator
     */
    protected $validator;

    /**
     * EntranceCategoriesController constructor.
     * @param EntranceCategoryRepository $repository
     * @param EntranceCategoryValidator $validator
     */
    public function __construct(EntranceCategoryRepository $repository, EntranceCategoryValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = $this->repository->with(['children'])->paginate(request('limit', 10));

        return json(1001, '获取成功', $categories);
    }

    /**
     * @param EntranceCategoryCreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(EntranceCategoryCreateRequest $request)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $category = $this->repository->create($request->all());

            return json(1001, '添加成功', $category);
        } catch (\Exception $e) {
            return json(5001, $e->getMessage());
        }
    }

    /**
     * @param EntranceCategoryUpdateRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(EntranceCategoryUpdateRequest $request, $id)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $category = $this->repository->update($request->all(), $id);

            return json(1001, '修改成功', $category);
        } catch (\Exception $e) {
            return json(5001, $e->getMessage());
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
        try{
            $category = db('tbk_entrance_categories')->where('parent_id', $id)->first();
            if ($category) {
                throw new \Exception('禁止删除包含下级的分类');
            }
            $entrance = db('tbk_entrances')->where('category_id', $id)->first();
            if ($entrance) {
                throw new \Exception('禁止删除包含入口的分类');
            }
            $this->repository->delete($id);

            return json(1001, '删除成功');

        }catch(\Exception $e){
            return json('5001',$e->getMessage());
        }

    }
}
