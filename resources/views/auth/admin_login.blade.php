<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
    
    <!-- Bootstrap 4 CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <!-- FontAwesome CDN -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">

    <!-- Custom Style -->
    <link rel="stylesheet" type="text/css" href="styles.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>
<div class="container">
    <div class="d-flex justify-content-center h-100">
        <div class="card">
            <div class="card-header">
                <h3>ADMIN LOG IN</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('loginUser') }}" method="POST">
                    @if(Session::has('success'))
                    <div class="alert alert-success">{{ Session::get('success') }}</div>
                    @endif

                    @if(Session::has('fail'))
                    <div class="alert alert-danger">{{ Session::get('fail') }}</div>
                    @endif

                    @csrf
                    <div class="input-group form-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                        </div>
                        <input type="email" class="form-control" placeholder="username" value="{{ old('email') }}" name ="email">
                    </div>
					@error('email')
   					<span class="text-danger">{{ $message }}</span>
					@enderror
                    <div class="input-group form-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                        </div>
                        <input type="password" class="form-control" placeholder="password" name="password">
                    </div>
					@error('password')
					<span class="text-danger">{{ $message }}</span>
					@enderror
                    <div class="row align-items-center remember">
                        <input type="checkbox"> Remember Me
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Login" class="btn float-right login_btn">
                    </div>
                </form>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-center">
                    <a href="forgetPassword" id="forgotPasswordLink">Forgot your password?</a>

                    <div id="toast" style="visibility: hidden; position: fixed; bottom: 70px; left: 50%; transform: translateX(-50%); background-color: #333; color: white; padding: 10px; border-radius: 5px; z-index: 1000;">
                      Please contact SuperAdmin or your Developer.
                    </div>
                   
                    <script>
                      document.getElementById('forgotPasswordLink').addEventListener('click', function(event) {
                        event.preventDefault(); 
                   
                        const toast = document.getElementById('toast');
                        toast.style.visibility = 'visible';
                   
                        setTimeout(function() {
                          toast.style.visibility = 'hidden';
                        }, 5000);
                      });
                    </script>
                   
                </div>

            </div>
        </div>
    </div>
</div>

    <!-- JQuery and Bootstrap 4 CDN -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="{{ asset('js/hideError.js') }}"></script>
    
</body>
</html>
