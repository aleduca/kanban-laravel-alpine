<?php

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
  $allTasks = Task::all();

  $tasks = $allTasks->sortBy('position_id')->groupBy('col_id');

  return view('kanban', compact('tasks'));
});


Route::put('/update/kanban', function (Request $request) {
  $all = $request->all();

  DB::beginTransaction();

  try {
    //code...

    $processUpdates = function ($data) {
      $updates = [];
      foreach ($data as $column_id => $columns) {
        foreach ($columns as $id => $position) {
          $updates[] = [
            'id' => $id,
            'col_id' => $column_id,
            'position_id' => $position
          ];
        }
      }
      return $updates;
    };

    $updates = $processUpdates($all);

    foreach ($updates as $update) {
      Task::where('id', $update['id'])->update([
        'col_id' => $update['col_id'],
        'position_id' => $update['position_id']
      ]);
    }

    DB::commit();

    return response()->json([
      'success' => true,
      'message' => 'Kanban updated successfully'
    ], 200);
  } catch (\Throwable $th) {
    //throw $th;
    DB::rollBack();

    return response()->json([
      'success' => false,
      'message' => $th->getMessage()
    ], 500);
  }
})->name('update.kanban');
