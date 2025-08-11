<?php

namespace App\Http\Controllers;

use App\Models\MasterClassDate;
use Illuminate\Http\Request;

class MasterClassDateController extends Controller
{
    function index ()
    {
        $masterDates = MasterClassDate::all();
        return view('master_date.index', compact('masterDates'));
    }

    function addTopic ($id)
    {
        $masterDate = MasterClassDate::findOrFail($id);
        return view('master_class_topic.create', compact('masterDate'));
    }
}
