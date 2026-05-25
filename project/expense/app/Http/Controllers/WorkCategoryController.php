<?php

namespace App\Http\Controllers;

use App\Repositories\WorkCategoryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

class WorkCategoryController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $workCategoryRepository;
    public function __construct(WorkCategoryRepository $workCategoryRepository)
    {
        // $this->middleware('auth');
        $this->workCategoryRepository = $workCategoryRepository;
    }

    /**
     * Show the application Work Category list.
     *
     * @return \Illuminate\Http\Response
     */
    public function work_category_list()
    {
        $perPage = config('constants.pagination.work_category');

        $work_category_list = $this->workCategoryRepository->get_all_work_category_list($perPage);
        return view('setting/work_category/list', compact('work_category_list'));
    }

    /**
     * Toggle Work Category status (Active / Inactive)
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggle_status()
    {
        $id = request('id');
        $updatedBy = auth()->user()->name;

        $result = $this->workCategoryRepository->toggle_status($id, $updatedBy);

        // default fail message
        $response['message'] = Lang::get('messages.work_category.toggle.fail');
        $response['design'] = 'alert-danger';

        if ($result) {
            $response['message'] = Lang::get('messages.work_category.toggle.success');
            $response['design'] = 'alert-success';
        }

        return redirect('/work_category_list')->with('response', $response);
    }

    /**
     * Get the Work Category based on id.
     *
     * @return \Illuminate\Http\Response
     */
    public function get_by_id(Request $request)
    {
        $data = $this->workCategoryRepository->get_by_id($request->id);
        return response()->json($data);
    }

    /**
     * register the Work Category.
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {

        $request->validate(
            [
                'work_category_name' => 'required|max:50',
            ],
            [
                // custom error messages for validation
                'work_category_name.required' => Lang::get('messages.work_category.validation.name.required'),
                'work_category_name.max' => Lang::get('messages.work_category.validation.name.max'),
            ]
        );

        $result = $this->workCategoryRepository->register_data(request('work_category_name'), auth()->user()->name);
        // default response
        $response['message'] = Lang::get('messages.work_category.create.fail');
        $response['design'] = "alert-danger";

        if ($result) {
            $response['message'] = Lang::get('messages.work_category.create.success');
            $response['design'] = "alert-success";
        }
        return redirect('work_category_list')->with('response', $response);
    }


    /**
     * update the Work Category.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        $request->validate(
            [
                'id' => 'required',
                'work_category_name' => 'required|max:50',
            ],
            [
                // custom error messages for validation
                'id.required' => 'The Work Category Id is required.',
                'work_category_name.required' => Lang::get('messages.work_category.validation.name.required'),
                'work_category_name.max' => Lang::get('messages.work_category.validation.name.max'),
            ]
        );

        $result = $this->workCategoryRepository->update_data($request->id, $request->work_category_name, auth()->user()->name);
        // default response
        $response['message'] = Lang::get('messages.work_category.update.fail');
        $response['design'] = "alert-danger";

        if ($result !== false) {
            $response['message'] = Lang::get('messages.work_category.update.success');
            $response['design'] = "alert-success";
        }
        return redirect('work_category_list')->with('response', $response);
    }
}
