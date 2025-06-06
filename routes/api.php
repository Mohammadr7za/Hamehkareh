<?php

use App\Http\Resources\UserDataResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
use App\Http\Controllers\API;

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
/*
normal api_token
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
*/
require __DIR__ . '/admin-api.php';

//Route::get('category-full-list', [API\CategoryController::class, 'getCategoryFullList']);
Route::get('subcategory-list', [API\SubCategoryController::class, 'getSubCategoryList']);

Route::get('type-list', [API\CommanController::class, 'getTypeList']);
Route::get('blog-list', [API\BlogController::class, 'getBlogList']);
Route::post('blog-detail', [API\BlogController::class, 'getBlogDetail']);

Route::post('country-list', [API\CommanController::class, 'getCountryList']);
Route::post('state-list', [API\CommanController::class, 'getStateList']);
Route::post('city-list', [API\CommanController::class, 'getCityList']);
Route::get('search-list', [API\CommanController::class, 'getSearchList']);
Route::get('slider-list', [API\SliderController::class, 'getSliderList']);
Route::get('top-rated-service', [API\ServiceController::class, 'getTopRatedService']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        $user_id = $request->user()->id;

        $data = \App\Models\User::where('id', $user_id)
            ->with('city')
            ->with('country')
            ->with('payment')
            ->with('booking')
            ->with('state')->get();

        return comman_message_response($data);
        return comman_message_response(UserDataResource::make($data));
    });

    Route::post('request_otp', [API\User\UserController::class, 'requestOtp']);
    Route::get('splash', [API\User\UserController::class, 'splash']);
    Route::post('confirm-otp', [API\User\UserController::class, 'confirmOtp']);
});
Route::post('register', [API\User\UserController::class, 'register']);
Route::post('login', [API\User\UserController::class, 'login']);
Route::post('login-with-mobile', [API\User\UserController::class, 'loginWithMobile']);
Route::post('forgot-password', [API\User\UserController::class, 'forgotPassword']);
Route::post('forgot-password-mobile', [API\User\UserController::class, 'forgotPasswordMobile']);
Route::post('change-password-otp', [API\User\UserController::class, 'changePasswordWithOtp']);
Route::post('social-login', [API\User\UserController::class, 'socialLogin']);
Route::post('contact-us', [API\User\UserController::class, 'contactUs']);


Route::get('dashboard-detail', [API\DashboardController::class, 'dashboardDetail']);
Route::get('service-rating-list', [API\ServiceController::class, 'getServiceRating']);
Route::get('user-detail', [API\User\UserController::class, 'userDetail']);
Route::post('service-detail', [API\ServiceController::class, 'getServiceDetail']);
Route::get('user-list', [API\User\UserController::class, 'userList']);
Route::get('booking-status', [API\BookingController::class, 'bookingStatus']);
Route::post('handyman-reviews', [API\User\UserController::class, 'handymanReviewsList']);
Route::post('service-reviews', [API\ServiceController::class, 'serviceReviewsList']);
Route::get('post-job-status', [API\PostJobRequestController::class, 'postRequestStatus']);

Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::get('category-list', [API\CategoryController::class, 'getCategoryList']);

    Route::resource('upload-files', Controllers\WorkSampleController::class)->middleware('optimizeImages');
    Route::get('upload-files/user/{id}', [Controllers\WorkSampleController::class,'getWorkSamplesByUserId']);

    Route::get('service-list', [API\ServiceController::class, 'getServiceList']);
    Route::get('service-list-combo', [API\ServiceController::class, 'getServiceListCombo']);
    Route::get('categories-service-list-combo', [API\ServiceController::class, 'getServiceListComboBaseOnCategories']);

    Route::post('service-save', [App\Http\Controllers\ServiceController::class, 'store']);
    Route::post('service-delete/{id}', [App\Http\Controllers\ServiceController::class, 'destroy']);
    Route::post('booking-save', [App\Http\Controllers\BookingController::class, 'store']);
    Route::post('booking-update', [API\BookingController::class, 'bookingUpdate']);
    Route::get('provider-dashboard', [API\DashboardController::class, 'providerDashboard']);
    Route::get('admin-dashboard', [API\DashboardController::class, 'adminDashboard']);
    Route::get('booking-list', [API\BookingController::class, 'getBookingList']);
    Route::get('all-booking-list', [API\BookingController::class, 'getAllBookingList']);
    Route::get('user-booking-list', [API\BookingController::class, 'getUserBookingList']);
    Route::get('provider-booking-list', [API\BookingController::class, 'getProviderBookingList']);
    Route::get('handyman-booking-list', [API\BookingController::class, 'getHandymanBookingList']);
    Route::post('booking-detail', [API\BookingController::class, 'getBookingDetail']);
    Route::post('save-booking-rating', [API\BookingController::class, 'saveBookingRating']);
    Route::post('delete-booking-rating', [API\BookingController::class, 'deleteBookingRating']);
    Route::get('get-user-ratings', [API\BookingController::class, 'getUserRatings']);

    Route::post('save-favourite', [API\ServiceController::class, 'saveFavouriteService']);
    Route::post('save-favourite-list', [API\ServiceController::class, 'saveFavouriteServiceList']);
    Route::post('delete-favourite', [API\ServiceController::class, 'deleteFavouriteService']);
    Route::get('user-favourite-service', [API\ServiceController::class, 'getUserFavouriteService']);

    Route::post('booking-action', [API\BookingController::class, 'action']);

    Route::post('booking-assigned', [App\Http\Controllers\BookingController::class, 'bookingAssigned']);

    Route::post('user-update-status', [API\User\UserController::class, 'userStatusUpdate']);
    Route::post('change-password', [API\User\UserController::class, 'changePassword']);
    Route::post('update-profile', [API\User\UserController::class, 'updateProfile']);
    Route::post('notification-list', [API\NotificationController::class, 'notificationList']);
    Route::post('remove-file', [App\Http\Controllers\HomeController::class, 'removeFile']);
    Route::get('logout', [API\User\UserController::class, 'logout']);

