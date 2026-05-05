<?php

namespace App\Http\Controllers;

use App\Http\Repositories\ProjectTypeRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

class ProjectTypeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $projectTypeRepository;
    public function __construct(ProjectTypeRepository $projectTypeRepository)
    {
        $this->middleware('auth');
        $this->projectTypeRepository = $projectTypeRepository;
    }

    /**
     * Show the application Project Type list.
     *
     * @return \Illuminate\Http\Response
     */
    public function project_type_list()
    {
        $perPage = config('constants.pagination.project_type');

        $project_type_list = $this->projectTypeRepository->get_all_project_type_list($perPage);
        return view('setting/project_type/list', compact('project_type_list'));
    }

    /**
     * Toggle Project Type status (Active / Inactive)
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggle_status()
    {
        $id = request('id');
        $updatedBy = auth()->user()->name;

        $result = $this->projectTypeRepository->toggle_status($id, $updatedBy);

        // default fail message
        $response['message'] = Lang::get('messages.project_type.toggle.fail');
        $response['design'] = 'alert-danger';

        if ($result) {
            $response['message'] = Lang::get('messages.project_type.toggle.success');
            $response['design'] = 'alert-success';
        }

        return redirect('/project_type_list')->with('response', $response);
    }

    /**
     * Get the Project Type based on id.
     *
     * @return \Illuminate\Http\Response
     */
    public function get_by_id(Request $request)
    {
        $data = $this->projectTypeRepository->get_by_id($request->id);
        return response()->json($data);
    }

    /**
     * register the Project Type.
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {

        $this->validate(
            $request,
            [
                'project_type_name' => 'required|max:50',
            ],
            [
                // custom error messages for validation
                'project_type_name.required' => 'The Project Type Name is required.',
                'project_type_name.max' => 'The Project Type must be 50 characters or less.',
            ]
        );

        $result = $this->projectTypeRepository->register_data(request('project_type_name'), auth()->user()->name);
        // default response
        $response['message'] = Lang::get('messages.project_type.create.fail');
        $response['design'] = "alert-danger";

        if ($result) {
            $response['message'] = Lang::get('messages.project_type.create.success');
            $response['design'] = "alert-success";
        }
        return redirect('project_type_list')->with('response', $response);
    }


    /**
     * update the Project Type.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        $this->validate(
            $request,
            [
                'id' => 'required',
                'project_type_name' => 'required|max:50',
            ],
            [
                // custom error messages for validation
                'id.required' => 'The Project Type Id is required.',
                'project_type_name.required' => 'The Project Type Name is required.',
                'project_type_name.max' => 'The Project Type must be 50 characters or less.',
            ]
        );

        $result = $this->projectTypeRepository->update_data($request->id, $request->project_type_name, auth()->user()->name);
        // default response
        $response['message'] = Lang::get('messages.project_type.update.fail');
        $response['design'] = "alert-danger";

        if ($result !== false) {
            $response['message'] = Lang::get('messages.project_type.update.success');
            $response['design'] = "alert-success";
        }
        return redirect('project_type_list')->with('response', $response);
    }
}
