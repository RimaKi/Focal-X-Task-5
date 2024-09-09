<?php

namespace App\Services;

use App\Models\Task;

class TaskService
{
    /**
     * update task and validate for permission
     * @param $data
     * @param Task $task
     * @return Task
     * @throws \Exception
     */
    public function updateTask($data, Task $task)
    {
        if (auth()->user()->uuid != $task->created_by) {
            throw new \Exception('You do not have permission to edit this task.');
        }
        $final_data = array_filter($data, function ($value) {
            return !is_null($value);
        });
        $task->update($final_data);
        return $task;
    }

    /**
     *  delete task and validate for permission
     * @param Task $task
     * @return void
     * @throws \Exception
     */
    public function deleteTask(Task $task)
    {
        if (auth()->user()->uuid != $task->created_by) {
            throw new \Exception('You do not have permission to delete this task.');
        }
        $task->delete();
    }

}
