<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Category;

class Categories extends Component
{
    use WithFileUploads;
    public $categories;
    public $name,$image,$status,$slug,$category_id,$parent_id,$temp_image,$logo,$temp_logo,$description,$page;
    public $edit = false;
    
    protected $listeners = ['delete'];

    /**
     * Create category or subcategory page 
     */
    public function addCategory($parent_id)
    {
        $this->resetInputvalues();
        if(!empty($parent_id)){
            $this->page ='subcategory';
        }else{
            $this->page ='category';
        }
        $this->parent_id = $parent_id;
    }
    
    /**
     * Edit category or subcategory page 
     */
    public function editCategory($id,$type){

        if($type=='category'){
            $this->page ='category';
        }else{
           $this->page ='subcategory';
        }
        $category = Category::find($id);
        $this->name = $category->name;
        $this->temp_image = $category->image;
        $this->temp_logo = $category->logo;
        $this->status = $category->status;
        $this->category_id =$id;
        $this->parent_id =$category->parent_id;
        $this->slug =$category->slug;
        $this->description = $category->description;
        $this->edit = true;
    }
    
    /**
     * Store new or existing category in database
     */
    public function storeCategory(){
        $rules = [
            'name' => 'required|max:180|unique:categories,name,'.$this->category_id??null.',id,deleted_at,NULL',
            'description' => 'required|max:180', 
            'status' => 'required'
        ];
        
        if(!empty($this->category_id))
        {
            $rules['image'] = 'nullable|image|max:1024';
            $rules['logo'] = 'nullable|image|max:1024';            
        }else{
            $rules['image'] = 'required|image|max:1024';
            $rules['logo'] = 'required|image|max:1024';
        }
        $validateData = $this->validate($rules);

        unset($validateData['image']);
        unset($validateData['logo']);
        if(!empty($this->image)){
            $filename = $this->image->store('categories','public');
            $validateData['image'] = $filename;
        }
        if(!empty($this->logo)){
            $logoname = $this->logo->store('categories','public');
            $validateData['logo'] = $logoname;
        }
        $validateData['slug'] = str_replace(' ','-',strtolower($this->name));

        Category::updateOrCreate(
            ['id' => $this->category_id],
            $validateData
        );
        if(!empty($this->category_id)){
            if(empty($this->parent_id) && $this->status =='inactive'){
                Category::whereParentId($this->category_id)->update(['status' => 'inactive']);
            }elseif(empty($this->parent_id)){
                Category::whereParentId($this->category_id)->update(['status' => 'active']);
            }
        }
        session()->flash('message', 'Category successfully saved.');
        $this->resetInputvalues();
    }

    /**
     * Store new or existing category in database
     */
    public function storeSubCategory(){
        
        if(!empty($this->category_id))
        {
            $catgeory = Category::find($this->category_id);
            $rules =[                
                'name' => 'required|max:100|unique:categories,name,'.$this->category_id.',id,deleted_at,NULL,parent_id,'.$this->parent_id,
                'status' => 'required',
                'description' => 'required|max:180', 
                'parent_id' => 'required',
                'image'=>'nullable|image|max:1024',
                'logo'=>'nullable|image|max:1024'
            ];
            
            $validateData = $this->validate($rules);
            unset($validateData['image']);
            unset($validateData['logo']);
            if(!empty($this->image)){                
                $filename = $this->image->store('files','public');
                $validateData['image'] = $filename;
            }
            if(!empty($this->logo)){                
                $logoname = $this->logo->store('files','public');
                $validateData['logo'] = $logoname;
            }
            $validateData['slug'] = str_replace(' ','-',strtolower($this->name));
            $catgeory->update($validateData);
        }else
        {
            $validateData = $this->validate([
                'name' => 'required|max:100|unique:categories,name,null,id,deleted_at,NULL,parent_id,'.$this->parent_id,
                'image' => 'required|image|max:1024',
                'logo' => 'required|image|max:1024',
                'status' => 'required',
                'description' => 'required|max:180', 
                'parent_id' => 'required'
            ]);
            $filename = $this->image->store('files','public');
            $validateData['image'] = $filename;         
            $logoname = $this->logo->store('files','public');
            $validateData['logo'] = $logoname;            
            $validateData['slug'] = str_replace(' ','-',strtolower($this->name));
            Category::create($validateData);
        }
        session()->flash('message', 'Sub Category successfully saved.');
        $this->resetInputvalues();
    } 

    public function delete($id){
        $category = Category::find($id);
        $category->delete();
    }

    public function cancel(){
        $this->resetInputvalues();
    }

    private function resetInputvalues(){      
        $this->reset(['name', 'temp_image', 'image', 'temp_logo', 'logo', 'status', 'slug', 'category_id', 'parent_id', 'description','edit','page']);  
    }   
    
    public function mount($privileges){
        $this->privileges = $privileges;
    }

    public function render()
    {
        \Log::info($this->page);
        $this->categories = Category::whereNULL('parent_id')->orderBy('sort','asc')->get();

        return view('livewire.categories');
    }
}
