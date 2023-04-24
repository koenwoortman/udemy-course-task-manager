<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserCollection;
use App\Models\Project;
use Illuminate\Http\Request;

class MembersController extends Controller
{
    public function index(Request $request, Project $project)
    {
        $members = $project->members;

        return new UserCollection($members);
    }

    public function store(Request $request, Project $project)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $project->members()->syncWithoutDetaching([$request->user_id]);

        $members = $project->members;

        return new UserCollection($members);
    }

    public function destroy(Request $request, Project $project, int $member)
    {
        abort_if($project->creator_id === $member, 400, 'Cannot remove creator from project.');

        $project->members()->detach([$member]);

        $members = $project->members;

        return new UserCollection($members);
    }
}
