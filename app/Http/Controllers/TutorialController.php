<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// Tambahan
use App\Models\Tutorial;

class TutorialController extends Controller
{
    public function index()
    {
        // Menampilkan Semua Tutorial
        return Tutorial::all();

        // Menampilkan Semua Tutorial dan Semua Komentar
        // return Tutorial::with('comments')->get();
    }
    
    public function show($id)
    {
        // $tutorial = Tutorial::find($id);
        // Eager Loading
        $tutorial = Tutorial::with('comments')->where('id', $id)->get();
        
        if (!$tutorial) {
            return response()->json(['error' => 'Tutorial tidak ditemukan !'], 404);
        }

        return $tutorial;
    }
    
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'body'  => 'required',
        ]);

        $tutorial = $request->user()->tutorials()->create([
            'title' => $request->json('title'),
            'slug'  => str_slug($request->json('title')),
            'body'  => $request->json('body'),
        ]);

        return $tutorial;
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required',
            'body'  => 'required',
        ]);

        $tutorial = Tutorial::find($id);

        // Menguji kepemilikan tutorial
        if ($request->user()->id != $tutorial->user_id) {
            return response()->json(['error' => 'Anda bukan pemilik tutorial ini !'], 403);
        }

        $tutorial->title = $request->title;
        $tutorial->body  = $request->body;
        $tutorial->save();

        return $tutorial;
    }

    public function destroy(Request $request, $id)
    {
        $tutorial = Tutorial::find($id);

        // Menguji kepemilikan tutorial
        if ($request->user()->id != $tutorial->user_id) {
            return response()->json(['error' => 'Anda tidak dapat menghapus tutorial ini !'], 403);
        }

        $tutorial->delete();

        return response()->json(['success' => 'Tutorial berhasil dihapus.'], 200);
    }
}
