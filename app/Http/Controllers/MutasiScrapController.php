<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MutasiScrapController extends Controller
{
    public function index(Request $request)
    {
        if (isset($request->dtfrom)) {
            if ($request->searchtext == null) {

                $dtfr = $request->input('dtfrom');
                $dtto = $request->input('dtto');
                $datefrForm = Carbon::createFromFormat('d/m/Y', $dtfr)->format('Y-m-d');
                $datetoForm = Carbon::createFromFormat('d/m/Y', $dtto)->format('Y-m-d');
                $compcode = session()->get('comp_code');

                $results = DB::select('CALL rptmutasiscrap (?,?,?)', [$datefrForm, $datetoForm, $compcode]);

                // $query = DB::select('EXEC rptTest ?,?,?',[$datefrForm,$datetoForm,'BC 4.0']);

                $page = request('page', 1);
                $pageSize = 25;
                $query = DB::select('CALL rptmutasiscrap (?,?,?)', [$datefrForm, $datetoForm, $compcode]);
                $offset = ($page * $pageSize) - $pageSize;
                $data = array_slice($query, $offset, $pageSize, true);
                // $results = new \Illuminate\Pagination\LengthAwarePaginator($data, count($data), $pageSize, $page);

                // dd($results);

                return view('reports.mutasiscrap', [
                    'results' => $results
                ]);
            } else if ($request->searchtext != null) {
                $searchtext = $request->searchtext;
                $dtfr = $request->input('dtfrom');
                $dtto = $request->input('dtto');
                $jenisdok = $request->input('jenisdok');
                $compcode = session()->get('comp_code');
                $datefrForm = Carbon::createFromFormat('d/m/Y', $dtfr)->format('Y-m-d');
                $datetoForm = Carbon::createFromFormat('d/m/Y', $dtto)->format('Y-m-d');

                $results = $results = DB::select('CALL rptmutasiscrap (?,?,?)', [$datefrForm, $datetoForm, $compcode]);

                return view('reports.mutasiscrap', [
                    'results' => $results
                ]);
            }
        }
        return view('reports.mutasiscrap');
    }

    public function exportExcel(Request $request)
    {
        $dtfr = $request->input('dtfrom');
        $dtto = $request->input('dtto');
        $datefrForm = Carbon::createFromFormat('d/m/Y', $dtfr)->format('Y-m-d');
        $datetoForm = Carbon::createFromFormat('d/m/Y', $dtto)->format('Y-m-d');
        $comp_code = session()->get('comp_code');
        $comp_name = session()->get('comp_name');

        $results = DB::select('CALL rptmutasiscrap (?,?,?)', [$datefrForm, $datetoForm, $comp_code]);

        // dd($results);

        return view('print.excel.mutasiscrap_report', compact('results', 'datefrForm', 'datetoForm', 'comp_name'));
    }

    public function exportPdf(Request $request)
    {
        $dtfr = $request->input('dtfrom');
        $dtto = $request->input('dtto');
        $datefrForm = Carbon::createFromFormat('d/m/Y', $dtfr)->format('Y-m-d');
        $datetoForm = Carbon::createFromFormat('d/m/Y', $dtto)->format('Y-m-d');
        $compcode = session()->get('comp_code');
        $comp_name = session()->get('comp_name');

        $results = DB::select('CALL rptmutasiscrap (?,?,?)', [$datefrForm, $datetoForm, $compcode]);

        // dd($results);

        return view('print.pdf.mutasiscrap_report', compact('results', 'datefrForm', 'datetoForm', 'compcode','comp_name'));
    }
}
