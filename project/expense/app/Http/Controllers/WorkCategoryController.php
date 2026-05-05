<?php

namespace App\Http\Controllers;

use App\Http\Repositories\WorkCategoryRepository;

class WorkCategoryController extends Controller
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
     * Show the application Work Category list.
     *
     * @return \Illuminate\Http\Response
     */
    public function work_category_list()
    {
        $WorkCategoryRepository = new WorkCategoryRepository();
        $work_category_list = $WorkCategoryRepository->get_all_work_category_list();
        return view('setting/work_category/list', compact('work_category_list'));
    }
}
