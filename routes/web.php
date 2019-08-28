<?php
use App\Services\DownloadFilesService;

//* CREDENTIALS ROUTE
Auth::routes();

Route::post('mail/unpaid', 'Backend\TicketOrder\TicketOrderController@EmailUnpaid')->name('mail.unpaid');


// SOCMED INTEGRATION
Route::namespace('Auth')->group(function(){
  Route::get('auth/{provider}', 'AuthController@redirectToProvider')->name('auth.login');
  Route::get('auth/{provider}/callback', 'AuthController@handleProviderCallback')->name('auth.redirect');
  Route::get('register/{token}','RegisterController@activating')->name('activating-account');
  // Route::get('registera/','RegisterController@activating')->name('activating-account');
  Route::get('backend', 'LoginController@showBackendLoginForm')->name('login.backend');
  Route::post('backend', 'LoginController@loginBackend')->name('backend');
});

// Route::get('login/{id_produk}', 'Auth\LoginController@showLoginForm')->name('login.redirect');
// Route::post('login/redirect', 'Auth\LoginController@login')->name('login.repost');

// Route::get('session', 'Others\SessionController@index')->name('home');
// Route::post('profile/assign', 'Frontend\EticketingController@assign_user_profile')->name('assign.profile');
// Route::post('assign/payment', 'Frontend\EticketingController@assign_payment')->name('assign.payment');
// Route::post('assign/order', 'Frontend\EticketingController@assign_order')->name('assign.order');
// Route::post('cart/total_price', 'Frontend\EticketingController@total')->name('cart.total_price');
// Route::get('riwayat/pemesanan', 'Frontend\EticketingController@history_order')->name('riwayat.pemesanan');


Route::get('/invoicepdf',function(){

});


Route::namespace('Frontend')->group(function(){
  // ESPAY
  Route::post('inquiry', 'EticketingController@inquiry')->name('espay.inquiry');
  Route::post('payment', 'EticketingController@payment_notif')->name('espay.payment');
  Route::post('payment_status', 'EticketingController@payment_status')->name('espay.payment_status');
  Route::post('payment_status2', 'EticketingController@payment_status2')->name('espay.payment_status2');

  Route::post('get_order_value', 'EticketingController@get_order_value')->name('espay.get_order_value');
  Route::post('payment_success', 'EticketingController@payment_success')->name('espay.payment_success');


  Route::post('prepare_order', 'TmiiEtOrderedTicketTxController@store')->name('payment.prepare_order')->middleware('cors');


      Route::get('cobaakses','TmiiEtOrderedTicketTxController@cobaakses');
  Route::get('indexticket','TmiiEtOrderedTicketTxController@index');
    Route::get('mailticket','TmiiEtOrderedTicketTxController@sendmail');
  Route::get('testemail','TmiiEtOrderedTicketTxController@sendEmail');
  Route::get('testemail2','EticketingController@testEmailControllerLain');
  Route::post('get_order', 'TmiiEtOrderedTicketTxController@show')->name('payment.get_order');
  Route::post('gensignature', 'EticketingController@genSignature')->name('espay.genSignature');
  Route::post('sendinv', 'EticketingController@sendInv')->name('espay.sendinv');

    Route::post('sendinv2', 'EticketingController@sendInv2')->name('espay.sendinv2');
  Route::post('closeinv', 'EticketingController@closeInv')->name('espay.closeinv');
  Route::post('updateinvstatus', 'EticketingController@updateINVStatus')->name('espay.updateinvstatus');

  //* FRONTEND HOME
  Route::get('/', 'HomeController@homePage');
  Route::get('promotion/view/{id}/{login?}', 'HomeController@viewPromotion')->name('promo.view');
  Route::get('promotion/{new?}', 'HomeController@promotion')->name('promotion');
  Route::get('product/view/{id}/{login?}', 'HomeController@viewProduct')->name('product.view');
  Route::get('product/{new?}', 'HomeController@product')->name('product');
  Route::post('ticket/check_price', 'HomeController@checkPrice')->name('ticket.check_price');

  //* FRONTEND CART & CHECKING ITEM
  Route::prefix('cart')->group(function(){
    Route::get('/', 'CartController@index')->name('cart');
    Route::post('list', 'CartController@cartUpdate')->name('cart.list');
    Route::post('remove', 'CartController@removeCartBd')->name('cart.remove');
  });

  Route::get('/send/email', 'EticketingController@test')->name('test');
    Route::get('testinginvoice', 'Frontend\EticketingController@testing')->name('testinginvoice');
  Route::get('/create_ticket/{a}/{b}', 'EticketingController@create_ticket')->name('create_ticket');
  Route::post('', 'CartController@addToCart')->name('ticket.add_to_cart');






});



// Route::get('reservasi', 'Backend\ReservationController@index')->name('reservation');


