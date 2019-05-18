<?php

namespace [[appns]]Http\Controllers;



use [[appns]]Http\Middleware\TrimStrings;
use [[appns]][[model_uc]];
use Illuminate\Http\Request;

use [[appns]]Http\Requests\[[model_uc]]Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use App\Exports\[[model_uc]]Export;
use Maatwebsite\Excel\Facades\Excel;

class [[model_uc]]Controller extends Controller
{

    /**
     * Examples
     *
     * Vue component example.
     *
        <ui-select-pick-one
            label="My Label"
            url="/api-[[view_folder]]/options"
            class="form-group"
            v-model="[[model_singular]]Selected"
            v-on:input="getData">
        </ui-select-pick-one>
     *
     *
     * Blade component example.
     *
     *   In Controler
     *
             $[[model_singular]]_options = \App\[[model_uc]]::getOptions();


     *
     *   In View

            @component('../components/select-pick-one', [
                'fld' => '[[model_singular]]_id',
                'selected_id' => $RECORD->[[model_singular]]_id,
                'first_option' => 'Select a [[model_uc_plural]]',
                'options' => $[[model_singular]]_options
            ])
            @endcomponent
     *
     * Permissions
     *

             Permission::create(['name' => '[[model_singular]] index']);
             Permission::create(['name' => '[[model_singular]] add']);
             Permission::create(['name' => '[[model_singular]] update']);
             Permission::create(['name' => '[[model_singular]] view']);
             Permission::create(['name' => '[[model_singular]] destroy']);
             Permission::create(['name' => '[[model_singular]] export-pdf']);
             Permission::create(['name' => '[[model_singular]] export-excel']);

    */


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if (!Auth::user()->can('[[model_singular]] index')) {
            \Session::flash('flash_error_message', 'You do not have access to [[display_name_singular]]');
            return Redirect::route('home');
        }

        // Remember the search parameters, we saved them in the Query
        $page = session('[[model_singular]]_page', '');
        $search = session('[[model_singular]]_keyword', '');
        $column = session('[[model_singular]]_column', 'Name');
        $direction = session('[[model_singular]]_direction', '-1');

        $can_add = Auth::user()->can('[[model_singular]] add');
        $can_show = Auth::user()->can('[[model_singular]] view');
        $can_edit = Auth::user()->can('[[model_singular]] edit');
        $can_delete = Auth::user()->can('[[model_singular]] delete');
        $can_excel = Auth::user()->can('[[model_singular]] excel');
        $can_pdf = Auth::user()->can('[[model_singular]] pdf');

        return view('[[view_folder]].index', compact('page', 'column', 'direction', 'search', 'can_add', 'can_edit', 'can_delete', 'can_show', 'can_excel', 'can_pdf'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
	public function create()
	{

        if (!Auth::user()->can('[[model_singular]] add')) {
            \Session::flash('flash_error_message', 'You do not have access to add a [[display_name_singular]]');
            return Redirect::route('home');
        }

	    return view('[[view_folder]].create');
	}


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store([[model_uc]]Request $request)
    {

        $[[model_singular]] = new \App\[[model_uc]];

        try {
            $[[model_singular]]->add($request->validated());
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Unable to process request'
            ], 400);
        }

        \Session::flash('flash_success_message', 'Vc Vendor ' . $[[model_singular]]->name . ' was added');

        return response()->json([
            'message' => 'Added record'
        ], 200);

    }

    /**
     * Display the specified resource.
     *
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        if (!Auth::user()->can('[[model_singular]] view')) {
            \Session::flash('flash_error_message', 'You do not have access to add a [[display_name_singular]]');
            return Redirect::route('home');
        }

        if ($[[model_singular]] = $this->find($id)) {
            return view('[[view_folder]].show', compact('[[model_singular]]'));
        } else {
            \Session::flash('flash_error_message', 'Unable to find [[display_name_singular]] to display');
            return Redirect::route('[[view_folder]].index');
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
        if (!Auth::user()->can('[[model_singular]] edit')) {
            \Session::flash('flash_error_message', 'You do not have access to add a [[display_name_singular]]');
            return Redirect::route('home');
        }

        if ($[[model_singular]] = $this->find($id)) {
            return view('[[view_folder]].edit', compact('[[model_singular]]'));
        } else {
            \Session::flash('flash_error_message', 'Unable to find [[display_name_singular]] to edit');
            return Redirect::route('[[view_folder]].index');
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\[[model_uc]] $[[model_singular]]
     * @return \Illuminate\Http\Response
     */
    public function update([[model_uc]]Request $request, $id)
    {
        if (!$[[model_singular]] = $this->find($id)) {
       //     \Session::flash('flash_error_message', 'Unable to find [[display_name_singular]] to edit');
            return response()->json([
                'message' => 'Not Found'
            ], 404);
        }

        $[[model_singular]]->fill($request->all());

        if ($[[model_singular]]->isDirty()) {

            try {
                $[[model_singular]]->save();
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Unable to process request'
                ], 400);
            }

            \Session::flash('flash_success_message', '[[display_name_singular]] ' . $[[model_singular]]->name . ' was changed');
        } else {
            \Session::flash('flash_info_message', 'No changes were made');
        }

        return response()->json([
            'message' => 'Changed record'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\[[model_uc]] $[[model_singular]]
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Auth::user()->can('[[model_singular]] delete')) {
            \Session::flash('flash_error_message', 'You do not have access to remove a [[display_name_singular]]');
            if (!Auth::user()->can('[[model_singular]] index')) {
                 return Redirect::route('[[view_folder]].index');
            } else {
                return Redirect::route('home');
            }
        }

        $[[model_singular]] = $this->find($id);
        if ( $[[model_singular]]) {
            $[[model_singular]]->delete();
            \Session::flash('flash_success_message', 'Invitation for ' . $[[model_singular]]->name . ' was removed.');
        } else {
            \Session::flash('flash_error_message', 'Unable to find Invite to delete');

        }

        if (!Auth::user()->can('[[model_singular]] index')) {
             return Redirect::route('[[view_folder]].index');
        } else {
            return Redirect::route('home');
        }


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


    public function download()
    {

        if (!Auth::user()->can('[[model_singular]] excel')) {
            \Session::flash('flash_error_message', 'You do not have access to download [[display_name_singular]]');
            return Redirect::route('home');
        }

        // Remember the search parameters, we saved them in the Query
        $search = session('[[model_singular]]_keyword', '');
        $column = session('[[model_singular]]_column', 'name');
        $direction = session('[[model_singular]]_direction', '-1');

        $column = $column ? $column : 'name';

        // #TODO wrap in a try/catch and display english message on failuer.

        info(__METHOD__ . ' line: ' . __LINE__ . " $column, $direction, $search");

        $dataQuery = [[model_uc]]::exportDataQuery($column, $direction, $search);
        //dump($data->toArray());
        //if ($data->count() > 0) {

        // TODO: is it possible to do 0 check before query executes somehow? i think the query would have to be executed twice, once for count, once for excel library
        return Excel::download(
            new [[model_uc]]Export($dataQuery),
            '[[view_folder]].xlsx'
        );

        //} else {
        //    \Session::flash('flash_error_message', 'There are no organizations to download.');
        //    return Redirect::route('organization.index');
        //}

    }

}
