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
    public function index(Request $request)
    {
        $jenis_pencarian = $request->input('jenis_pencarian', 'No Pendaftaran');
        if (isset($request->jenisdok)) {
            if($jenis_pencarian == 'No Pendaftaran'){
                if ($request->searchtext == null || $request->searchtext == '') {
                    if ($request->jenisdok != "All") {
                        $dtfr = $request->input('dtfrom');
                        $dtto = $request->input('dtto');
                        $jenisdok = $request->input('jenisdok');
                        $datefrForm = Carbon::createFromFormat('d/m/Y', $dtfr)->format('Y-m-d');
                        $datetoForm = Carbon::createFromFormat('d/m/Y', $dtto)->format('Y-m-d');
    
                        // $results = DB::table('pengeluaran_dokumen')->whereBetween('dptanggal',[$datefrForm,$datetoForm])->where('tstatus','=',1)->where('jenis_dokumen','=',$jenisdok)->get();
                        $results = DB::table('pengeluaran_dokumen')->whereBetween('dptanggal', [$datefrForm, $datetoForm])->where('stat', '=', 1)->where('jenis_dokumen', '=', $jenisdok)->orderBy('dpnomor','asc')->orderBy('dptanggal','asc')->orderBy('bpbnomor','asc')->get();
    
                        return view('reports.pengeluaran', [
                            'results' => $results
                        ]);
                    } else if ($request->jenisdok == "All") {
                        $dtfr = $request->input('dtfrom');
                        $dtto = $request->input('dtto');
                        $jenisdok = $request->input('jenisdok');
                        $datefrForm = Carbon::createFromFormat('d/m/Y', $dtfr)->format('Y-m-d');
                        $datetoForm = Carbon::createFromFormat('d/m/Y', $dtto)->format('Y-m-d');
    
                        // $results = DB::table('pengeluaran_dokumen')->whereBetween('dptanggal',[$datefrForm,$datetoForm])->where('tstatus','=',1)->paginate(10);
                        $results = DB::table('pengeluaran_dokumen')->whereBetween('dptanggal', [$datefrForm, $datetoForm])->where('stat', '=', 1)->orderBy('dpnomor','asc')->orderBy('dptanggal','asc')->orderBy('bpbnomor','asc')->get();
    
                        return view('reports.pengeluaran', [
                            'results' => $results
                        ]);
                    }
                } else if ($request->searchtext != null) {
                    if ($request->jenisdok != "All") {
                        $searchtext = $request->searchtext;
                        $dtfr = $request->input('dtfrom');
                        $dtto = $request->input('dtto');
                        $jenisdok = $request->input('jenisdok');
                        $datefrForm = Carbon::createFromFormat('d/m/Y', $dtfr)->format('Y-m-d');
                        $datetoForm = Carbon::createFromFormat('d/m/Y', $dtto)->format('Y-m-d');
    
                        // $results = DB::table('pengeluaran_dokumen')->whereBetween('dptanggal',[$datefrForm,$datetoForm])->where('tstatus','=',1)->where('jenis_dokumen','=',$jenisdok)->where('dpnomor','=',$searchtext)->paginate(10);
                        $results = DB::table('pengeluaran_dokumen')->whereBetween('dptanggal', [$datefrForm, $datetoForm])->where('stat', '=', 1)->where('jenis_dokumen', '=', $jenisdok)->where('dpnomor', '=', $searchtext)->orderBy('dptanggal','desc')->orderBy('dpnomor','desc')->get();
    
                        return view('reports.pengeluaran', [
                            'results' => $results
                        ]);
                    } else if ($request->jenisdok == "All") {
                        $searchtext = $request->searchtext;
                        $dtfr = $request->input('dtfrom');
                        $dtto = $request->input('dtto');
                        $jenisdok = $request->input('jenisdok');
                        $datefrForm = Carbon::createFromFormat('d/m/Y', $dtfr)->format('Y-m-d');
                        $datetoForm = Carbon::createFromFormat('d/m/Y', $dtto)->format('Y-m-d');
    
                        // $results = DB::table('pengeluaran_dokumen')->whereBetween('dptanggal',[$datefrForm,$datetoForm])->where('tstatus','=',1)->where('dpnomor','=',$searchtext)->paginate(10);
                        $results = DB::table('pengeluaran_dokumen')->whereBetween('dptanggal', [$datefrForm, $datetoForm])->where('stat', '=', 1)->where('dpnomor', '=', $searchtext)->orderBy('dptanggal','desc')->orderBy('dpnomor','desc')->get();
    
                        return view('reports.pengeluaran', [
                            'results' => $results
                        ]);
                    }
                }
            }
            if($jenis_pencarian == 'No Bukti Penerimaan'){
                if ($request->searchtext == null || $request->searchtext == '') {
                    if ($request->jenisdok != "All") {
                        $dtfr = $request->input('dtfrom');
                        $dtto = $request->input('dtto');
                        $jenisdok = $request->input('jenisdok');
                        $datefrForm = Carbon::createFromFormat('d/m/Y', $dtfr)->format('Y-m-d');
                        $datetoForm = Carbon::createFromFormat('d/m/Y', $dtto)->format('Y-m-d');
    
                        // $results = DB::table('pengeluaran_dokumen')->whereBetween('dptanggal',[$datefrForm,$datetoForm])->where('tstatus','=',1)->where('jenis_dokumen','=',$jenisdok)->get();
                        $results = DB::table('pengeluaran_dokumen')->whereBetween('dptanggal', [$datefrForm, $datetoForm])->where('stat', '=', 1)->where('jenis_dokumen', '=', $jenisdok)->orderBy('dptanggal','desc')->orderBy('bpbnomor','desc')->get();
    
                        return view('reports.pengeluaran', [
                            'results' => $results
                        ]);
                    } else if ($request->jenisdok == "All") {
                        $dtfr = $request->input('dtfrom');
                        $dtto = $request->input('dtto');
                        $jenisdok = $request->input('jenisdok');
                        $datefrForm = Carbon::createFromFormat('d/m/Y', $dtfr)->format('Y-m-d');
                        $datetoForm = Carbon::createFromFormat('d/m/Y', $dtto)->format('Y-m-d');
    
                        // $results = DB::table('pengeluaran_dokumen')->whereBetween('dptanggal',[$datefrForm,$datetoForm])->where('tstatus','=',1)->paginate(10);
                        $results = DB::table('pengeluaran_dokumen')->whereBetween('dptanggal', [$datefrForm, $datetoForm])->where('stat', '=', 1)->orderBy('dptanggal','desc')->orderBy('bpbnomor','desc')->get();
    
                        return view('reports.pengeluaran', [
                            'results' => $results
                        ]);
                    }
                } else if ($request->searchtext != null) {
                    if ($request->jenisdok != "All") {
                        $searchtext = $request->searchtext;
                        $dtfr = $request->input('dtfrom');
                        $dtto = $request->input('dtto');
                        $jenisdok = $request->input('jenisdok');
                        $datefrForm = Carbon::createFromFormat('d/m/Y', $dtfr)->format('Y-m-d');
                        $datetoForm = Carbon::createFromFormat('d/m/Y', $dtto)->format('Y-m-d');
    
                        // $results = DB::table('pengeluaran_dokumen')->whereBetween('dptanggal',[$datefrForm,$datetoForm])->where('tstatus','=',1)->where('jenis_dokumen','=',$jenisdok)->where('dpnomor','=',$searchtext)->paginate(10);
                        $results = DB::table('pengeluaran_dokumen')->whereBetween('dptanggal', [$datefrForm, $datetoForm])->where('stat', '=', 1)->where('jenis_dokumen', '=', $jenisdok)->where('bpbnomor', '=', $searchtext)->orderBy('dptanggal','desc')->orderBy('bpbnomor','desc')->get();
    
                        return view('reports.pengeluaran', [
                            'results' => $results
                        ]);
                    } else if ($request->jenisdok == "All") {
                        $searchtext = $request->searchtext;
                        $dtfr = $request->input('dtfrom');
                        $dtto = $request->input('dtto');
                        $jenisdok = $request->input('jenisdok');
                        $datefrForm = Carbon::createFromFormat('d/m/Y', $dtfr)->format('Y-m-d');
                        $datetoForm = Carbon::createFromFormat('d/m/Y', $dtto)->format('Y-m-d');
    
                        // $results = DB::table('pengeluaran_dokumen')->whereBetween('dptanggal',[$datefrForm,$datetoForm])->where('tstatus','=',1)->where('bpbnomor','=',$searchtext)->paginate(10);
                        $results = DB::table('pengeluaran_dokumen')->whereBetween('dptanggal', [$datefrForm, $datetoForm])->where('stat', '=', 1)->where('bpbnomor', '=', $searchtext)->orderBy('dptanggal','desc')->orderBy('bpbnomor','desc')->get();
    
                        return view('reports.pengeluaran', [
                            'results' => $results
                        ]);
                    }
                }
            }
            if($jenis_pencarian == 'Supplier'){
                if ($request->searchtext == null || $request->searchtext == '') {
                    if ($request->jenisdok != "All") {
                        $dtfr = $request->input('dtfrom');
                        $dtto = $request->input('dtto');
                        $jenisdok = $request->input('jenisdok');
                        $datefrForm = Carbon::createFromFormat('d/m/Y', $dtfr)->format('Y-m-d');
                        $datetoForm = Carbon::createFromFormat('d/m/Y', $dtto)->format('Y-m-d');
    
                        // $results = DB::table('pengeluaran_dokumen')->whereBetween('dptanggal',[$datefrForm,$datetoForm])->where('tstatus','=',1)->where('jenis_dokumen','=',$jenisdok)->get();
                        $results = DB::table('pengeluaran_dokumen')->whereBetween('dptanggal', [$datefrForm, $datetoForm])->where('stat', '=', 1)->where('jenis_dokumen', '=', $jenisdok)->orderBy('dptanggal','desc')->orderBy('bpbnomor','desc')->get();
    
                        return view('reports.pengeluaran', [
                            'results' => $results
                        ]);
                    } else if ($request->jenisdok == "All") {
                        $dtfr = $request->input('dtfrom');
                        $dtto = $request->input('dtto');
                        $jenisdok = $request->input('jenisdok');
                        $datefrForm = Carbon::createFromFormat('d/m/Y', $dtfr)->format('Y-m-d');
                        $datetoForm = Carbon::createFromFormat('d/m/Y', $dtto)->format('Y-m-d');
    
                        // $results = DB::table('pengeluaran_dokumen')->whereBetween('dptanggal',[$datefrForm,$datetoForm])->where('tstatus','=',1)->paginate(10);
                        $results = DB::table('pengeluaran_dokumen')->whereBetween('dptanggal', [$datefrForm, $datetoForm])->where('stat', '=', 1)->orderBy('dptanggal','desc')->orderBy('bpbnomor','desc')->get();
    
                        return view('reports.pengeluaran', [
                            'results' => $results
                        ]);
                    }
                } else if ($request->searchtext != null) {
                    if ($request->jenisdok != "All") {
                        $searchtext = $request->searchtext;
                        $dtfr = $request->input('dtfrom');
                        $dtto = $request->input('dtto');
                        $jenisdok = $request->input('jenisdok');
                        $datefrForm = Carbon::createFromFormat('d/m/Y', $dtfr)->format('Y-m-d');
                        $datetoForm = Carbon::createFromFormat('d/m/Y', $dtto)->format('Y-m-d');
    
                        // $results = DB::table('pengeluaran_dokumen')->whereBetween('dptanggal',[$datefrForm,$datetoForm])->where('tstatus','=',1)->where('jenis_dokumen','=',$jenisdok)->where('dpnomor','=',$searchtext)->paginate(10);
                        $results = DB::table('pengeluaran_dokumen')->whereBetween('dptanggal', [$datefrForm, $datetoForm])->where('stat', '=', 1)->where('jenis_dokumen', '=', $jenisdok)->where('pemasok_pengirim', 'like', '%'.$searchtext.'%')->orderBy('dptanggal','desc')->orderBy('pemasok_pengirim','desc')->get();
    
                        return view('reports.pengeluaran', [
                            'results' => $results
                        ]);
                    } else if ($request->jenisdok == "All") {
                        $searchtext = $request->searchtext;
                        $dtfr = $request->input('dtfrom');
                        $dtto = $request->input('dtto');
                        $jenisdok = $request->input('jenisdok');
                        $datefrForm = Carbon::createFromFormat('d/m/Y', $dtfr)->format('Y-m-d');
                        $datetoForm = Carbon::createFromFormat('d/m/Y', $dtto)->format('Y-m-d');
    
                        // $results = DB::table('pengeluaran_dokumen')->whereBetween('dptanggal',[$datefrForm,$datetoForm])->where('tstatus','=',1)->where('bpbnomor','=',$searchtext)->paginate(10);
                        $results = DB::table('pengeluaran_dokumen')->whereBetween('dptanggal', [$datefrForm, $datetoForm])->where('stat', '=', 1)->where('pemasok_pengirim', 'like', '%'.$searchtext.'%')->orderBy('dptanggal','desc')->orderBy('pemasok_pengirim','desc')->get();
    
                        return view('reports.pengeluaran', [
                            'results' => $results
                        ]);
                    }
                }
            }
            if($jenis_pencarian == 'Kode Barang'){
                if ($request->searchtext == null || $request->searchtext == '') {
                    if ($request->jenisdok != "All") {
                        $dtfr = $request->input('dtfrom');
                        $dtto = $request->input('dtto');
                        $jenisdok = $request->input('jenisdok');
                        $datefrForm = Carbon::createFromFormat('d/m/Y', $dtfr)->format('Y-m-d');
                        $datetoForm = Carbon::createFromFormat('d/m/Y', $dtto)->format('Y-m-d');
    
                        // $results = DB::table('pengeluaran_dokumen')->whereBetween('dptanggal',[$datefrForm,$datetoForm])->where('tstatus','=',1)->where('jenis_dokumen','=',$jenisdok)->get();
                        $results = DB::table('pengeluaran_dokumen')->whereBetween('dptanggal', [$datefrForm, $datetoForm])->where('stat', '=', 1)->where('jenis_dokumen', '=', $jenisdok)->orderBy('dptanggal','desc')->orderBy('bpbnomor','desc')->get();
    
                        return view('reports.pengeluaran', [
                            'results' => $results
                        ]);
                    } else if ($request->jenisdok == "All") {
                        $dtfr = $request->input('dtfrom');
                        $dtto = $request->input('dtto');
                        $jenisdok = $request->input('jenisdok');
                        $datefrForm = Carbon::createFromFormat('d/m/Y', $dtfr)->format('Y-m-d');
                        $datetoForm = Carbon::createFromFormat('d/m/Y', $dtto)->format('Y-m-d');
    
                        // $results = DB::table('pengeluaran_dokumen')->whereBetween('dptanggal',[$datefrForm,$datetoForm])->where('tstatus','=',1)->paginate(10);
                        $results = DB::table('pengeluaran_dokumen')->whereBetween('dptanggal', [$datefrForm, $datetoForm])->where('stat', '=', 1)->orderBy('dptanggal','desc')->orderBy('bpbnomor','desc')->get();
    
                        return view('reports.pengeluaran', [
                            'results' => $results
                        ]);
                    }
                } else if ($request->searchtext != null) {
                    if ($request->jenisdok != "All") {
                        $searchtext = $request->searchtext;
                        $dtfr = $request->input('dtfrom');
                        $dtto = $request->input('dtto');
                        $jenisdok = $request->input('jenisdok');
                        $datefrForm = Carbon::createFromFormat('d/m/Y', $dtfr)->format('Y-m-d');
                        $datetoForm = Carbon::createFromFormat('d/m/Y', $dtto)->format('Y-m-d');
    
                        // $results = DB::table('pengeluaran_dokumen')->whereBetween('dptanggal',[$datefrForm,$datetoForm])->where('tstatus','=',1)->where('jenis_dokumen','=',$jenisdok)->where('dpnomor','=',$searchtext)->paginate(10);
                        $results = DB::table('pengeluaran_dokumen')->whereBetween('dptanggal', [$datefrForm, $datetoForm])->where('stat', '=', 1)->where('jenis_dokumen', '=', $jenisdok)->where('kode_barang', 'like', '%'.$searchtext.'%')->orderBy('dptanggal','desc')->orderBy('kode_barang','desc')->get();
    
                        return view('reports.pengeluaran', [
                            'results' => $results
                        ]);
                    } else if ($request->jenisdok == "All") {
                        $searchtext = $request->searchtext;
                        $dtfr = $request->input('dtfrom');
                        $dtto = $request->input('dtto');
                        $jenisdok = $request->input('jenisdok');
                        $datefrForm = Carbon::createFromFormat('d/m/Y', $dtfr)->format('Y-m-d');
                        $datetoForm = Carbon::createFromFormat('d/m/Y', $dtto)->format('Y-m-d');
    
                        // $results = DB::table('pengeluaran_dokumen')->whereBetween('dptanggal',[$datefrForm,$datetoForm])->where('tstatus','=',1)->where('bpbnomor','=',$searchtext)->paginate(10);
                        $results = DB::table('pengeluaran_dokumen')->whereBetween('dptanggal', [$datefrForm, $datetoForm])->where('stat', '=', 1)->where('kode_barang', 'like', '%'.$searchtext.'%')->orderBy('dptanggal','desc')->orderBy('kode_barang','desc')->get();
    
                        return view('reports.pengeluaran', [
                            'results' => $results
                        ]);
                    }
                }
            }
            if($jenis_pencarian == 'Nama Barang'){
                if ($request->searchtext == null || $request->searchtext == '') {
                    if ($request->jenisdok != "All") {
                        $dtfr = $request->input('dtfrom');
                        $dtto = $request->input('dtto');
                        $jenisdok = $request->input('jenisdok');
                        $datefrForm = Carbon::createFromFormat('d/m/Y', $dtfr)->format('Y-m-d');
                        $datetoForm = Carbon::createFromFormat('d/m/Y', $dtto)->format('Y-m-d');
    
                        // $results = DB::table('pengeluaran_dokumen')->whereBetween('dptanggal',[$datefrForm,$datetoForm])->where('tstatus','=',1)->where('jenis_dokumen','=',$jenisdok)->get();
                        $results = DB::table('pengeluaran_dokumen')->whereBetween('dptanggal', [$datefrForm, $datetoForm])->where('stat', '=', 1)->where('jenis_dokumen', '=', $jenisdok)->orderBy('dptanggal','desc')->orderBy('bpbnomor','desc')->get();
    
                        return view('reports.pengeluaran', [
                            'results' => $results
                        ]);
                    } else if ($request->jenisdok == "All") {
                        $dtfr = $request->input('dtfrom');
                        $dtto = $request->input('dtto');
                        $jenisdok = $request->input('jenisdok');
                        $datefrForm = Carbon::createFromFormat('d/m/Y', $dtfr)->format('Y-m-d');
                        $datetoForm = Carbon::createFromFormat('d/m/Y', $dtto)->format('Y-m-d');
    
                        // $results = DB::table('pengeluaran_dokumen')->whereBetween('dptanggal',[$datefrForm,$datetoForm])->where('tstatus','=',1)->paginate(10);
                        $results = DB::table('pengeluaran_dokumen')->whereBetween('dptanggal', [$datefrForm, $datetoForm])->where('stat', '=', 1)->orderBy('dptanggal','desc')->orderBy('bpbnomor','desc')->get();
    
                        return view('reports.pengeluaran', [
                            'results' => $results
                        ]);
                    }
                } else if ($request->searchtext != null) {
                    if ($request->jenisdok != "All") {
                        $searchtext = $request->searchtext;
                        $dtfr = $request->input('dtfrom');
                        $dtto = $request->input('dtto');
                        $jenisdok = $request->input('jenisdok');
                        $datefrForm = Carbon::createFromFormat('d/m/Y', $dtfr)->format('Y-m-d');
                        $datetoForm = Carbon::createFromFormat('d/m/Y', $dtto)->format('Y-m-d');
    
                        // $results = DB::table('pengeluaran_dokumen')->whereBetween('dptanggal',[$datefrForm,$datetoForm])->where('tstatus','=',1)->where('jenis_dokumen','=',$jenisdok)->where('dpnomor','=',$searchtext)->paginate(10);
                        $results = DB::table('pengeluaran_dokumen')->whereBetween('dptanggal', [$datefrForm, $datetoForm])->where('stat', '=', 1)->where('jenis_dokumen', '=', $jenisdok)->where('nama_barang', 'like', '%'.$searchtext.'%')->orderBy('dptanggal','desc')->orderBy('kode_barang','desc')->get();
    
                        return view('reports.pengeluaran', [
                            'results' => $results
                        ]);
                    } else if ($request->jenisdok == "All") {
                        $searchtext = $request->searchtext;
                        $dtfr = $request->input('dtfrom');
                        $dtto = $request->input('dtto');
                        $jenisdok = $request->input('jenisdok');
                        $datefrForm = Carbon::createFromFormat('d/m/Y', $dtfr)->format('Y-m-d');
                        $datetoForm = Carbon::createFromFormat('d/m/Y', $dtto)->format('Y-m-d');
    
                        // $results = DB::table('pengeluaran_dokumen')->whereBetween('dptanggal',[$datefrForm,$datetoForm])->where('tstatus','=',1)->where('bpbnomor','=',$searchtext)->paginate(10);
                        $results = DB::table('pengeluaran_dokumen')->whereBetween('dptanggal', [$datefrForm, $datetoForm])->where('stat', '=', 1)->where('nama_barang', 'like', '%'.$searchtext.'%')->orderBy('dptanggal','desc')->orderBy('kode_barang','desc')->get();
    
                        return view('reports.pengeluaran', [
                            'results' => $results
                        ]);
                    }
                }
            }
        }
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

    public function searchPengeluaran(Request $request)
    {

        if ($request->searchtext == null) {
            if ($request->jenisdok != "All") {
                $dtfr = $request->input('dtfrom');
                $dtto = $request->input('dtto');
                $jenisdok = $request->input('jenisdok');
                $datefrForm = Carbon::createFromFormat('d/m/Y', $dtfr)->format('Y-m-d');
                $datetoForm = Carbon::createFromFormat('d/m/Y', $dtto)->format('Y-m-d');

                $results = DB::table('pengeluaran_dokumen')->whereBetween('dptanggal', [$datefrForm, $datetoForm])->where('stat', '=', 1)->where('jenis_dokumen', '=', $jenisdok)->paginate(10);

                return view('pengeluaran', [
                    'results' => $results
                ]);
            } else if ($request->jenisdok == "All") {
                $dtfr = $request->input('dtfrom');
                $dtto = $request->input('dtto');
                $jenisdok = $request->input('jenisdok');
                $datefrForm = Carbon::createFromFormat('d/m/Y', $dtfr)->format('Y-m-d');
                $datetoForm = Carbon::createFromFormat('d/m/Y', $dtto)->format('Y-m-d');

                $results = DB::table('pengeluaran_dokumen')->whereBetween('dptanggal', [$datefrForm, $datetoForm])->where('stat', '=', 1)->paginate(10);

                return view('reports.pengeluaran', [
                    'results' => $results
                ]);
            }
        } else if ($request->searchtext != null) {
            if ($request->jenisdok != "All") {
                $searchtext = $request->searchtext;
                $dtfr = $request->input('dtfrom');
                $dtto = $request->input('dtto');
                $jenisdok = $request->input('jenisdok');
                $datefrForm = Carbon::createFromFormat('d/m/Y', $dtfr)->format('Y-m-d');
                $datetoForm = Carbon::createFromFormat('d/m/Y', $dtto)->format('Y-m-d');

                $results = DB::table('pengeluaran_dokumen')->whereBetween('dptanggal', [$datefrForm, $datetoForm])->where('stat', '=', 1)->where('jenis_dokumen', '=', $jenisdok)->where('dpnomor', '=', $searchtext)->paginate(10);

                return view('reports.pengeluaran', [
                    'results' => $results
                ]);
            } else if ($request->jenisdok == "All") {
                $searchtext = $request->searchtext;
                $dtfr = $request->input('dtfrom');
                $dtto = $request->input('dtto');
                $jenisdok = $request->input('jenisdok');
                $datefrForm = Carbon::createFromFormat('d/m/Y', $dtfr)->format('Y-m-d');
                $datetoForm = Carbon::createFromFormat('d/m/Y', $dtto)->format('Y-m-d');

                $results = DB::table('pengeluaran_dokumen')->whereBetween('dptanggal', [$datefrForm, $datetoForm])->where('stat', '=', 1)->where('dpnomor', '=', $searchtext)->paginate(10);

                return view('reports.pengeluaran', [
                    'results' => $results
                ]);
            }
        }
    }

    public function exportExcel(Request $request)
    {
        if ($request->jenisdok != "All") {
            $dtfr = $request->input('dtfrom');
            $dtto = $request->input('dtto');
            $jenisdok = $request->input('jenisdok');
            $datefrForm = Carbon::createFromFormat('d/m/Y', $dtfr)->format('Y-m-d');
            $datetoForm = Carbon::createFromFormat('d/m/Y', $dtto)->format('Y-m-d');
            $comp_name = session()->get('comp_name');

            $results = DB::table('pengeluaran_dokumen')->whereBetween('dptanggal', [$datefrForm, $datetoForm])->where('stat', '=', 1)->where('jenis_dokumen', '=', $jenisdok)->orderBy('dptanggal','desc')->orderBy('dpnomor','desc')->get();

            // $results = DB::select('EXEC rptTest ?,?,?',[$datefrForm,$datetoForm,$jenisdok]);

            // dd($results);
        } else if ($request->jenisdok == "All") {
            $dtfr = $request->input('dtfrom');
            $dtto = $request->input('dtto');
            $jenisdok = $request->input('jenisdok');
            $datefrForm = Carbon::createFromFormat('d/m/Y', $dtfr)->format('Y-m-d');
            $datetoForm = Carbon::createFromFormat('d/m/Y', $dtto)->format('Y-m-d');            
            $comp_name = session()->get('comp_name');

            $results = DB::table('pengeluaran_dokumen')->whereBetween('dptanggal', [$datefrForm, $datetoForm])->where('stat', '=', 1)->orderBy('dptanggal','desc')->orderBy('dpnomor','desc')->get();

            // dd($results);
        }
        return view('print.excel.pengeluaran_report', compact('results', 'datefrForm', 'datetoForm', 'comp_name'));
    }

    public function exportExcelFull(Request $request)
    {
        if ($request->jenisdok != "All") {
            $dtfr = $request->input('dtfrom');
            $dtto = $request->input('dtto');
            $jenisdok = $request->input('jenisdok');
            $datefrForm = Carbon::createFromFormat('d/m/Y', $dtfr)->format('Y-m-d');
            $datetoForm = Carbon::createFromFormat('d/m/Y', $dtto)->format('Y-m-d');
            $comp_name = session()->get('comp_name');

            $results = DB::table('pengeluaran_dokumen')->whereBetween('dptanggal', [$datefrForm, $datetoForm])->where('stat', '=', 1)->where('jenis_dokumen', '=', $jenisdok)->orderBy('dpnomor','asc')->orderBy('dptanggal','asc')->orderBy('bpbnomor','asc')->get();

            // $results = DB::select('EXEC rptTest ?,?,?',[$datefrForm,$datetoForm,$jenisdok]);

            // dd($results);
        } else if ($request->jenisdok == "All") {
            $dtfr = $request->input('dtfrom');
            $dtto = $request->input('dtto');
            $jenisdok = $request->input('jenisdok');
            $datefrForm = Carbon::createFromFormat('d/m/Y', $dtfr)->format('Y-m-d');
            $datetoForm = Carbon::createFromFormat('d/m/Y', $dtto)->format('Y-m-d');
            $comp_name = session()->get('comp_name');

            $results = DB::table('pengeluaran_dokumen')->whereBetween('dptanggal', [$datefrForm, $datetoForm])->where('stat', '=', 1)->orderBy('dpnomor','asc')->orderBy('dptanggal','asc')->orderBy('bpbnomor','asc')->get();

            // dd($results);
        }
        return view('print.excel.pengeluaran_report_full', compact('results', 'datefrForm', 'datetoForm', 'comp_name'));
    }

    public function exportPdf(Request $request){
        if ($request->jenisdok != "All") {
            $dtfr = $request->input('dtfrom');
            $dtto = $request->input('dtto');
            $jenisdok = $request->input('jenisdok');
            $datefrForm = Carbon::createFromFormat('d/m/Y', $dtfr)->format('Y-m-d');
            $datetoForm = Carbon::createFromFormat('d/m/Y', $dtto)->format('Y-m-d');

            $results = DB::table('pengeluaran_dokumen')->whereBetween('dptanggal', [$datefrForm, $datetoForm])->where('stat', '=', 1)->where('jenis_dokumen', '=', $jenisdok)->get();

            // $results = DB::select('EXEC rptTest ?,?,?', [$datefrForm, $datetoForm, $jenisdok]);

            // dd($results);
        } else if ($request->jenisdok == "All") {
            $dtfr = $request->input('dtfrom');
            $dtto = $request->input('dtto');
            $jenisdok = $request->input('jenisdok');
            $datefrForm = Carbon::createFromFormat('d/m/Y', $dtfr)->format('Y-m-d');
            $datetoForm = Carbon::createFromFormat('d/m/Y', $dtto)->format('Y-m-d');

            $results = DB::table('pengeluaran_dokumen')->whereBetween('dptanggal', [$datefrForm, $datetoForm])->where('stat', '=', 1)->get();
        }
        return view('print.pdf.pengeluaran_report', compact('results', 'datefrForm', 'datetoForm'));
    }
}
