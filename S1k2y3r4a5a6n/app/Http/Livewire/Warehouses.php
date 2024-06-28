<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Admin;
use App\Models\Zone;
use App\Models\Warehouse;

class Warehouses extends Component
{
    public $warehouse_id,$ware_house_address,$lat,$lng,$admins;
    public $admin_ids = [];
    public $status='active';
    public $zone_ids = [];
    public $selected_zone_ids = [];
    
    public function store(){
        $validateData = $this->validate(['status'=>'required','ware_house_address'=>'required|max:180|min:6']);
        $validateData['address'] = $this->ware_house_address;
        $validateData['lat'] = $this->lat;
        $validateData['lng'] = $this->lng;
        $validateData['admin_ids'] = implode(',',$this->admin_ids);
        unset($validateData['ware_house_address']);
        
        $warehouse = Warehouse::updateOrCreate(['id' => $this->warehouse_id],$validateData);

        
        $this->warehouse_id = $warehouse->id;

        $selected_zone_ids = $this->selected_zone_ids;
        $unselected_zone_ids = $zone_ids = $this->zone_ids;
        foreach($selected_zone_ids as $zone_id)
        {
            if(!in_array($zone_id,$zone_ids)){
                $zone = Zone::find($zone_id);
                $warehouse_ids = explode(',',$zone->warehouse_ids);
                array_push($warehouse_ids, $this->warehouse_id);
                $zone->warehouse_ids = implode(',',$warehouse_ids);
                $zone->save();
            }
        }
        $unselected_zone_ids = array_diff($zone_ids, $selected_zone_ids);

        foreach($unselected_zone_ids as $zone_id)
        {        
            $zone = Zone::find($zone_id);
            $warehouse_ids = explode(',',$zone->warehouse_ids);
            $warehouse_ids = array_diff($warehouse_ids, [$this->warehouse_id]);
            $zone->warehouse_ids = implode(',',$warehouse_ids);
            $zone->save();
        }

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
            $this->zone_ids = Zone::whereRaw('FIND_IN_SET(?, warehouse_ids)', [$id])->whereStatus('active')->pluck('id')->toArray();
            $this->selected_zone_ids = $this->zone_ids;
        }
        $this->zones = Zone::whereStatus('active')->get();
        $this->admins = Admin::whereRole('subadmin')->get();
    }

    public function render()
    {
        return view('livewire.warehouses');
    }
}
