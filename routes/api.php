<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

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


/*  Routes for JWT Auth */ 

/*Route::post('/auth/register', 'AuthController@postRegister');
Route::post('auth/login', 'AuthController@postLogin');
Route::group(['middleware' => ['auth.jwt']], function () {
 
    Route::get('auth/logout', 'AuthController@logout');
});*/


/*Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);    
});*/
/* -------- */

//Route::get('inlineapi/uploadmyfile', 'InlineclientappController@uploaduserfile');  

Route::post('inlineclientapi/uploadmyfile', 'InlineclientappController@uploaduserfile');
Route::post('inlineclientapi/registeremail', 'InlineclientappController@registeruseremail');
Route::post('inlineclientapi/verifyMyOTP', 'InlineclientappController@verifyMyFileOTP');
Route::post('inlineclientapi/getProgress', 'InlineclientappController@getFileProgress');
Route::post('inlineclientapi/startProcess', 'InlineclientappController@startMyProcess');
Route::post('inlineclientapi/completePayment', 'InlineclientappController@completeMyPayment');


Route::post('reprocessUnknown', 'UserController@reprocessUnknownEmails');  
Route::post('getSharedContent', 'AdminController@getSharedPosts');  
Route::post('getDiscountRequest', 'AdminController@getSpecialDiscountRequest');  
Route::post('approveSpecialDiscountRequest', 'AdminController@approveSpecialDiscount');  


Route::get('getusername', 'UserController@getMyUserName');  
Route::post('getAdditionalCoupons', 'UserController@getMyAdditionalCoupons');  
Route::get('check_affiliate_account', 'UserController@checkMyAffiliateAccount');  
Route::get('getsessionmsg', 'UserController@checksessionmessage');  
Route::get('checkusersession', 'UserController@checkusersession'); 

Route::post('cancelMySubscription', 'UserController@cancelUserSubscription');  

Route::post('getMyGetResponseListInfo', 'UserController@getGetResponseMembers'); 
Route::post('getMyCCListInfo', 'UserController@getCCMembers'); 
Route::post('ReportsPaidUsers', 'AdminController@getReportsPaidUsers'); 
Route::post('RevenueByLoginSource', 'AdminController@getRevenueBySource'); 
Route::post('discountRequest', 'UserController@sendDiscountRequest'); 



Route::post('ReportsHighestOrders', 'AdminController@getReportsHighestOrders'); 
Route::post('ReportsSubscribers', 'AdminController@getReportsSubscribers'); 


Route::post('getMyAweberListInfo', 'UserController@getAweberMembers'); 
Route::post('getMyAweberAccount', 'UserController@getAweberAccount');
Route::post('getMyAweberToken', 'UserController@getAweberToken');


Route::post('getToken', 'UserController@getToken');
Route::post('saveSocialLink', 'UserController@saveMySocialLink');
Route::post('getSharedLinks', 'UserController@getUserSharedLinks');


Route::get('getIntegrations', 'UserController@getUserIntegrations');
Route::get('getusercoupons', 'UserController@getUserCouponCodes');  

Route::post('unsubscribePlan', 'UserController@unsubscribePlan');  
Route::post('stopProcess', 'UserController@stopVerifyProcess');  
Route::post('saveMyAPIKey', 'UserController@saveLibAPIKey');  

Route::post('disconnect', 'UserController@disconnectAPI');  
Route::post('resendactivationlink', 'UserController@resendEmailActivationLink');  


Route::middleware('auth:api')->get('/user', function (Request $request){
    return $request->user();
});


Route::post('buyCreditsPaymentAuthorized/', 'UserController@creditsPayment');
Route::post('resendCode/', 'UserController@resendVerificationCode');
Route::post('askPaymentConfirmation/', 'UserController@paymentConfirmation');

Route::post('pendingPaymentAuthorized/', 'UserController@processPendingPayment');


Route::post('toggleSwitch/', 'UserController@changeSwitch');
Route::post('updateCreditLimit/', 'UserController@changeCreditLimit');



Route::post('sendsupportmessage', 'UserController@sendInquiryMessage');
Route::post('validate_email', 'UserController@validateEmail');


