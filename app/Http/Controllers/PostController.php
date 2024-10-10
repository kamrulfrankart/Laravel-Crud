<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;


class PostController extends Controller
{
    public function create()
    {
        return view('create');
    }

    public function ourfilestore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|mimes:jpeg,png|max:2048',
        ]);

        $imageName = null;
        if (isset($request->image)) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);
        } else {
            return redirect()->back()->withErrors(['image' => 'Please upload an image.']);
        }

        $post = new Post;

        $post->name = $request->name;
        $post->description = $request->description;
        $post->image = $imageName;

        $post->save();

        return redirect()->route('home')->with('success', 'Your post has been created!');
    }

    public function editData($id)
    {
        $post = Post::findOrFail($id);

        return view('edit', ['ourPost' => $post]);
    }

    public function updateData($id, Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|mimes:jpeg,png|max:2048',
        ]);

        $post = Post::findOrFail($id);

        $post->name = $request->name;
        $post->description = $request->description;

        if (isset($request->image)) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            $post->image = $imageName;
        } else {
            return redirect()->back()->withErrors(['image' => 'Please upload an image.']);
        }

        $post->save();

        return redirect()->route('home')->with('success', 'Your post has been updated!');
    }

    public function deleteData($id)
    {
        $post = Post::findOrFail($id);

        $post->delete();

        session()->flash('success', 'Your post has been deleted.');

        return redirect()->route('home');
    }
}
//1:34 