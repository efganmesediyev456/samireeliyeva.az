<?php

use App\Http\Controllers\Api\About\AboutController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\PasswordResetController;
use App\Http\Controllers\Api\CatalogApiController;
use App\Http\Controllers\Api\GalleryController;
use App\Http\Controllers\Api\Users\UserController;
use App\Http\Controllers\Api\WebsiteLikeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Api\Regulations\LanguageController;
use \App\Http\Controllers\Api\Regulations\TranslationController;
use \App\Http\Controllers\Api\Regulations\TeamController;
use \App\Http\Controllers\Api\Regulations\CertificateController;
use \App\Http\Controllers\Api\Products\ProductController;
use \App\Http\Controllers\Api\Products\CategoryController;
use App\Http\Controllers\Api\Products\ProductReviewController;
use App\Http\Controllers\Api\Subscribe\SubscriberController;
use App\Http\Controllers\Api\Users\EmailChangeController;
use App\Http\Controllers\Api\Users\UserFavoriteController;
use App\Http\Controllers\Api\Users\UserOrderController;
use App\Http\Controllers\Api\Users\UserCardController;
use App\Http\Controllers\Api\VacancyApiController;
use App\Http\Controllers\Api\OurOnMapController;
use App\Http\Controllers\Api\StaticPageController;
use App\Http\Controllers\Api\BlogAndNewsController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\PriceQuoteController;
use App\Http\Controllers\Api\PropertyController;
use App\Http\Controllers\Api\Orders\OrderController;
use App\Http\Controllers\Api\Orders\CancelOrderReasonController;
use App\Http\Controllers\Api\Orders\CityController;
use App\Http\Controllers\Api\AdvertisementController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\TextBookController;
use App\Http\Controllers\Api\TermsAndConditionController;
use App\Http\Controllers\Api\HaveQuestionController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\SubScriptionController;
use App\Http\Controllers\Api\ExamsController;
use App\Http\Controllers\Api\SiteSettingController;
use App\Http\Controllers\Api\FreeOnlineLessonsController;
use App\Http\Controllers\Api\TextBookBannerController;
use App\Http\Controllers\Api\UserExamResultsController;




/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/



Route::get('/users',[UserController::class,'index']);
Route::post('/register',[AuthController::class,'register']);
Route::post('/resend-otp',[AuthController::class,'resendOtp']);


Route::post('/email/verify', [AuthController::class,'verify']);

Route::post('/login',[AuthController::class,'login']);
Route::post('/refresh-token',[AuthController::class,'refreshToken']);


Route::middleware('auth:api')->post('/user',[AuthController::class,'user']);


Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLinkEmail']);
Route::post('/reset-password', [PasswordResetController::class, 'resetPassword']);

Route::get('/site-languages', [LanguageController::class, 'index']);
Route::get('/about', [AboutController::class, 'index']);
Route::get('/textbook-banner', [TextBookBannerController::class, 'index']);



Route::get('/translations', [TranslationController::class, 'index']);
Route::get('/teams', [TeamController::class, 'index']);
Route::get('/certificates', [CertificateController::class, 'index']);



Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{slug}', [ProductController::class, 'single']);
Route::get('/all-products', [ProductController::class, 'allProducts']);
Route::get('/product/{slug}', [ProductController::class, 'product']);
Route::get('/catalogs', [CatalogApiController::class,'index']);
Route::get('/vacancies', [VacancyApiController::class,'index']);
Route::get('/vacancy-receipents', [VacancyApiController::class,'vacancyReceipents']);
Route::get('/share-links', [VacancyApiController::class,'vacancyShareLinks']);
Route::get('/vacancies/{slug}', [VacancyApiController::class,'single']);
Route::get('/our-on-map', [OurOnMapController::class,'index']);
Route::get('/pages/{slug}', [StaticPageController::class,'index']);

Route::get('/home-products', [HomeController::class,'index']);
Route::get('/brends', [HomeController::class,'getBrends']);
Route::get('/properties', [PropertyController::class,'index']);
Route::get('/banners', [HomeController::class,'getBanners']);
Route::get('/weekly-offers', [HomeController::class,'getWeeklyOffers']);
Route::get('/banner-details', [HomeController::class,'getBannerDetails']);
Route::get('/discounted-products', [HomeController::class,'getDiscountedProducts']);
Route::get('/social-links', [HomeController::class,'getSocialLinks']);
Route::post('/vacancy/apply', [VacancyApiController::class,'apply']);
Route::get('/vacancy/{id}', [VacancyApiController::class, 'show']);
Route::post('/cancel-order-reasons', [CancelOrderReasonController::class, 'index']);
Route::get('/cities', [CityController::class, 'index']);
Route::get('vacancy-banner', [VacancyApiController::class, 'banner']);