//******************************************CMS****************************************// BACKEND
Route::middleware('auth:administrator')->namespace('Backend')->group(function(){
  Route::get('dashboard', 'DashboardController@index')->name('dashboard');

  // SETUP EVENT CALENDAR
  Route::namespace('SetupEventCalendar')->group(function(){
    Route::get('setup/event', 'SetupEventCalendarController@index')->name('event');
    Route::get('api/get/setup_calendar', 'ApiSetupEventCalendarController@apiGetEventCalendar')->name('api.get.setup_calendar');
    Route::get('api/get/modal_setup_calendar/{start}/{end}', 'ApiSetupEventCalendarController@apiGetModalEventCalendar')->name('api.get.modal_setup_calendar');
    Route::post('store/setup_calendar/insert', 'SetupEventCalendarController@storeInsertSetupCalendar')->name('store.setup_calendar.insert');
    Route::post('store/setup_calendar/delete', 'SetupEventCalendarController@storeUpdateSetupCalendar')->name('store.setup_calendar.delete');
  });

  // SETUP PROMOTION
  Route::namespace('MasterPromotion')->group(function(){
    Route::get('master/promotion', 'MasterPromotionController@index')->name('master.promotion');
    Route::post('store/master_promotion/insert', 'ApiMasterPromotionController@storePromotion')->name('store.master_promotion.insert');
    Route::post('store/master_promotion/change_state', 'ApiMasterPromotionController@changeStatePromotion')->name('promo.img.state');
    Route::get('master/promotion/download/template', 'MasterPromotionController@templateImage')->name('promo.download.template');
  });
  // SETUP PRODUCT
  Route::namespace('MasterProduct')->group(function(){
    Route::get('master/product', 'MasterProductController@index')->name('master.product');
    Route::post('store/master_product/insert', 'ApiMasterProductController@storeProduct')->name('store.master_product.insert');
  });

  // SETUP USER
  Route::namespace('SetupUserManagement')->group(function(){
    Route::get('setup/user_management', 'SetupUserManagementController@index')->name('setup.user');
    Route::post('store/user/update', 'ApiSetupUserManagementController@updateUser')->name('store.user.update');
    Route::post('store/user/create', 'ApiSetupUserManagementController@createUserModal')->name('store.user.create');
    Route::post('store/user/create/save', 'SetupUserManagementController@createUser')->name('store.user.create.save');
  });

  // SETUP ORGANIZATION
  Route::namespace('SetupOrgManagement')->group(function(){
    Route::get('setup/org_management', 'SetupOrgManagementController@index')->name('setup.org');
    Route::post('store/org/update', 'ApiSetupOrgManagementController@updateOrg')->name('store.org.update');
    Route::post('store/org/create', 'ApiSetupOrgManagementController@createOrgModal')->name('store.org.create');
    Route::post('store/org/create/save', 'SetupOrgManagementController@createOrg')->name('store.org.create.save');
  });

  // ORDERED TICKET
  Route::get('ticket/order', 'TicketOrder\TicketOrderController@index')->name('ticket.order');
  // GENERATED TICKET
  Route::prefix('ticket')->namespace('Ticket')->group(function (){
    Route::get('/', 'TicketController@index')->name('ticket');
    Route::post('regenerate', 'TicketController@regenerate')->name('ticket.regenerate');
    Route::post('email/resend', 'TicketController@resend')->name('ticket.email.resend');
    Route::post('download', 'TicketController@download')->name('ticket.download');
    Route::get('scan', 'TicketController@scan')->name('ticket.scan');
    Route::get('temporary_process_paid_all_cart_order', 'TicketController@temporary_process_paid_all_cart_order')->name('ticket.temporary_process_paid_all_cart_order');
    Route::post('scan/process', 'ApiTicketController@scanningTicket')->name('ticket.scanning');
    Route::post('scan/redeem', 'ApiTicketController@redeemTicket')->name('ticket.redeem');
  });

  // REPORT
  Route::prefix('report')->group(function(){
    Route::get('dashboard_penjualan', 'Report\DashboardPenjualanController@index')->name('dashboard.penjualan');
    Route::get('sales', 'ReportSales\ReportSalesController@index')->name('report.sales');
    Route::get('customer', 'ReportCustomer\ReportCustomerController@index')->name('report.customer');
  });

  //OTHER STUFF BACKEND
  Route::get('download/template/{type}', function(DownloadFilesService $download, $type) {
    return $download->templateImage($type);
  })->name('download.template');

  // DATATABLE STUFF
  Route::post('api/get/{module}',  function ($module, Request $request) {
    return App::call('\App\Http\Controllers\Backend\\'.$module.'\Api'.$module.'Controller@getDataTable');
  })->name('datatable.api');

  Route::post('api/get/detail/{module}',  function ($module, Request $request) {
    return App::call('\App\Http\Controllers\Backend\\'.$module.'\Api'.$module.'Controller@getDetailDataTable');
  })->name('datatable.api.details');
});

Route::view('error/403', 'errors.403')->name('error.403');
