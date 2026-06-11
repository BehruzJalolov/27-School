<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CKEditorController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'upload' => ['required', 'image', 'mimes:jpeg,jpg,png,gif,webp', 'max:5120'],
        ]);

        $file = $request->file('upload');
        $filename = time().'_'.Str::random(8).'.'.$file->getClientOriginalExtension();
        $file->move(public_path('uploads'), $filename);

        $url = asset('uploads/'.$filename);
        $CKEditorFuncNum = $request->input('CKEditorFuncNum');
        $msg = 'Rasm yuklandi';

        return response("<script>window.parent.CKEDITOR.tools.callFunction({$CKEditorFuncNum}, '{$url}', '{$msg}');</script>")
            ->header('Content-Type', 'text/html; charset=utf-8');
    }
}
