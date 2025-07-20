<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\ParentCategory;
use Livewire\Component;
use Livewire\WithPagination;

class Categories extends Component
{
    use WithPagination;

    public $isUpdateParentCategoryMode = false;
    public $pcategory_id;
    public $pcategory_name;

    public $isUpdatedCategoryMode = false;
    public $category_id;
    public $parent = 0;
    public $category_name;

    public $pcategoriesPerPage = 5;
    public $categoriesPerPage = 5;

    protected $listeners = [
        'updateParentCategoryOrdering',
        'updateCategoryOrdering',
        'deleteParentCategoryAction',
        'deleteCategoryAction',
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
                'draggable' => true,
            ]);
        } else {
            $this->dispatch('swalAlert', [
                'title' => "Oops...\n Something went wrong!",
                'icon' => 'error',
                'draggable' => true,
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
            'pcategory_name' => 'required|unique:parent_categories,name,'.$pcategory->id,
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
                'draggable' => true,
            ]);
        } else {
            $this->dispatch('swalAlert', [
                'title' => "Oops...\n Something went wrong!",
                'icon' => 'error',
                'draggable' => true,
            ]);
        }
    }

    public function updateParentCategoryOrdering($positions)
    {
        // dd($positions);
        foreach ($positions as $position) {
            $index = $position[0];
            $new_position = $position[1];
            ParentCategory::where('id', $index)->update(['ordering' => $new_position]);
            $this->dispatch('swalAlert', [
                'title' => 'Parent Categories ordering have been updated Successfully',
                'icon' => 'success',
                'draggable' => true,
            ]);
        }
    }

    public function updateCategoryOrdering($positions)
    {
        // dd($positions);
        foreach ($positions as $position) {
            $index = $position[0];
            $new_position = $position[1];
            Category::where('id', $index)->update(['ordering' => $new_position]);
            $this->dispatch('swalAlert', [
                'title' => 'Categories ordering have been updated Successfully',
                'icon' => 'success',
                'draggable' => true,
            ]);
        }
    }

    public function deleteCategory($id)
    {
        $this->dispatch('deleteCategory', ['id' => $id]);
    }

    public function deleteParentCategory($id)
    {
        $this->dispatch('deleteParentCategory', ['id' => $id]);
    }

    public function addCategory()
    {
        $this->category_id = null;
        $this->category_name = null;
        $this->parent = 0;
        $this->isUpdatedCategoryMode = false;
        $this->showCategoryModalForm();
    }

    public function createCategory()
    {
        $this->validate([
            'category_name' => 'required|unique:categories,name',
        ], [
            'category_name.required' => 'Please enter category name',
            'category_name.unique' => 'Category name already exists',
        ]);

        $category = new Category();
        $category->parent = $this->parent;
        $category->name = $this->category_name;
        $saved = $category->save();
        if ($saved) {
            $this->hideCategoryModalForm();
            $this->dispatch('swalAlert', [
                'title' => 'Category Added Successfully',
                'icon' => 'success',
                'draggable' => true,
            ]);
        } else {
            $this->dispatch('swalAlert', [
                'title' => "Oops...\n Something went wrong!",
                'icon' => 'error',
                'draggable' => true,
            ]);
        }
    }

    public function editCategory($id)
    {
        $category = Category::findOrFail($id);
        $this->category_id = $category->id;
        $this->category_name = $category->name;
        $this->parent = $category->parent;
        $this->isUpdatedCategoryMode = true;
        $this->showCategoryModalForm();
    }

    public function updateCategory()
    {
        $category = Category::findOrFail($this->category_id);
        $this->validate([
            'category_name' => 'required|unique:categories,name,'.$category->id,
        ], [
            'category_name.required' => 'Please enter category name',
            'category_name.unique' => 'Category name already exists',
        ]);
        $category->parent = $this->parent;
        $category->name = $this->category_name;
        $category->slug = null;
        $updated = $category->save();
        if ($updated) {
            $this->hideCategoryModalForm();
            $this->dispatch('swalAlert', [
                'title' => 'Category Updated Successfully',
                'icon' => 'success',
                'draggable' => true,
            ]);
        } else {
            $this->dispatch('swalAlert', [
                'title' => "Oops...\n Something went wrong!",
                'icon' => 'error',
                'draggable' => true,
            ]);
        }
    }

    public function showParentCategoryModalForm()
    {
        $this->resetErrorBag();
        $this->dispatch('showParentCategoryModalForm');
    }

    public function deleteParentCategoryAction($id)
    {
        $pcategory = ParentCategory::findOrFail($id);

        if ($pcategory->children->count() > 0) {
            foreach ($pcategory->children as $category) {
                Category::where('id', $category->id)->update(['parent' => 0]);
            }
        }

        $delete = $pcategory->delete();
        if ($delete) {
            $this->dispatch('swalAlert', [
                'title' => 'Deleted!',
                'text' => 'Parent Category has been deleted successfully.',
                'icon' => 'success',
                'draggable' => true,
            ]);
        } else {
            $this->dispatch('swalAlert', [
                'title' => "Oops...\n Something went wrong!",
                'icon' => 'error',
                'draggable' => true,
            ]);
        }
    }

    public function deleteCategoryAction($id)
    {
        $category = Category::findOrFail($id);
        $delete = $category->delete();
        if ($delete) {
            $this->dispatch('swalAlert', [
                'title' => 'Deleted!',
                'text' => 'Parent Category has been deleted successfully.',
                'icon' => 'success',
                'draggable' => true,
            ]);
        } else {
            $this->dispatch('swalAlert', [
                'title' => "Oops...\n Something went wrong!",
                'icon' => 'error',
                'draggable' => true,
            ]);
        }
    }

    public function hideParentCategoryModalForm()
    {
        $this->dispatch('hideParentCategoryModalForm');
        $this->isUpdateParentCategoryMode = false;
        $this->pcategory_id = $this->pcategory_name = null;
    }

    public function showCategoryModalForm()
    {
        $this->resetErrorBag();
        $this->dispatch('showCategoryModalForm');
    }

    public function hideCategoryModalForm()
    {
        $this->dispatch('hideCategoryModalForm');
        $this->isUpdatedCategoryMode = false;
        $this->category_id = $this->category_name = null;
        $this->parent = 0;
    }

    public function render()
    {
        return view('livewire.admin.categories', [
            'pcategories' => ParentCategory::orderBy('ordering', 'asc')->paginate($this->pcategoriesPerPage, ['*'], 'pcat_page'),
            'categories' => Category::orderBy('ordering', 'asc')->paginate($this->categoriesPerPage, ['*'], 'cat_page'),
        ]);
    }
}
