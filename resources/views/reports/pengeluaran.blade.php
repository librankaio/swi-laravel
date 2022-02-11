@extends('layouts.main')
@section('content')
@php auth()->user() @endphp
  <!-- Form -->
  <form action="{{ route('searchpengeluaran') }}" method="get" class="container-fluid px-5 py-2">
    <div class="head-form">
      <div class="row">                
        <div class="container col-md-8 bg-white px-4 pt-3" style="border-top-left-radius: 10px; border-top-right-radius: 10px;">
          <div class="row">              
            <div class="col-md-6 bg-white">
              <div class="mb-1">
                <h5>PENGELUARAN DOKUMEN</h5>                
              </div>              
            </div>
            <div class="col-md-6 bg-white">
              <div class="mb-3">
              </div>
            </div>
          </div>    
        </div>        
        
        <div class="container col-md-4 px-4 py-4"></div>           
      </div>
      <div class="row">                
        <div class="container col-md-4 bg-white px-4 py-4" style="border-bottom-left-radius: 10px;">
          <div class="row">              
            <div class="col-md-6 bg-white">
              <div class="mb-3">                
                <label for="jenisdokumen" class="form-label">Jenis Dokumen</label>
                <select class="form-select" aria-label="Default select example" name="jenisdok">
                  <?php 
                  if(request()->input('jenisdok') == NULL){ 
                  ?>
                    <option value='All'>All</option>
                    <option value="BC 2.3">BC 2.3</option>
                    <option value="BC 2.6.2">BC 2.6.2</option>
                    <option value="BC 2.7">BC 2.7</option>
                    <option value="BC 4.0">BC 4.0</option>
                  <?php }else{ ?>
                    <option selected value='{{ $_GET['jenisdok'] }}'>{{ $_GET['jenisdok'] }}</option>
                    <option value='All'>All</option>
                    <option value="BC 2.3">BC 2.3</option>
                    <option value="BC 2.6.2">BC 2.6.2</option>
                    <option value="BC 2.7">BC 2.7</option>
                    <option value="BC 4.0">BC 4.0</option>
                  <?php } ?>
                </select>
                <br>
                  <button type="submit" class="btn btn-primary"><span> View</span></button>
                  {{-- <button type="button" class="btn btn-primary"><span> Refresh</span></button> --}}
              </div>              
            </div>
            <div class="col-md-6 bg-white">
              <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Tanggal Dari</label>
                <div class="input-group date" id="dtfrom">
                    <input type="text" class="form-control"  value="@php if(request()->input('dtfrom')==NULL){ echo date('d/m/Y');} else{ echo $_GET['dtfrom']; } @endphp"  name="dtfrom">
                    <span class="input-group-append">
                        <span class="input-group-text bg-white d-block">
                            <i class="fa fa-calendar"></i>
                        </span>
                    </span>
                </div>
              </div>
            </div>
          </div>    
        </div>        
        <div class="container col-md-4 bg-white px-4 py-4" style="border-bottom-right-radius: 10px;">
          <div class="row">
            <div class="col-md-6 bg-white">
              <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Sampai Tanggal</label>
                <div class="input-group date" id="dtto">
                    <input type="text" class="form-control" value="@php if(request()->input('dtto')==NULL){ echo date('d/m/Y');} else{ echo $_GET['dtto']; } @endphp" name="dtto">
                    <span class="input-group-append">
                        <span class="input-group-text bg-white d-block">
                            <i class="fa fa-calendar"></i>
                        </span>
                    </span>
                </div>
              </div> 
            </div>
            <div class="col-md-6">
            </div>
          </div>
        </div> 
        <div class="container col-md-4 px-4 py-4">
        </div>           
      </div>
    </div>
    <br>
    <div class="row">                
      <div class="container col-md-12 bg-white ps-4 pe-3 py-4" style="border-radius: 10px;">
        <div class="nav-table px-1">
          <div class="row d-flex">
            <div class="col-md-6"></div>
            <div class="col-md-6 text-end">
              <form action="{{ 'exportexcel' }}" method="get">
                <button type="submit" class="btn btn-success"><i class="far fa-file-excel"></i><span> Export Excel</span></button> 
              </form>
              {{-- <button type="button" class="btn btn-primary"><i class="fas fa-print"></i><span> Print</span></button> --}}
            </div>
          </div>
        </div>
        <div class="nav-table py-2 px-1">
          <div class="row d-flex">
            <div class="col-md-6"></div>
            <div class="col-md-2"></div>
            <div class="col-md-2">
              <div class="row">
                <div class="col-md-6"></div>
                <div class="col-md-6 text-end">
                  <label for="searchtext" class="form-label py-2">Search :</label>
                </div>
              </div>
            </div>
            <div class="col-md-2">
              <input type="text" class="form-control" id="searchtext" aria-describedby="searchtext" name="searchtext" placeholder="Search Nomor Pendaftaran...">
            </div>
          </div>
        </div>
        <table class="table table-striped table-hover">
          
          <thead>
            <tr align="center" class="" style="font-weight: bold;">
              <td scope="col" class="border-bottom-0 border-2">No</td>
              <td scope="col" class="border-bottom-0 border-2">Jenis Dokumen</td>
              <td align="center" colspan="2" class="border-2">Dokumen Pabean</td>
              <td align="center" colspan="2" class="border-2">Bukti Penerimaan Barang</td>
              <td scope="col" class="border-bottom-0 border-2">Pemasok Pengirim</td>
              <td scope="col" class="border-bottom-0 border-2">Kode Barang</td>
              <td scope="col" class="border-bottom-0 border-2">Nama Barang</td>
              <td scope="col" class="border-bottom-0 border-2">Satuan</td>
              <td scope="col" class="border-bottom-0 border-2">Jumlah</td>
              <td align="center" colspan="2" class="border-2">Nilai Barang</td>
            </tr>
            <tr align="center" class="border-top-0 border-2">
              <th scope="col" class="border-top-0 border-bottom-0 border-2"></th>
              <th scope="col" class="border-top-0 border-bottom-0  border-2"></th>
              <th scope="col" class="border-top-0 border-bottom-0  border-2">Nomor</th>
              <th scope="col" class="border-top-0 border-bottom-0  border-2">Tanggal</th>
              <th scope="col" class="border-top-0 border-bottom-0  border-2">Nomor</th>
              <th scope="col" class="border-top-0 border-bottom-0  border-2">Tanggal</th>
              <th scope="col" class="border-top-0 border-bottom-0  border-2"></th>
              <th scope="col" class="border-top-0 border-bottom-0  border-2"></th>
              <th scope="col" class="border-top-0 border-bottom-0  border-2"></th>
              <th scope="col" class="border-top-0 border-bottom-0  border-2"></th>
              <th scope="col" class="border-top-0 border-bottom-0  border-2"></th>
              <th scope="col" class="border-top-0 border-bottom-0  border-2">USD</th>
              <th scope="col" class="border-top-0 border-bottom-0  border-2">Rupiah</th>
            </tr>
          </thead>
          <tbody>
            @isset($results)
                @if(count($results) > 0)
                  @php $no=0; @endphp        
                  @foreach ($results as $item)  
                  @php $no++ @endphp  
            <tr>
                  {{-- <th scope="row" class="border-2">{{ $no }}</th>
                  <td class="border-2">{{ $item->jenis_dokumen }}</td>
                  <td class="border-2">{{ $item->nomoraju }}</td>
                  <td class="border-2">{{ $item->dpnomor }}</td>
                  <td class="border-2">{{ $item->dptanggal }}</td>
                  <td class="border-2">{{ $item->bpbnomor }}</td>
                  <td class="border-2">{{ $item->bpbtanggal }}</td>
                  <td class="border-2">{{ $item->pembeli_penerima }}</td>
                  <td class="border-2">{{ $item->kode_barang }}</td>
                  <td class="border-2">{{ $item->nama_barang }}</td>
                  <td class="border-2">{{ $item->sat }}</td>
                  <td class="border-2">{{ $item->jumlah }}</td>
                  <td class="border-2">{{ $item->nilai_barang }}</td> --}}
              <th scope="row" class="border-2">{{ $no }}</th>
              <td class="border-2">{{ $item->jenis_dokumen }}</td>
              <td class="border-2">{{ $item->nomoraju }}</td>
              <td class="border-2">{{ date("d/m/Y", strtotime($item->dptanggal)) }}</td>
              <td class="border-2">{{ $item->dpnomor }}</td>
              <td class="border-2">{{ date("d/m/Y", strtotime($item->bpbtanggal)) }}</td>
              <td class="border-2">{{ $item->pembeli_penerima }}</td>
              <td class="border-2">{{ $item->kode_barang }}</td>
              <td class="border-2">{{ $item->nama_barang }}</td>
              <td class="border-2">{{ $item->sat }}</td>
              <td class="border-2">{{ $item->jumlah }}</td>
              <td class="border-2">{{ number_format($item->nilai_barang, 2, '.', ',') }}</td>
              <td class="border-2">{{ number_format($item->nilai_barang_usd, 2, '.', ',') }}</td>
            </tr>
                @endforeach
                @elseif(count($results) == 0)
                  <td colspan="13" class="border-2"> 
                    <label for="noresult" class="form-label">NO DATA RESULTS...</label>
                  </td>
              @endif
          </tbody>
        </table>
        <div class="row">
          <div class="col-md-6 py-3">
            <div class="d-flex justify-content-start">
              Showing
              {{ $results->firstItem() }}
              to
              {{ $results->lastItem() }}
              of
              {{ $results->total() }}
              Entries
            </div>
          </div>
          <div class="col-md-6">
            <div class="d-flex justify-content-end">
              {{ $results->appends(request()->input())->links() }}
            </div>
          </div>
          @endisset
        </div>
      </div>  
  </form>
  <!-- END Form -->
  
@endsection