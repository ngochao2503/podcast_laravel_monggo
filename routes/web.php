<?php


use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Podcaster\PodCasterController;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Podcast\PodcastController;
use App\Http\Controllers\Comment\CommentController;
use App\Http\Controllers\Notification\NotificationController;
use App\Http\Controllers\PodcasterFollower\PodcasterFollowerController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::middleware(['web'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('index');


    Route::get('/about', function () {
        return view('about');
    })->name('about')->middleware('verified');


    Route::get('/contact', function () {
        return view('contact');
    })->name('contact');



    Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
        Route::get('/{provider}/redirect', [AuthController::class, 'redirectSocial'])->name('social.redirect');
        Route::get('/{provider}/callback', [AuthController::class, 'callbackSocial'])->name('social.callback');
    });



    Route::group(['prefix' => 'podcasters', 'as' => 'podcasters.'], function () {
        Route::get('/edit/{podcaster}', [PodCasterController::class, 'edit'])->name('edit');
        Route::put('/update/{podcaster}', [PodCasterController::class, 'update'])->name('update');
        Route::get('/{podcaster}', [PodCasterController::class, 'index'])->name('index');

    });



    Auth::routes(['verify' => true]);

    Route::get('/login', [AuthController::class, 'getLogin'])->name('get_login');
    Route::get('/register', [AuthController::class, 'getRegister'])->name('get_register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/email/verify', [VerificationController::class, 'show'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify')->middleware(['signed']);
    Route::post('/email/resend', [VerificationController::class, 'resend'])->name('verification.resend');

    Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');


    Route::get('/podcast/redirect/{podcast}', [PodcastController::class, 'redirectByPodcastId'])->name('podcast.redirect');
    Route::get('/{category}/podcast/{id}', [PodcastController::class, 'podcast_detail'])->name('podcast.podcast_detail');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
        Route::put('/comments/{id}', [CommentController::class, 'update'])->name('comments.update');
        Route::delete('/comments/{id}', [CommentController::class, 'destroy'])->name('comments.destroy');

    });

    Route::post('/follow', [PodcasterFollowerController::class, 'follow'])->name('follow');
    Route::post('/unfollow', [PodcasterFollowerController::class, 'unfollow'])->name('unfollow');


    Route::group(['prefix' => 'podcasters', 'as' => 'podcasters.', 'middleware' => 'verified'], function () {
        Route::get('/edit/{podcaster}', [PodcasterController::class, 'edit'])->name('edit');
        Route::put('/update/{podcaster}', [PodCasterController::class, 'update'])->name('update');
        Route::get('/{podcaster}', [PodCasterController::class, 'index'])->name('index');
    });
    Route::get('/crud/add', [PodcastController::class, 'loadAddPage'])->name('podcast.loadAddPage');
    Route::post('/crud/add', [PodcastController::class, 'addPodcast'])->name('podcast.addPodcast');

    Route::delete('/crud/delete/{id}', [PodcastController::class, 'deletePodcast'])->name('podcast.deletePodcast');
    Route::post('/crud/restore/{id}', [PodcastController::class, 'restore'])->name('podcast.restore');

    Route::get('/crud/update/{id}', [PodcastController::class, 'loadUpdatePage'])->name('podcast.loadUpdatePage');
    Route::put('/crud/update/{id}', [PodcastController::class, 'updatePodcast'])->name('podcast.updatePodcast');

    Route::get('/crud', [PodcastController::class, 'index'])->name('podcast.crud');
    Route::get('/podcast/{id}', [PodcastController::class, 'show']);


    Route::post('/podcasters/{podcaster}/subscribe', [PodCasterFollowerController::class, 'subscribe'])->name('podcasters.subscribe');
    Route::post('/podcasters/{podcaster}/unsubscribe', [PodCasterFollowerController::class, 'unsubscribe'])->name('podcasters.unsubscribe');
});
