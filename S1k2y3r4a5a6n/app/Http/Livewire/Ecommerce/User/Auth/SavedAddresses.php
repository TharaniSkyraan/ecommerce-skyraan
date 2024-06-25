<?php

namespace App\Http\Livewire\Ecommerce\User\Auth;

use Livewire\Component;
use App\Models\SavedAddress;
use App\Models\City;
use App\Models\State;
use App\Models\Country;

class SavedAddresses extends Component
{
    public $address_id,$address,$country,$city,$state,$landmark,$zip_code,$name,$phone,$alternative_phone;

    public $isProcessing = false;

    protected $listeners = ['editAddress','FomatAddress'];

    public function updated($propertyName)
    {
        $this->isProcessing = false;
    }
    public function editAddress($id)
    {
        $this->isProcessing = true;
        $this->resetInputvalues();
        $this->resetValidation();
        $address = SavedAddress::find($id);
        if(isset($address)){
            $this->address_id = $id;
            $this->address = $address->address;
            $this->country = $address->country;
            $this->city = $address->city;
            $this->state = $address->state;
            $this->phone = $address->phone;
            $this->alternative_phone = $address->alternative_phone;
            $this->landmark = $address->landmark;
            $this->zip_code = $address->zip_code;
            $this->name = $address->name;
        }
    }

    public function store(){

        $validatedData = $this->validate([
            'phone' => 'required|numeric|phone:IN',
            'alternative_phone' => 'nullable|numeric|phone:IN',
            'name' => 'required|string|min:3|max:30',
            'state' => 'required',
            'city' => 'required|string|min:3|max:100',
            'landmark' => 'nullable|string|min:3|max:100',
            'address' => 'required|string|min:3|max:255',
            'zip_code' => 'required|postal_code:IN',
        ], [
            'phone.required' => 'Phone number is required',
            'phone.numeric'=> 'Please enter valid Phone Number',
            'phone.phone' => 'Please enter valid Phone Number',
            'alternative_phone.required' => 'Phone number is required',
            'alternative_phone.numeric'=> 'Please enter valid Phone Number',
            'alternative_phone.phone' => 'Please enter valid Phone Number',
            'state.required' => 'State is required',
            'city.required' => 'City is required',
            'city.min' => 'City must be at least 3 characters',
            'city.max' => 'City must be less than 100 characters.',
            'address.required' => 'Address is required',
            'address.min' => 'Address must be at least 3 characters',
            'address.max' => 'Address must be less than 255 characters.',
            'landmark.min' => 'Landmark must be at least 3 characters',
            'landmark.max' => 'Landmark must be less than 255 characters.',
            'name.required' => 'Name is required',
            'name.min' => 'Name must be at least 3 characters',
            'name.max' => 'Name must be less than 30 characters.',
            'zip_code.required' => 'Zip code is required',
            'zip_code.postal_code' => 'Please enter valid zip code'
        ]);
        
        $validatedData['user_id'] = auth()->user()->id;
        $validatedData['country'] = 'IN';
        
        if(!isset(auth()->user()->address)){
            $validatedData['is_default'] = 'yes';
        }
        SavedAddress::updateOrCreate(
            ['id' => $this->address_id],
            $validatedData
        );
        $this->emit('showToast', 'Address saved successfully!.');
        $this->emit('addressList');
    }

    public function FomatAddress($address)
    {
        
        $apiKey = 'AIzaSyC5S9f4bqHOjf0DP3yeL1C32t0S609fUQM'; // Replace with your Google Geocoding API key
        
        $url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . urlencode($address) . "&key=$apiKey";
        
        // You can also use cURL for the API request if file_get_contents is disabled on your server
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        
        $decodedResponse = json_decode($response, true);

        if ($decodedResponse['status'] === 'OK') {
            $addressComponents = $decodedResponse['results'][0]['address_components'];
            $formattedAddress = $decodedResponse['results'][0]['formatted_address'];

            $this->address = $formattedAddress;
           
            foreach ($addressComponents as $component) {
                $types = $component['types'];
                $longName = $component['long_name'];
                $shortName = $component['short_name'];
                if (in_array('locality', $types)) {
                    $locality = $longName;
                } elseif (in_array('administrative_area_level_1', $types)) {
                    $region1 = $longName;
                } elseif (in_array('administrative_area_level_2', $types)) {
                    $region2 = $longName;
                }elseif (in_array('administrative_area_level_3', $types)) {
                    $region3 = $longName;
                }elseif (in_array('neighborhood', $types)) {
                    $this->landmark = $longName;
                } elseif (in_array('postal_code', $types)) {
                    $this->zip_code = $longName;
                }
            }

            // Uncomment the following code to store state information
            $this->state = isset($region1) ? trim($region1) : (isset($region2) ? $region2 : (isset($region3) ? $region3 : null)) ;
           
            // Uncomment the following code to store city information
            $this->city = isset($region3) ? trim($region3) : (isset($locality) ? $locality : (isset($region2) ? $region2 : null));
           
        } else {
            // Handle API error
        }
        
    }

    private function resetInputvalues(){      
        $this->reset(['address_id','name', 'phone', 'alternative_phone', 'city', 'state', 'address', 'landmark', 'zip_code']);  
    } 

    public function mount(){
        $this->states = State::whereCountryId(101)->get();
    }

    public function render()
    {
        return view('livewire.ecommerce.user.auth.saved-addresses');
    }

}