Route::post('csvinfo', 'UserController@getcsvinfo');
Route::post('startProcess', 'UserController@startVerifyProcess');  
Route::post('restartProcess', 'UserController@restartUnknownProcess');  
Route::post('setmode', 'UserController@setfilemode');  


Route::post('processedFileinfo', 'UserController@getProcessedFileinfo');


Route::post('getAlertLogs', 'AdminController@getAlertLogs'); 
Route::post('submitemailist', 'UserController@submitemailist'); 
Route::post('processemailist', 'UserController@processemailist'); 


Route::post('getMylistInfo', 'UserController@getMailchimpListInfo'); 


Route::get('checkresults/{id}', 'UserController@checkUploadResults'); 
Route::get('getProfile', 'UserController@getUserProfile'); 
Route::get('getCouponData/{id}', 'AdminController@getCouponData'); 
Route::get('deleteMyAccount', 'UserController@deleteAccount'); 
Route::get('deleteCoupon/{id}', 'AdminController@deleteCoupon'); 

Route::post('sendAlert/', 'AdminController@sendalert');

Route::get('getAPIKey', 'UserController@getAPIKey'); 
Route::get('generateAPIKey', 'UserController@generateAPIKey'); 


Route::post('saveProfile', 'UserController@saveUserProfile');
Route::post('sendotp', 'UserController@sendotp');
Route::post('verifyotp', 'UserController@verifyotp');
Route::post('verify_payer', 'UserController@verify_paypal_payer');
Route::post('createUser', 'UserController@createUser');
Route::post('changePassword', 'UserController@changePassword');
Route::post('submitCouponData', 'AdminController@submitCouponData');


// Route::get('getLogs/{id}', 'UserController@getLogs'); 
Route::post('getLogs', 'UserController@getLogs');   
    
        
Route::post('validationhistory', 'UserController@getValidationRecords');
Route::get('deletethisfile/{id}', 'UserController@deletethisfile');

        


Route::post('applyCoupon', 'UserController@applyCouponCode'); 
Route::post('signin', 'UserController@signIn'); 
Route::get('logout', 'UserController@signOut'); 
Route::post('ForgotPassword', 'UserController@ForgotPassword'); 
Route::post('ResetUserPassword', 'UserController@ResetUserPassword'); 
 

Route::get('ResetPassword/{token}', 'UserController@ResetPassword'); 
//Route::get('createAfflLink', 'UserController@createAfflAccount'); 
Route::get('requestAfflLink', 'UserController@requestAfflAccount'); 


Route::post('invoiceHistory', 'UserController@invoiceHistory'); 
Route::post('creditHistory', 'UserController@creditHistory'); 


Route::get('downloadInvoice/{invoiceno}', 'UserController@downloadInvoice'); 
Route::post('confirmMyInvoice', 'UserController@confirmInvoice'); 

Route::post('getAllUsers', 'AdminController@getAllUsers'); 
Route::post('getAllOrders', 'AdminController@getAllOrders'); 
Route::post('getAllUploads', 'AdminController@getAllUploads'); 


Route::post('updateUserStatus', 'AdminController@updateUserStatus'); 
Route::post('allowUsersUpload', 'AdminController@allowUsersToUpload'); 

Route::post('updateCouponStatus', 'AdminController@updateCouponStatus'); 


Route::post('addcredits', 'AdminController@addCredits'); 
Route::post('releasecredits', 'AdminController@releaseBlockedCredits'); 


Route::post('RewardFreeCredits', 'AdminController@addRewardCredits'); 
Route::post('rejectreward', 'AdminController@rejectuserreward'); 

Route::post('updateAffiliate', 'AdminController@updateUserAffiliate'); 



//Route::get('getStats', 'AdminController@getStats');  
Route::post('getStats', 'AdminController@getStats');  
Route::post('getSettings', 'AdminController@getSettings');  
Route::post('getAdminSettings', 'AdminController@getAdminSettings');  
Route::post('saveAdminSettings', 'AdminController@saveAdminSettings');  


Route::get('markasread', 'UserController@markasread'); 



Route::post('getAllCoupons', 'AdminController@getAllCoupons'); 

Route::get('getSubscriptionDetails', 'UserController@getSubscriptionDetails'); 

/*Route::post('api/validate_email/', function(){
	return "test";
});

Route::post('validatemail/', function(){
	return "test";
});*/
