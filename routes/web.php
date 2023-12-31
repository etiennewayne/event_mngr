<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use App\Models\Event;
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

Route::get('/', function () {
    return view('auth.login');
});


Auth::routes([
    'login' => true,
    'register' => false, // Registration Routes...
    'reset' => false, // Password Reset Routes...
    'verify' => true, // Email Verification Routes...
]);



// Route::get('/date', function(){

//     return new DateTime();
// });


Route::get('/get-user', function(){
    if(Auth::check()){
        return Auth::user();
    }

    return [];
});


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//registration
Route::resource('/register-page', App\Http\Controllers\RegisterPageController::class);

//ADDRESS
//Route::get('/load-provinces', [App\Http\Controllers\AddressController::class, 'loadProvinces']);
//Route::get('/load-cities', [App\Http\Controllers\AddressController::class, 'loadCities']);
//Route::get('/load-barangays', [App\Http\Controllers\AddressController::class, 'loadBarangays']);


Route::get('/load-browse-events', [App\Http\Controllers\OpenController::class, 'loadBrowseEvents']);

Route::get('/load-academic-years', [App\Http\Controllers\OpenAcademicYearController::class, 'loadAcademicYears']);
Route::get('/load-departments', [App\Http\Controllers\OpenController::class, 'loadDepartments']);
Route::get('/load-event-types', [App\Http\Controllers\OpenController::class, 'loadEventTypes']);
Route::get('/load-event-venues', [App\Http\Controllers\OpenController::class, 'loadEventVenues']);


/*     ADMINSITRATOR      */
Route::middleware(['verified', 'admin'])->group(function() {

    Route::resource('/users', App\Http\Controllers\Administrator\UserController::class);
    Route::get('/get-users', [App\Http\Controllers\Administrator\UserController::class, 'getUsers']);
    Route::post('/user-activate/{id}', [App\Http\Controllers\Administrator\UserController::class, 'userActivate']);
    Route::post('/reset-password/{id}', [App\Http\Controllers\Administrator\UserController::class, 'resetPassword']);

    Route::resource('/questions', App\Http\Controllers\Administrator\QuestionController::class);
    Route::get('/get-questions', [App\Http\Controllers\Administrator\QuestionController::class, 'getQuestions']);

    Route::resource('/departments', App\Http\Controllers\Administrator\DepartmentController::class);
    Route::get('/get-departments', [App\Http\Controllers\Administrator\DepartmentController::class, 'getData']);
    //Route::get('/get-browse-events', [App\Http\Controllers\Administrator\QuestionController::class, 'getBrowseEvents']);


});
/*     ADMINSITRATOR          */


Route::middleware(['verified', 'adminstaff'])->group(function() {

    Route::resource('/event-venues', App\Http\Controllers\Administrator\EventVenueController::class);
    Route::get('/get-event-venues', [App\Http\Controllers\Administrator\EventVenueController::class, 'getData']);

    Route::resource('/event-types', App\Http\Controllers\Administrator\EventTypeController::class);
    Route::get('/get-event-types', [App\Http\Controllers\Administrator\EventTypeController::class, 'getData']);


});


Route::get('/dashboard', [App\Http\Controllers\Administrator\DashboardController::class,'index']);



