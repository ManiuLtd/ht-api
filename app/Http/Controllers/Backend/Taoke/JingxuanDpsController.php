<?php

namespace App\Http\Controllers\Taoke;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\JingxuanDpCreateRequest;
use App\Http\Requests\JingxuanDpUpdateRequest;
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
        $jingxuanDps = $this->repository->all();

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $jingxuanDps,
            ]);
        }

        return view('jingxuanDps.index', compact('jingxuanDps'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  JingxuanDpCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(JingxuanDpCreateRequest $request)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $jingxuanDp = $this->repository->create($request->all());

            $response = [
                'message' => 'JingxuanDp created.',
                'data'    => $jingxuanDp->toArray(),
            ];

            if ($request->wantsJson()) {

                return response()->json($response);
            }

            return redirect()->back()->with('message', $response['message']);
        } catch (ValidatorException $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'error'   => true,
                    'message' => $e->getMessageBag()
                ]);
            }

            return redirect()->back()->withErrors($e->getMessageBag())->withInput();
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
        $jingxuanDp = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $jingxuanDp,
            ]);
        }

        return view('jingxuanDps.show', compact('jingxuanDp'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $jingxuanDp = $this->repository->find($id);

        return view('jingxuanDps.edit', compact('jingxuanDp'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  JingxuanDpUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(JingxuanDpUpdateRequest $request, $id)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $jingxuanDp = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'JingxuanDp updated.',
                'data'    => $jingxuanDp->toArray(),
            ];

            if ($request->wantsJson()) {

                return response()->json($response);
            }

            return redirect()->back()->with('message', $response['message']);
        } catch (ValidatorException $e) {

            if ($request->wantsJson()) {

                return response()->json([
                    'error'   => true,
                    'message' => $e->getMessageBag()
                ]);
            }

            return redirect()->back()->withErrors($e->getMessageBag())->withInput();
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
        $deleted = $this->repository->delete($id);

        if (request()->wantsJson()) {

            return response()->json([
                'message' => 'JingxuanDp deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'JingxuanDp deleted.');
    }
}
