<?php

namespace App\Http\Controllers;

use App\Models\Set;
use Illuminate\Http\Request;

class SetController extends Controller
{
    public function __invoke()
    {
        return response()->json(Set::all()->map(function ($item) {
            return ["code"=>$item->code,"name"=>$item->name,"templates"=>$item->templates->map(function ($template) {
                return $template->title;
            })->toArray()];
        })->toArray());
    }
}
