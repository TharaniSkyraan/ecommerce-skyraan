<?php

namespace App\Http\Livewire\Attribute;

use Livewire\Component;
use Livewire\WithFileUploads;

use App\Models\Attribute;
use App\Models\AttributeSet;
use App\Models\Category;

class Create extends Component
{
    use WithFileUploads;
    public $attribute_id,$name,$slug, $categories,$is_default;
    public $category_ids = [];
    public $attributeList = [];
    public $status='active';
    
    public function addRow()
    {
        $this->attributeList[] = ['id' => '', 'is_default' => 'no', 'name' => '', 'color' => '#ffffff00', 'image' => null, 'temp_image' => null];
    }

    
    public function removeRow($index)
    {
        if(!empty($this->attributeList[$index]['attribute_id'])){
            AttributeSet::where('id',$this->attributeList[$index]['id'])->delete();
        }
        unset($this->attributeList[$index]);
        $this->attributeList = array_values($this->attributeList); // Re-index the array
    }
    
    public function store()
    {

        $category_ids = array_keys(array_filter(array_filter($this->category_ids, function($key) {
            return $key !== "";
        }, ARRAY_FILTER_USE_KEY)));
        $rules = [
            'name' => 'required|string|max:180|unique:attributes,name,'.$this->attribute_id.',id,deleted_at,NULL',
            'status' => 'required|in:active,inactive',
            'is_default' => 'required',
            'status' => 'required|in:active,inactive',
            'attributeList.*.name' => 'required|string|max:255',
            // 'attributeList.*.color' => 'required|string|max:7',
            // 'attributeList.*.image' => 'nullable|image|max:2048',
        ];

        $validateData = $this->validate($rules);
        $validateData['slug'] = str_replace(' ','-',strtolower($this->name));
        $validateData['category_ids'] = implode(',',$category_ids);
        $atrribute = Attribute::updateOrCreate(
            ['id' => $this->attribute_id],
            $validateData
        );
        $this->attribute_id = $atrribute->id;
        foreach($this->attributeList as $key => $attr){

            if(!empty($attr['image'])){
                $image = $attr['image'];
                $filename = $image->store('attributes','public');
                $attr['image'] = $filename;
            }else{
                $attr['image'] = $attr['temp_image']??' ';
            }
            $attr['attribute_id'] = $this->attribute_id;
            $attr['is_default'] = ($this->is_default==$key)?'yes':'no';
            $attr['slug'] = str_replace(' ','-',strtolower($attr['name']));

            AttributeSet::updateOrCreate(
                ['id' => $attr['id']],
                $attr
            );
        }

        // Your store logic here

        session()->flash('message', 'Attribute successfully saved.');

        return redirect()->to('admin/attribute');
    }

    public function mount($attribute_id)
    {
        $this->attribute_id = $attribute_id;
        $is_default = null;

        if(!empty($attribute_id)){
            $attribute = Attribute::find($attribute_id);
            $this->name = $attribute->name;
            $this->category_ids =  array_fill_keys(explode(',',$attribute->category_ids), true);
            $this->slug = $attribute->slug;
            $this->status = $attribute->status;
            $attributeList = AttributeSet::where('attribute_id',$attribute_id)->get()->toArray();
            $this->attributeList = array_map(function ($attributelist, $key) use (&$is_default) {
                                        
                                        if($attributelist['is_default']=='yes'){
                                            $is_default = $key;
                                        }
                                        $attributelist['temp_image'] = ($attributelist['image'] !=' ')?$attributelist['image']:'';
                                        $attributelist['image'] = '';
                                        return $attributelist;

                                    }, $attributeList, array_keys($attributeList));
        }
        $this->is_default = $is_default;
    }

    public function render()
    {
        $this->categories = Category::whereNULL('parent_id')->orderBy('sort','asc')->whereStatus('active')->get();
        return view('livewire.attribute.create');
    }

}