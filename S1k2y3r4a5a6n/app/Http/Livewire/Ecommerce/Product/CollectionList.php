<?php

namespace App\Http\Livewire\Ecommerce\Product;

use Livewire\Component;
use App\Models\Collection;

class CollectionList extends Component
{
    public function render()
    {
        $collections = Collection::whereStatus('active')
                                ->get()->each(function ($items) {
                                    $items->append(['product_slug','product_created']);
                                })->toArray();
                
        $midpoint = ceil(count($collections) / 2);

        // Split the array into two arrays
        $this->collection1 = array_slice($collections, 0, $midpoint);
        $this->collection2 = array_slice($collections, $midpoint);

        return view('livewire.ecommerce.product.collection-list');
    }
}
