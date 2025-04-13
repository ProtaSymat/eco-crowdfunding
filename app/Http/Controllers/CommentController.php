<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Store a newly created comment in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'project_id' => 'required|exists:projects,id',
            'parent_id' => 'nullable|exists:comments,id'
        ]);

        $comment = new Comment();
        $comment->content = $request->content;
        $comment->project_id = $request->project_id;
        $comment->user_id = Auth::id();
        $comment->parent_id = $request->parent_id ?: null;
        $comment->is_hidden = false;
        $comment->save();

        return redirect()->back()->with('success', 'Votre commentaire a été publié.');
    }

    /**
     * Update the specified comment in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        if (Auth::id() !== $comment->user_id) {
            return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à modifier ce commentaire.');
        }

        $request->validate([
            'content' => 'required|string'
        ]);

        $comment->content = $request->content;
        $comment->save();

        return redirect()->back()->with('success', 'Votre commentaire a été modifié.');
    }

    /**
     * Remove the specified comment from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        if (Auth::id() !== $comment->user_id && Auth::user()->role !== 'admin') {
            return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à supprimer ce commentaire.');
        }

        if ($comment->parent_id === null) {
            Comment::where('parent_id', $comment->id)->delete();
        }

        $comment->delete();

        return redirect()->back()->with('success', 'Le commentaire a été supprimé.');
    }

    /**
     * Hide a comment (admin only).
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function hide(Comment $comment)
{
    $comment->is_hidden = true;
    $comment->save();

    return back()->with('status', 'Commentaire masqué avec succès.');
}

    /**
     * Unhide a comment (admin only).
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function unhide(Comment $comment)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à rendre ce commentaire visible.');
        }

        $comment->is_hidden = false;
        $comment->save();

        return redirect()->back()->with('success', 'Le commentaire est maintenant visible.');
    }
}