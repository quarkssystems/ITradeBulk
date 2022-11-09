<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;


class AdminSettingController extends Controller
{


	public function index()
	{
		$pageTitle = "COMMISSION SETTINGS";

		return view('admin.setting.index', compact('pageTitle'));
	}

	public function store(Request $request)
	{
		$rules = Setting::getValidationRules();
		$data = $this->validate($request, $rules);

		$validSettings = array_keys($rules);

		foreach ($data as $key => $val) {
			if (in_array($key, $validSettings)) {
				Setting::add($key, $val, Setting::getDataType($key));
			}
		}
		return  redirect()->back()->with(['status' => 'success', 'message' => trans('success.admin|setting|save')]);
	}
}
