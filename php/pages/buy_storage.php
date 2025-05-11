<?php

session_start();
require("../db.php");
$user_email = $_SESSION['user'];

$user_sql ="SELECT * FROM users WHERE email = '$user_email'";

 $user_res = $db->query($user_sql);

 $user_data = $user_res->fetch_assoc();

 $plan = $user_data['plans'];

 $p_status ="";
 if($plan != "free")
 {
    $ed =$user_data['expiry_date'];
    $cd = date('Y-m-d');
    if ($ed < $cd) 
    {
        $p_status ="deactivate";
    }
     else
     {
         $p_status ="activate";
     }
 
 }

$s_btn ='<button class="btn btn-secondary w-50 buy" plan="silver" amount="170">&#8377;170/Month</button>
           ';
$g_btn =' <button class="btn btn-secondary w-50 buy" plan="gold" amount="320">&#8377;320/month</button>
           ';
$p_btn =' <button class="btn btn-secondary w-50 buy" plan="premium" amount="500">&#8377;500/month</button>
           ';


           if ($plan == "silver" && $p_status =="activate")
            {
                $s_btn ='<button class="btn btn-secondary w-50 buy disabled" plan="silver" amount="170">Current Plan</button>
                ';
                $g_btn =' <button class="btn btn-secondary w-50 buy" plan="gold" amount="320">&#8377;320/month</button>
                ';
                $p_btn =' <button class="btn btn-secondary w-50 buy" plan="premium" amount="500">&#8377;500/month</button>
                ';
          }
          else if ($plan == "silver" && $p_status =="deactivate")
          {

            $s_btn ='<button class="btn btn-secondary w-50 buy" plan="silver" amount="170">&#8377;170/Month</button>
            ';
            $g_btn =' <button class="btn btn-secondary w-50 buy" plan="gold" amount="320">&#8377;320/month</button>
                        ';
            $p_btn =' <button class="btn btn-secondary w-50 buy" plan="premium" amount="500">&#8377;500/month</button>
                        ';
          }
           
           else if ($plan =="gold" && $p_status =="activate") 
           {
            $s_btn ='<button class="btn btn-secondary w-50 buy disabled" plan="silver" amount="170">&#8377;170/Month</button>
            ';
            $g_btn =' <button class="btn btn-secondary w-50 buy disabled" plan="gold" amount="320">Current Plan</button>
            ';
            $p_btn =' <button class="btn btn-secondary w-50 buy" plan="premium" amount="500">&#8377;500/month</button>
            ';

           }
           else if ($plan =="gold" && $p_status =="deactivate") 
          {
                 $s_btn ='<button class="btn btn-secondary w-50 buy disabled" plan="silver" amount="170">&#8377;170/Month</button>
                ';
                $g_btn =' <button class="btn btn-secondary w-50 buy" plan="gold" amount="320">&#8377;320/month</button>
                ';
                $p_btn =' <button class="btn btn-secondary w-50 buy" plan="premium" amount="500">&#8377;500/month</button>
                ';

          }

           else if ($plan =="premium" && $p_status =="activate") 
           {
                    
            $s_btn ='<button class="btn btn-secondary w-50 buy disabled" plan="silver" amount="170">&#8377;170/Month</button>
            ';
            $g_btn =' <button class="btn btn-secondary w-50 buy disabled" plan="gold" amount="320">&#8377;320/month</button>
            ';
            $p_btn =' <button class="btn btn-secondary w-50 buy disabled" plan="premium" amount="500">Current Plan</button>
            ';
            }
            else if ($plan =="premium" && $p_status =="deactivate") 
            {
            $s_btn ='<button class="btn btn-secondary w-50 buy disabled" plan="silver" amount="170">&#8377;170/Month</button>
            ';
            $g_btn =' <button class="btn btn-secondary w-50 buy disabled" plan="gold" amount="320">&#8377;320/month</button>
            ';
            $p_btn =' <button class="btn btn-secondary w-50 buy" plan="premium" amount="500">&#8377;500/month</button>
            ';
            }
          
        
?>

<div class="row">
    <h2 class="text-center mt-4">OUR PLANS</h2>
    <div class="col-md-4 px-5 mb-5">
        <div class="card shadow" style="background-color :#EAEAEA;border-width:4px; border-radius:12px;">
        <div class="card-body text-center border-0">  
        <label class="fs-5 mt-4">SILVER</label><br>
            <label class="fs-2">50 GB</label>
            <br><br>
            <?php
                echo $s_btn;
                ?>

             <hr>
                <label>50 GB Storage</label>
                <hr>
                <label>24X7 Technical Support</label>
                <hr>
                <label>Data Security</label>
                <hr>
                <label>SEO Services</label>
                <hr>
                <label>Email Statistics</label>
                <hr>
                <label>Sharing Flexibility</label>
        </div>
    </div>
    </div>

    <div class="col-md-4 px-5 mb-5">
        <div class="card shadow" style="background-color :#FFEFD5;border-width:4px; border-radius:12px;">
        <div class="card-body text-center border-0">  
        <label class="fs-5 mt-4">GOLD</label><br>
            <label class="fs-2">100 GB</label>
            <br><br>
                <?php
                echo $g_btn;
                ?>

            <hr>
                <label>100 GB Storage</label>
                <hr>
                <label>24X7 Technical Support</label>
                <hr>
                <label>Data Security</label>
                <hr>
                <label>SEO Services</label>
                <hr>
                <label>Email Statistics</label>
                <hr>
                <label>Sharing Flexibility</label>
        </div>
    </div>
    </div>

    <div class="col-md-4 px-5 mb-4">
        <div class="card shadow" style="background-color :#e8f2ff;border-width:4px; border-radius:12px;">
        <div class="card-body text-center border-0">  
        <label class="fs-5 mt-4">PREMIUM</label><br>
            <label class="fs-2">UNLIMITED</label>
            <br><br>
            <?php
                echo $p_btn;
                ?>

            <hr>
                <label>UNLIMITED Storage</label>
                <hr>
                <label>24X7 Technical Support</label>
                <hr>
                <label>Data Security</label>
                <hr>
                <label>SEO Services</label>
                <hr>
                <label>Email Statistics</label>
                <hr>
                <label>Sharing Flexibility</label>
        </div>
    </div>
    </div>
</div>

<script>
        $(document).ready(function(){
            $(".buy").each(function(){
                $(this).click(function(){
                
              var plan = $(this).attr("plan");
              var amt = $(this).attr("amount");
              location.href ="php/pay.php?plan="+plan+"&amt="+amt;
            })
        });
    })

</script>