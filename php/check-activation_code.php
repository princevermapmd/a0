<?php

require("db.php");

if ($_SERVER['REQUEST_METHOD'] == "POST")
{
    
        $email = $_POST['email'];
        $atc = $_POST['atc'];    

        $check = "SELECT * FROM users WHERE email ='$email' AND activation_code ='$atc'";

        $response = $db->query($check);
        if($response->num_rows !=0) 
        {
          $status_update ="UPDATE users SET status = 'active' WHERE email = 
          '$email'";
          if($db->query($status_update))
          {
            $get_id ="SELECT id FROM users WHERE email = '$email'";
            $id_res = $db->query($get_id);
            $id_array = $id_res->fetch_assoc();
            $user_table_name ="user_".$id_array['id'];

            $create_user_table ="CREATE TABLE $user_table_name(
            id INT(11) NOT NULL AUTO_INCREMENT,
            file_name VARCHAR(100),
            file_size VARCHAR(100),
            star VARCHAR(100) DEFAULT 'no',
            date_time DATETIME DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY(id)
            )";
                
                if($db->query($create_user_table))
                {
                    if(mkdir("../data/" . $user_table_name))
                      {
                      echo "active";
                      }
                      else 
                        {
                      echo "folder not created";
                      }

                  }
              else
            {
              echo "Table not created";
            }
            
          }
          else
           {
            echo "stats not updated";
          }
         }
        else 
        {
          echo "wrong activation code";      
        }
}
else
 {
    echo "unauthorised request";
}
?>