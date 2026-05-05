<?php

namespace App\Http\Controllers;

use App\Http\Repositories\WorkTypeRepository;

class WorkTypeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application Work Type list.
     *
     * @return \Illuminate\Http\Response
     */
    public function work_type_list()
    {
        $workTypeRepository = new WorkTypeRepository();
        $work_type_list = $workTypeRepository->get_all_work_type_list();
        return view('setting/work_type/list', compact('work_type_list'));
    }
}