/*     organizer          */
Route::middleware(['verified', 'ao'])->group(function() {

    Route::resource('/academic-years', App\Http\Controllers\Administrator\AcademicYearController::class);
    Route::get('/get-academic-years', [App\Http\Controllers\Administrator\AcademicYearController::class, 'getData']);
    Route::post('/academic-year-set-active/{id}', [App\Http\Controllers\Administrator\AcademicYearController::class, 'setActive']);

    Route::resource('/events', App\Http\Controllers\Administrator\EventController::class);
    Route::post('/events-update/{id}', [App\Http\Controllers\Administrator\EventController::class, 'updateEvent']);
    Route::get('/get-events', [App\Http\Controllers\Administrator\EventController::class, 'getEvents']);
    Route::get('/get-archive-events', [App\Http\Controllers\Administrator\EventController::class, 'getArchiveEvents']);
    Route::post('/archive-events', [App\Http\Controllers\Administrator\EventController::class, 'archiveEvents']);
    Route::post('/undo-archive-events', [App\Http\Controllers\Administrator\EventController::class, 'undoArchive']);
    Route::post('/event-file-attachment-delete/{id}', [App\Http\Controllers\Administrator\EventController::class, 'removeEventFileAttachment']);
    Route::post('/ custom-recipient-delete/{id}', [App\Http\Controllers\Administrator\EventController::class, 'removeCustomRecipient']);

    Route::post('/events-approve/{id}', [App\Http\Controllers\Administrator\EventController::class, 'eventApprove']);
    Route::post('/events-cancel/{id}', [App\Http\Controllers\Administrator\EventController::class, 'eventCancel']);
    Route::post('/events-open-evaluation/{id}', [App\Http\Controllers\Administrator\EventController::class, 'eventOpenEvaluation']);
    Route::post('/events-close-evaluation/{id}', [App\Http\Controllers\Administrator\EventController::class, 'eventCloseEvaluation']);

    Route::get('/event-attendees/{eventId}', [App\Http\Controllers\Administrator\EventAttendeeController::class, 'index']);
    Route::get('/get-event-attendees', [App\Http\Controllers\Administrator\EventAttendeeController::class, 'getEventAttendees']);
    Route::post('/event-attendees-approve/{attendeeId}', [App\Http\Controllers\Administrator\EventAttendeeController::class, 'approveAttendee']);
    Route::post('/event-attendees-decline/{attendeeId}', [App\Http\Controllers\Administrator\EventAttendeeController::class, 'declineAttendee']);

    Route::get('/event-attendees-print-preview/{attendeeId}', [App\Http\Controllers\Administrator\EventAttedeePrintPreview::class, 'eventAttendeePrintPreview']);


    Route::get('/event-attendances/{eventId}', [App\Http\Controllers\Administrator\EventAttendanceController::class, 'index']);
    Route::get('/get-event-attendances', [App\Http\Controllers\Administrator\EventAttendanceController::class, 'getData']);

    Route::get('/student-evaluated', [App\Http\Controllers\Administrator\StudentEvaluatedController::class, 'index']);
    Route::get('/get-participant-evaluated', [App\Http\Controllers\Administrator\StudentEvaluatedController::class, 'getStudentsEvaluated']);

    Route::get('/report-event-attendances', [App\Http\Controllers\Report\ReportEventAttendanceController::class, 'index']);

    Route::get('/report-event-list', [App\Http\Controllers\Report\ReportEventListController::class, 'index']);
    Route::get('/load-report-event-lists', [App\Http\Controllers\Report\ReportEventListController::class, 'loadReportEventLists']);

    Route::resource('/evaluations', App\Http\Controllers\Administrator\EvaluationController::class);
    Route::get('/get-report-event-evaluations', [App\Http\Controllers\Administrator\EvaluationController::class, 'getReportEvaluations']);

    Route::get('/load-report-event-attendances', [App\Http\Controllers\Report\ReportEventAttendanceController::class, 'loadReportEventAttendances']);


    
});
/*     organizer          */



Route::middleware(['verified', 'attendee'])->group(function() {
    Route::resource('/event-feeds', App\Http\Controllers\User\UserEventFeedController::class);
    Route::get('/load-event-feeds', [App\Http\Controllers\User\UserEventFeedController::class, 'loadEventFeeds']);

    Route::resource('/profile', App\Http\Controllers\User\UserProfileController::class);
    Route::get('/load-profile', [App\Http\Controllers\User\UserProfileController::class, 'loadProfile']);
});



//other
Route::middleware(['verified'])->group(function() {
    Route::get('/load-approving-officers', [App\Http\Controllers\Administrator\AdminOpenController::class, 'loadApprovingOfficers']);

});



