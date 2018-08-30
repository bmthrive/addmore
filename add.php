<?php
    session_start();
    if(!isset($_SESSION['login_lab'])){ //if login in session is not set
        header("Location: index.php");
    } 

    include_once 'header.php';
    include_once 'sidebar.php';
    include_once 'conn.php';
    include_once 'functions.php';

    $products_all = dis_all_data('tb_service_based_products');
     
    $products_array = array();

    if(count($products_all) > 0 ){
       foreach($products_all as $ser){
           $products_array[$ser['id']] = $ser['name'];
       }
    }
    
    $serviceproduct = '<div class="form-group"><label for="BX_birth">Products</label><select class="product_selected form-controll" id="service_products_name" name="service_products_name[]" >';
   
    $serviceproduct .= '<option value="">Select Products</option>';
                 
    foreach($products_all as $product){
         
         $serviceproduct .= '<option value="'.$product['id'].'">'.$product['name'].'</option>';
    }
   
    $serviceproduct .= '</select></div><div class="form-group"><div><label>Quantity</label><input type="number" name="service_products_quantity[]" class="productservice_qua form-controll"></div></div>';
   
   
    if(isset($_POST['btnUpload'])){
        
        $products = '';
  	    if(isset($_POST['service_products_name'])){
  	         $products =  implode(",",$_POST['service_products_name']);
  	    }
        
        $quantity = '';
  	    if(isset($_POST['service_products_quantity'])){
  	         $quantity =  implode(",",$_POST['service_products_quantity']);
  	    }
        
        $arr['name'] = $_POST['name'];
        $arr['price'] = $_POST['price'];
        $arr['status'] = isset( $_POST['status'] )? $_POST['status'] : '0';   
        $arr['products'] = $products;
        $arr['quantity'] = $quantity;
        
        
        insert_records($arr,'tb_services','Data Added','Error occureed');
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
              
            </div>
            <!-- /.box-header -->
            <!-- form start -->
           <div class="add-product">
  <form enctype="multipart/form-data" action="" method="POST">


			  	<div class="form-group">
          			<label for="description">Service Name</label>
        			<input type="text" class="form-control" name="name"  required=""  />
        		</div>


        		<div class="form-group"> 	
          			<label for="description">Price</label>
        			 <input type="text" class="form-control"  name="price" />
        		</div>
        		
            <div class="form-group">  
                <input type="checkbox" name="status" value="1">Status<br>
            </div>

            <div class="inline-btns" style="text-align:center; margin:0 auto">

                <p>
                    <a class="btn btn-primary new-btn bg-color" style=" background-color:#2e4678;" id="add_service" > <img src="images/icon-hair3.png" "> <span> Add Products </span></a>
                </p>
                      
            </div>

            <fieldset class="row2">
                        <table id="dynamic_field" class="table" style="border: none;"> </table>
                        <div class="clear"></div>
                     </fieldset>

    			<input type="submit" value="Submit" class="btn btn-primary" name="btnUpload"></input>
  </form>
</div>
          </div>
          <!-- /.box -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->

  <?php 
		include_once 'footer.php';
  ?>


<script>
  
    var serviceproduct = '<?php echo $serviceproduct; ?>'

    $(document).ready(function(){  
         var p=1;  
         var i=1;  
   
         $('#add_service').click(function(){  
   
              i++;         
              $('#dynamic_field').append('<div class="row row-new1" id="row'+i+'" style="padding-left:4px; border-top:solid 2px #2e4678; margin-left:1; margin-top:10px;">'+serviceproduct+'<div class="form-group clo"><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove remove_service button-size">X</button></div> </div>');  
         
        });
        
        $(document).on('click', '.remove_service', function(event){  
   
            event.preventDefault();
            var button_id = $(this).attr("id");   
            $('#row'+button_id+'').remove();  
            $('#'+button_id+'').remove(); 
   
       });

    });

</script>
