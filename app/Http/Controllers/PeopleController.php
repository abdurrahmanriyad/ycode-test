<?php

namespace App\Http\Controllers;

use App\Services\Team\Manager;
use Illuminate\Support\Facades\Http;

class PeopleController extends Controller
{

	public function index(Manager $teamDatabaseManager)
	{
	    $people = $teamDatabaseManager->getAll();
		return view('people', compact('people'));
	}
}
