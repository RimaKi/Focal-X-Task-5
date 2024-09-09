<?php

namespace App\Http\Controllers;

use App\Http\Requests\Task\StoreRequest;
use App\Http\Requests\Task\UpdateRequest;
use App\Models\Task;
use App\Models\User;
use App\Services\TaskService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     *  Display a listing of the resource.
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index(Request $request)
    {
        $tasks = Task::query();
        if ($request->has('priority')) {
            $tasks = $tasks->priority($request->priority);
        }
        if ($request->has('status')) {
            $tasks = $tasks->status($request->status);
        }
        return $tasks->get();

    }

    /**
     *  Store a newly created resource in storage.
     * @param StoreRequest $request
     * @return string
     */
    public function store(StoreRequest $request)
    {
        $data = $request->only(['title', 'description', 'priority', 'assigned_to']);
        $task = new Task($data);
        $task->created_by = auth()->user()->uuid;
        $task->save();
        return "added successfully";
    }


    /**
     *  Display the specified resource.
     * @param Task $task
     * @return Task
     */
    public function show(Task $task)
    {
        return $task->load('assignedTo', 'createdBy');
    }

    /**
     *  Update the specified resource in storage.
     * @param UpdateRequest $request
     * @param Task $task
     * @return array
     * @throws \Exception
     */
    public function update(UpdateRequest $request, Task $task)
    {
        $data = $request->only(['title', 'description', 'priority', 'assigned_to', 'status']);
        $task = (new TaskService())->updateTask($data, $task);
        return [
            'message' => 'updated successfully',
            'task' => $task
        ];
    }

    /**
     *  Remove the specified resource from storage.
     * @param Task $task
     * @return string
     * @throws \Exception
     */
    public function destroy(Task $task)
    {
        (new TaskService())->deleteTask($task);
        return "deleted successfully";
    }

    /**
     * Show tasks that have been soft deleted
     * @return mixed
     */
    public function deletedTasks()
    {
        return Task::with('assignedTo', 'createdBy')->onlyTrashed()->get();
    }

    /**
     * Assign a task to a specific user
     * @param Task $task
     * @param User $user
     * @return string
     */
    public function assignedTo(Task $task, User $user)
    {
        $task->update(['assigned_to' => $user->uuid]);
        return 'assigned successfully';
    }

    /**
     * Task status changes when starting it
     * @param Task $task
     * @return string
     */
    public function startTask(Task $task)
    {
        $task->update(['status' => 'processing']);
        return 'Done';
    }

    /**
     * Change task status upon completion and add due date
     * @param Task $task
     * @return string
     */
    public function finishTask(Task $task)
    {
        $task->update(['status' => 'done', 'due_date' => Carbon::now()]);
        return 'Done';
    }

    public function failedTask(Task $task)
    {
        $task->update(['status' => 'failed']);
        return 'Done';
    }

    /**
     * Task status changes when it fails
     * @return mixed
     */
    public function viewMyTask()
    {
        return Task::where('assigned_to', auth()->user()->uuid)->get();
    }
}
