<?php

use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BranchController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Str;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//admin can only access
Route::middleware(['auth','isadmin'])->group(function(){
    Route::get('/admin_home',function(){
        return view('admin.admin_home');
    })->name('admin_home');

    #Employee Routes admin can access
    Route::get('/employees',[UserController::class,'showEmployees'])->name('showEmployees');
    Route::delete('/employees/{id}/delete', [UserController::class,'destroy'])->name('Employees.delete');
    Route::get('/employees/create',[UserController::class,'addEmployee'])->name('Employees.add');
    Route::post('/employees/store', [UserController::class,'store'])->name('Employees.store');
    Route::get('/employees/{id}/update',[UserController::class,'editEmployee'])->name('Employees.update');
    Route::put('/employees/{id}/edit',[UserController::class,'updateEmployee'])->name('Employees.edit');
    Route::get('employees/{id}/display',[UserController::class,'viewEmployee'])->name('Employees.view');

    Route::get('/Branches',[BranchController::class,'showbranches'])->name('branches.show');
    Route::delete('/Branches/delete', [BranchController::class,'destroy'])->name('branches.delete');
    Route::get('/Branches/create',[BranchController::class,'create'])->name('branches.create');
    Route::post('/Branches/store', [BranchController::class,'store'])->name('branches.store');
});
//emp logout
Route::get('/logout',[UserController::class,'logout'])->name('logout');


//employees and not admin can access
Route::middleware(['auth','isemp'])->group(function(){
    Route::get('/emp_home/{id}',[UserController::class,'displayEmployee'])->name('emp_home');
    Route::get('/emp_home/{id}/edit',[UserController::class,'editEmp'])->name('emp_edit');
    Route::put('/emp_home/{id}/update',[UserController::class,'updateEmp'])->name('emp_update');
    Route::get('/emp_home/{id}/branchDetails',[UserController::class,'branchDetail'])->name('emp_showBranch');
});



Route::get('/', function () {
    return view('home');
})->middleware('home')->name('home');

Route::get('/login_form',function(){
    return view('login_form');
})->middleware('guest')->name('login_form');


//student login
Route::middleware(['auth:student','isstudent'])->group(function(){
Route::get('/student_home',[StudentController::class,'student_home'])->name('student_home');
Route::get('/student_logout',[StudentController::class,'logout'])->name('student_logout');

});



Route::get('/student/login',function(){
    return view('student.loginform');
})->name('student_login');

Route::post('/student/login',[StudentController::class,'login'])->name('student_login_logic');



Route::post('login',[UserController::class,'login']);



Route::get('changepass',[UserController::class,'changepass']);

Route::get('/register',[StudentController::class,'register'])->name('register');
Route::Post('/student_register',[StudentController::class,'student_register'])->name('student_register');


//Password

//got to reset password link for employees
Route::get('/forgot-password', function () {
    return view('emp.forgot-password');
})->middleware('guest')->name('password.request');

//employee enters email to send link of password
Route::post('/forgot-password', function (Request $request) {
    $request->validate(['email' => 'required|email']);
 
    $status = Password::sendResetLink(
        $request->only('email')
    );
 
    return $status === Password::RESET_LINK_SENT
                ? back()->with(['status' => __($status)])
                : back()->withErrors(['email' => __($status)]);
})->middleware('guest')->name('password.email');

//after user clicks on link
Route::get('/reset-password/{token}', function (string $token) {
    return view('emp.reset-password', ['token' => $token]);
})->middleware('guest')->name('password.reset');

Route::post('/reset-password', function (Request $request) {
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:5|confirmed',
    ]);
 
    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function (User $user, string $password) {
            $user->forceFill([
                'password' => Hash::make($password)
            ])->setRememberToken(Str::random(60));
 
            $user->save();
 
            event(new PasswordReset($user));
        }
    );
 
    return $status === Password::PASSWORD_RESET
                ? redirect()->route('home')->with('status', __($status))
                : back()->withErrors(['status' => [__($status)]]);
})->middleware('guest')->name('password.update');
