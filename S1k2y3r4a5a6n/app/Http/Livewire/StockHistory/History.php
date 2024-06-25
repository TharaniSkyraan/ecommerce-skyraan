<?php

namespace App\Http\Livewire\StockHistory;

use Livewire\Component;
use App\Models\StockHistory;

class History extends Component
{
    public $stock_history;
    
    public function mount($history_id){
        $this->stock_history = StockHistory::findOrFail($history_id);
    }

    public function render()
    {
        return view('livewire.stock-history.history');
    }
}
