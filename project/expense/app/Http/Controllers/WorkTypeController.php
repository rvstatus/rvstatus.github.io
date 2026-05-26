<?php

namespace App\Http\Controllers;

use App\Repositories\WorkTypeRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

class WorkTypeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $workTypeRepository;
    public function __construct(WorkTypeRepository $workTypeRepository)
    {
        // $this->middleware('auth');
        $this->workTypeRepository = $workTypeRepository;
    }

    /**
     * Show the application Work Type list.
     *
     * @return \Illuminate\Http\Response
     */
    public function work_type_list()
    {
        $perPage = config('constants.pagination.work_type');

        $work_type_list = $this->workTypeRepository->get_all_work_type_list($perPage);
        return view('setting/work_type/list', compact('work_type_list'));
    }

    /**
     * Toggle Work Type status (Active / Inactive)
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggle_status()
    {
        $id = request('id');
        $updatedBy = auth()->user()->name;

        $result = $this->workTypeRepository->toggle_status($id, $updatedBy);

        // default fail message
        $response['message'] = Lang::get('messages.work_type.toggle.fail');
        $response['design'] = 'alert-danger';

        if ($result) {
            $response['message'] = Lang::get('messages.work_type.toggle.success');
            $response['design'] = 'alert-success';
        }

        return redirect('/work_type_list')->with('response', $response);
    }

    /**
     * Get the Work Type based on id.
     *
     * @return \Illuminate\Http\Response
     */
    public function get_by_id(Request $request)
    {
        $data = $this->workTypeRepository->get_by_id($request->id);
        return response()->json($data);
    }

    /**
     * register the Work Type.
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {

        $request->validate(
            [
                'work_type_name' => 'required|max:50',
            ],
            [
                // custom error messages for validation
                'work_type_name.required' => Lang::get('messages.work_type.validation.name.required'),
                'work_type_name.max' => Lang::get('messages.work_type.validation.name.max'),
            ]
        );

        $result = $this->workTypeRepository->register_data(request('work_type_name'), auth()->user()->name);
        // default response
        $response['message'] = Lang::get('messages.work_type.create.fail');
        $response['design'] = "alert-danger";

        if ($result) {
            $response['message'] = Lang::get('messages.work_type.create.success');
            $response['design'] = "alert-success";
        }
        return redirect('work_type_list')->with('response', $response);
    }


    /**
     * update the Work Type.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        $request->validate(
            [
                'id' => 'required',
                'work_type_name' => 'required|max:50',
            ],
            [
                // custom error messages for validation
                'id.required' => 'The Work Type Id is required.',
                'work_type_name.required' => Lang::get('messages.work_type.validation.name.required'),
                'work_type_name.max' => Lang::get('messages.work_type.validation.name.max'),
            ]
        );

        $result = $this->workTypeRepository->update_data($request->id, $request->work_type_name, auth()->user()->name);
        // default response
        $response['message'] = Lang::get('messages.work_type.update.fail');
        $response['design'] = "alert-danger";

        if ($result !== false) {
            $response['message'] = Lang::get('messages.work_type.update.success');
            $response['design'] = "alert-success";
        }
        return redirect('work_type_list')->with('response', $response);
    }
}
