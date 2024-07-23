<?php

namespace App\Http\Livewire\Ecommerce\Product;

use Livewire\Component;
use App\Models\Collection;

class CollectionList extends Component
{
    public function render()
    {
        $this->collections = Collection::whereStatus('active')
                                ->get()->each(function ($items) {
                                    $items->append(['product_slug','product_created']);
                                })->toArray();

        return view('livewire.ecommerce.product.collection-list');
    }
}
