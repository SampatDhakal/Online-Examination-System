<?php

include('main/admin/oea.php');

$object = new oea();


?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Online Examination</title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
        <!-- Bootstrap icons-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="main/vendor/bootstrap/styles.css" rel="stylesheet" />
    </head>
    <body>
        <!-- Responsive navbar-->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container px-5">
                <a class="navbar-brand" href="#!">Online Examination Application</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item"><a class="nav-link active" aria-current="page" href="#!">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="main/index.php">Student</a></li>
                        <li class="nav-item"><a class="nav-link" href="main/admin/index.php">Admin</a></li>
                    </ul>
                </div>
            </div>
        </nav>

         <img src="assets/1.jpg" class="img-fluid" alt="Responsive image">
        <section class="bg-white py-5 border-bottom">
            <div class="container px-5 my-5 px-5">
                <div class="container">
                    <div class="card mt-4 mb-4">
                        <div class="card-header">Latest News</div>
                        <div class="card-body">
                        <?php
                        $object->query = "
                        SELECT * FROM exam_oea  
                        WHERE exam_result_datetime != '0000-00-00 00:00:00' 
                        ORDER BY exam_result_datetime ASC
                        ";

                        $object->execute();

                        if($object->row_count() > 0)
                        {
                            $result = $object->statement_result();
                            foreach($result as $row)
                            {
                              if(time() < strtotime($row["exam_result_datetime"]))
                              {
                                  echo '<p>Result of <b>'.$row["exam_title"].' </b>exam of <b>'.$object->Get_class_name($row["exam_class_id"]).'</b> will publish on '.$row["exam_result_datetime"].'</p>';
                              }
                            
                              if(time() > strtotime($row["exam_result_datetime"]))
                              {
                                  echo '<p>Result of <b>'.$row["exam_title"].' </b>exam of <b>'.$object->Get_class_name($row["exam_class_id"]).'</b> was published on '.$row["exam_result_datetime"].'</p>';
                              }
                            }
                        }
                        else
                        {
                            echo '<p>No News Found</p>';
                        }



                        ?>
                      <?php
                      $object->query = "
                        SELECT * FROM pexam_oea 
                        WHERE exam_result_datetime != '0000-00-00 00:00:00' 
                        ORDER BY exam_result_datetime ASC
                        ";

                        $object->execute();

                        if($object->row_count() > 0)
                        {
                            $result = $object->statement_result();
                            foreach($result as $row)
                            {
                                if(time() < strtotime($row["exam_result_datetime"]))
                                {
                                    echo '<p>Result of <b>'.$row["exam_title"].' </b>exam of <b>'.$object->Get_class_name($row["exam_class_id"]).'</b> will publish on '.$row["exam_result_datetime"].'</p>';
                                }
                              
                                if(time() > strtotime($row["exam_result_datetime"]))
                                {
                                    echo '<p>Result of <b>'.$row["exam_title"].' </b>exam of <b>'.$object->Get_class_name($row["exam_class_id"]).'</b> was published on '.$row["exam_result_datetime"].'</p>';
                                }
                            }
                        }
                        else
                        {
                            echo '<p>No News Found</p>';
                        }



                        ?>
                        </div>
                    </div>
                </div>
                    
            </div>
        </section>

        
        <!-- Footer-->
        <footer class="py-5 bg-light">
            <div class="container px-5"><p class="m-0 text-center text-dark">Copyright &copy; Web Based Online Examination Application 2021</p></div>
        </footer>
    </body>
</html>
