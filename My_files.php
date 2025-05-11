<?php
session_start();

require("../db.php");

$user_email = $_SESSION['user'];

$user_sql ="SELECT * FROM users WHERE email = '$user_email'";

$user_res = $db->query($user_sql);

$user_data = $user_res->fetch_assoc();

$user_id = $user_data['id'];
$total_storage = $user_data['storage'];

$tf ="user_".$user_id;
$e_mail ="user".$user_email;

?>
<h1 class="text-center mt-2 mb-3">  All files</h1>
    <?php
        $file_data_sql ="SELECT * FROM $e_mail";
        $file_res = $db->query($file_data_sql);

        while($file_array = $file_res->fetch_assoc())
        {
            $fd_array = pathinfo($file_array['file_name']);

            $f_ext = $fd_array['extension'];
            $basename = $fd_array['basename'];

            echo '
            <div class="col-md-4">
                    <div class="me-3">';

                    if($f_ext == "csv")
                    {
                        echo "<img src='images/csv.png' class='thumb'>";
                    }
                    else if($f_ext == "doc" || $f_ext =="docx")
                    {
                        echo "<img src='images/doc.png' class='thumb'>";
                    }
                    else if($f_ext == "mp3")
                    {
                        echo "<img src='images/mp3.png' class='thumb'>";
                    }
                    else if($f_ext == "mp4")
                    {
                        echo "<img src='images/mp4.png' class='thumb'>";
                    }
                    else if($f_ext == "pdf")
                    {
                        echo "<img src='images/pdf.png' class='thumb'>";
                    }
                    else if($f_ext == "ppt" || $f_ext == "pptx" )
                    {
                        echo "<img src='images/ppt.png' class='thumb'>";
                    }
                    else if($f_ext == "txt")
                    {
                        echo "<img src='images/txt.png' class='thumb'>";
                    }
                    else if($f_ext == "video")
                    {
                        echo "<img src='images/video.png' class='thumb'>";
                    }
                    else if($f_ext == "xls" || $f_ext == "xlsx")
                    {
                        echo "<img src='images/xls.png' class='thumb'>";
                    }
                    else if($f_ext == "zip")
                    {
                        echo "<img src='images/zip.png' class='thumb'>";
                    }
                    else if($f_ext == "jpg" || $f_ext =="jpeg" || $f_ext =="png" || $f_ext =="gif" || $f_ext =="webp")
                    {
                        echo "<img src='data/".$tf."/".$basename."' class='thumb'>";
                    }

                    echo '</div>
                                   </div>
            </div>
            ';
        }
    ?>

<script>
    $(document).ready(function(){
        $(".del").each(function(){
            $(this).click(function(){
                var id = $(this).attr('id');
                var folder = $(this).attr('folder');
                var file =$(this).attr('file');
                var ce =$(this);

                $.ajax({
                    type:"POST",
                    url :"php/del_file.php",
                    data :{
                        id:id,
                        folder:folder,
                        file:file
                    },
                    success:function(response){
                        var obj = JSON.parse(response);

                        if (obj.msg =="File Delete Success")
                        {
                            var new_per =(obj.used_storage*100)/<?php echo $total_storage ?>;

                            $(".us").html(obj.used_storage);
                            $(".pb").css("width",new_per+"%");

                            var div =document.createElement("DIV");
                            div.className ="alert alert-success mt-3";
                            div.innerHTML = obj.msg;
                            $(".upload_msg").append(div);
                            $(ce).parent().parent().parent().parent().remove();

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
                })
            })
    
// star function code
            $(".star").each(function(){
                $(this).click(function(){
                    var star_id = $(this).attr('id');
                    var star_status =$(this).attr('status');
                    var s_folder =$(this).attr('folder');

                    $.ajax({
                        type:"POST",
                        url:"php/star_files.php",
                        data:{
                            sid:star_id,
                            s_status:star_status,
                            s_folder :s_folder

                        },
                        success:function(response){
                           if (response.trim() == "success") 
                           {
                            $('[p_link="My_files"]').click();

                           }
                           else
                           {
                                alert("response");
                           }
                        }
                    })
                })
            })
    })
        
</script>
