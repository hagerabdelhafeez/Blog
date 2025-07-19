<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\ParentCategory;
use App\Models\Category;

class Categories extends Component
{
    public $isUpdateParentCategoryMode = false;
    public $pcategory_id, $pcategory_name;

    protected $listeners = [
        'updateCategoryOrdering',
        'deleteCategoryAction'
    ];

    public function addParentCategory()
    {
        $this->pcategory_id = null;
        $this->pcategory_name = null;
        $this->isUpdateParentCategoryMode = false;
        $this->showParentCategoryModalForm();
    }

    public function createParentCategory()
    {
        $this->validate([
            'pcategory_name' => 'required|unique:parent_categories,name',
        ], [
            'pcategory_name.required' => 'Please enter parent category name',
            'pcategory_name.unique' => 'Parent category name already exists',
        ]);

        $pcategory = new ParentCategory();
        $pcategory->name = $this->pcategory_name;
        $save = $pcategory->save();
        if ($save) {
            $this->hideParentCategoryModalForm();
            $this->dispatch('swalAlert', [
                'title' => 'Parent Category Added Successfully',
                'icon' => 'success',
                'draggable' => true
            ]);
        } else {
            $this->dispatch('swalAlert', [
                'title' => "Oops...\n Something went wrong!",
                'icon' => 'error',
                'draggable' => true
            ]);
        }
    }

    public function editParentCategory($id)
    {
        $pcategory = ParentCategory::findOrFail($id);
        $this->pcategory_id = $pcategory->id;
        $this->pcategory_name = $pcategory->name;
        $this->isUpdateParentCategoryMode = true;
        $this->showParentCategoryModalForm();
    }

    public function updateParentCategory()
    {
        $pcategory = ParentCategory::findOrFail($this->pcategory_id);
        $this->validate([
            'pcategory_name' => 'required|unique:parent_categories,name,' . $pcategory->id,
        ], [
            'pcategory_name.required' => 'Please enter parent category name',
            'pcategory_name.unique' => 'Parent category name already exists',
        ]);
        $pcategory->name = $this->pcategory_name;
        $pcategory->slug = null;
        $updated = $pcategory->save();
        if ($updated) {
            $this->hideParentCategoryModalForm();
            $this->dispatch('swalAlert', [
                'title' => 'Parent Category Updated Successfully',
                'icon' => 'success',
                'draggable' => true
            ]);
        } else {
            $this->dispatch('swalAlert', [
                'title' => "Oops...\n Something went wrong!",
                'icon' => 'error',
                'draggable' => true
            ]);
        }
    }

    public function updateCategoryOrdering($positions)
    {
        // dd($positions);
        foreach ($positions as $position) {
            $index = $position[0];
            $new_position = $position[1];
            ParentCategory::where('id', $index)->update(['ordering' => $new_position]);
            $this->dispatch('swalAlert', [
                'title' => 'Parent Categories ordering have been updated Successfully',
                'icon' => 'success',
                'draggable' => true
            ]);
        }
    }

    public function deleteParentCategory($id)
    {
        $this->dispatch('deleteParentCategory', ['id' => $id]);
    }

    public function showParentCategoryModalForm()
    {
        $this->resetErrorBag();
        $this->dispatch('showParentCategoryModalForm');
    }

    public function deleteCategoryAction($id)
    {
        $pcategory = ParentCategory::findOrFail($id);
        $delete = $pcategory->delete();
        if ($delete) {
            $this->dispatch('swalAlert', [
                'title' => 'Deleted!',
                'text' => "Parent Category has been deleted successfully.",
                'icon' => 'success',
                'draggable' => true
            ]);
        } else {
            $this->dispatch('swalAlert', [
                'title' => "Oops...\n Something went wrong!",
                'icon' => 'error',
                'draggable' => true
            ]);
        }
    }

    public function hideParentCategoryModalForm()
    {
        $this->dispatch('hideParentCategoryModalForm');
        $this->isUpdateParentCategoryMode = false;
        $this->pcategory_id = $this->pcategory_name = null;
    }

    public function render()
    {
        return view('livewire.admin.categories', [
            'pcategories' => ParentCategory::orderBy('ordering', 'asc')->get()
        ]);
    }
}
