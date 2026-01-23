<?php

namespace App\Http\Controllers;

use App\Exports\PemasukkanExport;
use App\Models\Pemasukkan;
use Barryvdh\DomPDF\PDF as DomPDFPDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PDF;

class PemasukkanController extends Controller
{
    public function index(Request $request)
    {
        if (isset($request->jenisdok)) {
            $dtfr = $request->input('dtfrom');
            $dtto = $request->input('dtto');
            $jenisdok = $request->input('jenisdok');
            $datefrForm = Carbon::createFromFormat('d/m/Y', $dtfr)->format('Y-m-d');
            $datetoForm = Carbon::createFromFormat('d/m/Y', $dtto)->format('Y-m-d');

            if($request->jenis_pencarian == 'No Pendaftaran'){
                if ($request->searchtext == null) {
                    if ($request->jenisdok != "All") {
                        $results = DB::table('pemasukan_dokumen')->whereBetween('dptanggal', [$datefrForm, $datetoForm])->where('stat', '=', 1)->where('jenis_dokumen', '=', $jenisdok)->orderBy('dptanggal','asc')->orderBy('dpnomor','asc')->orderBy('bpbnomor','asc')->get();

                        $totalNilaiBarang = DB::table('pemasukan_dokumen')
                        ->whereBetween('dptanggal', [$datefrForm, $datetoForm])
                        ->where('stat', 1)
                        ->where('jenis_dokumen', $jenisdok)
                        ->sum('nilai_barang');

                        $totalNilaiBarangUSD = DB::table('pemasukan_dokumen')
                        ->whereBetween('dptanggal', [$datefrForm, $datetoForm])
                        ->where('stat', 1)
                        ->where('jenis_dokumen', $jenisdok)
                        ->sum('nilai_barang_usd');

                        return view('reports.pemasukkan', [
                            'totalNilaiBarang' => $totalNilaiBarang,
                            'totalNilaiBarangUSD' => $totalNilaiBarangUSD,
                            'results' => $results
                        ]);
                    } else if ($request->jenisdok == "All") {
                        $results = DB::table('pemasukan_dokumen')->whereBetween('dptanggal', [$datefrForm, $datetoForm])->where('stat', '=', 1)->orderBy('dptanggal','asc')->orderBy('dpnomor','asc')->orderBy('bpbnomor','asc')->get();

                        $totalNilaiBarang = DB::table('pemasukan_dokumen')
                        ->whereBetween('dptanggal', [$datefrForm, $datetoForm])
                        ->where('stat', 1)
                        ->sum('nilai_barang');

                        $totalNilaiBarangUSD = DB::table('pemasukan_dokumen')
                        ->whereBetween('dptanggal', [$datefrForm, $datetoForm])
                        ->where('stat', 1)
                        ->sum('nilai_barang_usd');

                        return view('reports.pemasukkan', [
                            'totalNilaiBarang' => $totalNilaiBarang,
                            'totalNilaiBarangUSD' => $totalNilaiBarangUSD,
                            'results' => $results
                        ]);
                    }
                } else if ($request->searchtext != null) {
                    $searchtext = $request->searchtext;
                    if ($request->jenisdok != "All") {
                        $results = DB::table('pemasukan_dokumen')->whereBetween('dptanggal', [$datefrForm, $datetoForm])->where('stat', '=', 1)->where('jenis_dokumen', '=', $jenisdok)->where('dpnomor', 'like', '%'.$searchtext.'%')->orderBy('dptanggal','asc')->orderBy('dpnomor','asc')->orderBy('bpbnomor','asc')->get();

                        $totalNilaiBarang = DB::table('pemasukan_dokumen')
                        ->whereBetween('dptanggal', [$datefrForm, $datetoForm])
                        ->where('stat', 1)
                        ->where('jenis_dokumen', $jenisdok)
                        ->where('dpnomor', 'like', '%'.$searchtext.'%')
                        ->sum('nilai_barang');

                        $totalNilaiBarangUSD = DB::table('pemasukan_dokumen')
                        ->whereBetween('dptanggal', [$datefrForm, $datetoForm])
                        ->where('stat', 1)
                        ->where('jenis_dokumen', $jenisdok)
                        ->where('dpnomor', 'like', '%'.$searchtext.'%')
                        ->sum('nilai_barang_usd');

                        return view('reports.pemasukkan', [
                            'totalNilaiBarang' => $totalNilaiBarang,
                            'totalNilaiBarangUSD' => $totalNilaiBarangUSD,
                            'results' => $results
                        ]);
                    } else if ($request->jenisdok == "All") {
                        $results = DB::table('pemasukan_dokumen')->whereBetween('dptanggal', [$datefrForm, $datetoForm])->where('stat', '=', 1)->where('dpnomor', 'like', '%'.$searchtext.'%')->orderBy('dptanggal','asc')->orderBy('dpnomor','asc')->orderBy('bpbnomor','asc')->get();

                        $totalNilaiBarang = DB::table('pemasukan_dokumen')
                        ->whereBetween('dptanggal', [$datefrForm, $datetoForm])
                        ->where('stat', 1)
                        ->where('dpnomor', 'like', '%'.$searchtext.'%')
                        ->sum('nilai_barang');

                        $totalNilaiBarangUSD = DB::table('pemasukan_dokumen')
                        ->whereBetween('dptanggal', [$datefrForm, $datetoForm])
                        ->where('stat', 1)
                        ->where('dpnomor', 'like', '%'.$searchtext.'%')
                        ->sum('nilai_barang_usd');

                        return view('reports.pemasukkan', [
                            'totalNilaiBarang' => $totalNilaiBarang,
                            'totalNilaiBarangUSD' => $totalNilaiBarangUSD,
                            'results' => $results
                        ]);
                    }
                }
            }
            if($request->jenis_pencarian == 'No Bukti Penerimaan'){
                if($request->searchtext == null){
                    if ($request->jenisdok != "All") {
                        $results = DB::table('pemasukan_dokumen')->whereBetween('dptanggal', [$datefrForm, $datetoForm])->where('stat', '=', 1)->where('jenis_dokumen', '=', $jenisdok)->orderBy('dptanggal','asc')->orderBy('dpnomor','asc')->orderBy('bpbnomor','asc')->get();

                        $totalNilaiBarang = DB::table('pemasukan_dokumen')
                        ->whereBetween('dptanggal', [$datefrForm, $datetoForm])
                        ->where('stat', 1)
                        ->where('jenis_dokumen', $jenisdok)
                        ->sum('nilai_barang');

                        $totalNilaiBarangUSD = DB::table('pemasukan_dokumen')
                        ->whereBetween('dptanggal', [$datefrForm, $datetoForm])
                        ->where('stat', 1)
                        ->where('jenis_dokumen', $jenisdok)
                        ->sum('nilai_barang_usd');

                        return view('reports.pemasukkan', [
                            'totalNilaiBarang' => $totalNilaiBarang,
                            'totalNilaiBarangUSD' => $totalNilaiBarangUSD,
                            'results' => $results
                        ]);
                    } else if ($request->jenisdok == "All") {
                        $results = DB::table('pemasukan_dokumen')->whereBetween('dptanggal', [$datefrForm, $datetoForm])->where('stat', '=', 1)->orderBy('dptanggal','asc')->orderBy('dpnomor','asc')->orderBy('bpbnomor','asc')->get();

                        $totalNilaiBarang = DB::table('pemasukan_dokumen')
                        ->whereBetween('dptanggal', [$datefrForm, $datetoForm])
                        ->where('stat', 1)
                        ->sum('nilai_barang');

                        $totalNilaiBarangUSD = DB::table('pemasukan_dokumen')
                        ->whereBetween('dptanggal', [$datefrForm, $datetoForm])
                        ->where('stat', 1)
                        ->sum('nilai_barang_usd');

                        return view('reports.pemasukkan', [
                            'totalNilaiBarang' => $totalNilaiBarang,
                            'totalNilaiBarangUSD' => $totalNilaiBarangUSD,
                            'results' => $results
                        ]);
                    }
                }else if ($request->searchtext != null) {
                    $searchtext = $request->searchtext;
                    if ($request->jenisdok != "All") {
                        $results = DB::table('pemasukan_dokumen')->whereBetween('dptanggal', [$datefrForm, $datetoForm])->where('stat', '=', 1)->where('jenis_dokumen', '=', $jenisdok)->where('bpbnomor', 'like', '%'.$searchtext.'%')->orderBy('dptanggal','asc')->orderBy('dpnomor','asc')->orderBy('bpbnomor','asc')->get();

                        $totalNilaiBarang = DB::table('pemasukan_dokumen')
                        ->whereBetween('dptanggal', [$datefrForm, $datetoForm])
                        ->where('stat', 1)
                        ->where('jenis_dokumen', $jenisdok)
                        ->where('bpbnomor', 'like', '%'.$searchtext.'%')
                        ->sum('nilai_barang');

                        $totalNilaiBarangUSD = DB::table('pemasukan_dokumen')
                        ->whereBetween('dptanggal', [$datefrForm, $datetoForm])
                        ->where('stat', 1)
                        ->where('jenis_dokumen', $jenisdok)
                        ->where('bpbnomor', 'like', '%'.$searchtext.'%')
                        ->sum('nilai_barang_usd');

                        return view('reports.pemasukkan', [
                            'totalNilaiBarang' => $totalNilaiBarang,
                            'totalNilaiBarangUSD' => $totalNilaiBarangUSD,
                            'results' => $results
                        ]);
                    } else if ($request->jenisdok == "All") {
                        $results = DB::table('pemasukan_dokumen')->whereBetween('dptanggal', [$datefrForm, $datetoForm])->where('stat', '=', 1)->where('bpbnomor', 'like', '%'.$searchtext.'%')->orderBy('dptanggal','asc')->orderBy('dpnomor','asc')->orderBy('bpbnomor','asc')->get();

                        $totalNilaiBarang = DB::table('pemasukan_dokumen')
                        ->whereBetween('dptanggal', [$datefrForm, $datetoForm])
                        ->where('stat', 1)
                        ->where('bpbnomor', 'like', '%'.$searchtext.'%')
                        ->sum('nilai_barang');

                        $totalNilaiBarangUSD = DB::table('pemasukan_dokumen')
                        ->whereBetween('dptanggal', [$datefrForm, $datetoForm])
                        ->where('stat', 1)
                        ->where('bpbnomor', 'like', '%'.$searchtext.'%')
                        ->sum('nilai_barang_usd');

                        return view('reports.pemasukkan', [
                            'totalNilaiBarang' => $totalNilaiBarang,
                            'totalNilaiBarangUSD' => $totalNilaiBarangUSD,
                            'results' => $results
                        ]);
                    }
                }
            }
            if($request->jenis_pencarian == 'Supplier'){
                if($request->searchtext == null){
                    if ($request->jenisdok != "All") {
                        $results = DB::table('pemasukan_dokumen')->whereBetween('dptanggal', [$datefrForm, $datetoForm])->where('stat', '=', 1)->where('jenis_dokumen', '=', $jenisdok)->orderBy('dptanggal','asc')->orderBy('dpnomor','asc')->orderBy('bpbnomor','asc')->get();

                        $totalNilaiBarang = DB::table('pemasukan_dokumen')
                        ->whereBetween('dptanggal', [$datefrForm, $datetoForm])
                        ->where('stat', 1)
                        ->where('jenis_dokumen', $jenisdok)
                        ->sum('nilai_barang');

                        $totalNilaiBarangUSD = DB::table('pemasukan_dokumen')
                        ->whereBetween('dptanggal', [$datefrForm, $datetoForm])
                        ->where('stat', 1)
                        ->where('jenis_dokumen', $jenisdok)
                        ->sum('nilai_barang_usd');

                        return view('reports.pemasukkan', [
                            'totalNilaiBarang' => $totalNilaiBarang,
                            'totalNilaiBarangUSD' => $totalNilaiBarangUSD,
                            'results' => $results
                        ]);
                    } else if ($request->jenisdok == "All") {
                        $results = DB::table('pemasukan_dokumen')->whereBetween('dptanggal', [$datefrForm, $datetoForm])->where('stat', '=', 1)->orderBy('dptanggal','asc')->orderBy('dpnomor','asc')->orderBy('bpbnomor','asc')->get();

                        $totalNilaiBarang = DB::table('pemasukan_dokumen')
                        ->whereBetween('dptanggal', [$datefrForm, $datetoForm])
                        ->where('stat', 1)
                        ->sum('nilai_barang');

                        $totalNilaiBarangUSD = DB::table('pemasukan_dokumen')
                        ->whereBetween('dptanggal', [$datefrForm, $datetoForm])
                        ->where('stat', 1)
                        ->sum('nilai_barang_usd');

                        return view('reports.pemasukkan', [
                            'totalNilaiBarang' => $totalNilaiBarang,
                            'totalNilaiBarangUSD' => $totalNilaiBarangUSD,
                            'results' => $results
                        ]);
                    }
                }else if ($request->searchtext != null) {
                    $searchtext = $request->searchtext;
                    if ($request->jenisdok != "All") {
                        $results = DB::table('pemasukan_dokumen')->whereBetween('dptanggal', [$datefrForm, $datetoForm])->where('stat', '=', 1)->where('jenis_dokumen', '=', $jenisdok)->where('pemasok_pengirim', 'like', '%'.$searchtext.'%')->orderBy('dptanggal','asc')->orderBy('dpnomor','asc')->orderBy('bpbnomor','asc')->get();

                        $totalNilaiBarang = DB::table('pemasukan_dokumen')
                        ->whereBetween('dptanggal', [$datefrForm, $datetoForm])
                        ->where('stat', 1)
                        ->where('jenis_dokumen', $jenisdok)
                        ->where('pemasok_pengirim', 'like', '%'.$searchtext.'%')
                        ->sum('nilai_barang');

                        $totalNilaiBarangUSD = DB::table('pemasukan_dokumen')
                        ->whereBetween('dptanggal', [$datefrForm, $datetoForm])
                        ->where('stat', 1)
                        ->where('jenis_dokumen', $jenisdok)
                        ->where('pemasok_pengirim', 'like', '%'.$searchtext.'%')
                        ->sum('nilai_barang_usd');

                        return view('reports.pemasukkan', [
                            'totalNilaiBarang' => $totalNilaiBarang,
                            'totalNilaiBarangUSD' => $totalNilaiBarangUSD,
                            'results' => $results
                        ]);
                    } else if ($request->jenisdok == "All") {
                        $results = DB::table('pemasukan_dokumen')->whereBetween('dptanggal', [$datefrForm, $datetoForm])->where('stat', '=', 1)->where('pemasok_pengirim', 'like', '%'.$searchtext.'%')->orderBy('dptanggal','asc')->orderBy('dpnomor','asc')->orderBy('bpbnomor','asc')->get();

                        $totalNilaiBarang = DB::table('pemasukan_dokumen')
                        ->whereBetween('dptanggal', [$datefrForm, $datetoForm])
                        ->where('stat', 1)
                        ->where('pemasok_pengirim', 'like', '%'.$searchtext.'%')
                        ->sum('nilai_barang');

                        $totalNilaiBarangUSD = DB::table('pemasukan_dokumen')
                        ->whereBetween('dptanggal', [$datefrForm, $datetoForm])
                        ->where('stat', 1)
                        ->where('pemasok_pengirim', 'like', '%'.$searchtext.'%')
                        ->sum('nilai_barang_usd');

                        return view('reports.pemasukkan', [
                            'totalNilaiBarang' => $totalNilaiBarang,
                            'totalNilaiBarangUSD' => $totalNilaiBarangUSD,
                            'results' => $results
                        ]);
                    }
                }
            }
            if($request->jenis_pencarian == 'Kode Barang'){
                if($request->searchtext == null){
                    if ($request->jenisdok != "All") {
                        $results = DB::table('pemasukan_dokumen')->whereBetween('dptanggal', [$datefrForm, $datetoForm])->where('stat', '=', 1)->where('jenis_dokumen', '=', $jenisdok)->orderBy('dptanggal','asc')->orderBy('dpnomor','asc')->orderBy('bpbnomor','asc')->get();

                        $totalNilaiBarang = DB::table('pemasukan_dokumen')
                        ->whereBetween('dptanggal', [$datefrForm, $datetoForm])
                        ->where('stat', 1)
                        ->where('jenis_dokumen', $jenisdok)
                        ->sum('nilai_barang');

                        $totalNilaiBarangUSD = DB::table('pemasukan_dokumen')
                        ->whereBetween('dptanggal', [$datefrForm, $datetoForm])
                        ->where('stat', 1)
                        ->where('jenis_dokumen', $jenisdok)
                        ->sum('nilai_barang_usd');

                        return view('reports.pemasukkan', [
                            'totalNilaiBarang' => $totalNilaiBarang,
                            'totalNilaiBarangUSD' => $totalNilaiBarangUSD,
                            'results' => $results
                        ]);
                    } else if ($request->jenisdok == "All") {
                        $results = DB::table('pemasukan_dokumen')->whereBetween('dptanggal', [$datefrForm, $datetoForm])->where('stat', '=', 1)->orderBy('dptanggal','asc')->orderBy('dpnomor','asc')->orderBy('bpbnomor','asc')->get();

                        $totalNilaiBarang = DB::table('pemasukan_dokumen')
                        ->whereBetween('dptanggal', [$datefrForm, $datetoForm])
                        ->where('stat', 1)
                        ->sum('nilai_barang');

                        $totalNilaiBarangUSD = DB::table('pemasukan_dokumen')
                        ->whereBetween('dptanggal', [$datefrForm, $datetoForm])
                        ->where('stat', 1)
                        ->sum('nilai_barang_usd');

                        return view('reports.pemasukkan', [
                            'totalNilaiBarang' => $totalNilaiBarang,
                            'totalNilaiBarangUSD' => $totalNilaiBarangUSD,
                            'results' => $results
                        ]);
                    }
                }else if ($request->searchtext != null) {
                    $searchtext = $request->searchtext;
                    if ($request->jenisdok != "All") {
                        $results = DB::table('pemasukan_dokumen')->whereBetween('dptanggal', [$datefrForm, $datetoForm])->where('stat', '=', 1)->where('jenis_dokumen', '=', $jenisdok)->where('kode_barang', 'like', '%'.$searchtext.'%')->orderBy('dptanggal','asc')->orderBy('dpnomor','asc')->orderBy('bpbnomor','asc')->get();

                        $totalNilaiBarang = DB::table('pemasukan_dokumen')
                        ->whereBetween('dptanggal', [$datefrForm, $datetoForm])
                        ->where('stat', 1)
                        ->where('jenis_dokumen', $jenisdok)
                        ->where('kode_barang', 'like', '%'.$searchtext.'%')
                        ->sum('nilai_barang');

                        $totalNilaiBarangUSD = DB::table('pemasukan_dokumen')
                        ->whereBetween('dptanggal', [$datefrForm, $datetoForm])
                        ->where('stat', 1)
                        ->where('jenis_dokumen', $jenisdok)
                        ->where('kode_barang', 'like', '%'.$searchtext.'%')
                        ->sum('nilai_barang_usd');

                        return view('reports.pemasukkan', [
                            'totalNilaiBarang' => $totalNilaiBarang,
                            'totalNilaiBarangUSD' => $totalNilaiBarangUSD,
                            'results' => $results
                        ]);
                    } else if ($request->jenisdok == "All") {
                        $results = DB::table('pemasukan_dokumen')->whereBetween('dptanggal', [$datefrForm, $datetoForm])->where('stat', '=', 1)->where('kode_barang', 'like', '%'.$searchtext.'%')->orderBy('dptanggal','asc')->orderBy('dpnomor','asc')->orderBy('bpbnomor','asc')->get();

                        $totalNilaiBarang = DB::table('pemasukan_dokumen')
                        ->whereBetween('dptanggal', [$datefrForm, $datetoForm])
                        ->where('stat', 1)
                        ->where('kode_barang', 'like', '%'.$searchtext.'%')
                        ->sum('nilai_barang');

                        $totalNilaiBarangUSD = DB::table('pemasukan_dokumen')
                        ->whereBetween('dptanggal', [$datefrForm, $datetoForm])
                        ->where('stat', 1)
                        ->where('kode_barang', 'like', '%'.$searchtext.'%')
                        ->sum('nilai_barang_usd');

                        return view('reports.pemasukkan', [
                            'totalNilaiBarang' => $totalNilaiBarang,
                            'totalNilaiBarangUSD' => $totalNilaiBarangUSD,
                            'results' => $results
                        ]);
                    }
                }
            }
            if($request->jenis_pencarian == 'Nama Barang'){
                if($request->searchtext == null){
                    if ($request->jenisdok != "All") {
                        $results = DB::table('pemasukan_dokumen')->whereBetween('dptanggal', [$datefrForm, $datetoForm])->where('stat', '=', 1)->where('jenis_dokumen', '=', $jenisdok)->orderBy('dptanggal','asc')->orderBy('dpnomor','asc')->orderBy('bpbnomor','asc')->get();

                        $totalNilaiBarang = DB::table('pemasukan_dokumen')
                        ->whereBetween('dptanggal', [$datefrForm, $datetoForm])
                        ->where('stat', 1)
                        ->where('jenis_dokumen', $jenisdok)
                        ->sum('nilai_barang');

                        $totalNilaiBarangUSD = DB::table('pemasukan_dokumen')
                        ->whereBetween('dptanggal', [$datefrForm, $datetoForm])
                        ->where('stat', 1)
                        ->where('jenis_dokumen', $jenisdok)
                        ->sum('nilai_barang_usd');

                        return view('reports.pemasukkan', [
                            'totalNilaiBarang' => $totalNilaiBarang,
                            'totalNilaiBarangUSD' => $totalNilaiBarangUSD,
                            'results' => $results
                        ]);
                    } else if ($request->jenisdok == "All") {
                        $results = DB::table('pemasukan_dokumen')->whereBetween('dptanggal', [$datefrForm, $datetoForm])->where('stat', '=', 1)->orderBy('dptanggal','asc')->orderBy('dpnomor','asc')->orderBy('bpbnomor','asc')->get();

                        $totalNilaiBarang = DB::table('pemasukan_dokumen')
                        ->whereBetween('dptanggal', [$datefrForm, $datetoForm])
                        ->where('stat', 1)
                        ->sum('nilai_barang');

                        $totalNilaiBarangUSD = DB::table('pemasukan_dokumen')
                        ->whereBetween('dptanggal', [$datefrForm, $datetoForm])
                        ->where('stat', 1)
                        ->sum('nilai_barang_usd');

                        return view('reports.pemasukkan', [
                            'totalNilaiBarang' => $totalNilaiBarang,
                            'totalNilaiBarangUSD' => $totalNilaiBarangUSD,
                            'results' => $results
                        ]);
                    }
                }else if ($request->searchtext != null) {
                    $searchtext = $request->searchtext;
                    if ($request->jenisdok != "All") {
                        $results = DB::table('pemasukan_dokumen')->whereBetween('dptanggal', [$datefrForm, $datetoForm])->where('stat', '=', 1)->where('jenis_dokumen', '=', $jenisdok)->where('nama_barang', 'like', '%'.$searchtext.'%')->orderBy('dptanggal','asc')->orderBy('dpnomor','asc')->orderBy('bpbnomor','asc')->get();

                        $totalNilaiBarang = DB::table('pemasukan_dokumen')
                        ->whereBetween('dptanggal', [$datefrForm, $datetoForm])
                        ->where('stat', 1)
                        ->where('jenis_dokumen', $jenisdok)
                        ->where('nama_barang', 'like', '%'.$searchtext.'%')
                        ->sum('nilai_barang');

                        $totalNilaiBarangUSD = DB::table('pemasukan_dokumen')
                        ->whereBetween('dptanggal', [$datefrForm, $datetoForm])
                        ->where('stat', 1)
                        ->where('jenis_dokumen', $jenisdok)
                        ->where('nama_barang', 'like', '%'.$searchtext.'%')
                        ->sum('nilai_barang_usd');

                        return view('reports.pemasukkan', [
                            'totalNilaiBarang' => $totalNilaiBarang,
                            'totalNilaiBarangUSD' => $totalNilaiBarangUSD,
                            'results' => $results
                        ]);
                    } else if ($request->jenisdok == "All") {
                        $results = DB::table('pemasukan_dokumen')->whereBetween('dptanggal', [$datefrForm, $datetoForm])->where('stat', '=', 1)->where('nama_barang', 'like', '%'.$searchtext.'%')->orderBy('dptanggal','asc')->orderBy('dpnomor','asc')->orderBy('bpbnomor','asc')->get();

                        $totalNilaiBarang = DB::table('pemasukan_dokumen')
                        ->whereBetween('dptanggal', [$datefrForm, $datetoForm])
                        ->where('stat', 1)
                        ->where('nama_barang', 'like', '%'.$searchtext.'%')
                        ->sum('nilai_barang');

                        $totalNilaiBarangUSD = DB::table('pemasukan_dokumen')
                        ->whereBetween('dptanggal', [$datefrForm, $datetoForm])
                        ->where('stat', 1)
                        ->where('nama_barang', 'like', '%'.$searchtext.'%')
                        ->sum('nilai_barang_usd');

                        return view('reports.pemasukkan', [
                            'totalNilaiBarang' => $totalNilaiBarang,
                            'totalNilaiBarangUSD' => $totalNilaiBarangUSD,
                            'results' => $results
                        ]);
                    }
                }
            }
        }
        return view('reports.pemasukkan');
    }

