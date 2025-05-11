
<?php
session_start();
if(empty($_SESSION['user']))
{
    header("Location:login.php");
}


require("php/db.php");
$user_email = $_SESSION['user'];

$user_sql ="SELECT * FROM users WHERE email = '$user_email'";

 $user_res = $db->query($user_sql);

 $user_data = $user_res->fetch_assoc();

 $user_name = $user_data['full_name'];

 $total_storage = $user_data['storage'];

 $used_storage = $user_data['used_storage'];

 $per = round(($used_storage*100)/$total_storage,2);

 $user_id = $user_data['id'];
 
 $tf ="user_".$user_id;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/bootstrap.bundle.min.js"></script>
     <script src="js/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"/>
    <style>
    .main-container {
        width: 100%;
        height: 100vh;
        display: flex;
    }
    .left {
        width: 17%;
        height: 100%;
        background-color: #080429;
    }
    .right {
        width: 90%;
        height: 100%;
        overflow: auto;
    }
    .profile_pic {
        width: 100px;
        height: 100px;
        border-radius: 100%;
        border: 4px solid white;
    }
    .line {
        background-color: red;
        width: 100%;
    }
    .storage {
        width: 80%;
    }
    .thumb {
        width: 75px;
        height: 75px;
    }
</style>

</head>
<body>

        <div class="main-container d-flex">
                    <div class="left">
                    <div class="d-flex justify-content-center align-items-center flex-column pt-4">
                        <div class="profile_pic d-flex justify-content-center 
                        align-items-center">
                            <i class="fa fa-user fs-1 text-white"></i>
                        </div>
                        <span class="text-white fs-3 mt-3"><?php echo $user_name ?></span>
                     <hr class="line">

                     <button class="btn btn-light rounded-pill upload"> <i class="fa fa-upload"> 
                     </i>Upload file</button>

                     <div class="progress storage mt-3">
                        <div class="progress-bar bg-primary upload_p" style="width:)%"></div>                        
                            </div>
                            <div class="upload_msg"></div>
                    
                     <hr class="line">

                     <span class="text-white mb-2">STORAGE</span>
                    <div class="progress storage">
                        <div class="progress-bar bg-primary pb" style="width:<?php echo $per ?>%"></div>                        
                    </div>
                        <span class="text-white"><span class="us"><?php echo $used_storage ?></span>MB/<?php echo $total_storage ?>MB</span>
                     <a href="php/logout.php" class="btn btn-light mt-3">Logout</a>
                    </div>

                    </div>
                    <div class="right">
                        <nav class="navbar navbar-light bg-light p-3 shadow-sm sticky-top">
                        <div class="container-fluid">
                            <form class="d-flex ms-auto">
                            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                            <button class="btn btn-outline-primary" type="submit">Search</button>
                            </form>
                        </div>
                        </nav>
                        <div class="content p-4">
                            <div class="row">
                            <?php
                            
                            $file_data_sql ="SELECT * FROM $tf";
                            $file_res = $db->query($file_data_sql);

                            while($file_array = $file_res->fetch_assoc())
                            {
                               $fd_array = pathinfo($file_array['file_name']);

                             

                               $file_name = $fd_array['filename']; 
                               $f_ext = $fd_array['extension'];
                               $basename = $fd_array['basename'];
                                
                               echo '
                               
                               <div class="col-md-"
                                    <div>
                                         <div>';
                                         if($f_ext == "csv")
                                        {
                                            echo "<img src='images/csv.png' class='thumb'>";
                                        }
                                        else if($f_ext == "doc" || $f_ext =="docx")
                                        {
                                            echo "<imd src='images/doc.png' class='thumb'>";
                                        }
                                        else if($f_ext == "file")
                                        {
                                            echo "<imd src='images/file.png' class='thumb'>";
                                        }else if($f_ext == "jpg")
                                        {
                                            echo "<imd src='images/jpg.png' class='thumb'>";
                                        }else if($f_ext == "mp3")
                                        {
                                            echo "<imd src='images/mp3.png' class='thumb'>";
                                        }else if($f_ext == "mp4")
                                        {
                                            echo "<imd src='images/mp4.png' class='thumb'>";
                                        }else if($f_ext == "pdf")
                                        {
                                            echo "<imd src='images/pdf.png' class='thumb'>";
                                        }else if($f_ext == "ppt" || $f_ext == "pptx" )
                                        {
                                            echo "<imd src='images/ppt.png' class='thumb'>";
                                        }else if($f_ext == "txt")
                                        {
                                            echo "<imd src='images/txt.png' class='thumb'>";
                                        }else if($f_ext == "video")
                                        {
                                            echo "<imd src='images/video.png' class='thumb'>";
                                        }else if($f_ext == "xls" || $f_ext == "xlsx")
                                        {
                                            echo "<imd src='images/xls.png' class='thumb'>";
                                        }
                                        else if($f_ext == "zip")
                                        {
                                            echo "<imd src='images/zip.png' class='thumb'>";
                                        }
                                        else if($f_ext == "jpg" || $f_ext =="jpeg" || $f_ext =="png"|| $f_ext =="gif"|| $f_ext =="webp")
                                        {
                                            echo "<imd src='data/".$tf."/".$basename."' class='thumb'>";
                                        }
                                     echo '</div>
                                     <div>
                                     </div>  
                                    </div>
                                  </div>
                               
                               ';
                            }
                            ?>
                            </div>
                        </div>             
                    </div>


        </div>
       
        <script>
            $(document).ready(function(){
                $(".upload").click(function(){
                    var input = document.createElement("INPUT");
                    input.setAttribute("type","file");
                    input.click();
                    input.onchange = function(){
                    var file = new FormData();
                    file.append("data",input.files[0]);
                        
                        $.ajax({
                            type:"POST",
                            url :"php/upload.php",
                            data : file,
                            processData:false,
                            contentType:false,
                            cache:false,
                            xhr :function(){
                                var request = new XMLHttpRequest();
                                request.upload.onprogress = function(e){
                                  var loaded = (e.loaded/1024/1024).toFixed(2);
                                  var total = (e.total/1024/1024).toFixed(2);
                                  var upload_per = ((loaded*100)/total).toFixed(0);

                                  $(".upload_p").css("width",upload_per+"%");
                                  $(".upload_p").html(upload_per+"%");
                                }
                                return request;
                            },
                            success:function(response){
                               var obj = JSON.parse(response);

                               if (obj.msg =="File Upload Succesfully") 
                               {
                                    var new_per =(obj.used_storage*100)/<?php echo $total_storage ?>;

                                   $(".us").html(obj.used_storage);
                                   $(".pb").css("width",new_per+"%");

                                   var div =document.createElement("DIV");
                                    div.className ="alert alert-success mt-3";
                                    div.innerHTML = obj.msg;
                                    $(".upload_msg").append(div);

                                        setTimeout(function(){
                                        $(".upload_msg").html("");
                                        $(".upload_p").css("width","0%");
                                        $(".upload_p").html("");
                                
                                        },3000);
                                    

                               }
                               else
                               {
                                
                                   var div =document.createElement("DIV");
                                    div.className ="alert alert-danger mt-3";
                                    div.innerHTML = obj.msg;
                                    $(".upload_msg").append(div);

                                        setTimeout(function(){
                                        $(".upload_msg").html("");
                                        $(".upload_p").css("width","0%");
                                        $(".upload_p").html("");
                                
                                        },3000);
                                    

                               }
                            

                            }
                        })
                    }
                });
            })
        </script>
</body>
</html>