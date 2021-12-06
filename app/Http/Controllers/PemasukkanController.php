<?php

namespace App\Http\Controllers;

use App\Models\Pemasukkan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PemasukkanController extends Controller
{
    public function index(){
        return view('reports.pemasukkan');
    }

    public function searchPemasukan(Request $request){        
        if(isset($request->jenisdok,$request->dtfrom,$request->dtto)){
            // $cmb_search = $request->input('jenisdok');
            $dtfr = $request->input('dtfrom');
            $dtto = $request->input('dtto');
            $jenisdok = $request->input('jenisdok');
            // dd($dtfr,$dtto);
            $datefrForm = Carbon::createFromFormat('d/m/Y', $dtfr)->format('Y-m-d');
            $datetoForm = Carbon::createFromFormat('d/m/Y', $dtto)->format('Y-m-d');
            
            $results = DB::table('pemasukan_dokumen')->whereBetween('dptanggal',[$datefrForm,$datetoForm])->where('tstatus','=',1)->where('jenis_dokumen','=',$jenisdok)->paginate(10);

            $data_cmb = DB::table('pemasukan_dokumen')->select('jenis_dokumen')->where('tstatus','=',1)->get();
            // return view('pengeluaran',compact('results','data_cmb'));
            return view('reports.pemasukkan', [
                'results' => $results]);
        }else{
            return view('reports.pemasukkan');
        }
    }

    public function exportExcel(Request $request) {
        if(isset($request->results)){
            $spreadsheet = $request->results;
            // $sheet = $spreadsheet->getActiveSheet();
            // $sheet->setCellValue('A1', 'Hello World !');

            $writer = new Xlsx($spreadsheet);
            $writer->save('hello world.xlsx');
        }
    }
}