    public function searchPemasukan(Request $request)
    {
        if ($request->searchtext == null) {
            if ($request->jenisdok != "All") {
                $dtfr = $request->input('dtfrom');
                $dtto = $request->input('dtto');
                $jenisdok = $request->input('jenisdok');
                $datefrForm = Carbon::createFromFormat('d/m/Y', $dtfr)->format('Y-m-d');
                $datetoForm = Carbon::createFromFormat('d/m/Y', $dtto)->format('Y-m-d');
                $page = request('page', 1);
                $pageSize = 10;
                $query = DB::select('EXEC rptTest ?,?,?', [$datefrForm, $datetoForm, $jenisdok]);
                $offset = ($page * $pageSize) - $pageSize;
                $data = array_slice($query, $offset, $pageSize, true);
                $results = new \Illuminate\Pagination\LengthAwarePaginator($data, count($data), $pageSize, $page);

                // dd($results);

                return view('reports.pemasukkan', [
                    'results' => $results
                ]);
            } else if ($request->jenisdok == "All") {
                $dtfr = $request->input('dtfrom');
                $dtto = $request->input('dtto');
                $jenisdok = $request->input('jenisdok');
                $datefrForm = Carbon::createFromFormat('d/m/Y', $dtfr)->format('Y-m-d');
                $datetoForm = Carbon::createFromFormat('d/m/Y', $dtto)->format('Y-m-d');

                $results = DB::table('pemasukan_dokumen')->whereBetween('dptanggal', [$datefrForm, $datetoForm])->where('tstatus', '=', 1)->get();

                return view('reports.pemasukkan', [
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

                $results = DB::table('pemasukan_dokumen')->whereBetween('dptanggal', [$datefrForm, $datetoForm])->where('tstatus', '=', 1)->where('jenis_dokumen', '=', $jenisdok)->where('dpnomor', '=', $searchtext)->get();

                return view('reports.pemasukkan', [
                    'results' => $results
                ]);
            } else if ($request->jenisdok == "All") {
                $searchtext = $request->searchtext;
                $dtfr = $request->input('dtfrom');
                $dtto = $request->input('dtto');
                $jenisdok = $request->input('jenisdok');
                $datefrForm = Carbon::createFromFormat('d/m/Y', $dtfr)->format('Y-m-d');
                $datetoForm = Carbon::createFromFormat('d/m/Y', $dtto)->format('Y-m-d');

                $results = DB::table('pemasukan_dokumen')->whereBetween('dptanggal', [$datefrForm, $datetoForm])->where('tstatus', '=', 1)->where('dpnomor', '=', $searchtext)->get();

                return view('reports.pemasukkan', [
                    'results' => $results
                ]);
            }
        }
    }

    public function exportExcel(Request $request)
    {
        // dd(request()->all());
        if ($request->jenisdok != "All") {
            $dtfr = $request->input('dtfrom');
            $dtto = $request->input('dtto');
            $jenisdok = $request->input('jenisdok');
            $datefrForm = Carbon::createFromFormat('d/m/Y', $dtfr)->format('Y-m-d');
            $datetoForm = Carbon::createFromFormat('d/m/Y', $dtto)->format('Y-m-d');
            $comp_name = session()->get('comp_name');

            // $results = DB::table('pemasukan_dokumen')->whereBetween('dptanggal', [$datefrForm, $datetoForm])->where('stat', '=', 1)->where('jenis_dokumen', '=', $jenisdok)->orderBy('dpnomor','desc')->get();

            $results = DB::table('pemasukan_dokumen')->whereBetween('dptanggal', [$datefrForm, $datetoForm])->where('stat', '=', 1)->where('jenis_dokumen', '=', $jenisdok)->orderBy('dpnomor','asc')->orderBy('dptanggal','asc')->orderBy('bpbnomor','asc')->get();

            // $results = DB::select('EXEC rptTest ?,?,?', [$datefrForm, $datetoForm, $jenisdok]);

            // dd($results);
        } else if ($request->jenisdok == "All") {
            $dtfr = $request->input('dtfrom');
            $dtto = $request->input('dtto');
            $jenisdok = $request->input('jenisdok');
            $datefrForm = Carbon::createFromFormat('d/m/Y', $dtfr)->format('Y-m-d');
            $datetoForm = Carbon::createFromFormat('d/m/Y', $dtto)->format('Y-m-d');
            $comp_name = session()->get('comp_name');

            // $results = DB::table('pemasukan_dokumen')->whereBetween('dptanggal', [$datefrForm, $datetoForm])->where('stat', '=', 1)->orderBy('dptanggal','desc')->get();

            $results = DB::table('pemasukan_dokumen')->whereBetween('dptanggal', [$datefrForm, $datetoForm])->where('stat', '=', 1)->orderBy('dpnomor','asc')->orderBy('dptanggal','asc')->orderBy('bpbnomor','asc')->get();
        }
        return view('print.excel.pemasukkan_report', compact('results', 'datefrForm', 'datetoForm', 'comp_name'));
    }
    public function exportExcelFull(Request $request)
    {
        // dd(request()->all());
        if ($request->jenisdok != "All") {
            $dtfr = $request->input('dtfrom');
            $dtto = $request->input('dtto');
            $jenisdok = $request->input('jenisdok');
            $datefrForm = Carbon::createFromFormat('d/m/Y', $dtfr)->format('Y-m-d');
            $datetoForm = Carbon::createFromFormat('d/m/Y', $dtto)->format('Y-m-d');
            $comp_name = session()->get('comp_name');

            // $results = DB::table('pemasukan_dokumen')->whereBetween('dptanggal', [$datefrForm, $datetoForm])->where('stat', '=', 1)->where('jenis_dokumen', '=', $jenisdok)->orderBy('dpnomor','desc')->get();

            $results = DB::table('pemasukan_dokumen')->whereBetween('dptanggal', [$datefrForm, $datetoForm])->where('stat', '=', 1)->where('jenis_dokumen', '=', $jenisdok)->orderBy('dptanggal','asc')->orderBy('dpnomor','asc')->orderBy('bpbnomor','asc')->get();

            // $results = DB::select('EXEC rptTest ?,?,?', [$datefrForm, $datetoForm, $jenisdok]);

            // dd($results);
        } else if ($request->jenisdok == "All") {
            $dtfr = $request->input('dtfrom');
            $dtto = $request->input('dtto');
            $jenisdok = $request->input('jenisdok');
            $datefrForm = Carbon::createFromFormat('d/m/Y', $dtfr)->format('Y-m-d');
            $datetoForm = Carbon::createFromFormat('d/m/Y', $dtto)->format('Y-m-d');
            $comp_name = session()->get('comp_name');

            // $results = DB::table('pemasukan_dokumen')->whereBetween('dptanggal', [$datefrForm, $datetoForm])->where('stat', '=', 1)->orderBy('dptanggal','desc')->get();

            $results = DB::table('pemasukan_dokumen')->whereBetween('dptanggal', [$datefrForm, $datetoForm])->where('stat', '=', 1)->orderBy('dptanggal','asc')->orderBy('dpnomor','asc')->orderBy('bpbnomor','asc')->get();
        }
        return view('print.excel.pemasukkan_report_full', compact('results', 'datefrForm', 'datetoForm', 'comp_name'));
    }

    public function exportPdf(Request $request){
        if ($request->jenisdok != "All") {
            $dtfr = $request->input('dtfrom');
            $dtto = $request->input('dtto');
            $jenisdok = $request->input('jenisdok');
            $datefrForm = Carbon::createFromFormat('d/m/Y', $dtfr)->format('Y-m-d');
            $datetoForm = Carbon::createFromFormat('d/m/Y', $dtto)->format('Y-m-d');

            $results = DB::table('pemasukan_dokumen')->whereBetween('dptanggal', [$datefrForm, $datetoForm])->where('stat', '=', 1)->where('jenis_dokumen', '=', $jenisdok)->get();


            // if($request->has('download'))
            // {
            //     $pdf = DomPDFPDF::loadView('print.pdf.pemasukkan_report',compact('results'));
            //         return $pdf->stream('pdfview.pdf');
            // }
            // $results = DB::select('EXEC rptTest ?,?,?', [$datefrForm, $datetoForm, $jenisdok]);

            // dd($results);
        } else if ($request->jenisdok == "All") {
            $dtfr = $request->input('dtfrom');
            $dtto = $request->input('dtto');
            $jenisdok = $request->input('jenisdok');
            $datefrForm = Carbon::createFromFormat('d/m/Y', $dtfr)->format('Y-m-d');
            $datetoForm = Carbon::createFromFormat('d/m/Y', $dtto)->format('Y-m-d');

            $results = DB::table('pemasukan_dokumen')->whereBetween('dptanggal', [$datefrForm, $datetoForm])->where('stat', '=', 1)->get();

            // if($request->has('download'))
            // {
            //     $pdf = DomPDFPDF::loadView('print.pdf.pemasukkan_report',compact('results'));
            //         return $pdf->stream('pdfview.pdf');    
            // }
        }
        return view('print.pdf.pemasukkan_report', compact('results', 'datefrForm', 'datetoForm'));
    }

    // public function exportExcel2(Request $request)
    // {
    //     if ($request->jenisdok != "All") {
    //         $dtfr = $request->input('dtfrom');
    //         $dtto = $request->input('dtto');
    //         $jenisdok = $request->input('jenisdok');
    //         $datefrForm = Carbon::createFromFormat('d/m/Y', $dtfr)->format('Y-m-d');
    //         $datetoForm = Carbon::createFromFormat('d/m/Y', $dtto)->format('Y-m-d');
    //         $comp_name = session()->get('comp_name');

    //         $results = DB::table('pemasukan_dokumen')->whereBetween('dptanggal', [$datefrForm, $datetoForm])->where('stat', '=', 1)->where('jenis_dokumen', '=', $jenisdok)->orderBy('dpnomor','asc')->orderBy('dptanggal','asc')->orderBy('bpbnomor','asc')->get();
    //     } else if ($request->jenisdok == "All") {
    //         $dtfr = $request->input('dtfrom');
    //         $dtto = $request->input('dtto');
    //         $jenisdok = $request->input('jenisdok');
    //         $datefrForm = Carbon::createFromFormat('d/m/Y', $dtfr)->format('Y-m-d');
    //         $datetoForm = Carbon::createFromFormat('d/m/Y', $dtto)->format('Y-m-d');
    //         $comp_name = session()->get('comp_name');

    //         $results = DB::table('pemasukan_dokumen')->whereBetween('dptanggal', [$datefrForm, $datetoForm])->where('stat', '=', 1)->orderBy('dpnomor','asc')->orderBy('dptanggal','asc')->orderBy('bpbnomor','asc')->get();
    //     }

    //     return Excel::download(new PemasukkanExport($results, $datefrForm, $datetoForm, $comp_name), 'Laporan_PemasukanDokumen.xlsx');
    // }
    public function exportExcel2(Request $request)
{
    $dtfr = $request->dtfrom;
    $dtto = $request->dtto;

    $datefrForm = Carbon::createFromFormat('d/m/Y', $dtfr)->format('Y-m-d');
    $datetoForm = Carbon::createFromFormat('d/m/Y', $dtto)->format('Y-m-d');

    $jenisdok  = $request->jenisdok;
    $comp_name = session('comp_name');

    return Excel::download(
        new PemasukkanExport(
            $datefrForm,
            $datetoForm,
            $jenisdok,
            $comp_name
        ),
        'Laporan_PemasukanDokumen.xlsx'
    );
}
}
