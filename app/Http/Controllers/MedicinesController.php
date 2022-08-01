<?php

namespace App\Http\Controllers;


use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Medicine\StoreMedicineRequest;
use Faker\Provider\Medical;
use App\Http\Controllers\Interfaces\ViewMethods;
use Barryvdh\DomPDF\Facade\Pdf;

class MedicinesController extends Controller implements ViewMethods
{

    public function add()
    {
        return view("medicines.medicine_add");
    }

    public function manage()
    {
        return view("medicines.medicine_manage");
    }
    public function search(Request $request)
    {

        $query = Medicine::where("user_id", Auth::user()->id);
        if ($request->filled("name"))
        {

            $query = $query->where("name", "like", $request->name . "%");
        }
        if ($request->filled("generic"))
        {
            $query = $query->where("generic_name", "like", $request->generic . "%");
        }
        $query->orderBy("name", "asc")->get();
        $medicines = $query->simplePaginate(8);;
        return view("sub.medicine_table", compact("medicines"))->render();
    }
    public function generatePDF(Request $request)
    {
        $medicines = Medicine::where("user_id", Auth::user()->id);
        if ($request->filled("name"))
        {

            $medicines = $medicines->where("name", "like", $request->name . "%");
        }
        if ($request->filled("generic"))
        {
            $medicines = $medicines->where("generic_name", "like", $request->generic . "%");
        }
        $medicines = $medicines->orderBy("name", "asc")->get();
        $pdf = PDF::loadView("pdf.medicines", compact("medicines"));
        return $pdf->download('medicines.pdf');
    }

    public function store(StoreMedicineRequest $request)
    {
        $validated = $request->validated();
        $medicine = Medicine::create($validated + [
            "user_id" => Auth::user()->id,
        ]);
        return response()->json(["instance" => $medicine], 201);
    }

    public function modify(Request $request)
    {
        $medicine = null;
        if ($request->filled("id"))
        {
            $medicine = Medicine::where("user_id", Auth::user()->id)->find($request->id);
            if ($request->filled("name"))
            {
                $medicine->name = $request['name'];
            }
            if ($request->filled("generic"))
            {
                $medicine->generic_name = $request['generic'];
            }
            if ($request->filled("price"))
            {
                $medicine->price = $request['price'];
            }
            if ($request->filled("strip"))
            {
                $medicine->strip = $request['strip'];
            }
            if ($request->filled("description"))
            {
                $medicine->description = $request['description'];
            }
        }
        return response()->json(["success" => 1, "instance" => $medicine]);
    }

    public function show(Request $request)
    {
        $medicine = null;
        if ($request->filled("id"))
        {
            $medicine = Medicine::where("user_id", Auth::user()->id)->where("id", $request->id)->first();
        }
        if ($request->filled("medicine"))
        {
            $medicine = Medicine::where("user_id", Auth::user()->id)->where("name", "=", $request->medicine)->first();
        }
        return response()->json(["success" => 1, "instance" => $medicine]);
    }
    public function destroy(Request $request)
    {
        if ($request->filled("id"))
        {

            Medicine::destroy($request->id);
        }
        return response()->json(["success" => 1]);
    }
    public function all()
    {
        $medicines = Medicine::all();
        return response()->json(["success" => true, "data" => $medicines]);
    }
    public function isExist(Request $request)
    {
        $exist = 0;

        if ($request->has("medicine"))
        {

            $exist = Medicine::join("stocks", "medicines.id", "=", "stocks.medicine_id")->where("stocks.user_id", "=", Auth::User()->id)->where("name", "=", $request->medicine)->count();
            if ($exist !== 0)
            {
                $exist = 1;
            }
        }
        return response()->json(['success' => true, "exist" => $exist]);
    }
}
