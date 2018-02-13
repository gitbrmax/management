<!--Statt Main Content-->
<section>
<div class="main-content">
<div class="row">
<div class="inner-contatier">    
<div class="col-md-12 col-lg-12 col-sm-12 content-title"><h4>Paket</h4></div>

<!--Alert-->
<div class="system-alert-box">
<div class="alert alert-success ajax-notify"></div>
</div>
<!--End Alert-->


<div class="col-md-8 col-lg-8 col-sm-8 branch-div">
<!--Start Panel-->
<div class="panel panel-default">
    <!-- Default panel contents -->
    <div class="panel-heading">Add Sales Channel</div>
    <div class="panel-body add-client">
    <?php if(!isset($edit_sales_channel)){ ?>  
    <form id="add-paket">
      <input type="hidden" name="action" id="action" value="insert"/>  
      <input type="hidden" name="paket_id" id="paket_id" value=""/>    
      <div class="form-group">
        <label for="acc_name">Sales Channel</label>
        <input type="text" class="form-control" name="sales_channel" id="sales_channel">
      </div>
      <div class="form-group">
        <label for="balance">Branch</label>
        <input type="text" class="form-control" name="branch_id" id="branch_id">
      </div>
      <div class="form-group">
        <label for="balance">Sub Channel</label>
        <input type="text" class="form-control" name="sub_channel" id="sub_channel">
      </div>
      <div class="form-group">
        <label for="note">Input By</label>
        <input type="text" class="form-control" name="update_by" id="update_by" value="<?php echo $this->session->userdata('username'); ?>" readonly>
      </div>    
      <button type="submit" class="mybtn btn-submit"><i class="fa fa-check"></i> Save</button>
    </form>
    <?php }else{ ?>

    <form id="add-paket">
      <input type="hidden" name="action" id="action" value="update"/>  
      <input type="hidden" name="id_channel" id="id_channel" value="<?php echo $edit_sales_channel->id_channel ?>"/>   
      <div class="form-group">
        <label for="sales_channel">Sales Channel</label>
        <input type="text" class="form-control" name="sales_channel" id="sales_channel" value="<?php echo $edit_sales_channel->sales_channel ?>">
      </div>
      <div class="form-group">
        <label for="ketua">Branch</label>
        <input type="text" class="form-control" name="branch_id" id="branch_id" value="<?php echo $edit_sales_channel->branch_id ?>">
      </div>
      <div class="form-group">
        <label for="ketua">Sub Channel</label>
        <input type="text" class="form-control" name="sub_channel" id="sub_channel" value="<?php echo $edit_sales_channel->sub_channel ?>">
      </div> 
      <div class="form-group">
        <label for="update_by">Update By</label>
        <input type="text" class="form-control" name="update_by" id="update_by" value="<?php echo $this->session->userdata('username'); ?>" readonly>
      </div>    
          
      <button type="submit"  class="mybtn btn-submit"><i class="fa fa-check"></i> Save</button>
    </form>

 <?php } ?>

    </div>
    <!--End Panel Body-->
</div>
<!--End Panel-->    
    
</div>


</div><!--End Inner container-->
</div><!--End Row-->
</div><!--End Main-content DIV-->
</section><!--End Main-content Section-->
<script type="text/javascript">
$(document).ready(function(){

if($(".sidebar").width()=="0"){
  $(".main-content").css("padding-left","0px");
} 

//for number only
$("#harga_paket").keypress(function (e) {
  //if the letter is not digit then display error and don't type anything
  if (e.which != 8 && e.which != 0 &&  (e.which < 48 || e.which > 57)) {
    //display error message
    return false;
  }
});
$('#id_kategori').select2();

$('#add-paket').on('submit',function(){    
  $.ajax({
    method : "POST",
    url : "<?php echo site_url('paket/add/insert') ?>",
    data : $(this).serialize(),
    beforeSend : function(){
      $(".block-ui").css('display','block'); 
    },success : function(data){ 
    if(data=="true"){  
      sucessAlert("Saved Sucessfully"); 
      $(".block-ui").css('display','none'); 
      if($("#action").val()!='update'){        
        $('#nama_paket').val("");
        $('#harga_paket').val("");
        $("#aktif").val("");
        $('#id_kategori').val("");      
      }
    }else{
      failedAlert2(data);
      $(".block-ui").css('display','none');
    }   
    }
  });    
  return false;

});

});
</script>