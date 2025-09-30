<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!--favicon-->
  <link rel="icon" href="{{ asset('telenta.png') }}" type="image/png" />
  <!--plugins-->
  <link href="{{ asset('assets/plugins/simplebar/css/simplebar.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/metismenu/css/metisMenu.min.css') }}" rel="stylesheet" />
  <!-- loader-->
  <link href="{{ asset('assets/css/pace.min.css') }}" rel="stylesheet" />
  <script src="{{ asset('assets/js/pace.min.js') }}"></script>
  <!-- Bootstrap CSS -->
  <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet">
  <title></title>
  <script
  src="https://code.jquery.com/jquery-3.6.0.js"
  integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
  crossorigin="anonymous"></script>
</head>
<style>
  .overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5); /* Grey transparent overlay */
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 999; /* Ensure the overlay is on top of other elements */
    display: none; /* Initially hidden */
  }

  .spinner {
    border: 8px solid #f3f3f3; /* Light grey */
    border-top: 8px solid #3498db; /* Blue */
    border-radius: 50%;
    width: 50px;
    height: 50px;
    animation: spin 1s linear infinite;
  }

  @keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
  }
</style>
<body class="bg-login">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <div class="overlay" id="loadingOverlay">
    <div class="spinner"></div>
  </div>


  <!--wrapper-->
  <div class="wrapper">
    <div class="section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-0">
      <div class="container-fluid">
        <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
          <div class="col mx-auto">
            <div class="mb-4 text-center">
              <img src="{{ asset('telenta.png') }}" width="220" alt="" />
            </div>
            <div class="card">
              <div class="card-body">
                <div class="border p-4 rounded">
                  <div class="text-center">
                    <div class="text-center">
                    </div>
                    <h3 class="">Forgot Password?</h3>
                  </div>

                  <div class="login-separater text-center mb-4"> <span>Enter Email to reset</span>
                    <hr/>
                  </div>
                  <div class="form-body">
                    <div class="row g-3">
                      <div class="col-12">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" placeholder="Email">
                      </div>
                      <div class="col-md-6">
                        <div class="form-check form-switch">

                        </div>
                      </div>
                      <div class="col-12">
                        <div class="d-grid">
                          <button class="btn btn-primary" onclick="sendRequest()">Send</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!--end row-->
      </div>
    </div>
  </div>
  <!--end wrapper-->
  <!-- Bootstrap JS -->
  <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
  <!--plugins-->
  <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
  <script src="{{ asset('assets/js/sweetalert.min.js') }}"></script>    
  <script src="{{ asset('assets/plugins/simplebar/js/simplebar.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/metismenu/js/metisMenu.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
  <!--Password show & hide js -->

  <script type="text/javascript">

    /* init variable */
    var mail = document.getElementById("email");

    mail.addEventListener("keyup", function(event) {
      if (event.keyCode === 13) {
        event.preventDefault();
        sendRequest();
      }
    });


    function sendRequest() {
      showOverlay();

      /* initiate variable */
      var email = $('#email').val();

      var url = "{{ url('forgot-password') }}";
      $.ajax({
        method: "POST",
        url: url,
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        data: JSON.stringify({
          email: email
        }),
        contentType: "application/json",
        dataType: "json",
        success: function(data, textStatus, jqXHR) {

          if(jqXHR.status == 200){

            hideOverlay();
            swal("Password reset request has been sent, Please check your email");

          } else {
            hideOverlay();
            swal(data.message, "", "error");
          }


        },
        error: function(jqXHR, textStatus, errorThrown) {
          console.log('System error');
        }
      });
    }


    function showOverlay() {
      document.getElementById("loadingOverlay").style.display = "flex";
    }

    function hideOverlay() {
      console.log("hide");
      document.getElementById("loadingOverlay").style.display = "none";
    }
  </script>
  <!--app JS-->
  <script src="{{ asset('assets/js/app.js') }}"></script>
</body>

</html>