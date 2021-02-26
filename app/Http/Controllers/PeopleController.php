<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTeamMember;
use App\Services\Team\Manager;
use Illuminate\Support\Facades\Http;

class PeopleController extends Controller
{

	public function index(Manager $teamDatabaseManager)
	{
	    $people = $teamDatabaseManager->getAll();
		return view('people', compact('people'));
	}

    public function store(CreateTeamMember $request)
    {
        if (!$request->wantsJson()) {
            abort(404);
        }

        return response()->json([
            'message' => 'Successfully added new team member!',
            'data' => [
            ]
        ]);
	}
}
