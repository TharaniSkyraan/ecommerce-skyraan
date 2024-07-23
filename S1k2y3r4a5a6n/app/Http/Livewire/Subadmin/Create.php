<?php

namespace App\Http\Livewire\Subadmin;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use App\Models\Module;
use App\Models\Warehouse;

class Create extends Component
{
    use WithFileUploads;
    public $subadmin_id,$name,$email,$password,$profile_photo_path,$temp_profile_photo_path,$modules,$warehouses;
    public $privileges = [];
    public $warehouse_ids = [];
    public $selected_warehouse_ids = [];
    public $password_input = 'show';

    /**
     * Store new or existing category in database
     */
    public function store()
    {

        $privileges = array_keys(array_filter(array_filter($this->privileges, function($key) {
            return $key !== "";
        }, ARRAY_FILTER_USE_KEY)));

        $rules = [
            'email' => 'required|max:180|unique:admins,email,'.$this->subadmin_id.',id',
            'name' => 'required|max:180'
        ];
        if(!empty($this->subadmin_id))
        {
            if(!empty($this->profile_photo_path)){
                $rules['profile_photo_path'] = 'required|image|max:1024';
            }
        }else{
            $rules['profile_photo_path'] = 'required|image|max:1024';
            $rules['password'] = 'required|max:12|min:8';
        }
        $validateData = $this->validate($rules);
        
        if(!empty($this->password))
        {
            $validateData['password'] = Hash::make($this->password);
        }
        if(!empty($this->profile_photo_path)){
            $filename = $this->profile_photo_path->store('subadmins','public');
            $validateData['profile_photo_path'] = $filename;
        }
        $validateData['privileges'] = implode(',',$privileges);
        $subadmin = Admin::updateOrCreate(
            ['id' => $this->subadmin_id],
            $validateData
        );
        $this->subadmin_id = $subadmin->id;

        $selected_warehouse_ids = $this->selected_warehouse_ids;
        $unselected_warehouse_ids = $warehouse_ids = $this->warehouse_ids;
        foreach($selected_warehouse_ids as $warehouse_id)
        {
            if(!in_array($warehouse_id,$warehouse_ids)){
                $warehouse = Warehouse::find($warehouse_id);
                $admin_ids = explode(',',$warehouse->admin_ids);
                array_push($admin_ids, $this->subadmin_id);
                $warehouse->admin_ids = implode(',',$admin_ids);
                $warehouse->save();
            }
        }
        $unselected_warehouse_ids = array_diff($warehouse_ids, $selected_warehouse_ids);

        foreach($unselected_warehouse_ids as $warehouse_id)
        {        
            $warehouse = Warehouse::find($warehouse_id);
            $admin_ids = explode(',',$warehouse->admin_ids);
            $admin_ids = array_diff($admin_ids, [$this->subadmin_id]);
            $warehouse->admin_ids = implode(',',$admin_ids);
            $warehouse->save();
        }

        session()->flash('message', 'Admin successfully saved.');
        
        return redirect()->to('admin/subadmin');
    }
    
    public function render()
    {
        $this->modules = Module::whereParentId(0)->orderBy('sort','asc')->get();
        
        return view('livewire.subadmin.create');
    }
    public function changePassword(){
        $this->password_input = 'show';
    }

    public function mount($subadmin_id)
    {
        $this->subadmin_id = $subadmin_id;

        if(!empty($subadmin_id)){
            $subadmin = Admin::find($subadmin_id);
            $this->name = $subadmin->name;
            $this->temp_profile_photo_path = $subadmin->profile_photo_path;
            $this->email = $subadmin->email;
            $this->privileges =  array_fill_keys(explode(',',$subadmin->privileges), true);
            $this->warehouse_ids = Warehouse::whereRaw('FIND_IN_SET(?, admin_ids)', [$subadmin_id])->whereStatus('active')->pluck('id')->toArray();
            $this->selected_warehouse_ids = $this->warehouse_ids;
            $this->password_input = 'hide';
        }
        $this->warehouses = Warehouse::whereStatus('active')->get();
    }

}