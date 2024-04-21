<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{

    public function update(User $user, Project $project): bool
    {
        return $this->projectBelongsToUser($user, $project);
    }

    public function delete(User $user, Project $project): bool
    {
        return $this->projectBelongsToUser($user, $project);
    }

    private function projectBelongsToUser(User $user, Project $project): bool
    {
        return $project->user_id == $user->id;
    }

}
