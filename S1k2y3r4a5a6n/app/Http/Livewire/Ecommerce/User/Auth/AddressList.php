<?php

namespace App\Http\Livewire\Ecommerce\User\Auth;

use Livewire\Component;
use App\Models\SavedAddress;
use App\Traits\ZoneConfig;

class AddressList extends Component
{
    use ZoneConfig;
    protected $listeners = ['addressList','remove','setdefaultAddress'];
    public function addressList()
    {
        $this->addresses = SavedAddress::whereUserId(auth()->user()->id)->get()->toArray();

        $zone = \Session::get('zone_config');
        $address_id = (auth()->user()->usercart->address->id??(auth()->user()->address->id??0));
        
        if(empty($zone['address_id']) || ($zone['address_id'] != $address_id)){
            $address = SavedAddress::find($address_id);
            $data = array(
                'address_id' => $address->id??'',
                'city' => $address->city??'', 
                'latitude' => '', 
                'longitude' => '', 
                'postal_code' => $address->postal_code??''
            );      
            $result = $this->configzone($data); 
            session(['zone_config' => $result]);
            view()->share('zone_data',\Session::get('zone_config'));
        }

    }

    public function edit($id=''){
        $this->emit('editAddress',$id);
    }

    public function remove($id=''){
        $address = SavedAddress::find($id);
        if($address->is_default=='yes'){
            $updatedefault = SavedAddress::where('id','!=',$id)->first();
            if(isset($updatedefault)){
                SavedAddress::where('id',$updatedefault->id)->update(['is_default'=>'yes']);
                SavedAddress::where('id',$id)->update(['is_default'=>'no']);
            }
        }
        $address->delete();
        $this->emit('delateAddressToast', 'Address deleted successfully!.');
        $this->addressList();
    }

    public function setdefaultAddress($id=''){
        SavedAddress::where('id','!=',$id)->update(['is_default'=>'no']);
        SavedAddress::where('id',$id)->update(['is_default'=>'yes']);
        $this->emit('setdefaultAddressToast', 'Address saved successfully!.');
        $this->addressList();
    }
    
    public function mount($from='')
    {
        $this->from = $from;
        $this->addressList();
    }

    public function render()
    {
        return view('livewire.ecommerce.user.auth.address-list');
    }
}
