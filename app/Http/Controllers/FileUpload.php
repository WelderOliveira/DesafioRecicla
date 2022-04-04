<?php

namespace App\Http\Controllers;

use App\Models\Faturamento;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FileUpload extends Controller
{
    public function createForm()
    {
        return view('file-upload');
    }

    public function fileUpload(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt,xlx,xls,pdf|max:2048'
        ]);
        $fileModel = new File;
        if ($request->file()) {
            $fileName = time() . '_' . $request->file->getClientOriginalName();
            $filePath = $request->file('file')->storeAs('uploads', $fileName, 'public');
            $fileModel->name = time() . '_' . $request->file->getClientOriginalName();
            $fileModel->file_path = '/storage/' . $filePath;
            $fileModel->save();

            $content = \Illuminate\Support\Facades\File::get(storage_path('app\\public\\uploads\\' . $fileName));
            $arrtable = Str::of($content)->explode("\n");
//            dd($arrtable[1]);
            $total = 0;
            $totalItens = 0;
            $totalCompradores = 0;
            $data = array();
            foreach ($arrtable as $key => $arr) {
                $arrLine = Str::of($arr)->explode("\t");

                $data[] = [
                    'comprador' => $arrLine[0],
                    'descricao' => $arrLine[1],
                    'preco_unit' => $arrLine[2],
                    'qnt' => $arrLine[3],
                    'endereco' => $arrLine[4],
                    'fornecedor' => $arrLine[5],
                ];
                if (is_numeric($arrLine[2]) and is_numeric($arrLine[3])){
                    $total = $total+($arrLine[2]*$arrLine[3]);
                }
                if (is_numeric($arrLine[3])){
                    $totalItens = $totalItens+$arrLine[3];
                }
                $totalCompradores++;

            }
            for ($i = 1; $i < sizeof($data); $i++) {
                $value = $data[$i]['preco_unit']*$data[$i]['qnt'];
//                dd($value);
                Faturamento::create([
                    'comprador' => $data[$i]['comprador'],
                    'descricao' => $data[$i]['descricao'],
                    'preco_unit' => $data[$i]['preco_unit'],
                    'quantidade' => $data[$i]['qnt'],
                    'endereco' => $data[$i]['endereco'],
                    'fornecedor' => $data[$i]['fornecedor'],
                    'file_id' => $fileModel->id
                ]);

            }
//            dd($data);
            return view('teste', [
                'data' => $data,
                'total' => $total,
                'totalItens'=>$totalItens,
                'totalCompradores' => $totalCompradores-1
            ]);
        }
    }
}
