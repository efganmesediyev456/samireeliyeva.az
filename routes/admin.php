<?php

use App\Http\Controllers\Backend\Auth\LoginController;
use App\Http\Controllers\Backend\CriticalReadingController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\EducationalRegionController;
use App\Http\Controllers\Backend\EssayExampleController;
use App\Http\Controllers\Backend\ExamBannerController;
use App\Http\Controllers\Backend\ExamCategoryController;
use App\Http\Controllers\Backend\ExamController;
use App\Http\Controllers\Backend\ExamQuestionController;
use App\Http\Controllers\Backend\ExamStatusController;
use App\Http\Controllers\Backend\FreeOnlineLessonController;
use App\Http\Controllers\Backend\GalleryVideoController;
use App\Http\Controllers\Backend\ImportantLinkController;
use App\Http\Controllers\Backend\InterviewPreparationController;
use App\Http\Controllers\Backend\LittleVideoRolicController;
use App\Http\Controllers\Backend\PartnerController;
use App\Http\Controllers\Backend\Regulations\BrendController;
use App\Http\Controllers\Backend\Regulations\CityController;
use App\Http\Controllers\Backend\Regulations\LanguageController;
use App\Http\Controllers\Backend\Regulations\TranslationController;
use App\Http\Controllers\Backend\Regulations\TeamController;
use App\Http\Controllers\Backend\Regulations\CertificateController;
use App\Http\Controllers\Backend\Regulations\SubCategoryController;
use App\Http\Controllers\Backend\Regulations\BrandController;
use App\Http\Controllers\Backend\Regulations\VacancyReceipentController;
use App\Http\Controllers\Backend\Regulations\VacancyShareSocialController;
use App\Http\Controllers\Backend\ServiceController;
use App\Http\Controllers\Backend\TextbookBannerController;
use App\Http\Controllers\Backend\TopicCategoryController;
use App\Http\Controllers\Backend\TopicFileController;
use App\Http\Controllers\Backend\Users\UserController;
use App\Http\Controllers\Backend\VideoController;
use App\Http\Controllers\Backend\WebsiteLikeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\About\AboutController;
use App\Http\Controllers\Backend\OurOnMap\OurOnMapController;
use App\Http\Controllers\Backend\Products\ProductController;
use App\Http\Controllers\Backend\BlogNew\BlogNewController;
use App\Http\Controllers\Backend\Rating\RatingController;
use App\Http\Controllers\Backend\ReturnPolicy\ReturnPolicyController;
use App\Http\Controllers\Backend\ComplainManagement\ComplainManagementController;
use App\Http\Controllers\Backend\DeliveryPayment\DeliveryPaymentController;
use App\Http\Controllers\Backend\Catalog\CatalogController;
use App\Http\Controllers\Backend\Regulations\BannerController;
use App\Http\Controllers\Backend\Regulations\BannerDetailController;
use App\Http\Controllers\Backend\SocialLinkController;
use App\Http\Controllers\Backend\VacancyController;
use App\Http\Controllers\Backend\PriceQuoteController;
use App\Http\Controllers\Backend\VacancyApplicationController;
use App\Http\Controllers\Backend\OrderController;
use App\Http\Controllers\Backend\OrderCancellationReasonController;
use App\Http\Controllers\Backend\Vacancy\VacancyBannerController;
use App\Http\Controllers\Backend\GalleryPhotoController;
use App\Http\Controllers\Backend\AdvertisementController;
use App\Http\Controllers\Backend\TermsAndConditionController;
use App\Http\Controllers\Backend\TextbookController;
use App\Http\Controllers\Backend\TextbookAttributeController;
use App\Http\Controllers\Backend\HaveQuestionController;
use App\Http\Controllers\Backend\Regulations\CategoryController;
use App\Http\Controllers\Backend\TopicController;
use App\Http\Controllers\Backend\PastExamQuestionController;
use App\Http\Controllers\Backend\SubCategoryPacketController;
use App\Http\Controllers\Backend\Settings\SiteSettingController;
use App\Http\Controllers\Backend\GeneralController;
use App\Http\Controllers\Backend\ExamResultController;
use App\Http\Controllers\Backend\SubscriptionController;





Route::get("/login", [LoginController::class, 'login'])->name('.login');
Route::post("/login", [LoginController::class, 'loginPost'])->name('.login.post');
Route::post("/logout", [LoginController::class, 'logout'])->name('.logout');

