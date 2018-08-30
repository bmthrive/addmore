<?php 

  session_start();
  if(!isset($_SESSION['login_lab'])){ //if login in session is not set
      header("Location: index.php");
  } 

  include_once 'header.php';
  include_once 'sidebar.php';
  include_once 'conn.php';

  include_once 'functions.php';

  $data = display_data('id',$_REQUEST['id'],'tb_services');

  $disalldata = dis_all_data('tb_service_based_products');

  $servicebasedproducts = array();

  $products_array = array();

  foreach($disalldata as $disdata){

    $servicebasedproducts[$disdata['id']] = $disdata['name'];
    $products_array[$disdata['id']] = $disdata['name'];

  }

  // echo '<pre>';

  // var_dump($servicebasedproducts);

  // echo '</pre>';


    $serviceproduct = '<div class="form-group"><label for="BX_birth">Products</label><select class="product_selected form-controll" id="service_products_name" name="service_products_name[]" >';
   
    $serviceproduct .= '<option value="">Select Products</option>';
                 
    foreach($servicebasedproducts as $pp => $kk){
         
         $serviceproduct .= '<option value="'.$pp.'">'.$kk.'</option>';
    }
   
    $serviceproduct .= '</select></div><div class="form-group"><div><label>Quantity</label><input type="number" name="service_products_quantity[]" class="productservice_qua form-controll"></div></div>';
  
  if(isset($_POST['btnSubmit'])){

      // echo '<pre>';
      // var_dump($_POST);
      // echo '</pre>';
      // die();

        $arr;

        $products = '';
        if(isset($_POST['service_products_name'])){
             $products =  implode(",",$_POST['service_products_name']);
        }
        
        $quantity = '';
        if(isset($_POST['service_products_quantity'])){
             $quantity =  implode(",",$_POST['service_products_quantity']);
        }

        $arr['products'] = $products;
        $arr['quantity'] = $quantity;

        $arr['name'] = $_POST['name'];
        $arr['price'] = $_POST['price'];
        $arr['status'] = isset( $_POST['status'] )? $_POST['status'] : '0';
        update_records($arr, 'id',$_REQUEST['id'],'tb_services','Data Added','Error occureed','edit-services.php?id='.$_REQUEST['id']);
                  
  }


?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">View Service</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <div class="add-product">
             <form enctype="multipart/form-data" action="" method="POST">

                <div class="form-group">
                    <label for="exampleInputPassword1">Name</label>
                    <input type="text" class="form-control" name = "name" id="name" value="<?php echo $data['name']; ?>">
                </div>

                <div class="form-group">
                    <label for="exampleInputPassword1">Price</label>
                    <input type="text" class="form-control" name = "price" id="name" value="<?php echo $data['price']; ?>">
                </div>

                <div class="form-group">
                    <input type="checkbox" name="status" value="1" <?php if($data['status'] == '1'){  echo ' checked';} ?> >Status <br>
                </div>

                    <div class="inline-btns" style="text-align:center; margin:0 auto">

                        <p>
                            <a class="btn btn-primary new-btn bg-color" style=" background-color:#2e4678;" id="add_service" > 
                                <img src="images/icon-hair3.png" > 
                                <span>  Add Products </span>
                            </a>
                        </p>
                    </div>

                    <fieldset class="rowklm">
                        <div id="dynamic_field" class="table" style="border: none;"> 
                            <?php 

                                if(trim( $data['products'] ) != '' ){

                                    $quantityarray = explode(',',$data['quantity']);

                                    $pparray = explode(',',$data['products']);
                                
                                    if( count($pparray) > 0 ){

                                        $count = 1; 

                                        $i = 0 ;

                                        foreach($pparray as $pp){

                            ?>
      <div class="row row-new1  <?php echo $count; ?>" id="row<?php echo $count; ?>" style=" padding-left:4px; border-top:solid 2px #2e4678; margin-left:1; margin-top:10px; ">

          <div class="form-group"><label for="BX_birth">Products</label>

              <select class="product_selected form-controll" id="service_products_name" name="service_products_name[]" >';
                  
                  <option value="<?php echo $pp; ?>"> 
                      <?php echo $servicebasedproducts[$pp]; ?> 
                  </option>

              </select>

          </div>

          <div class="form-group">
              <div><label>Quantity</label>
                <input type="number" name="service_products_quantity[]" class="productservice_qua form-controll" value="<?php echo $quantityarray[$i] ?>">
              </div>
          </div>

          <div class="form-group clo">
              <button type="button" name="remove" id="<?php echo $count; ?>" class="btn btn-danger btn_remove remove_service button-size">X</button>
          </div> 

      </div>
      
                                  <?php 
                                        $i = $i + 1 ;
                                         $count = $count + 1 ;

                                        }
                                    }

                                }

                            ?>

                        </div>
                        <div class="clear"></div>

                     </fieldset>

            <input type="submit" value="SUBMIT" class="btn btn-primary" name="btnSubmit"></input>
        </form>

</div>

          <div class="">

                <div class="table-responsive " style=" border:1px solid #CCCCCC;">
                    <table class="table">
                       <tr style="background-color:#8EB1E7;"><td colspan="8"><b>Sales Record</b></td></tr>
                      <tr><th>#</th><th>Year</th><th>Month</th><th>Sales Per Month</th></tr>
                      <tr>
                          <?php 

                            //$sql = "SELECT YEAR(sales_date) AS y, MONTH(sales_date) AS m , MONTHNAME(sales_date) as mnthname, COUNT(DISTINCT id) as tot FROM tb_sales WHERE type = 'service' AND service_id = '".$_REQUEST['id']."' GROUP BY y, m ";

                          $sql = "SELECT YEAR(sales_date) AS y, MONTH(sales_date) AS m , MONTHNAME(sales_date) as mnthname, COUNT(DISTINCT id) as tot FROM tb_sales WHERE CONCAT(',' , service_id , ',') LIKE '%,".$_REQUEST['id'].",%' GROUP BY y, m ";

                            //echo $sql;
                            $result = mysqli_query($conn,$sql );
                            
                            foreach($result as $re) {
                                    
                                echo '<tr><td></td><td>'.$re['y'].'</td><td>'.$re['mnthname'].'</td><td>'.$re['tot'].'</td></tr>';
                                    
                            }
                       
                          ?>
                          
                      </tr>
                    
                    </table>
                    </div>
            
          </div>

          </div>
          <!-- /.box -->

         
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <?php 

include_once 'footer.php';

  ?>

<script>
  
    var serviceproduct = '<?php echo $serviceproduct; ?>';
    
    $(document).ready(function(){  

        $('#add_service').click(function(){  
              var i  = '<?php echo $i; ?>';
              i++;  
              //alert('i ' + i);       
              $('#dynamic_field').append('<div class="row row-new1  '+i+'" id="row'+i+'" style="padding-left:4px; border-top:solid 2px #2e4678; margin-left:1; margin-top:10px;">'+serviceproduct+'<div class="form-group clo"><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove remove_service button-size">X</button></div> </div>');  

        });
        
        $(document).on('click', '.remove_service', function(event){  
   
            event.preventDefault();
            var button_id = $(this).attr("id");   
            $('#row'+button_id+'').remove();  
            $('#'+button_id+'').remove(); 
   
       });

    });

</script>
