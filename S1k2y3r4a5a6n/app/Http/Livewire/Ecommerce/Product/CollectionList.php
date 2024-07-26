<?php

namespace App\Http\Livewire\Ecommerce\Product;

use Livewire\Component;
use App\Models\Collection;
use App\Models\ProductStock;

class CollectionList extends Component
{

    public $warehouse_ids = [];
    
    public function render()
    {
        $zone = \Session::get('zone_config');
        $this->warehouse_ids = array_filter(explode(',',$zone['warehouse_ids']));
        $collections = Collection::whereStatus('active')
                                ->get()->each(function ($items) {
                                    $items['product_stock'] = ProductStock::whereProductId(array_values(array_filter(explode(',',$items->product_ids))))
                                                                            ->whereIn('warehouse_id', $this->warehouse_ids)
                                                                            ->pluck('id')->first();

                                    $items->append(['product_slug','product_created']);
                                })->toArray();

        $this->collections = array_filter($collections, function($collection) {
            return $collection['product_stock'] !== null;
        });
        return view('livewire.ecommerce.product.collection-list');
    }
}
