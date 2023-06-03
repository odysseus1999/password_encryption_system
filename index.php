<?php
require_once "config.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Encryption</title>
    <link rel="stylesheet" href="./assets/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="./assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="stylesheet.css">
    <script src="./assets/js/jquery-3.6.1.min.js"></script>
    <script src="./assets/fontawesome/js/all.min.js"></script>
    <script src="./assets/js/bootstrap.bundle.min.js"></script>
</head>
<body class="">
    <nav class="navbar navbar-dark navbar-expand-lg bg-dark bg-gradient">
        <div class="container">
            <a class="navbar-brand" href="./">Password Encryption</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="./">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="login.php">Logout</a>
                    
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container my-5">
        <div class="col-lg-12 col-md-12 col-sm-12 mx-auto">
            <?php if(isset($_SESSION['success_msg'])): ?>
                <div class="alert alert-success rounded-0 mb-2">
                    <div><?= $_SESSION['success_msg'] ?></div>
                </div>
            <?php unset($_SESSION['success_msg']) ?>
            <?php endif; ?>
            <div style='text-align:center;'>";
            <h1> Welcome,Admin</h1>
            </div>
            <div class="card rounded-0 shadow">
                <div class="card-header rounded-0">
                    <div class="d-flex">
                        <div class="col-auto flex-shrink-1 flex-grow-1">
                            <div class="card-title"><b>Password List</b></div>
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-sm btn-primary rounded-0" id="add_pass"><i class="fa fa-plus"></i> Admin Login</button>
                        </div>
                    </div>
                </div>
                <div class="card-body rounded-0">
                    <fieldset>
                        <legend>Instruction:</legend>
                        <p class="text-muted ps-4">Hover the Encrypted Password Table Cell to view the Decrypted Password</p>
                    </fieldset>
                    <div class="container-fluid">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center">Name</th>
                                    <th class="text-center">Userame</th>
                                    <th class="text-center">Password Decrypt</th>
                                    <th class="text-center">Encrypted</th>
                                    <th class="text-center">Action</th>

                                    
                                </tr>
                            </thead>
                            <tbody>
                                <?php $data = $db->get_results("SELECT * FROM `record_list`"); ?>
                                <?php if($data->num_rows > 0): ?>
                                <?php while($row = $data->fetch_assoc()): ?>
                                    <tr>
                                        <td class="p-1">
                                            <div class="lh-1">
                                                <div><?= $row['name'] ?></div>
                                            </div>
                                        </td>
                                        <td class="p-1"><?= $row['username'] ?></td>
                                        <td class="p-1 fw-bold encrypted_pass" data-value="<?= $row['password'] ?>"><?= str_repeat("*", strlen($row['password'])) ?></td>
                                        <td class="p-1 fw-bold decrypted_pass" data-value="<?= $row['password'] ?>"><?=$row['password'] ?></td>
                                        <td class="text-center">
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light border border-dark dropdown-toggle rounded-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    Action
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item edit_record" href="javascript:void(0)" data-id="<?= $row['id'] ?>">Edit</a></li>
                                                    <li><a class="dropdown-item" href="api.php?action=delete_record&id=<?= $row['id'] ?>" onclick="if(confirm('Are you sure to delete this record?') == false) event.preventDefault();">Delete</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                        
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Form Modal -->
    <div class="modal fade" id="formModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog rounded-0 modal-dialog-centered modal-lg modal-dialog-scrollable">
            <div class="modal-content rounded-0">
            <div class="modal-header rounded-0">
                <h1 class="modal-title fs-5" id="FormModalTitle"></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body rounded-0">
                <div class="container-fluid">
                    <form action="" id="password-form" method="POST">
                        <input type="hidden" name="id" value="">
                        <div class="mb-3">
                            <label for="name" class="control-label">Name</label>
                            <input type="text" class="form-control rounded-0" id="name" name="name" value="" required="required">
                        </div>
                        <div class="mb-3">
                            <label for="username" class="control-label">Username</label>
                            <input type="text" class="form-control rounded-0" id="username" name="username" value="" required="required">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="control-label">Password</label>
                            <div class="input-group rounded-0">
                                <input type="password" class="form-control rounded-0" id="password" name="password" value="" required="required">
                                <button class="input-group-button btn btn-light border password_show" tabindex="-1" type="button"><i class="fa fa-eye-slash"></i></button>
                            </div>
                        </div>
                       
                        
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-primary rounded-0" form="password-form">Save</button>
                <button type="button" class="btn btn-sm btn-secondary rounded-0" data-bs-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
    </div>
    <nav>
        <div class="text-center text-muted">Developed by <a class="text-decoration-none" href="mailto:manchalasreekanth999@gmail.com" target="_blank">Manchala</a> and published @ <a href="https://manchala.me" class="text-decoration-none" target="_blank">Manchala</a></div>
    </nav>
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
        $('#formModal').on("show.bs.modal", function(){
            $(this).find(".password_show").html("<i class='fa fa-eye-slash'></i>")
            $(this).find(".password_show").siblings('input').attr('type', 'password')
        })
        $('#formModal').on("hide.bs.modal", function(){
            
            $('#password-form')[0].reset();
            $('#password-form [name="id"]').val('')
        })

        $('#add_pass').click(function(){
            $('#FormModalTitle').text("Add New Login Record")
            $('#formModal').modal('show')
        })
        $('.encrypted_pass').on('mouseenter', function(){
            var _this = $(this)
            var encrypted_pass = _this.attr('data-value')
            var dotted_pass = _this.text()
            $.ajax({
                url:'api.php?action=get_real_password',
                method:'post',
                data:{password_encrypted : encrypted_pass},
                dataType:'json',
                error: err => {
                    console.error(err)
                },
                success:function(response){
                    if(response.status == 'success'){
                        _this.text(response.password_decrypt)
                    }else{
                        console.error(err)
                    }
                }
            })
            _this.on('mouseleave', function(){
                _this.text(dotted_pass)
            })
        })
        $('.edit_record').click(function(){
            var id = $(this).attr('data-id')
            $.ajax({
                url:'api.php?action=get_single&id='+id,
                dataType:"json",
                error: err => {
                    console.error(err)
                },
                success:function(response){
                    if(response.status == 'success'){
                        $("[name='id']").val(response.data.id)
                        $("[name='name']").val(response.data.name)
                        $("[name='username']").val(response.data.username)
                        $("[name='password']").val(response.data.password)
                        $("[name='description']").val(response.data.description)
                    
                        $('#FormModalTitle').text("Edit Password Record")
                        $('#formModal').modal('show')
                    }else if(response.status == 'failed'){

                        console.error(response.error)
                        alert(response.error)
                    }else{
                        console.error("Saving Data Failed dur to unkown reason.")
                        alert("Saving Data Failed dur to unkown reason.")
                    }
                    
                }
            })
           
        })
        $('#password-form').submit(function(e){
            e.preventDefault()
            $('button[form="password-form"]').attr('disabled',true)
            var el = $("<div>")
            el.addClass("alert msg")
            el.hide()
            $('.msg').remove()
            $.ajax({
                url:"api.php?action=save",
                type:"post",
                data: $(this).serialize(),
                dataType:'json',
                error: err=>{
                    console.error(err)
                    // alert("An error occurred while saving the data. Please try again.");
                    // location.reload()
                },
                success:function(response){
                    if(response.status == 'success'){
                        location.replace("index.php");
                    }else if(response.status == 'failed'){
                        el.addClass("alert-danger")
                        el.text(response.error)
                    }else{
                        el.addClass("alert-danger")
                        el.text("Saving Data Failed dur to unkown reason.")
                    }
                    $('#password-form').prepend(el)
                    el.show('slideDown')
                    $('button[form="password-form"]').attr('disabled',false)
                }
            })
        })
    })
</script>
</html>

