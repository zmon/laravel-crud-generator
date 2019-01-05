<?php

namespace [[appns]]Http\Controllers;



use [[appns]]Http\Middleware\TrimStrings;
use [[appns]][[model_uc]];
use Illuminate\Http\Request;
use [[appns]]Http\Requests\[[model_uc]]StoreRequest;
use [[appns]]Http\Requests\[[model_uc]]EditRequest;
use Illuminate\Support\Facades\Redirect;

class [[controller_name]]Controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        // Remember the search parameters, we saved them in the Query
        $page = session('[[model_singular]]_page', '');
        $search = session('[[model_singular]]_keyword', '');
        $column = session('[[model_singular]]_column', 'Name');
        $direction = session('[[model_singular]]_direction', '-1');

        $can_add = true; // Auth::user()->isAllowed('[[model_singular]]-add');
        $can_edit = true; // Auth::user()->isAllowed('[[model_singular]]-edit');
        $can_delete = true; // Auth::user()->isAllowed('[[model_singular]]-delete');
        $can_show = true; // Auth::user()->isAllowed('[[model_singular]]-show');
        $can_excel = true; // Auth::user()->isAllowed('[[model_singular]]-excel');

        return view('[[view_folder]].index', compact('page', 'column', 'direction', 'search', 'can_add', 'can_edit', 'can_delete', 'can_show', 'can_excel'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
	public function create(Request $request)
	{
	    return view('[[view_folder]].create');
	}


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store([[model_uc]]StoreRequest $request)
    {

        $[[model_singular]] = new \App\[[model_uc]];

        if (!$[[model_singular]]->add($request->all())) {
            \Session::flash('flash_error_message', 'Member could not be added.  Try again.');
            return redirect('[[model_singular]]/create')
                ->withErrors($request->validator)
                ->withInput();
        }

        \Session::flash('flash_success_message', '[[model_uc]] ' . $[[model_singular]]->name . ' was added');

        return Redirect::route('[[model_singular]].index');

    }

    /**
     * Display the specified resource.
     *d
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if ($[[model_singular]] = $this->find($id)) {
            return view('[[view_folder]].show', compact('[[model_singular]]'));
        } else {
            \Session::flash('flash_error_message', 'Unable to find [[model_singular]] to display');
            return Redirect::route('[[model_singular]].index');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        if ($[[model_singular]] = $this->find($id)) {
            return view('[[view_folder]].edit', compact('[[model_singular]]'));
        } else {
            \Session::flash('flash_error_message', 'Unable to find [[model_singular]] to edit');
            return Redirect::route('[[model_singular]].index');
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\[[model_uc]] $[[model_singular]]
     * @return \Illuminate\Http\Response
     */
    public function update([[model_uc]]EditRequest $request, $id)
    {
        if (!$[[model_singular]] = $this->find($id)) {
            \Session::flash('flash_error_message', 'Unable to find [[model_singular]] to edit');
            return Redirect::route('[[model_singular]].index');
        }

        $[[model_singular]]->fill($request->all());

        if ($[[model_singular]]->isDirty()) {

            $[[model_singular]]->save();

            \Session::flash('flash_success_message', '[[model_uc]] ' . $[[model_singular]]->name . ' was changed');
        } else {
            \Session::flash('flash_info_message', 'No changes were made');
        }

        return Redirect::route('[[model_singular]].index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\[[model_uc]] $[[model_singular]]
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Find by ID, sanitize the ID first
     *
     * @param $id
     * @return [[model_uc]] or null
     */
    private function find($id)
    {
        return \App\[[model_uc]]::find(intval($id));
    }


	
}