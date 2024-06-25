<?php

namespace App\Http\Livewire\Settings\Tax;

use Livewire\Component;

use App\Models\Tax;
use App\Models\Setting as TaxSetting;

class Setting extends Component
{
    public $taxes,$default_tax,$is_enabled_default_tax,$shipping_tax,$is_enabled_shipping_tax;  
    
    public function mount()
    {
        $this->taxes = Tax::whereStatus('active')->get();
        $tax = TaxSetting::first();
        if(isset($tax)){
            $this->default_tax = $tax->default_tax;
            $this->is_enabled_default_tax = $tax->is_enabled_default_tax;
            $this->shipping_tax = $tax->shipping_tax;
            $this->is_enabled_shipping_tax = $tax->is_enabled_shipping_tax;
        }
    }
    public function store()
    {
        if($this->is_enabled_default_tax=='yes'){
            $this->validate(['default_tax' => 'required']);
        }
        if($this->is_enabled_shipping_tax=='yes'){
            $this->validate(['shipping_tax' => 'required']);
        }
        $data['default_tax'] = ($this->is_enabled_default_tax=='yes')?$this->default_tax:null;
        $data['is_enabled_default_tax'] = ($this->is_enabled_default_tax=='yes')?'yes':null;
        $data['shipping_tax'] = ($this->is_enabled_shipping_tax=='yes')?$this->shipping_tax:null;
        $data['is_enabled_shipping_tax'] = ($this->is_enabled_shipping_tax=='yes')?'yes':null;
        $tax = TaxSetting::updateOrCreate(['id' => 1],$data);
        session()->flash('message', 'Updated Successfully.');
    }

    public function render()
    {
        return view('livewire.settings.tax.setting');
    }


}
