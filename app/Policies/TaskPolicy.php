<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{

    public function update(User $user, Task $task): bool
    {
        return $this->taskBelongsToUser($user, $task);
    }

    public function delete(User $user, Task $task): bool
    {
        return $this->taskBelongsToUser($user, $task);
    }

    private function taskBelongsToUser(User $user, Task $task): bool
    {
        return $task->user_id == $user->id;
    }

}