Route::middleware('auth:api')->group(function () {
    Route::post('/logout',[AuthController::class,'logout']);

    Route::post('/products/reviews', [ProductReviewController::class, 'store']);
    Route::post('/favorites', [UserFavoriteController::class, 'addToFavorites']);
    Route::get('/user/favorites', [UserFavoriteController::class, 'getFavorites']);
    Route::post('/user/orders', [UserOrderController::class, 'getOrders']);
    Route::post('/user/profile', [UserController::class, 'update']);
    Route::post('/change-email/send-otp', [EmailChangeController::class, 'sendOtp']);
    Route::post('/change-email', [EmailChangeController::class, 'changeEmail']);
    //add card item
    Route::post('/cards', [UserCardController::class, 'addToCards']);
    //get user cards
    Route::get('/user/cards', [UserCardController::class, 'getCards']);
    Route::post('/cards/remove-product', [UserCardController::class, 'removeCardProduct']);
    Route::post('/cards/update-quantity-increase', [UserCardController::class, 'updateCardQuantityIncrease']);
    Route::post('/cards/update-quantity-decrease', [UserCardController::class, 'updateCardQuantityDecrease']);

    //orders
    Route::post('/order', [OrderController::class, 'placeOrder']);
    Route::post('/cancel-order', [OrderController::class, 'cancelOrder']);

    Route::get('/user/exam-results', [UserExamResultsController::class, 'index']);
    
    Route::prefix('user/notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index']);
        Route::get('/unread', [NotificationController::class, 'unread']);
        Route::post('/{id}/read', [NotificationController::class, 'markAsRead']);
        Route::post('/read-all', [NotificationController::class, 'markAllAsRead']);
        Route::delete('/delete-all', action: [NotificationController::class, 'destroryAll']);
        Route::delete('/{id}', [NotificationController::class, 'destroy']);
    });
});



Route::post('/subscribe', [SubscriberController::class,'subscribe']);
Route::post('/price-quote', [PriceQuoteController::class, 'store']);




Route::post('/order/create', [\App\Http\Controllers\Api\StripeController::class, 'createStripeCheckout'])->name('stripe.post');
Route::get('/order/status', [\App\Http\Controllers\Api\StripeController::class, 'checkPaymentStatus'])->name('order.status');





Route::get('/blog-and-news', [BlogAndNewsController::class,'index']);
Route::get('/blog-and-news/{slug}', [BlogAndNewsController::class,'single']);
Route::get('/blog-and-other-news', [BlogAndNewsController::class,'others']);
Route::get('/all-blog-and-news', [BlogAndNewsController::class,'getAllBlogs']);


Route::get('/gallery-photos', [GalleryController::class,'getAllPhotos']);
Route::get('/gallery-photos/{slug}', [GalleryController::class,'singlePhoto']);

Route::get('/gallery-videos', [GalleryController::class,'getAllVideos']);
Route::get('/gallery-videos/{slug}', [GalleryController::class,'singleVideo']);

Route::get('/advertisements', [AdvertisementController::class,'index']);
Route::get('/advertisements/{slug}', [AdvertisementController::class,'single']);
Route::get('other-advertisements', [AdvertisementController::class,'others']);



Route::get('/services', [ServiceController::class,'index']);
Route::get('/services/{slug}', [ServiceController::class,'single']);
Route::get('other-services', [ServiceController::class,'others']);
Route::get('/about', [AboutController::class, 'index']);
Route::get('/textbooks', [TextBookController::class, 'index']);
Route::get('/textbooks/{item}', [TextBookController::class, 'single']);
Route::get('/free-online-lessons', [FreeOnlineLessonsController::class, 'index']);
Route::get('/exam-banner', [AboutController::class, 'exam']);


Route::get('/home-categories', [HomeController::class, 'categories']);
Route::get('/important-links', [HomeController::class, 'importantLinks']);
Route::get('/educational-regions', [HomeController::class, 'educationalregions']);
Route::get('/partners', [HomeController::class, 'partners']);
Route::get('/home-textbooks', [HomeController::class, 'textbooks']);
Route::get('/home-advertisements', [HomeController::class, 'advertisements']);
Route::get('/home-blog-and-news', [HomeController::class, 'blogs']);


Route::get('/terms-and-conditions', [TermsAndConditionController::class, 'index']);


Route::post('/have-question', [HaveQuestionController::class, 'store']);
Route::get('/categories', [CategoryController::class, 'index']);


Route::get('/category/{slug}/subcategories', [CategoryController::class, 'getSubCategories']);
Route::get('subcategory/{slug}', [CategoryController::class, 'subcategory']);
Route::get('subcategory/{slug}/packets', [CategoryController::class, 'getPackets'])->name('packets');

Route::middleware(['subscribed','auth:api'])->group(function () {
    Route::get('subcategory/{slug}/topic', [CategoryController::class, 'getTopic']);
    Route::get('subcategory/{slug}/video', [CategoryController::class, 'getVideo']);
    Route::get('subcategory/{slug}/tests', [CategoryController::class, 'getTests']);
    Route::get('subcategory/{slug}/littleVideoRolics', [CategoryController::class, 'getLittleVideoRolics']);
    Route::get('subcategory/{slug}/interviewPreparations', [CategoryController::class, 'getInterviewPreparations']);
    Route::get('subcategory/{slug}/essayExamples', [CategoryController::class, 'getEssayExamples']);
    Route::get('subcategory/{slug}/criticalReadings', [CategoryController::class, 'getCriticalReadings']);
    Route::get('subcategory/{slug}/pastExamQuestions', [CategoryController::class, 'getPastExamQuestions']);
    Route::get('subcategory/{slug}/exams/{exam}', [ExamsController::class, 'getExam']);
    Route::post('subcategory/{slug}/exams/{exam}/start', [ExamsController::class, 'startExam']);
    Route::post('subcategory/{slug}/exams/{exam}', [ExamsController::class, 'postExam']);
    Route::get('subcategory/{slug}/exams/{exam}/card', [ExamsController::class, 'getCard']);
    Route::get('subcategory/{slug}/exams/{exam}/results', [ExamsController::class, 'getResults']);
});

Route::middleware('auth:api')->group(function () {
    Route::get('/user/subscriptions', [SubScriptionController::class, 'index']);
});

Route::get('/site-settings', [SiteSettingController::class, 'index']);
Route::post('/website-likes', [WebsiteLikeController::class, 'store']);

// Route::get('/category/{slug}', [CategoryController::class, 'category']);





