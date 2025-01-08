<?php

namespace App\Http\Controllers;

use App\Events\PostApproved;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Events\PostCreate;
use Carbon\Carbon;

class PostController extends Controller
{
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function index(Request $request)
    {
        $posts = Post::get();

        return view('posts', compact('posts'));
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function store(Request $request)
    {
        $this->validate($request, [
             'title' => 'required',
             'body' => 'required'
        ]);

        $post = Post::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'body' => $request->body
        ]);

        event(new PostCreate($post));

        return back()->with('success','Post created successfully.');
    }


    public function show($id)
    {
        $post = Post::findOrFail($id);
        // $notification = $user->notifications()->find($notificationId);
        // if ($notification) {
        //     $notification->markAsRead();
        // }

        return view('show', compact('post'));
    }

    public function approve($id)
    {
        $post = Post::findOrFail($id);
        $post->update(['is_approved' => 1, 'updated_at'=>Carbon::now()]);

        // Notify the visitor
        event(new PostApproved($post));

        return back()->with('success','Post Approved successfully.');
    }



}
