<?php

namespace App\Http\Controllers;

use App\Models\Statuses;
use Illuminate\Http\Request;

class StatusesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    //mendapatkan id status yg diinginkan
    public function getIdStatuses($data_inputan)
    {
        $data = Statuses::find($data_inputan);
        return response()->json($data, 200);
    }


    //menampilkan data
    public function index()
    {
        //mendapatkan semua data dari tabel statuses
        $data = Statuses::all();
        return response()->json($data, 200);
    }


}
