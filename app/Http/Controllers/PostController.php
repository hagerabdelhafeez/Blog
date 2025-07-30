<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ParentCategory;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

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
                /** Generate Resized Image and thumbnail */
                $resized_path = $path.'resized/';
                if (!File::isDirectory($resized_path)) {
                    File::makeDirectory($resized_path, 0777, true, true);
                }
                // Thumbnail(Aspect ratio: 1)
                Image::make($path.$new_filename)->fit(250, 250)->save($resized_path.'thumb_'.$new_filename);

                // Resized image (Aspect ratio: 1.6)
                Image::make($path.$new_filename)->fit(512, 320)->save($resized_path.'resized_'.$new_filename);

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

    public function allPosts(Request $request)
    {
        $data = [
            'pageTitle' => 'Posts',
        ];

        return view('back.pages.posts', $data);
    }

    public function editPost(Request $request, $id = null)
    {
        $post = Post::findOrFail($id);
        $categories_html = '';
        $pcategories = ParentCategory::whereHas('children')->orderBy('name', 'asc')->get();
        $categories = Category::where('parent', 0)->orderBy('name', 'asc')->get();

        if (count($pcategories) > 0) {
            foreach ($pcategories as $item) {
                $categories_html .= '<optgroup label = "'.$item->name.'">';
                foreach ($item->children as $category) {
                    $selected = $category->id == $post->category ? 'selected' : '';
                    $categories_html .= '<option value="'.$category->id.'"'.$selected.'>'.$category->name.'</option>';
                }
                $categories_html .= '</optgroup>';
            }
        }

        if (count($categories) > 0) {
            foreach ($categories as $item) {
                $selected = $item->id == $post->category ? 'selected' : '';
                $categories_html .= '<option value="'.$item->id.'"'.$selected.'>'.$item->name.'</option>';
            }
        }

        $data = [
            'pageTitle' => 'Edit Post',
            'categories_html' => $categories_html,
            'post' => $post,
        ];

        return view('back.pages.edit_post', $data);
    }

    public function updatePost(Request $request)
    {
        $post = Post::findOrFail($request->post_id);
        $featured_image_name = $post->featured_image;

        $request->validate([
            'title' => 'required|unique:posts,title,'.$post->id,
            'category' => 'required|exists:categories,id',
            'content' => 'required',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg|max:1024',
        ]);
        if ($request->hasFile('featured_image')) {
            $old_featured_image = $post->featured_image;
            $path = storage_path('app/public/posts/');
            $file = $request->file('featured_image');
            $filename = $file->getClientOriginalName();
            $new_filename = time().'_'.$filename;
            $upload = $file->move($path, $new_filename);
            if ($upload) {
                $resized_path = $path.'resized/';
                // Thumbnail(Aspect ratio: 1)
                Image::make($path.$new_filename)->fit(250, 250)->save($resized_path.'thumb_'.$new_filename);
                // Resized image (Aspect ratio: 1.6)
                Image::make($path.$new_filename)->fit(512, 320)->save($resized_path.'resized_'.$new_filename);
                if ($old_featured_image != null && File::exists($path.$old_featured_image)) {
                    File::delete($path.$old_featured_image);
                    if (File::exists($resized_path.'resized_'.$old_featured_image)) {
                        File::delete($resized_path.'resized_'.$old_featured_image);
                    }
                    if (File::exists($resized_path.'thumb_'.$old_featured_image)) {
                        File::delete($resized_path.'thumb_'.$old_featured_image);
                    }
                }
                $featured_image_name = $new_filename;
            } else {
                return response()->json(['status' => 0, 'message' => 'Image upload failed.']);
            }
        }
        $post->category = $request->category;
        $post->title = $request->title;
        $post->slug = null;
        $post->content = $request->content;
        $post->featured_image = $featured_image_name;
        $post->tags = $request->tags;
        $post->meta_keywords = $request->meta_keywords;
        $post->meta_description = $request->meta_description;
        $post->visibility = $request->visibility;
        $saved = $post->save();
        if ($saved) {
            return response()->json(['status' => 1, 'message' => 'Post updated successfully.']);
        } else {
            return response()->json(['status' => 0, 'message' => 'Post update failed.']);
        }
    }
}
