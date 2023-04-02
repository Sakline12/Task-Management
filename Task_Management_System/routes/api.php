<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Client\ClientController;
use App\Http\Controllers\Comment\CommentController;
use App\Http\Controllers\Department\DepartmentController;
use App\Http\Controllers\Designation\DesignationController;
use App\Http\Controllers\Project\ProjectController;
use App\Http\Controllers\Task\TaskController;
use App\Http\Controllers\User\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Register and Login
Route::post('register', [AuthController::class, 'User_create']);
Route::post('login', [AuthController::class, 'login']);


Route::group(['middleware' => ['auth:sanctum']], function () {
    //logout
    Route::post('logout', [AuthController::class, 'logout']);


    //Admin
    Route::post('create-project', [ProjectController::class, 'create_project']);
    Route::post('assigned-project', [ProjectController::class, 'project_assigned']);
    Route::post('create-task', [TaskController::class, 'task_create']);
    Route::post('assigned-task', [TaskController::class, 'task_assigned']);


    Route::post('user-create', [AuthController::class, 'User_create']);
    Route::get('user-list', [AdminController::class, 'user_list']);
    Route::post('user-delete', [AdminController::class, 'user_delete']);

    Route::post('create-comment', [CommentController::class, 'create_comment']);
    Route::post('create-file', [TaskController::class, 'file_create']);
    Route::post('delete-file', [TaskController::class, 'delete_file']);

    ///Designation and Department
    Route::post('create-designation', [DesignationController::class, 'designation_create']);
    Route::post('update-designation', [DesignationController::class, 'designation_update']);
    Route::get('list-designation', [DesignationController::class, 'designation_list']);
    Route::post('delete-designation', [DesignationController::class, 'designation_delete']);

    Route::post('create-department', [DepartmentController::class, 'department_create']);
    Route::post('update-department', [DepartmentController::class, 'department_update']);
    Route::get('list-department', [DepartmentController::class, 'department_list']);
    Route::post('delete-department', [DepartmentController::class, 'department_delete']);


    Route::get('list-projects', [ProjectController::class, 'project_list']);
    Route::post('update-project', [ProjectController::class, 'project_update']);
    Route::post('delete-project', [ProjectController::class, 'project_delete']);

    Route::get('project-with-task', [ProjectController::class, 'project_with_task']);
    Route::post('project-assigned-update', [ProjectController::class, 'project_assigned_update']);
    Route::post('project-for-specific-user', [ProjectController::class, 'project_for_specific_user']);

    Route::get('today-project', [ProjectController::class, 'today_project']);
    Route::get('my-project', [UserController::class, 'my_project']);


    Route::post('update-task', [TaskController::class, 'task_update']);
    Route::post('delete-task', [TaskController::class, 'task_delete']);
    Route::post('task-assigned-update', [TaskController::class, 'task_assigned_update']);
    Route::get('list-tasks', [TaskController::class, 'list_task']);
    Route::get('task-with-project', [TaskController::class, 'task_with_project']);
    Route::post('task-for-specific-project', [TaskController::class, 'task_for_specific_project']);

    Route::get('today-task', [TaskController::class, 'today_task']);
    Route::get('my-task', [UserController::class, 'my_task']);


    Route::get('list-comments', [CommentController::class, 'comment_list']);
    Route::post('update-comment', [CommentController::class, 'comment_update']);
    Route::post('delete-comment', [CommentController::class, 'comment_delete']);
    Route::post('comments-for-specific-task', [CommentController::class, 'comments_for_specific_task']);
    Route::get('my-Comment', [CommentController::class, 'my_Comment']);

    Route::post('create-client', [ClientController::class, 'client_create']);
    Route::get('list-clients', [ClientController::class, 'client_list']);
    Route::post('update-client', [ClientController::class, 'client_update']);
    Route::post('delete-client', [ClientController::class, 'client_delete']);

    Route::get('count-task', [TaskController::class, 'task_count']);
    Route::get('calculate-task', [TaskController::class, 'calculate_task']);


    Route::post('project-assigns-users', [ProjectController::class, 'project_assigns_users']);


    Route::post('project-status-update', [UserController::class, 'project_status_update']);
    Route::post('task-status-update', [UserController::class, 'task_status_update']);
    Route::get('project-remain_days', [TaskController::class, 'remain_days']);
});
