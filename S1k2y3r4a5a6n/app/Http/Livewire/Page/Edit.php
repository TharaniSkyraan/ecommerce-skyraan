<?php

namespace App\Http\Livewire\Page;

use Livewire\Component;
use App\Models\PageContent;

class Edit extends Component
{
    public $page_id, $content, $name;

    public function store()
    {
        PageContent::where('id',$this->page_id)->update(['content'=>$this->content]);
        session()->flash('message', 'Page content successfully changed.');
        
        return redirect()->to('admin/pages');
    }

    public function mount($page_id)
    {
        $page = PageContent::find($page_id);
        $this->page_id = $page_id;
        $this->name = $page->name;
        $this->content = $page->content;
    }

    public function render()
    {
        return view('livewire.page.edit');
    }

}
