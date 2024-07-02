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

        $count = count($collections);
        $duplicationCount = 10 - $count;
        
        $data = $collections;
        for ($i = 0; $i < $duplicationCount; $i++) {
            $data = array_merge($data, $collections);
        }

        // Split the array into two arrays

        $result = array_chunk($data, 5);
        
        // Check the count of the last chunk
        $lastChunkIndex = count($result) - 1;
        $lastChunkCount = count($result[$lastChunkIndex]);

        // If the last chunk has fewer than 6 elements, merge it with the first chunk
        if ($lastChunkCount < 5) {
            $result[$lastChunkIndex] = array_slice(array_merge($result[0], $result[$lastChunkIndex]), 0, 5);
        }
        
        $this->collections = $result[0];
        unset($result[0]);
        $this->collections_data = $result;

        return view('livewire.ecommerce.product.collection-list');
    }
}
