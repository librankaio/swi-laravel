<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use App\Exports\PengeluaranExport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PengeluaranController extends Controller
{
    //
    public function index(){
        // $data_pengeluaran = Pengeluaran::paginate(1);
        // return view('pengeluaran', [
        //     'data_pengeluaran' => $data_pengeluaran]);
        // $data_cmb = DB::table('pengeluaran_dokumen')->select('jenis_dokumen')->where('tstatus','=',1)->get();
        // $data_cmb = Pengeluaran::select('jenis_dokumen')->where('tstatus','=',1)->get()->toArray();
        // return view('pengeluaran',compact('data_cmb'));
        return view('pengeluaran');
        // return view('pengeluaran', [
        //     'data_cmb' => $data_cmb]);
    }

    // public function getPengeluaran(){
        // $data_pengeluaran = Pengeluaran::paginate(1);
        // // return $data_pengeluaran;
        // return view('pengeluaran', [
        //     'data_pengeluaran' => $data_pengeluaran]);
    // }

    // public function showReport(Request $request){
    //     $pengeluaranbtwn = [];
    //     return view('pengeluaran',compact('pengeluaranbtwn'));
    // }

    public function searchPengeluaran(Request $request){
        
        if(isset($request->jenisdok,$request->dtfrom,$request->dtto)){
            // dd($request->all());
            if($request->input('jenisdok') =='All'){
                $dtfr = $request->input('dtfrom');
                $dtto = $request->input('dtto');
                $jenisdok = $request->input('jenisdok');
                // dd($dtfr,$dtto);
                $datefrForm = Carbon::createFromFormat('d/m/Y', $dtfr)->format('Y-m-d');
                $datetoForm = Carbon::createFromFormat('d/m/Y', $dtto)->format('Y-m-d');
                
                $results = DB::table('pengeluaran_dokumen')->whereBetween('dptanggal',[$datefrForm,$datetoForm])->where('tstatus','=',1)->paginate(10);

                $data_cmb = DB::table('pengeluaran_dokumen')->select('jenis_dokumen')->where('tstatus','=',1)->get();
                // return view('pengeluaran',compact('results','data_cmb'));
                return view('pengeluaran', [
                    'results' => $results]);
            }elseif($request->input('jenisdok') != ''){
                $dtfr = $request->input('dtfrom');
                $dtto = $request->input('dtto');
                $jenisdok = $request->input('jenisdok');
                // dd($dtfr,$dtto);
                $datefrForm = Carbon::createFromFormat('d/m/Y', $dtfr)->format('Y-m-d');
                $datetoForm = Carbon::createFromFormat('d/m/Y', $dtto)->format('Y-m-d');
                
                $results = DB::table('pengeluaran_dokumen')->whereBetween('dptanggal',[$datefrForm,$datetoForm])->where('tstatus','=',1)->where('jenis_dokumen','=',$jenisdok)->paginate(10);

                $data_cmb = DB::table('pengeluaran_dokumen')->select('jenis_dokumen')->where('tstatus','=',1)->get();
                // return view('pengeluaran',compact('results','data_cmb'));
                return view('pengeluaran', [
                    'results' => $results]);
            }
            
        }else{
            return view('pengeluaran');
        }
    }

    public function exportExcel() {
        // return Excel::download(new PengeluaranExport, 'datapengeluaran.xlsx')->withHeadings();

        
    }
}