Route::middleware("auth:admin")->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('.dashboard');

    Route::group(['prefix' => 'languages', 'as' => '.languages'], function () {
        Route::get('/', [LanguageController::class, 'index'])->name('.index');
        Route::get('/create', [LanguageController::class, 'create'])->name('.create');
        Route::post('/store', [LanguageController::class, 'store'])->name('.store');
        Route::get('/{item}/edit', [LanguageController::class, 'edit'])->name('.edit');
        Route::put('/{item}/update', [LanguageController::class, 'update'])->name('.update');
        Route::delete('/{item}', [LanguageController::class, 'delete'])->name('.destroy');
    });
    
    Route::group(['prefix' => 'users', 'as' => '.users'], function () {
        Route::get('/', [UserController::class, 'index'])->name('.index');
        Route::get('/create', [UserController::class, 'create'])->name('.create');
        Route::post('/store', [UserController::class, 'store'])->name('.store');
        Route::get('/{item}/edit', [UserController::class, 'edit'])->name('.edit');
        Route::put('/{item}/update', [UserController::class, 'update'])->name('.update');
        Route::delete('/{item}', [UserController::class, 'delete'])->name('.destroy');

        Route::group(['prefix' => '{user}/exams', 'as' => '.exams'], function () {
            Route::get('/', [ExamResultController::class, 'index'])->name('.index');
            Route::group(['prefix' => '/{exam}/results', 'as' => '.results'], function () {
                Route::get('/', [ExamResultController::class, 'show'])->name('.show');
                Route::get('/{question}', [ExamResultController::class, 'showQuestion'])->name('.showQuestion');
                Route::post('/{question}/accept', [ExamResultController::class, 'acceptQuestion'])->name('.acceptQuestion');
            });
        });

        Route::group(['prefix' => '{user}/subscriptions', 'as' => '.subscriptions'], function () {
            Route::get('/', [SubScriptionController::class, 'index'])->name('.index');
        });
    });


    Route::group(['prefix' => 'translations', 'as' => '.translations'], function () {
        Route::get('/', [TranslationController::class, 'index'])->name('.index');
        Route::get('/create', [TranslationController::class, 'create'])->name('.create');
        Route::post('/store', [TranslationController::class, 'store'])->name('.store');
        Route::get('/{item}/edit', [TranslationController::class, 'edit'])->name('.edit');
        Route::put('/{item}/update', [TranslationController::class, 'update'])->name('.update');
        Route::delete('/{item}', [TranslationController::class, 'delete'])->name('.destroy');
    });

    Route::group(['prefix' => 'teams', 'as' => '.teams'], function () {
        Route::get('/', [TeamController::class, 'index'])->name('.index');
        Route::get('/create', [TeamController::class, 'create'])->name('.create');
        Route::post('/store', [TeamController::class, 'store'])->name('.store');
        Route::get('/{item}/edit', [TeamController::class, 'edit'])->name('.edit');
        Route::put('/{item}/update', [TeamController::class, 'update'])->name('.update');
        Route::delete('/{item}', [TeamController::class, 'delete'])->name('.destroy');
    });


    Route::group(['prefix' => 'certificates', 'as' => '.certificates'], function () {
        Route::get('/', [CertificateController::class, 'index'])->name('.index');
        Route::get('/create', [CertificateController::class, 'create'])->name('.create');
        Route::post('/store', [CertificateController::class, 'store'])->name('.store');
        Route::get('/{item}/edit', [CertificateController::class, 'edit'])->name('.edit');
        Route::put('/{item}/update', [CertificateController::class, 'update'])->name('.update');
        Route::delete('/{item}', [CertificateController::class, 'delete'])->name('.destroy');
    });


    Route::group(['prefix' => 'brands', 'as' => '.brands'], function () {
        Route::get('/', [BrandController::class, 'index'])->name('.index');
        Route::get('/create', [BrandController::class, 'create'])->name('.create');
        Route::post('/store', [BrandController::class, 'store'])->name('.store');
        Route::get('/{item}/edit', [BrandController::class, 'edit'])->name('.edit');
        Route::put('/{item}/update', [BrandController::class, 'update'])->name('.update');
        Route::delete('/{item}', [BrandController::class, 'delete'])->name('.destroy');
    });


    

    Route::group(['prefix' => 'products', 'as' => '.products'], function () {
        Route::get('/', [ProductController::class, 'index'])->name('.index');
        Route::get('/create', [ProductController::class, 'create'])->name('.create');
        Route::post('/store', [ProductController::class, 'store'])->name('.store');
        Route::get('/{item}/edit', [ProductController::class, 'edit'])->name('.edit');
        Route::put('/{item}/update', [ProductController::class, 'update'])->name('.update');
        Route::delete('/{item}', [ProductController::class, 'delete'])->name('.destroy');

        Route::get('/properties_old', [ProductController::class, 'properties'])->name('.properties_old.index');
        Route::get('/properties_old/create', [ProductController::class, 'propertiesCreate'])->name('.properties_old.create');
        Route::post('/properties_old/store', [ProductController::class, 'propertiesStore'])->name('.properties_old.store');
        Route::get('/properties_old/{id}/edit/{item_id}', [ProductController::class, 'propertiesEdit'])->name('.properties_old.edit');
        Route::put('/properties_old/{id}/update/{item_id}', [ProductController::class, 'propertiesUpdate'])->name('.properties_old.update');
        Route::delete('/properties_old/{id}/{item_id}', [ProductController::class, 'propertiesDelete'])->name('.properties_old.destroy');


        Route::post('/toggle-seasonal', [ProductController::class, 'toggleSeasonal'])->name('products.toggle-seasonal');
        Route::post('/toggle-special-offer', [ProductController::class, 'toggleSpecialOffer'])->name('products.toggle-special-offer');
        Route::post('/toggle-bundle', [ProductController::class, 'toggleBundle'])->name('products.toggle-bundle');
        Route::post('/toggle-weekly-offer', [ProductController::class, 'toggleWeeklyOffer'])->name('products.toggle-weekly-offer');

        Route::get('/get-sub-properties', [ProductController::class, 'getSubProperties'])->name('get-sub-properties');

    });


    Route::group(['prefix' => 'blognews', 'as' => '.blognews'], function () {
        Route::get('/', [BlogNewController::class, 'index'])->name('.index');
        Route::get('/create', [BlogNewController::class, 'create'])->name('.create');
        Route::post('/store', [BlogNewController::class, 'store'])->name('.store');
        Route::get('/{item}/edit', [BlogNewController::class, 'edit'])->name('.edit');
        Route::put('/{item}/update', [BlogNewController::class, 'update'])->name('.update');
        Route::delete('/{item}', [BlogNewController::class, 'delete'])->name('.destroy');
    });

    Route::group(['prefix' => 'oru-on-map', 'as' => '.ouronmap'], function () {
        Route::get('/', [OurOnMapController::class, 'index'])->name('.index');
        Route::put('/{item}/update', [OurOnMapController::class, 'update'])->name('.update');
    });

    Route::group(['prefix' => 'rating', 'as' => '.rating'], function () {
        Route::get('/', [RatingController::class, 'index'])->name('.index');
        Route::put('/{item}/update', [RatingController::class, 'update'])->name('.update');
    });
    Route::group(['prefix' => 'return-policy', 'as' => '.returnpolicy'], function () {
        Route::get('/', [ReturnPolicyController::class, 'index'])->name('.index');
        Route::put('/{item}/update', [ReturnPolicyController::class, 'update'])->name('.update');
    });

    Route::group(['prefix' => 'complain-management', 'as' => '.complainmanagement'], function () {
        Route::get('/', [ComplainManagementController::class, 'index'])->name('.index');
        Route::put('/{item}/update', [ComplainManagementController::class, 'update'])->name('.update');
    });

    Route::group(['prefix' => 'delivery-payment', 'as' => '.deliverypayment'], function () {
        Route::get('/', [DeliveryPaymentController::class, 'index'])->name('.index');
        Route::put('/{item}/update', [DeliveryPaymentController::class, 'update'])->name('.update');
    });


    Route::group(['prefix' => 'catalogs', 'as' => '.catalogs'], function () {
        Route::get('/', [CatalogController::class, 'index'])->name('.index');
        Route::get('/create', [CatalogController::class, 'create'])->name('.create');
        Route::post('/store', [CatalogController::class, 'store'])->name('.store');
        Route::get('/{item}/edit', [CatalogController::class, 'edit'])->name('.edit');
        Route::put('/{item}/update', [CatalogController::class, 'update'])->name('.update');
        Route::delete('/{item}', [CatalogController::class, 'delete'])->name('.destroy');
    });


    Route::group(['prefix' => 'vacancies', 'as' => '.vacancies'], function () {
        Route::get('/', [VacancyController::class, 'index'])->name('.index');
        Route::get('/create', [VacancyController::class, 'create'])->name('.create');
        Route::post('/store', [VacancyController::class, 'store'])->name('.store');
        Route::get('/{item}/edit', [VacancyController::class, 'edit'])->name('.edit');
        Route::put('/{item}/update', [VacancyController::class, 'update'])->name('.update');
        Route::delete('/{item}', [VacancyController::class, 'delete'])->name('.destroy');
    });


    Route::group(['prefix' => 'brends', 'as' => '.brends'], function () {
        Route::get('/', [BrendController::class, 'index'])->name('.index');
        Route::get('/create', [BrendController::class, 'create'])->name('.create');
        Route::post('/store', [BrendController::class, 'store'])->name('.store');
        Route::get('/{item}/edit', [BrendController::class, 'edit'])->name('.edit');
        Route::put('/{item}/update', [BrendController::class, 'update'])->name('.update');
        Route::delete('/{item}', [BrendController::class, 'delete'])->name('.destroy');
    });


    Route::group(['prefix' => 'banners', 'as' => '.banners'], function () {
        Route::get('/', [BannerController::class, 'index'])->name('.index');
        Route::get('/create', [BannerController::class, 'create'])->name('.create');
        Route::post('/store', [BannerController::class, 'store'])->name('.store');
        Route::get('/{item}/edit', [BannerController::class, 'edit'])->name('.edit');
        Route::put('/{item}/update', [BannerController::class, 'update'])->name('.update');
        Route::delete('/{item}', [BannerController::class, 'delete'])->name('.destroy');
    });


    Route::group(['prefix' => 'banner-details', 'as' => '.banner_details'], function () {
        Route::get('/', [BannerDetailController::class, 'index'])->name('.index');
        Route::get('/create', [BannerDetailController::class, 'create'])->name('.create');
        Route::post('/store', [BannerDetailController::class, 'store'])->name('.store');
        Route::get('/{item}/edit', [BannerDetailController::class, 'edit'])->name('.edit');
        Route::put('/{item}/update', [BannerDetailController::class, 'update'])->name('.update');
        Route::delete('/{item}', [BannerDetailController::class, 'delete'])->name('.destroy');
    });

    Route::group(['prefix' => 'social-links', 'as' => '.social_links'], function () {
        Route::get('/', [SocialLinkController::class, 'index'])->name('.index');
        Route::get('/create', [SocialLinkController::class, 'create'])->name('.create');
        Route::post('/store', [SocialLinkController::class, 'store'])->name('.store');
        Route::get('/{item}/edit', [SocialLinkController::class, 'edit'])->name('.edit');
        Route::put('/{item}/update', [SocialLinkController::class, 'update'])->name('.update');
        Route::delete('/{item}', [SocialLinkController::class, 'delete'])->name('.destroy');
    });

    Route::group(['prefix' => 'price-quote', 'as' => '.price-quotes'], function () {
        Route::get('/', [PriceQuoteController::class, 'index'])->name('.index');
    });

    Route::group(['prefix' => 'properties', 'as' => '.properties'], function () {
        Route::get('/', [\App\Http\Controllers\Backend\Regulations\PropertyController::class, 'index'])->name('.index');
        Route::get('/create', [\App\Http\Controllers\Backend\Regulations\PropertyController::class, 'create'])->name('.create');
        Route::post('/store', [\App\Http\Controllers\Backend\Regulations\PropertyController::class, 'store'])->name('.store');
        Route::get('/{item}/edit', [\App\Http\Controllers\Backend\Regulations\PropertyController::class, 'edit'])->name('.edit');
        Route::put('/{item}/update', [\App\Http\Controllers\Backend\Regulations\PropertyController::class, 'update'])->name('.update');
        Route::delete('/{item}', [\App\Http\Controllers\Backend\Regulations\PropertyController::class, 'delete'])->name('.destroy');
    });

    Route::group(['prefix' => 'sub-properties', 'as' => '.sub-properties'], function () {
        Route::get('/', [\App\Http\Controllers\Backend\Regulations\SubPropertyController::class, 'index'])->name('.index');
        Route::get('/create', [\App\Http\Controllers\Backend\Regulations\SubPropertyController::class, 'create'])->name('.create');
        Route::post('/store', [\App\Http\Controllers\Backend\Regulations\SubPropertyController::class, 'store'])->name('.store');
        Route::get('/{item}/edit', [\App\Http\Controllers\Backend\Regulations\SubPropertyController::class, 'edit'])->name('.edit');
        Route::put('/{item}/update', [\App\Http\Controllers\Backend\Regulations\SubPropertyController::class, 'update'])->name('.update');
        Route::delete('/{item}', [\App\Http\Controllers\Backend\Regulations\SubPropertyController::class, 'delete'])->name('.destroy');
    });

    Route::group(['prefix' => 'vacancy_applications', 'as' => '.vacancy_applications'], function () {
        Route::get('/', [VacancyApplicationController::class, 'index'])->name('.index');
    });

    Route::group(['prefix' => 'orders', 'as' => '.orders'], function () {
        Route::get('/', [OrderController::class, 'index'])->name('.index');
        Route::get('/{order}', [OrderController::class, 'show'])->name('.show');
        Route::delete('/{order}', [OrderController::class, 'destroy'])->name('.destroy');
        Route::post('/status/{order}', [OrderController::class, 'updateStatus'])->name('.update-status');
    });

    Route::group(['prefix' => 'order-cancellation-reasons', 'as' => '.order_cancellation_reasons'], function () {
        Route::get('/', [OrderCancellationReasonController::class, 'index'])->name('.index');
        Route::get('/create', [OrderCancellationReasonController::class, 'create'])->name('.create');
        Route::post('/store', [OrderCancellationReasonController::class, 'store'])->name('.store');
        Route::get('/{item}/edit', [OrderCancellationReasonController::class, 'edit'])->name('.edit');
        Route::put('/{item}/update', [OrderCancellationReasonController::class, 'update'])->name('.update');
        Route::delete('/{item}', [OrderCancellationReasonController::class, 'delete'])->name('.destroy');
    });


    Route::group(['prefix' => 'cities', 'as' => '.cities'], function () {
        Route::get('/', [CityController::class, 'index'])->name('.index');
        Route::get('/create', [CityController::class, 'create'])->name('.create');
        Route::post('/store', [CityController::class, 'store'])->name('.store');
        Route::get('/{item}/edit', [CityController::class, 'edit'])->name('.edit');
        Route::put('/{item}/update', [CityController::class, 'update'])->name('.update');
        Route::delete('/{item}', [CityController::class, 'delete'])->name('.destroy');
    });


    Route::group(['prefix'=>'vacancy-receipents', 'as'=>'.vacancy-receipents'],function(){
        Route::get('/', [VacancyReceipentController::class,'index'])->name('.index');
        Route::get('/create', [VacancyReceipentController::class,'create'])->name('.create');
        Route::post('/store', [VacancyReceipentController::class,'store'])->name('.store');
        Route::get('/{item}/edit', [VacancyReceipentController::class,'edit'])->name('.edit');
        Route::put('/{item}/update', [VacancyReceipentController::class,'update'])->name('.update');
        Route::delete('/{item}', [VacancyReceipentController::class,'delete'])->name('.destroy');
    });

    Route::group(['prefix'=>'share-socials', 'as'=>'.vacancy-share-socials'], function(){
        Route::get('/', [VacancyShareSocialController::class,'index'])->name('.index');
        Route::get('/create', [VacancyShareSocialController::class,'create'])->name('.create');
        Route::post('/store', [VacancyShareSocialController::class,'store'])->name('.store');
        Route::get('/{item}/edit', [VacancyShareSocialController::class,'edit'])->name('.edit');
        Route::put('/{item}/update', [VacancyShareSocialController::class,'update'])->name('.update');
        Route::delete('/{item}', [VacancyShareSocialController::class,'delete'])->name('.destroy');
    });


    Route::group(['prefix' => 'vacancy-banner', 'as' => '.vacancy-banner'], function () {
        Route::get('/', [VacancyBannerController::class, 'index'])->name('.index');
        Route::put('/{item}/update', [VacancyBannerController::class, 'update'])->name('.update');
    });
    
    Route::group(['prefix' => 'gallery-photos', 'as' => '.galleryphotos'], function () {
        Route::get('/', [GalleryPhotoController::class, 'index'])->name('.index');
        Route::get('/create', [GalleryPhotoController::class, 'create'])->name('.create');
        Route::post('/store', [GalleryPhotoController::class, 'store'])->name('.store');
        Route::get('/{item}/edit', [GalleryPhotoController::class, 'edit'])->name('.edit');
        Route::put('/{item}/update', [GalleryPhotoController::class, 'update'])->name('.update');
        Route::delete('/{item}', [GalleryPhotoController::class, 'delete'])->name('.destroy');
    });



    Route::group(['prefix' => 'gallery-videos', 'as' => '.gallery-videos'], function () {
        Route::get('/', [GalleryVideoController::class, 'index'])->name('.index');
        Route::get('/create', [GalleryVideoController::class, 'create'])->name('.create');
        Route::post('/store', [GalleryVideoController::class, 'store'])->name('.store');
        Route::get('/{item}/edit', [GalleryVideoController::class, 'edit'])->name('.edit');
        Route::put('/{item}/update', [GalleryVideoController::class, 'update'])->name('.update');
        Route::delete('/{item}', [GalleryVideoController::class, 'delete'])->name('.destroy');
    });


    Route::group(['prefix' => 'advertisements', 'as' => '.advertisements'], function () {
        Route::get('/', [AdvertisementController::class, 'index'])->name('.index');
        Route::get('/create', [AdvertisementController::class, 'create'])->name('.create');
        Route::post('/store', [AdvertisementController::class, 'store'])->name('.store');
        Route::get('/{item}/edit', [AdvertisementController::class, 'edit'])->name('.edit');
        Route::put('/{item}/update', [AdvertisementController::class, 'update'])->name('.update');
        Route::delete('/{item}', [AdvertisementController::class, 'delete'])->name('.destroy');
    });

    Route::group(['prefix' => 'services', 'as' => '.services'], function () {
        Route::get('/', [ServiceController::class, 'index'])->name('.index');
        Route::get('/create', [ServiceController::class, 'create'])->name('.create');
        Route::post('/store', [ServiceController::class, 'store'])->name('.store');
        Route::get('/{item}/edit', [ServiceController::class, 'edit'])->name('.edit');
        Route::put('/{item}/update', [ServiceController::class, 'update'])->name('.update');
        Route::delete('/{item}', [ServiceController::class, 'delete'])->name('.destroy');
    });

    Route::group(['prefix' => 'terms-and-conditions', 'as' => '.terms-and-conditions'], function () {
        Route::get('/', [TermsAndConditionController::class, 'index'])->name('.index');
        Route::put('/{item}/update', [TermsAndConditionController::class, 'update'])->name('.update');
    });


    Route::group(['prefix' => 'about', 'as' => '.about'], function () {
        Route::get('/', [AboutController::class, 'index'])->name('.index');
        Route::put('/{item}/update', [AboutController::class, 'update'])->name('.update');
    });


    Route::group(['prefix' => 'textbook-banner', 'as' => '.textbook_banner'], function () {
        Route::get('/', [TextbookBannerController::class, 'index'])->name('.index');
        Route::put('/{item}/update', [TextbookBannerController::class, 'update'])->name('.update');
    });

    
    // routes/web.php
    Route::group(['prefix' => 'textbooks', 'as' => '.textbooks'], function () {
        Route::get('/', [TextbookController::class, 'index'])->name('.index');
        Route::get('/create', [TextbookController::class, 'create'])->name('.create');
        Route::post('/store', [TextbookController::class, 'store'])->name('.store');
        Route::get('/{item}/edit', [TextbookController::class, 'edit'])->name('.edit');
        Route::put('/{item}/update', [TextbookController::class, 'update'])->name('.update');
        Route::delete('/{item}', [TextbookController::class, 'delete'])->name('.destroy');
        
        // Textbook attributes üçün marşrutlar
        Route::group(['prefix' => '{textbook}/attributes', 'as' => '.attributes'], function () {
            Route::get('/', [TextbookAttributeController::class, 'index'])->name('.index');
            Route::get('/create', [TextbookAttributeController::class, 'create'])->name('.create');
            Route::post('/store', [TextbookAttributeController::class, 'store'])->name('.store');
            Route::get('/{item}/edit', [TextbookAttributeController::class, 'edit'])->name('.edit');
            Route::put('/{item}/update', [TextbookAttributeController::class, 'update'])->name('.update');
            Route::delete('/{item}', [TextbookAttributeController::class, 'destroy'])->name('.destroy');
        });
    });


    Route::group(['prefix' => 'have-questions', 'as' => '.have_questions'], function () {
        Route::get('/', [HaveQuestionController::class, 'index'])->name('.index');
    });


    Route::group(['prefix' => 'categories', 'as' => '.categories'], function () {
        Route::get('/', [CategoryController::class, 'index'])->name('.index');
        Route::get('/create', [CategoryController::class, 'create'])->name('.create');
        Route::post('/store', [CategoryController::class, 'store'])->name('.store');
        Route::get('/{item}/edit', [CategoryController::class, 'edit'])->name('.edit');
        Route::put('/{item}/update', [CategoryController::class, 'update'])->name('.update');
        Route::delete('/{item}', [CategoryController::class, 'delete'])->name('.destroy');
    });


   
    Route::group(['prefix' => 'subcategories', 'as' => '.subcategories'], function () {
        Route::get('/', [SubcategoryController::class, 'index'])->name('.index');
        Route::get('/create', [SubcategoryController::class, 'create'])->name('.create');
        Route::post('/store', [SubcategoryController::class, 'store'])->name('.store');
        Route::get('/{item}/edit', [SubcategoryController::class, 'edit'])->name('.edit');
        Route::put('/{item}/update', [SubcategoryController::class, 'update'])->name('.update');
        Route::delete('/{item}', [SubcategoryController::class, 'delete'])->name('.destroy');

        // Topics routes
        Route::group(['prefix' => '{subcategories}/topics', 'as' => '.topics'], function () {
            Route::get('/', [TopicController::class, 'index'])->name('.index');
            Route::get('/create', [TopicController::class, 'create'])->name('.create');
            Route::post('/store', [TopicController::class, 'store'])->name('.store');
            Route::get('/{item}/edit', [TopicController::class, 'edit'])->name('.edit');
            Route::put('/{item}/update', [TopicController::class, 'update'])->name('.update');
            Route::delete('/{item}', [TopicController::class, 'destroy'])->name('.destroy');
            
            // Topic categories routes
            Route::group(['prefix' => '{topic}/categories', 'as' => '.categories'], function () {
                Route::get('/', [TopicCategoryController::class, 'index'])->name('.index');
                Route::get('/create', [TopicCategoryController::class, 'create'])->name('.create');
                Route::post('/store', [TopicCategoryController::class, 'store'])->name('.store');
                Route::get('/{category}/edit', [TopicCategoryController::class, 'edit'])->name('.edit');
                Route::put('/{category}/update', [TopicCategoryController::class, 'update'])->name('.update');
                Route::delete('/{category}', [TopicCategoryController::class, 'destroy'])->name('.destroy');
                
                // Topic files routes
                Route::group(['prefix' => '{category}/files', 'as' => '.files'], function () {
                    Route::get('/', [TopicFileController::class, 'index'])->name('.index');
                    Route::get('/create', [TopicFileController::class, 'create'])->name('.create');
                    Route::post('/store', [TopicFileController::class, 'store'])->name('.store');
                    Route::delete('/{file}', [TopicFileController::class, 'destroy'])->name('.destroy');
                });
            });
            
        });

        Route::group(['prefix' => '{subcategories}/videos', 'as' => '.videos'], function () {
            Route::get('/', [VideoController::class, 'index'])->name('.index');
            Route::get('/create', [VideoController::class, 'create'])->name('.create');
            Route::post('/store', [VideoController::class, 'store'])->name('.store');
            Route::get('/{item}/edit', [VideoController::class, 'edit'])->name('.edit');
            Route::put('/{item}/update', [VideoController::class, 'update'])->name('.update');
            Route::delete('/{item}', [VideoController::class, 'destroy'])->name('.destroy');
        });

        Route::group(['prefix' => '{subcategories}/little-video-rolics', 'as' => '.little-video-rolics'], function () {
            Route::get('/', [LittleVideoRolicController::class, 'index'])->name('.index');
            Route::get('/create', [LittleVideoRolicController::class, 'create'])->name('.create');
            Route::post('/store', [LittleVideoRolicController::class, 'store'])->name('.store');
            Route::get('/{item}/edit', [LittleVideoRolicController::class, 'edit'])->name('.edit');
            Route::put('/{item}/update', [LittleVideoRolicController::class, 'update'])->name('.update');
            Route::delete('/{item}', [LittleVideoRolicController::class, 'destroy'])->name('.destroy');
        });

        Route::group(['prefix' => '{subcategories}/interview-preparations', 'as' => '.interview-preparations'], function () {
            Route::get('/', [InterviewPreparationController::class, 'index'])->name('.index');
            Route::get('/create', [InterviewPreparationController::class, 'create'])->name('.create');
            Route::post('/store', [InterviewPreparationController::class, 'store'])->name('.store');
            Route::get('/{item}/edit', [InterviewPreparationController::class, 'edit'])->name('.edit');
            Route::put('/{item}/update', [InterviewPreparationController::class, 'update'])->name('.update');
            Route::delete('/{item}', [InterviewPreparationController::class, 'destroy'])->name('.destroy');
        });

         Route::group(['prefix' => '{subcategories}/exam-categories', 'as' => '.exam-categories'], function () {
                Route::get('/', [ExamCategoryController::class, 'index'])->name('.index');
                Route::get('/create', [ExamCategoryController::class, 'create'])->name('.create');
                Route::post('/store', [ExamCategoryController::class, 'store'])->name('.store');
                Route::get('/{examCategory}/edit', [ExamCategoryController::class, 'edit'])->name('.edit');
                Route::put('/{examCategory}/update', [ExamCategoryController::class, 'update'])->name('.update');
                Route::delete('/{examCategory}/destroy', [ExamCategoryController::class, 'destroy'])->name('.destroy');
        });

        Route::group(['prefix' => '{subcategories}/exam-statuses', 'as' => '.exam-statuses'], function () {
                Route::get('/', [ExamStatusController::class, 'index'])->name('.index');
                Route::get('/create', [ExamStatusController::class, 'create'])->name('.create');
                Route::post('/store', [ExamStatusController::class, 'store'])->name('.store');
                Route::get('/{examStatus}/edit', [ExamStatusController::class, 'edit'])->name('.edit');
                Route::put('/{examStatus}/update', [ExamStatusController::class, 'update'])->name('.update');
                Route::delete('/{examStatus}/destroy', [ExamStatusController::class, 'destroy'])->name('.destroy');
        });


        Route::group(['prefix' => '{subcategories}/exams', 'as' => '.exams'], function () {
            Route::get('/', [ExamController::class, 'index'])->name('.index');
            Route::get('/create', [ExamController::class, 'create'])->name('.create');
            Route::post('/store', [ExamController::class, 'store'])->name('.store');
            Route::get('/{item}/edit', [ExamController::class, 'edit'])->name('.edit');
            Route::put('/{item}/update', [ExamController::class, 'update'])->name('.update');
            Route::delete('/{item}', [ExamController::class, 'destroy'])->name('.destroy');
            Route::group(['prefix' => '{exam}/questions', 'as' => '.questions'], function () {
                Route::get('/', [ExamQuestionController::class, 'index'])->name('.index');
                Route::get('/create', [ExamQuestionController::class, 'create'])->name('.create');
                Route::post('/store', [ExamQuestionController::class, 'store'])->name('.store');
                Route::get('/{question}/edit', [ExamQuestionController::class, 'edit'])->name('.edit');
                Route::put('/{question}/update', [ExamQuestionController::class, 'update'])->name('.update');
                Route::delete('/{question}', [ExamQuestionController::class, 'destroy'])->name('.destroy');
            });
        });


       


        Route::group(['prefix' => '{subcategories}/essay-examples', 'as' => '.essay-examples'], function () {
            Route::get('/', [EssayExampleController::class, 'index'])->name('.index');
            Route::get('/create', [EssayExampleController::class, 'create'])->name('.create');
            Route::post('/store', [EssayExampleController::class, 'store'])->name('.store');
            Route::get('/{item}/edit', [EssayExampleController::class, 'edit'])->name('.edit');
            Route::put('/{item}/update', [EssayExampleController::class, 'update'])->name('.update');
            Route::delete('/{item}', [EssayExampleController::class, 'destroy'])->name('.destroy');
        });

        Route::group(['prefix' => '{subcategories}/critical-readings', 'as' => '.critical-readings'], function () {
            Route::get('/', [CriticalReadingController::class, 'index'])->name('.index');
            Route::get('/create', [CriticalReadingController::class, 'create'])->name('.create');
            Route::post('/store', [CriticalReadingController::class, 'store'])->name('.store');
            Route::get('/{item}/edit', [CriticalReadingController::class, 'edit'])->name('.edit');
            Route::put('/{item}/update', [CriticalReadingController::class, 'update'])->name('.update');
            Route::delete('/{item}', [CriticalReadingController::class, 'destroy'])->name('.destroy');
        });


        Route::group(['prefix' => '{subcategories}/past-exam-questions', 'as' => '.past-exam-questions'], function () {
            Route::get('/', [PastExamQuestionController::class, 'index'])->name('.index');
            Route::get('/create', [PastExamQuestionController::class, 'create'])->name('.create');
            Route::post('/store', [PastExamQuestionController::class, 'store'])->name('.store');
            Route::get('/{item}/edit', [PastExamQuestionController::class, 'edit'])->name('.edit');
            Route::put('/{item}/update', [PastExamQuestionController::class, 'update'])->name('.update');
            Route::delete('/{item}', [PastExamQuestionController::class, 'destroy'])->name('.destroy');
        });


        
        Route::group(['prefix' => '{subcategories}/packets', 'as' => '.packets'], function () {
            Route::get('/', [SubCategoryPacketController::class, 'index'])->name('.index');
            Route::get('/create', [SubCategoryPacketController::class, 'create'])->name('.create');
            Route::post('/store', [SubCategoryPacketController::class, 'store'])->name('.store');
            Route::get('/{packet}/edit', [SubCategoryPacketController::class, 'edit'])->name('.edit');
            Route::put('/{packet}/update', [SubCategoryPacketController::class, 'update'])->name('.update');
            Route::delete('/{packet}', [SubCategoryPacketController::class, 'destroy'])->name('.destroy');
        });
    });



    Route::group(['prefix' => 'settings', 'as' => '.settings'], function () {
        Route::get('/', [SiteSettingController::class, 'index'])->name('.index');
        Route::put('/{item}/update', [SiteSettingController::class, 'update'])->name('.update');
    });


    Route::group(['prefix' => 'free_online_lessons', 'as' => '.free_online_lessons'], function () {
        Route::get('/', [FreeOnlineLessonController::class, 'index'])->name('.index');
        Route::get('/create', [FreeOnlineLessonController::class, 'create'])->name('.create');
        Route::post('/store', [FreeOnlineLessonController::class, 'store'])->name('.store');
        Route::get('/{item}/edit', [FreeOnlineLessonController::class, 'edit'])->name('.edit');
        Route::put('/{item}/update', [FreeOnlineLessonController::class, 'update'])->name('.update');
        Route::delete('/{item}', [FreeOnlineLessonController::class, 'delete'])->name('.destroy');
    });


    Route::group(['prefix' => 'important_links', 'as' => '.important_links'], function () {
        Route::get('/', [ImportantLinkController::class, 'index'])->name('.index');
        Route::get('/create', [ImportantLinkController::class, 'create'])->name('.create');
        Route::post('/store', [ImportantLinkController::class, 'store'])->name('.store');
        Route::get('/{item}/edit', [ImportantLinkController::class, 'edit'])->name('.edit');
        Route::put('/{item}/update', [ImportantLinkController::class, 'update'])->name('.update');
        Route::delete('/{item}', [ImportantLinkController::class, 'delete'])->name('.destroy');
    });

    Route::group(['prefix' => 'educational-regions', 'as' => '.educational_regions'], function () {
        Route::get('/', [EducationalRegionController::class, 'index'])->name('.index');
        Route::get('/create', [EducationalRegionController::class, 'create'])->name('.create');
        Route::post('/store', [EducationalRegionController::class, 'store'])->name('.store');
        Route::get('/{item}/edit', [EducationalRegionController::class, 'edit'])->name('.edit');
        Route::put('/{item}/update', [EducationalRegionController::class, 'update'])->name('.update');
        Route::delete('/{item}', [EducationalRegionController::class, 'delete'])->name('.destroy');
    });

    Route::group(['prefix' => 'partners', 'as' => '.partners'], function () {
        Route::get('/', [PartnerController::class, 'index'])->name('.index');
        Route::get('/create', [PartnerController::class, 'create'])->name('.create');
        Route::post('/store', [PartnerController::class, 'store'])->name('.store');
        Route::get('/{item}/edit', [PartnerController::class, 'edit'])->name('.edit');
        Route::put('/{item}/update', [PartnerController::class, 'update'])->name('.update');
        Route::delete('/{item}', [PartnerController::class, 'delete'])->name('.destroy');
    });


    Route::group(['prefix' => 'exam-banner', 'as' => '.exam_banner'], function () {
        Route::get('/', [ExamBannerController::class, 'index'])->name('.index');
        Route::put('/{item}/update', [ExamBannerController::class, 'update'])->name('.update');
    });


    Route::group(['prefix' => 'website-likes', 'as' => '.website_likes'], function () {
        Route::get('/', [WebsiteLikeController::class, 'index'])->name('.index');
    });


    Route::post('/update-status', [GeneralController::class, 'updateStatus']) ->name('.update-status');
    Route::post('/all/update-order', [GeneralController::class, 'updateOrder'])->name('.all.update-order');
});



