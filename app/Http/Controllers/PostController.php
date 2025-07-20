<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ParentCategory;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function addPost(Request $request)
    {
        $categories_html = '';
        $pcategories = ParentCategory::whereHas('children')->orderBy('name', 'asc')->get();
        $categories = Category::where('parent', 0)->orderBy('name', 'asc')->get();

        if (count($pcategories) > 0) {
            foreach ($pcategories as $item) {
                $categories_html .= '<optgroup label = "'.$item->name.'">';
                foreach ($item->children as $category) {
                    $categories_html .= '<option value="'.$category->id.'">'.$category->name.'</option>';
                }
                $categories_html .= '</optgroup>';
            }
        }

        if (count($categories) > 0) {
            foreach ($categories as $item) {
                $categories_html .= '<option value="'.$item->id.'">'.$item->name.'</option>';
            }
        }

        $data = [
            'pageTitle' => 'Add new Post',
            'categories_html' => $categories_html,
        ];

        return view('back.pages.add_post', $data);
    }

    public function createPost(Request $request)
    {
        $request->validate([
            'title' => 'required|unique:posts,title',
            'category' => 'required|exists:categories,id',
            'content' => 'required',
            'featured_image' => 'required|image|mimes:jpeg,png,jpg|max:1024',
        ]);
        if ($request->hasFile('featured_image')) {
            $path = storage_path('app/public/posts/');
            $file = $request->file('featured_image');
            $filename = $file->getClientOriginalName();
            $new_filename = time().'_'.$filename;

            $upload = $file->move($path, $new_filename);
            if ($upload) {
                $post = new Post();
                $post->author_id = Auth::user()->id;
                $post->category = $request->category;
                $post->title = $request->title;
                $post->content = $request->content;
                $post->featured_image = $new_filename;
                $post->tags = $request->tags;
                $post->meta_keywords = $request->meta_keywords;
                $post->meta_description = $request->meta_description;
                $post->visibility = $request->visibility;
                $saved = $post->save();
                if ($saved) {
                    return response()->json(['status' => 1, 'message' => 'Post created successfully.']);
                } else {
                    return response()->json(['status' => 0, 'message' => 'Post creation failed.']);
                }
            } else {
                return response()->json(['status' => 0, 'message' => 'Image upload failed.']);
            }
        }
    }
}
