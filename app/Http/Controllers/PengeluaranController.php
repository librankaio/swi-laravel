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
        return view('reports.pengeluaran');
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
        
        if ($request->searchtext == null ){
            if($request->jenisdok != "All"){
                $dtfr = $request->input('dtfrom');
                $dtto = $request->input('dtto');
                $jenisdok = $request->input('jenisdok');
                $datefrForm = Carbon::createFromFormat('d/m/Y', $dtfr)->format('Y-m-d');
                $datetoForm = Carbon::createFromFormat('d/m/Y', $dtto)->format('Y-m-d');
                
                $results = DB::table('pengeluaran_dokumen')->whereBetween('dptanggal',[$datefrForm,$datetoForm])->where('tstatus','=',1)->where('jenis_dokumen','=',$jenisdok)->paginate(10);
    
                return view('reports.pengeluaran', [
                            'results' => $results]);
            }else if ($request->jenisdok == "All"){
                $dtfr = $request->input('dtfrom');
                $dtto = $request->input('dtto');
                $jenisdok = $request->input('jenisdok');
                $datefrForm = Carbon::createFromFormat('d/m/Y', $dtfr)->format('Y-m-d');
                $datetoForm = Carbon::createFromFormat('d/m/Y', $dtto)->format('Y-m-d');
                
                $results = DB::table('pengeluaran_dokumen')->whereBetween('dptanggal',[$datefrForm,$datetoForm])->where('tstatus','=',1)->paginate(10);
    
                return view('reports.pengeluaran', [
                            'results' => $results]);
            }
        }else if ($request->searchtext != null ){
            if($request->jenisdok != "All"){
                $searchtext = $request->searchtext;
                $dtfr = $request->input('dtfrom');
                $dtto = $request->input('dtto');
                $jenisdok = $request->input('jenisdok');
                $datefrForm = Carbon::createFromFormat('d/m/Y', $dtfr)->format('Y-m-d');
                $datetoForm = Carbon::createFromFormat('d/m/Y', $dtto)->format('Y-m-d');
                
                $results = DB::table('pengeluaran_dokumen')->whereBetween('dptanggal',[$datefrForm,$datetoForm])->where('tstatus','=',1)->where('jenis_dokumen','=',$jenisdok)->where('dpnomor','=',$searchtext)->paginate(10);
    
                return view('reports.pengeluaran', [
                            'results' => $results]);
            }else if ($request->jenisdok == "All"){
                $searchtext = $request->searchtext;
                $dtfr = $request->input('dtfrom');
                $dtto = $request->input('dtto');
                $jenisdok = $request->input('jenisdok');
                $datefrForm = Carbon::createFromFormat('d/m/Y', $dtfr)->format('Y-m-d');
                $datetoForm = Carbon::createFromFormat('d/m/Y', $dtto)->format('Y-m-d');
                
                $results = DB::table('pengeluaran_dokumen')->whereBetween('dptanggal',[$datefrForm,$datetoForm])->where('tstatus','=',1)->where('dpnomor','=',$searchtext)->paginate(10);
    
                return view('reports.pengeluaran', [
                            'results' => $results]);
            }
        }
    }

    public function exportExcel() {
        // return Excel::download(new PengeluaranExport, 'datapengeluaran.xlsx')->withHeadings();

        
    }
}
