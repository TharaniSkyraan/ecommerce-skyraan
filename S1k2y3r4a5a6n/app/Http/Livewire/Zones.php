<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Zone;
use App\Models\Warehouse;

class Zones extends Component
{
    public $coordinates = '[]';
    public $zone_coordinates = [];
    public $zone_id,$zone,$lat,$lng,$warehouses;
    public $warehouse_ids = [];
    public $status='active';

    public function store()
    {
        $validateData = $this->validate(['zone_coordinates'=>'required','warehouse_ids'=>'required','status'=>'required','zone'=>'required|max:180|min:10']);
        $validateData['zone_coordinates'] = json_encode($this->zone_coordinates);
        $validateData['lat'] = $this->lat;
        $validateData['lng'] = $this->lng;
        $validateData['warehouse_ids'] = implode(',',$this->warehouse_ids);
        Zone::updateOrCreate(['id' => $this->zone_id],$validateData);

        session()->flash('message', 'Zone successfully saved.');

        return redirect()->to('admin/zones');
    }

    public function mount($id='')
    {
        if(!empty($id)){
            $zonedata = Zone::find($id);
            $this->coordinates = $zonedata->zone_coordinates;
            $this->zone_coordinates = json_decode($zonedata->zone_coordinates);
            $this->zone_id = $id;
            $this->zone = $zonedata->zone; 
            $this->warehouse_ids = explode(',',$zonedata->warehouse_ids); 
            $this->status = $zonedata->status;  
        }
        $this->warehouses = Warehouse::get();
    }

    public function render()
    {
        return view('livewire.zones');
    }
}
