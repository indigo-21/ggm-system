<?php

namespace App\Http\Controllers;

use App\Models\OrderFile;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class OrderFileController extends Controller
{

    public function formValidation($fileType = 1){
        $data = [];

        switch ($fileType) {
            case '2':
                $data = ['file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx|max:5120'];
                break;
            case '3':
                $data = ['file' => 'required|file|max:5120'];
                break;
            default:
                # isPhoto
               $data = ["file" => "required|mimes:jpg,png|max:5120"];
                break;
        }

        return $data;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $file = $request->file("file");
        $fileType = $request->fileType;
        $orderId = $request->orderId;
        $description = $request->description;
        // $filenameInput = $request->filename ?? $file->getClientOriginalName();
        $filenameInput = $request->filename ? "_".$request->filename : "";
        
        $request->validate(self::formValidation($fileType));

        $filename = time().$filenameInput.".".$file->getClientOriginalExtension();
        
        $relativePath = $file->storeAs("files/$orderId", $filename, 'public');

        $orderFile = new OrderFile();
        
        $orderFile->order_id = $orderId;
        $orderFile->file_type = $fileType;
        $orderFile->filename = $filename;
        $orderFile->description = $description ?? "Photo Upload";
        $orderFile->filepath = $relativePath;
        $orderFile->created_by = Auth::id();

        $orderFile->save();
        

        return response()->json([
            'file_id' => $orderFile->id,
            'relative_path' => $relativePath,
            'absolute_path' => asset("storage/$relativePath"),
            'order_file' => $orderFile
        ]);

    }

    /**
     * Display the specified resource.
     */
    public function show(OrderFile $orderFile)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OrderFile $orderFile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $orderFileId = $request->fileId;
        $isAttachEmail = $request->isChecked == "true" ? 1 : 0;


        $orderFile = OrderFile::find($orderFileId);
        $orderFile->attach_email = $isAttachEmail;
        $orderFile->save();

         return response()->json([
            'file_id' => $orderFileId,
        ]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $fileId = $request->fileId;

        $orderFile = OrderFile::find($fileId);
        
        $orderFile->delete();

        return response()->json([
            'file_id' => $fileId,
        ]);

    }



}
