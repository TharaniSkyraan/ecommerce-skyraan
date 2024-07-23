<?php

use App\Http\Controllers\AdminController;

Route::group(['prefix'=>'admin','middleware'=>['admin:admin']],function(){
	Route::get('/login', [AdminController::class, 'loginForm']);
	Route::post('/login', [AdminController::class, 'store'])->name('admin.login');
});

Route::group(['prefix'=>'admin','namespace'=>'App\Http\Controllers\Admin','middleware'=>['auth:sanctum,admin', 'verified']],function(){
    Route::name('admin.')->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');
        
        Route::get('subadmin/fetchData','SubadminController@fetchData')->name('fetch.subadmin.data');
        Route::resource('subadmin','SubadminController');

        Route::get('category','CategoryController@index')->name('category.index');
        Route::post('category/sort','CategoryController@sort')->name('category.sort');

        Route::get('brand/fetchData','BrandController@fetchData')->name('fetch.brand.data');
        Route::resource('brand','BrandController');

        Route::get('attribute/fetchData','AttributeController@fetchData')->name('fetch.attribute.data');
        Route::resource('attribute','AttributeController');

        Route::get('label/fetchData','LabelController@fetchData')->name('fetch.label.data');
        Route::resource('label','LabelController');

        Route::get('cancel_reasons/fetchData','CancelReasonController@fetchData')->name('fetch.cancel_reasons.data');
        Route::resource('cancel_reasons','CancelReasonController');

        Route::get('collection/fetchData','CollectionController@fetchData')->name('fetch.collection.data');
        Route::resource('collection','CollectionController');

        Route::get('banner/fetchData','BannerController@fetchData')->name('fetch.banner.data');
        Route::resource('banner','BannerController');

        Route::get('buying-option/fetchData','BuyingOptionController@fetchData')->name('fetch.buying-option.data');
        Route::resource('buying-option','BuyingOptionController');

        Route::get('coupon/fetchData','CouponController@fetchData')->name('fetch.coupon.data');
        Route::resource('coupon','CouponController');

        Route::get('tax/fetchData','TaxController@fetchData')->name('fetch.tax.data');
        Route::resource('tax','TaxController');
        
        Route::get('product/fetchData','ProductController@fetchData')->name('fetch.product.data');
        Route::resource('product','ProductController');

        Route::get('/special-product', function () { return view('admin/special-product'); })->name('special-product');

        Route::get('/settings', function () { return view('admin/settings/settings'); })->name('settings');
        
        Route::get('zones/fetchData','ZoneController@fetchData')->name('fetch.zones.data');
        Route::resource('zones','ZoneController');
       
        Route::get('orders/fetchData','OrdersController@fetchData')->name('fetch.orders.data');
        Route::resource('orders','OrdersController');

        Route::get('shipments/fetchData','ShipmentController@fetchData')->name('fetch.shipments.data');
        Route::resource('shipments','ShipmentController');

        Route::get('invoices/fetchData','InvoiceController@fetchData')->name('fetch.invoices.data');
        Route::resource('invoices','InvoiceController');


        Route::get('pages/fetchData','PageController@fetchData')->name('fetch.pages.data');
        Route::resource('pages','PageController');

        // Route::get('/invoices', function () { return view('admin/invoices'); })->name('invoices');
        // Route::get('/shipments', function () { return view('admin/shipments'); })->name('shipments');
        Route::get('/returns', function () { return view('admin/returns'); })->name('returns');

        Route::get('warehouses/fetchData','WarehouseController@fetchData')->name('fetch.warehouses.data');
        Route::resource('warehouses','WarehouseController');
       
        Route::get('stock-history/fetchData','StockHistoryController@fetchData')->name('fetch.stock-history.data');
        Route::get('stock-history/Data','StockHistoryController@Data')->name('fetch.data');
        Route::resource('stock-history','StockHistoryController');
        
        Route::get('manage-stock/fetchData','ManageStockController@fetchData')->name('fetch.manage-stock.data');
        Route::resource('manage-stock','ManageStockController');
        
        Route::get('sales-dashboard/fetchData','ManageStockController@fetchData')->name('fetch.sales-dahbsoard.data');
        Route::resource('sales-dashboard','ManageStockController');
        
        Route::get('sales-report/fetchData','ManageStockController@fetchData')->name('fetch.sales-report.data');
        Route::resource('sales-report','ManageStockController');

	    Route::post('/logout', [AdminController::class, 'destroy'])->name('logout');

    });

});
