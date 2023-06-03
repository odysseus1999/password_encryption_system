<?php
require_once "config.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owenership - Password</title>
    <link rel="stylesheet" href="./assets/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="./assets/css/bootstrap.min.css">
    <script src="./assets/js/jquery-3.6.1.min.js"></script>
    <script src="./assets/fontawesome/js/all.min.js"></script>
    <script src="./assets/js/bootstrap.bundle.min.js"></script>
    <style>
        html, body{
            min-height: calc(100%);
            width: calc(100%);
        }
    </style>
</head>
<body class="bg-gradient bg-dark">
    <div class="container my-5">
        <div class="col-lg-8 col-md-7 col-sm-12 mx-auto my-auto">
            <h1 class="text-center text-light fw-bold">
                Setup Admin Password Encryption
            </h1>
            <hr class="bg-light">
            <?php if(isset($message)): ?>
                <div class="alert alert-success rounded-0 mb-2">
                    <div><?= $message ?></div>
                </div>
            <?php unset($message) ?>
            <?php endif; ?>
            <div class="card rounded-0 shadow my-4">
                <div class="card-header rounded-0">
                    <div class="card-title"><b>Please fill-in all the required fields</b></div>
                </div>
                <div class="card-body rounded-0">
                    <div class="container-fluid">
                        <form action="" id="setup-form" method="POST">
                            <div class="mb-3">
                                <label for="firstname" class="control-label">First Name</label>
                                <input type="text" class="form-control rounded-0" id="firstname" name="firstname" value="" required="required">
                            </div>
                            <div class="mb-3">
                                <label for="middlename" class="control-label">Middle Name</label>
                                <input type="text" class="form-control rounded-0" id="middlename" name="middlename" value="">
                            </div>
                            <div class="mb-3">
                                <label for="lastname" class="control-label">Last Name</label>
                                <input type="text" class="form-control rounded-0" id="lastname" name="lastname" value="" required="required">
                            </div>
                            <div class="mb-3">
                                <label for="password" class="control-label">Password</label>
                                <div class="input-group rounded-0">
                                    <input type="password" class="form-control rounded-0" id="password" name="password" value="" required="required">
                                    <button class="input-group-button btn btn-light border password_show" tabindex="-1" type="button"><i class="fa fa-eye-slash"></i></button>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="cpassword" class="control-label">Confirm  Password</label>
                                <div class="input-group rounded-0">
                                    <input type="password" class="form-control rounded-0" id="cpassword" name="cpassword" value="" required="required">
                                    <button class="input-group-button btn btn-light border password_show" tabindex="-1" type="button"><i class="fa fa-eye-slash"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-footer py-2">
                    <div class="d-flex justify-content-center">
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <button class="btn btn-primary btn-sm rounded-0 w-100" form="setup-form"><i class="fa fa-save"></i> Login</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
    $(document).ready(function(){
        $('.password_show').click(function(){
            var cur_type = $(this).siblings('input').attr('type')
            if(cur_type == 'password'){
                $(this).html("<i class='fa fa-eye'></i>")
                $(this).siblings('input').attr('type', 'text').focus()
            }else{
                $(this).html("<i class='fa fa-eye-slash'></i>")
                $(this).siblings('input').attr('type', 'password').focus()
            }
        })


        $('#setup-form').submit(function(e){
            e.preventDefault()
            $('button[form="setup-form"]').attr('disabled',true)
            var el = $("<div>")
            el.addClass("alert msg")
            el.hide()
            $('.msg').remove()
            $.ajax({
                url:"api.php?action=setup_ownership",
                type:"post",
                data: $(this).serialize(),
                dataType:'json',
                error: err=>{
                    console.error(err)
                    alert("An error occurred while saving the data. Please try again.");
                    location.reload()
                },
                success:function(response){
                    if(response.status == 'success'){
                        location.replace("login.php");
                    }else if(response.status == 'failed'){
                        el.addClass("alert-danger")
                        el.text(response.error)
                    }else{
                        el.addClass("alert-danger")
                        el.text("Saving Data Failed dur to unkown reason.")
                    }
                    $('#setup-form').prepend(el)
                    el.show('slideDown')
                    $('button[form="setup-form"]').attr('disabled',false)
                }
            })
        })
    })
</script>
</html>