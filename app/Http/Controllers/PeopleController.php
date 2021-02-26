<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTeamMember;
use App\Services\FileUploader;
use App\Services\Team\Manager;
use Illuminate\Support\Facades\Http;

class PeopleController extends Controller
{

    /**
     * Shows the view where member can be added and show existing members
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
	public function index()
	{
		return view('people');
	}

    /**
     * Get all the team members from airtable/any other provider
     * @param Manager $teamDatabaseManager
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll(Manager $teamDatabaseManager)
    {
        $people = $teamDatabaseManager->getAll();
        return response()->json($people);
    }

    /**
     * Stores new team member to airtable/any other provider through  api.
     * @param CreateTeamMember $request
     * @param FileUploader $fileUploader
     * @param Manager $teamDatabaseManager
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\InvalidFile
     */
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

    /**
     * prepares request body to create a new team member
     * @param array $data
     * @return array[]
     */
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
