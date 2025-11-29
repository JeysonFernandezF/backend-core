<?php

use App\Http\Controllers\Api\ActivityController;
use App\Http\Controllers\Api\AreaController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BarrierController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\ConductController;
use App\Http\Controllers\Api\CriticalRiskController;
use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\EquipmentController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\TemplateFormController;
use App\Http\Controllers\Api\FormController;
use App\Http\Controllers\Api\ManagementController;
use App\Http\Controllers\Api\ObservationController;
use App\Http\Controllers\Api\ObservedTaskController;
use App\Http\Controllers\Api\PositionController;
use App\Http\Controllers\Api\ProgramDetailController;
use App\Http\Controllers\Api\ProgramRegisterController;
use App\Http\Controllers\Api\TurnController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\WorksiteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('auth/register', [AuthController::class, 'register']);
Route::post('auth/login', [AuthController::class, 'login']);

Route::get('prueba', function(){
    return auth('api')->user();
});

Route::middleware('auth:api')->group(function () {
    // auth
    Route::post('auth/logout', [AuthController::class, 'logout']);
    Route::post('auth/refresh', [AuthController::class, 'refresh']);
    Route::get('auth/me', [AuthController::class, 'me']);

    // usuarios
    Route::get('users', [UserController::class, 'index']);
    Route::post('users/assign-roles', [UserController::class, 'assignRoles']);
    // roles y permisos
    Route::apiResource('permissions', PermissionController::class);
    Route::apiResource('roles', RoleController::class);

    // tablas catálogo
    Route::apiResource('activities', ActivityController::class);
    Route::apiResource('equipments', EquipmentController::class);
    Route::apiResource('worksites', WorksiteController::class);
    Route::apiResource('positions', PositionController::class);
    Route::apiResource('barriers', BarrierController::class);
    Route::apiResource('companies', CompanyController::class);
    Route::apiResource('managements', ManagementController::class);
    Route::apiResource('departments', DepartmentController::class);
    Route::apiResource('areas', AreaController::class);
    Route::apiResource('critical-risks', CriticalRiskController::class);
    Route::apiResource('turns', TurnController::class);
    Route::apiResource('conducts', ConductController::class);
    Route::apiResource('observed-tasks', ObservedTaskController::class);

    // observaciones conductuales
    Route::apiResource('program-details', ProgramDetailController::class);
    Route::get('program-registers/catalog', [ProgramRegisterController::class, 'getCatalog']);
    Route::post('program-registers/with-details', [ProgramRegisterController::class, 'storeProgramRegisterWithDetails']);
    Route::patch('program-registers/{program_register}/change-status', [ProgramRegisterController::class, 'changeStatus']);
     // Ruta Mobile
    Route::get('program-registers/programs-by-worksites', [ProgramRegisterController::class, 'getProgramsByWorksites']);

    Route::apiResource('program-registers', ProgramRegisterController::class);
    Route::apiResource('forms', FormController::class);
    Route::get('observations/catalog', [ObservationController::class, 'getCatalog']);
    Route::apiResource('observations', ObservationController::class);
    Route::get('observations/show-with-questions/{observation}', [ObservationController::class, 'showObservationWithQuestions']);
    Route::post('observations/store-record-questions', [ObservationController::class, 'storeRecordQuestions']);
    Route::get('observations/{observation}/form-with-answers',[ObservationController::class, 'showFormWithAnswer']);
    // plantillas de formularios
    Route::get('template-forms/without-paginate', [TemplateFormController::class, 'indexWithoutPaginate']);
    Route::apiResource('template-forms', TemplateFormController::class);

});





