<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Admin;
use App\Models\Warehouse;

class Warehouses extends Component
{
    public $warehouse_id,$ware_house_address,$lat,$lng,$admins;
    public $admin_ids = [];
    public $status='active';
    
    public function store(){
        $validateData = $this->validate(['status'=>'required','ware_house_address'=>'required|max:180|min:6']);
        $validateData['address'] = $this->ware_house_address;
        $validateData['lat'] = $this->lat;
        $validateData['lng'] = $this->lng;
        $validateData['admin_ids'] = implode(',',$this->admin_ids);
        unset($validateData['ware_house_address']);
        
        Warehouse::updateOrCreate(['id' => $this->warehouse_id],$validateData);

        session()->flash('message', 'Warehouse successfully saved.');

        return redirect()->to('admin/warehouses');
    }

    public function mount($id='')
    {
        if(!empty($id)){
            $warehousedata = Warehouse::find($id);
            $this->warehouse_id = $id;
            $this->ware_house_address = $warehousedata->address; 
            $this->admin_ids = explode(',',$warehousedata->admin_ids); 
            $this->status = $warehousedata->status;  
        }
        $this->admins = Admin::whereRole('subadmin')->get();
    }

    public function render()
    {
        return view('livewire.warehouses');
    }
}
