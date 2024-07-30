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
                        ->get()
                        ->each(function ($items) {
                            $productIds = array_values(array_filter(explode(',', $items->product_ids)));
                            $items['product_stock'] = ProductStock::whereIn('product_id', $productIds)
                                ->whereIn('warehouse_id', $this->warehouse_ids)
                                ->pluck('id')
                                ->first();
                        })
                        ->toArray();
        $collections = array_filter($collections, function($collection) {
            return $collection['product_stock'] !== null;
        });
        // Check if collections are less than 5, if so, duplicate the items to make a continuous loop
        if (count($collections) < 4) {
            $collections = array_merge($collections, $collections, $collections); // Duplicate to fill space
        } elseif (count($collections) == 1) {
            $collections = array_fill(1, 5, $collections[1]); // Duplicate single image to fill space
        }

        // dd($collections);
        $this->collections = $collections;
        return view('livewire.ecommerce.product.collection-list');
    }
}
