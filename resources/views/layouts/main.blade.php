<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Switaatpajak | ERP APPS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="/css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css">

    <!-- DATETIME PICKER -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <!-- END DATETIME PICKER -->
</head>
<body> 
    @include('partials.navbar')
    @php auth()->user() @endphp
    <!-- Content -->
    <div class="content">
      <!-- Header -->
        <header>
          <div class="content_Nav">
            <div class="container-fluid text-end py-2 px-5">
                {{-- <button type="submit" class="btn btn-danger"><i class="fas fa-power-off"></i><span> SignOut</span></button> --}}
                <a class="btn btn-danger" href="{{ 'logout' }}"><i class="fas fa-power-off"></i><span> SignOut</span></a>
            </div>
          </div>
        </header>
        <br>
      <!-- END Header -->
        @yield('content')
    </div>
    <!-- END Content -->
<!-- js importer -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<!-- END js importer -->

<script type="text/javascript">
  $(function(){
    $('#dtfrom').datepicker({
      format: 'dd/mm/yyyy'
    });
    $('#dtto').datepicker({
      format: 'dd/mm/yyyy'
    });
  });
</script>
</body>
<script>
  var btnContainer = document.getElementById("sidebar");
  var btns = btnContainer.getElementsByClassName("btn_Nav");

  for (var i = 0; i < btns.length; i++) {
    btns[i].addEventListener("click", function () {
      var current = document.getElementsByClassName("btn_NavActive");
      current[0].className = current[0].className.replace(" btn_NavActive");
      this.className += " btn_NavActive";
    });    
  }
//   var btnContainer = document.getElementById("sidebar");

// // Get all buttons with class="btn" inside the container
// var btns = btnContainer.getElementsByClassName("btn_Nav");

// // Loop through the buttons and add the active class to the current/clicked button
// for (var i = 0; i < btns.length; i++) {
//   btns[i].addEventListener("click", function() {
//     var current = document.getElementsByClassName("btn_NavActve");
//     current[0].className = current[0].className.replace(" btn_NavActve", "");
//     this.className += " btn_NavActve";
//   });
// }
</script>
</html>