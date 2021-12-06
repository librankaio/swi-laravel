<input type="checkbox" id="check">
    
    
    <!-- Sidebar -->    
    <div class="sidebar" id="sidebar">
    
      <div class="head-sidebar">
      <a href="{{ 'pemasukan' }}" class="logo-btn"><img src="img/Logo_swi.png" alt="" class="logo color-light"><span>SWIFECT | ERP APPS</span></img></a>
        <label for="check">
          <i class="fas fa-bars" id="sidebar_toggle"></i>
        </label>
      </div>      
      <center class="profile_form">
        {{-- <img src="/img/quavo.jpg" alt="" class="profile_img"></img> --}}
        <h4>PT.ABC MANTAP</h4>
      </center>
      <a href="{{ 'pemasukan' }}" class="btn_Nav {{ 'pemasukan' == request()->path() ? 'btn_NavActive' : '' }}"><i class="fas fa-sign-in-alt"></i><span>Pemasukan Dokumen</span></a>
      <a href="{{ 'pengeluaran' }}" class="btn_Nav {{ 'pengeluaran' == request()->path() ? 'btn_NavActive' : '' }}"><i class="fas fa-sign-out-alt"></i><span>Pengeluaran Dokumen</span></a>
      {{-- <a href="#" class="btn_Nav"><i class="fas fa-desktop"></i><span>Mutasi Bahan Baku</span></a>
      <a href="#" class="btn_Nav"><i class="fas fa-desktop"></i><span>Mutasi Barang Jadi</span></a>
      <a href="#" class="btn_Nav"><i class="fas fa-desktop"></i><span>Mutasi Mesin</span></a>
      <a href="#" class="btn_Nav"><i class="fas fa-desktop"></i><span>Mutasi Working Process</span></a>
      <a href="#" class="btn_Nav"><i class="fas fa-desktop"></i><span>Log History</span></a> --}}
    </div>
    <!-- END Sidebar -->