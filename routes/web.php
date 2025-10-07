<?php

use App\Models\Task;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
  $allTasks = Task::all();

  $tasks = $allTasks->sortBy('position_id')->groupBy('col_id');

  return view('kanban', compact('tasks'));
});
