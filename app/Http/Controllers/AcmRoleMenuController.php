<?php

namespace App\Http\Controllers;


use App\Models\AcmRoleMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AcmRoleMenuController extends Controller
{


	public function checkAccess(Request $request) {

		$validated = $request->validate([
			'name' => 'required'
		]);

		$role = Session::get('role');
		$name = $validated['name'];


		$data = AcmRoleMenu::whereHas('role', function ($query) use ($role) {
			$query->where('name', $role);
		})->whereHas('acmMenu', function ($query) use ($name) {
			$query->where('name', $name);
		})->first();

		return response()->json($data);


		// $view->with('role', $role);
	}
}
