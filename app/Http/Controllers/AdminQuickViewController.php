<?php

namespace App\Http\Controllers;

use App\AdminQuickView;
use Illuminate\Http\Request;
use Auth;

class AdminQuickViewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageTitle = "QUICK VIEW METHOD";

        $quickView = AdminQuickView::where('user_id', Auth::user()->uuid)->first();

        return view('admin.quickView.index', compact('pageTitle', 'quickView'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->request->remove('_token');
        $quickViewData = AdminQuickView::where('user_id', $request->user_id)->first();
        if ($quickViewData != null) {
            $val = $request->name;

            $onoff = '0';
            if ($quickViewData->$val == '1') {
                $onoff = '0';
            } else {
                $onoff = '1';
            }

            AdminQuickView::where('user_id', $request->user_id)->update([$request->name => $onoff]);
        } else {
            AdminQuickView::where('user_id', $request->user_id)->create([
                $request->name => '1',
                'user_id' => $request->user_id
            ]);
        }
        return redirect()->back();
        //    dd($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\AdminQuickView  $adminQuickView
     * @return \Illuminate\Http\Response
     */
    public function show(AdminQuickView $adminQuickView)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\AdminQuickView  $adminQuickView
     * @return \Illuminate\Http\Response
     */
    public function edit(AdminQuickView $adminQuickView)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\AdminQuickView  $adminQuickView
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AdminQuickView $adminQuickView)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\AdminQuickView  $adminQuickView
     * @return \Illuminate\Http\Response
     */
    public function destroy(AdminQuickView $adminQuickView)
    {
        //
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexSupplier()
    {
        $pageTitle = "QUICK VIEW METHOD";

        $quickView = AdminQuickView::where('user_id', Auth::user()->uuid)->first();

        return view('supplier.quickView.index', compact('pageTitle', 'quickView'));
    }
}
