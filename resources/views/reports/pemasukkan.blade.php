@extends('layouts.main')
@section('content')
  <!-- Form -->
  <form action="{{ route('searchpemasukkan') }}" method="get" class="container-fluid px-5 py-2">
    <div class="head-form">
      <div class="row">                
        <div class="container col-md-8 bg-white px-4 pt-3" style="border-top-left-radius: 10px; border-top-right-radius: 10px;">
          <div class="row">              
            <div class="col-md-6 bg-white">
              <div class="mb-1">
                <h5>PEMASUKAN DOKUMEN</h5>                
              </div>              
            </div>
            <div class="col-md-6 bg-white">
              <div class="mb-3">
                {{-- <label for="exampleInputEmail1" class="form-label">Tanggal Dari</label>
                <div class="input-group date" id="datepicker">
                    <input type="text" class="form-control">
                    <span class="input-group-append">
                        <span class="input-group-text bg-white d-block">
                            <i class="fa fa-calendar"></i>
                        </span>
                    </span>
                </div> --}}
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
                    <option selected value='{{ $_GET['jenisdok'] }}'>{{ $_GET['jenisdok'] }}</option>
                    <option value='All'>All</option>
                    <option value="BC 2.3">BC 2.3</option>
                    <option value="BC 2.6.2">BC 2.6.2</option>
                    <option value="BC 2.7">BC 2.7</option>
                    <option value="BC 4.0">BC 4.0</option>
                  <?php } ?>
                </select>
                <br>
                <form action="{{ route('searchpemasukkan') }}" method="get">
                  <button type="submit" class="btn btn-primary"><span> View</span></button>
                </form>
                  {{-- <button type="button" class="btn btn-primary"><span> Refresh</span></button> --}}
              </div>              
            </div>
            <div class="col-md-6 bg-white">
              <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Tanggal Dari</label>
                <div class="input-group date" id="dtfrom">
                    <input type="text" class="form-control"  value="@php if(request('dtfrom')==NULL){ echo date('d/m/Y');} else{ echo $_GET['dtfrom']; } @endphp"  name="dtfrom">
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
                    <input type="text" class="form-control" value="@php if(request('dtto')==NULL){ echo date('d/m/Y');} else{ echo $_GET['dtto']; } @endphp" name="dtto">
                    <span class="input-group-append">
                        <span class="input-group-text bg-white d-block">
                            <i class="fa fa-calendar"></i>
                        </span>
                    </span>
                </div>
              </div> 
            </div>
            <div class="col-md-6">
              <!-- <div class="mb-3">
                  <label for="exampleInputEmail1" class="form-label">Sampai Tanggal</label>
                  <div class="input-group date" id="datepicker">
                      <input type="text" class="form-control">
                      <span class="input-group-append">
                          <span class="input-group-text bg-white d-block">
                              <i class="fa fa-calendar"></i>
                          </span>
                      </span>
                  </div>
              </div>  -->
            </div>
          </div>
        </div> 
        <div class="container col-md-4 px-4 py-4">
          <!-- <div class="row">
            <div class="mb-3">
              <label for="exampleFormControlInput1" class="form-label">Email address</label>
              <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com">
            </div>
            <div class="mb-3">
              <label for="exampleFormControlTextarea1" class="form-label">Example textarea</label>
              <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
            </div>
          </div> -->
        </div>           
      </div>
    </div>
    <br>
    <div class="row px-5">                
      <div class="container col-md-12 bg-white ps-4 pe-3 py-4" style="border-radius: 10px;">
        <div class="nav-table px-1">
          <div class="row d-flex">
            <div class="col-md-6"></div>
            <div class="col-md-6 text-end">
              <button type="button" class="btn btn-success"><i class="far fa-file-excel"></i><span> Export Excel</span></button>
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
                  <label for="exampleInputEmail1" class="form-label py-2">Search :</label>
                </div>
              </div>
            </div>
            <div class="col-md-2">
              <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Search Nomor Pendaftaran...">
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
              {{-- @if(isset($results)) --}}
               
              <th scope="row" class="border-2">{{ $no }}</th>
              <td class="border-2">{{ $item->jenis_dokumen }}</td>
              <td class="border-2">{{ $item->nomoraju }}</td>
              <td class="border-2">{{ $item->dpnomor }}</td>
              <td class="border-2">{{ $item->dptanggal }}</td>
              <td class="border-2">{{ $item->bpbnomor }}</td>
              <td class="border-2">{{ $item->bpbtanggal }}</td>
              <td class="border-2">{{ $item->pemasok_pengirim }}</td>
              <td class="border-2">{{ $item->kode_barang }}</td>
              <td class="border-2">{{ $item->nama_barang }}</td>
              <td class="border-2">{{ $item->sat }}</td>
              <td class="border-2">{{ $item->jumlah }}</td>
              <td class="border-2">{{ $item->nilai_barang }}</td>
                  
              {{-- @endif --}}
            </tr>
                @endforeach
              @endif
            {{-- @endisset --}}
            <!-- <tr>
              <th scope="row">2</th>
              <td>Jacob</td>
              <td>Thornton</td>
              <td>@fat</td>
              <td>Mark</td>
              <td>Otto</td>
              <td>@mdo</td>
              <td>Mark</td>
              <td>Otto</td>
              <td>@mdo</td>
              <td>Mark</td>
              <td>Otto</td>
              <td>@mdo</td>
            </tr>
            <tr>
              <th scope="row">3</th>
              <td colspan="2">Larry the Bird</td>
              <td>@twitter</td>
            </tr> -->
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
      <!-- <div class="container col-md-2 bg-white ps-4 pe-4 py-4">.col</div> 
      <div class="container col-md-2 bg-white px-4 py-4">.col</div> -->
  </form>
  <!-- END Form -->
  
@endsection