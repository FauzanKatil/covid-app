<?php

namespace App\Http\Controllers;

use App\Models\Patients;
use App\Http\Controllers\StatusesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    //function index digunakan untuk menampilkan semua data
    public function index()
    {
        //membuat array untuk menampung data yang akan diterima dari controller lain
        $data_f = [];

        //mendapatkan seluruh data patient
        $data = Patients::all();

        //melakukan looping data
        foreach ($data as $data){
            //data_e digunakan untuk mengambil nilai dari controller lain
            $data_e = app('App\Http\Controllers\StatusesController')->getIdStatuses($data->statuses_id);
            $patient_data = [
                "name_patients" => $data->name_patients,
                "phone" => $data->phone,
                "address" => $data->address,
                "status" => $data_e->original,
                "in_date" => $data->in_date,
                "out_date" => $data->out_date
            ];

            //menambahkan data kedalam array yg telah dibuat diatas
            array_push($data_f, $patient_data);
        };

        if(isset($data)){
            $hasil = [
                "message" => "Get All Resource",
                "data" => $data_f
            ];
            return response()->json($hasil, 200);
        }

        else {
            $hasil = [
                "message" => "Data is Empty",
            ];
            return response()->json($hasil, 200);
        }
        
    }




    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    //function store digunakan untuk membuat/menambahkan data baru
    public function store(Request $request)
    {
        //membuat model validasi
        $validasi_data = [
            'name_patients' => 'required',
            'phone' => 'required|numeric',
            'address' => 'required',
            'statuses_id' => 'required',
        ];

        //melakukan validasi
        $validation = Validator::make($request->all(), $validasi_data);
        if($validation->fails()){
            $error = [
                'message' => "Your resource incorrect, please check!"
            ];

            return response()->json($error, 404);
        }

        else {
            //mengambil tanggal saat ini / sekarang
            $tanggal = date('Y-m-d H:i:s');
            $patient_data = Patients::create([
                'name_patients' => $request->name_patients,
                'phone' => $request->phone,
                'address' => $request->address,
                'statuses_id' => $request->statuses_id,
                'in_date' => $tanggal
            ]);
            $success = [
                'message' => "Resource is added successfully",
                'data' => $patient_data
            ];
            return response()->json($success, 200);
        }

    }




    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Patients  $patients
     * @return \Illuminate\Http\Response
     */

    //function show digunakan untuk mendapatkan nilai spesifik
    public function show($id)
    {
        //mencari sebuah data yang sesuai dengan id
        $data = Patients::find($id);
        if(isset($data)){
            $success = [
                "message" => "Get Detail Resource",
                "data" => $data
            ];
            return response()->json($success, 200);
        }

        else {
            $error = [
                "message" => "Resource not found"
            ];
            return response()->json($error, 404);
        }
        
    }




    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Patients  $patients
     * @return \Illuminate\Http\Response
     */

    //function update digunakan untuk melakukan update/edit data
    public function update(Request $request, $id)
    {
        //membuat model validasi
        $validasi_data = [
            'name_patients' => 'required'
        ];

        //melakukan validasi
        $validation = Validator::make($request->all(), $validasi_data);

        //jika validasi salah
        if($validation->fails()){
            $error = [
                'message' => "Your resource incorrect, please check!"
            ];

            return response()->json($error, 404);
        }

        else {
            $data = Patients::find($id);
            if(isset($data)){

                //melakukan update data
                $data->update([
                    'name_patients' => $request->name_patients ?? $data->name_patients,
                    'phone' => $request->phone ?? $data->phone,
                    'address' => $request->address ?? $data->address,
                    'statuses_id' => $request->statuses_id ?? $data->statuses_id,
                    'in_date' => $request->in_date ?? $data->in_date,
                    'out_date' => $request->out_date ?? $data->out_date
                ]);
                $success = [
                    'message' => "Resource is update successfully",
                    'data' => $data
                ];
    
                return response()->json($success, 200);

            }

            else {
                $error = [
                    'message' => "Resource not found"
                ];
    
                return response()->json($error, 404);
            }
        }
        
    }




    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Patients  $patients
     * @return \Illuminate\Http\Response
     */

    //function destroy digunakan untuk menghapus data
    public function destroy($id)
    {
        //mencari data yang akan di hapus
        $data = Patients::find($id);

        //melakukan seleksi kondisi
        if(isset($data)){

            //menghapus data
            $data->delete();
            $success = [
                'message' => "Resource is delete successfully"
            ];

            return response()->json($success, 200);
        }

        else {
            $error = [
                'message' => "Resource not found"
            ];

            return response()->json($error, 200);
        }
    }



    //merupakan function untuk mendapatkan nilai berdasarkan nama
    public function searchByName($request){
        if(isset($request)){

            //query build database untuk menemukan data sesuai inputan
            $filterData = Patients::query()
            ->where('name_patients', 'LIKE', "%{$request}%") 
            ->get();

            $message = [
                "message" => "Get Searched Resource",
                "data" => $filterData
            ];
            return response()->json($message, 200);
        }

        else {
            $message = [
                "message" => "Resource Not Found",
                "data" => $filterData
            ];
            return response()->json($message, 404);
        }
        
    }


    //merupakan function untuk mendapatkan data positif covid
    public function searchByPositive(){

        $filterData = Patients::query()
            ->where('statuses_id', '=', 1) 
            ->get();

            if(isset($filterData)){
                $message = [
                    "message" => "Get Positive Resource",
                    "data" => $filterData
                ];
                return response()->json($message, 200);
            }

            else {
                $message = [
                    "message" => "Resource not found",
                    "data" => $filterData
                ];
                return response()->json($message, 404);
            }
            
        
    }



    //merupakan function untuk mendapatkan data covid yang recovery
    public function searchByRecovery(){

        $filterData = Patients::query()
            ->where('statuses_id', '=', 2) 
            ->get();
            if(isset($filterData)){
                $message = [
                    "message" => "Get Recovery Resource",
                    "data" => $filterData
                ];
                return response()->json($message, 200);
            }

            else {
                $message = [
                    "message" => "Resource not found",
                    "data" => $filterData
                ];
                return response()->json($message, 404);
            }
        
    }


    //merupakan function untuk mendapatkan data kematian akibat covid
    public function searchByDeath(){

        $filterData = Patients::query()
            ->where('statuses_id', '=', 3) 
            ->get();
            if(isset($filterData)){
                $message = [
                    "message" => "Get Death Resource",
                    "data" => $filterData
                ];
                return response()->json($message, 200);
            }

            else {
                $message = [
                    "message" => "Resource not found",
                    "data" => $filterData
                ];
                return response()->json($message, 404);
            }
        
    }
}
