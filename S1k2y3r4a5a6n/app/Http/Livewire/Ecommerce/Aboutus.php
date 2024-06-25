<?php

namespace App\Http\Livewire\Ecommerce;

use Livewire\Component;
use App\Models\PageContent;

class Aboutus extends Component
{
    public $page_cnt;

    public function render()
    {
        $this->page_cnt =PageContent::where('name','about_us')->get();
        return view('livewire.ecommerce.aboutus');
    }
}
