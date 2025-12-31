<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SurveyjsBuilderController extends Controller
{
    public function index()
    {
        return view('livewire.form-builder.surveyjs-builder');
    }

    public function save(Request $request)
    {
        // Save the SurveyJS JSON to the database (stub)
        $json = $request->input('json');
        // TODO: Save $json to your forms table
        return response()->json(['success' => true]);
    }
}
