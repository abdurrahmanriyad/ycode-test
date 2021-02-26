<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTeamMember;
use App\Services\FileUploader;
use App\Services\Team\Manager;
use Illuminate\Support\Facades\Http;

class PeopleController extends Controller
{

	public function index()
	{
		return view('people');
	}

    public function getAll(Manager $teamDatabaseManager)
    {
        $people = $teamDatabaseManager->getAll();
        return response()->json($people);
    }

    public function store(CreateTeamMember $request, FileUploader $fileUploader, Manager $teamDatabaseManager)
    {
        $data = $request->validated();

        if (!$request->wantsJson()) {
            abort(404);
        }

        $data['photoUrl'] = null;
        if ($request->has('photo')) {
            $uploadFilePath = $fileUploader->upload($request->file('photo'), 'airtable');
            $data['photoUrl'] = asset('storage/' . $uploadFilePath);
        }

        $newRecord = $teamDatabaseManager->create($this->prepareNewTeamRequest($data));

        return response()->json([
            'message' => 'Successfully added new team member!',
            'data' => $newRecord
        ]);
	}

    private function prepareNewTeamRequest(array $data) : array
    {
        $payload =  [
            'fields' => [
                'Name' => $data['name'],
                'Email' => $data['email'],
            ]
        ];

        if (!blank($data['photoUrl'])) {
            $payload['fields']['Photo'] = [
                ['url' => $data['photoUrl']]
            ];
        }

        return $payload;
    }
}
