<?php

namespace App\Http\Controllers\Backend\Member;

use App\Criteria\DatePickerCriteria;
use App\Http\Controllers\Controller;
use App\Validators\Member\MemberValidator;
use App\Http\Requests\Member\MemberUpdateRequest;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Repositories\Interfaces\Member\MemberRepository;

/**
 * Class MembersController.
 */
class MembersController extends Controller
{
    /**
     * @var MemberRepository
     */
    protected $repository;

    /**
     * @var MemberValidator
     */
    protected $validator;

    /**
     * MembersController constructor.
     *
     * @param MemberRepository $repository
     * @param MemberValidator $validator
     */
    public function __construct(MemberRepository $repository, MemberValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    /**
     *  会员列表.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $members = $this->repository
            ->pushCriteria(new DatePickerCriteria())
            ->with(['level', 'inviter'])
            ->paginate(request('limit', 10));

        return json(1001, '列表获取成功', $members);
    }

    /**
     * 会员详情.
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $member = $this->repository->with(['level', 'inviter'])->find($id);

        return json(1001, '详情获取成功', $member);
    }

    /**
     * 编辑会员.
     * @param MemberUpdateRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(MemberUpdateRequest $request, $id)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $member = $this->repository->update($request->all(), $id);

            return json(1001, '更新成功', $member);
        } catch (ValidatorException $e) {
            return json(5001, $e->getMessageBag());
        }
    }

    /**
     * 删除会员.
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $this->repository->delete($id);

        return json(1001, '删除成功');
    }
}