//    Route::post('save-payment', [API\PaymentController::class, 'savePayment']);
    Route::post('add-payment', [API\PaymentController::class, 'addPayment']);
    Route::get('payment-list', [API\PaymentController::class, 'paymentList']);
    Route::post('transfer-payment', [API\PaymentController::class, 'transferPayment']);
    Route::get('payment-history', [API\PaymentController::class, 'paymentHistory']);
    Route::get('cash-detail', [API\PaymentController::class, 'paymentDetail']);
    Route::get('user-bank-detail', [API\CommanController::class, 'getBankList']);


    Route::post('save-provideraddress', [App\Http\Controllers\ProviderAddressMappingController::class, 'store']);
    Route::get('provideraddress-list', [API\ProviderAddressMappingController::class, 'getProviderAddressList']);
    Route::post('provideraddress-delete/{id}', [App\Http\Controllers\ProviderAddressMappingController::class, 'destroy']);
    Route::post('save-handyman-rating', [API\BookingController::class, 'saveHandymanRating']);
    Route::post('delete-handyman-rating', [API\BookingController::class, 'deleteHandymanRating']);

    Route::get('document-list', [API\DocumentsController::class, 'getDocumentList']);
    Route::get('provider-document-list', [API\ProviderDocumentController::class, 'getProviderDocumentList']);
    Route::post('provider-document-save', [App\Http\Controllers\ProviderDocumentController::class, 'store']);
    Route::post('provider-document-delete/{id}', [App\Http\Controllers\ProviderDocumentController::class, 'destroy']);
    Route::post('provider-document-action', [App\Http\Controllers\ProviderDocumentController::class, 'action']);

    Route::get('tax-list', [API\CommanController::class, 'getProviderTax']);
    Route::get('handyman-dashboard', [API\DashboardController::class, 'handymanDashboard']);

    Route::post('customer-booking-rating', [API\BookingController::class, 'bookingRatingByCustomer']);
    Route::post('handyman-delete/{id}', [App\Http\Controllers\HandymanController::class, 'destroy']);
    Route::post('handyman-action', [App\Http\Controllers\HandymanController::class, 'action']);

    Route::get('provider-payout-list', [API\PayoutController::class, 'providerPayoutList']);
    Route::get('handyman-payout-list', [API\PayoutController::class, 'handymanPayoutList']);

    Route::get('plan-list', [API\PlanController::class, 'planList']);
    Route::post('save-subscription', [API\SubscriptionController::class, 'providerSubscribe']);
    Route::post('cancel-subscription', [API\SubscriptionController::class, 'cancelSubscription']);
    Route::get('subscription-history', [API\SubscriptionController::class, 'getHistory']);
    Route::get('wallet-history', [API\WalletController::class, 'getHistory']);
    Route::post('wallet-top-up', [API\WalletController::class, 'walletTopup']);

    Route::post('save-service-proof', [API\BookingController::class, 'uploadServiceProof']);
    Route::post('handyman-update-available-status', [API\User\UserController::class, 'handymanAvailable']);
    Route::post('handyman-update-gps', [API\User\UserController::class, 'handymanGps']);
    Route::post('delete-user-account', [API\User\UserController::class, 'deleteUserAccount']);
    Route::post('delete-account', [API\User\UserController::class, 'deleteAccount']);

    Route::post('save-post-job', [App\Http\Controllers\PostJobRequestController::class, 'store']);
    Route::post('post-job-delete/{id}', [App\Http\Controllers\PostJobRequestController::class, 'destroy']);

    Route::get('get-post-job', [API\PostJobRequestController::class, 'getPostRequestList']);
    Route::post('get-post-job-detail', [API\PostJobRequestController::class, 'getPostRequestDetail']);

    Route::post('save-bid', [App\Http\Controllers\PostJobBidController::class, 'store']);
    Route::get('get-bid-list', [API\PostJobBidController::class, 'getPostBidList']);


    Route::post('save-provider-slot', [App\Http\Controllers\ProviderSlotController::class, 'store']);
    Route::get('get-provider-slot', [API\ProviderSlotController::class, 'getProviderSlot']);


    Route::post('package-save', [App\Http\Controllers\ServicePackageController::class, 'store']);
    Route::get('package-list', [API\ServicePackageController::class, 'getServicePackageList']);
    Route::post('package-delete/{id}', [App\Http\Controllers\ServicePackageController::class, 'destroy']);


    Route::post('blog-save', [App\Http\Controllers\BlogController::class, 'store']);
    Route::post('blog-delete/{id}', [App\Http\Controllers\BlogController::class, 'destroy']);
    Route::post('blog-action', [App\Http\Controllers\BlogController::class, 'action']);


    Route::post('save-favourite-provider', [API\ProviderFavouriteController::class, 'saveFavouriteProvider']);
    Route::post('delete-favourite-provider', [API\ProviderFavouriteController::class, 'deleteFavouriteProvider']);
    Route::get('user-favourite-provider', [API\ProviderFavouriteController::class, 'getUserFavouriteProvider']);
    Route::post('download-invoice', [API\CommanController::class, 'downloadInvoice']);
    Route::get('user-wallet-balance', [API\User\UserController::class, 'userWalletBalance']);


    Route::get('configurations', [API\DashboardController::class, "configurations"]);

});
