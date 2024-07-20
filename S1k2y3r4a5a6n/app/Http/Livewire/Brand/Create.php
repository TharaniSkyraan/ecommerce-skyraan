<?php

namespace App\Http\Livewire\Brand;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Brand;
use App\Models\Category;

class Create extends Component
{
    use WithFileUploads;
    public $brand_id,$name,$image,$temp_image,$description,$status,$slug, $categories,$website_link;
    public $category_ids = [];

    /**
     * Store new or existing category in database
     */
    public function store()
    {

        $category_ids = array_keys(array_filter(array_filter($this->category_ids, function($key) {
            return $key !== "";
        }, ARRAY_FILTER_USE_KEY)));

        $rules = [
            'name' => 'required|max:180|unique:brands,name,'.$this->brand_id.',id,deleted_at,NULL',
            'description' => 'required|max:180', 
            'status' => 'required',
            'website_link' => 'nullable|url|max:180'
        ];
        if(!empty($this->brand_id))
        {
            if(!empty($this->image)){
                $rules['image'] = 'required|image|max:1024';
            }
        }else{
            $rules['image'] = 'required|image|max:1024';
        }
        $validateData = $this->validate($rules);
        if(!empty($this->image)){
            $filename = $this->image->store('brands','public');
            $validateData['image'] = $filename;
        }
        $validateData['category_ids'] = implode(',',$category_ids);
        $validateData['slug'] = str_replace(' ','-',strtolower($this->name));
        $brand = Brand::updateOrCreate(
            ['id' => $this->brand_id],
            $validateData
        );
        $this->brand_id = $brand->id;
        session()->flash('message', 'Brand successfully saved.');
        
        return redirect()->to('admin/brand');
    }
    
    public function render()
    {
        $this->categories = Category::whereNULL('parent_id')->orderBy('sort','asc')->whereStatus('active')->get();
        return view('livewire.brand.create');
    }

    public function mount($brand_id)
    {
        $this->brand_id = $brand_id;

        if(!empty($brand_id)){
            $brand = Brand::find($brand_id);
            $this->name = $brand->name;
            $this->temp_image = $brand->image;
            $this->description = $brand->description;
            $this->website_link = $brand->website_link;
            $this->category_ids =  array_fill_keys(explode(',',$brand->category_ids), true);
            $this->slug = $brand->slug;
            $this->status = $brand->status;
        }
    }

}