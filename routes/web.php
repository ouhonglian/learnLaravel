<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/
function user_init(){
    return new App\User;
}
function question_init(){
    return new App\Questions;
}
Route::get('/', function () {
    return view('welcome');
});
//注册
Route::any('api/register',function (){
    $user=user_init();
    return $user->register();
});
//登录
Route::any('api/login',function (){
    $user=user_init();
    return $user->login();
});
//退出登录
Route::any('api/out',function (){
    $user=user_init();
    return $user->out();
});
//question create
Route::any('api/question/create',function (){
    $question=question_init();
    return $question->add_question();
});
//question updata
 Route::get('api/question/change',function (){
     $question=question_init();
     return $question->updata_question();
 });
//question red
Route::get('api/question/red',function (){
    $question=question_init();
    return $question->red_question();
});

//question delete
Route::get('api/question/del',function (){
    $question=question_init();
    return $question->del_question();
});