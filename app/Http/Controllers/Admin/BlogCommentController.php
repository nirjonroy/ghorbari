<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogComment;
use Illuminate\Http\RedirectResponse;
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

    public function update(BlogComment $blogComment): RedirectResponse
    {
        $blogComment->update([
            'is_approved' => ! $blogComment->is_approved,
        ]);

        return redirect()
            ->route('admin.blog-comments.index')
            ->with('status', 'Blog comment status updated successfully.');
    }

    public function destroy(BlogComment $blogComment): RedirectResponse
    {
        $blogComment->delete();

        return redirect()
            ->route('admin.blog-comments.index')
            ->with('status', 'Blog comment deleted successfully.');
    }

}
