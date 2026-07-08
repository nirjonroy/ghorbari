<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogComment;
use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogCommentController extends Controller
{
    public function index(): View
    {
        $comments = BlogComment::query()
            ->with(['post', 'user'])
            ->latest()
            ->paginate(15);

        return view('Admin.blog_comments.index', compact('comments'));
    }

    public function create(): View
    {
        return view('Admin.blog_comments.create', [
            'comment' => new BlogComment(),
            'posts' => $this->posts(),
            'users' => $this->users(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        BlogComment::create($this->validatedData($request));

        return redirect()
            ->route('admin.blog-comments.index')
            ->with('status', 'Blog comment created successfully.');
    }

    public function edit(BlogComment $blogComment): View
    {
        return view('Admin.blog_comments.edit', [
            'comment' => $blogComment,
            'posts' => $this->posts(),
            'users' => $this->users(),
        ]);
    }

    public function update(Request $request, BlogComment $blogComment): RedirectResponse
    {
        $blogComment->update($this->validatedData($request));

        return redirect()
            ->route('admin.blog-comments.index')
            ->with('status', 'Blog comment updated successfully.');
    }

    public function destroy(BlogComment $blogComment): RedirectResponse
    {
        $blogComment->delete();

        return redirect()
            ->route('admin.blog-comments.index')
            ->with('status', 'Blog comment deleted successfully.');
    }

    private function validatedData(Request $request): array
    {
        $data = $request->validate([
            'blog_post_id' => ['required', 'exists:blog_posts,id'],
            'user_id' => ['nullable', 'exists:users,id'],
            'name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'comment' => ['required', 'string'],
        ]);

        $data['is_approved'] = $request->boolean('is_approved');

        return $data;
    }

    private function posts()
    {
        return BlogPost::query()
            ->orderBy('title')
            ->get();
    }

    private function users()
    {
        return User::query()
            ->orderBy('name')
            ->get();
    }
}
