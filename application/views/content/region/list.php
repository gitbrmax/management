<!--Statt Main Content-->
<section>
<div class="main-content">
<div class="row">
<div class="inner-contatier">    
<div class="col-md-12 col-lg-12 col-sm-12 content-title"><h4><RP></RP>Region</h4></div>
<div class="col-md-12 col-lg-12 col-sm-12">
<!--Start Panel-->
<div class="panel panel-default">
    <!-- Default panel contents -->
    <div class="panel-heading">Manage Region <div class="add-button">
        <?php if($this->session->userdata('level')==4){ ?>
        <a class="mybtn btn-default asyn-link" href="<?php echo site_url('Admin/region_add') ?>">Add</a>
        <?php } ?>
    
    <a class="mybtn btn-default" href="<?php echo site_url('Admin/region_export/asyn') ?>" download="<?php echo site_url('Admin/region_export/asyn') ?>">Export</a>
    </div></div>
    <div class="panel-body" style="overflow: auto; font-size: 10px; font-family: tahoma;">
        <table id="repeat-region-table" class="display responsive nowrap" cellspacing="0" width="100%">
            <thead>    
                <th>NO</th><th>NAMA REGION</th>
                <th>STATUS</th><th>UPDATE BY</th><th>UPDATED</th>
                <th class="single-action">ACTION</th>
            </thead>

            <tbody>
                <?php $no=1; foreach($region as $new) { ?>    
                <tr>
                    <td class="date"><?php echo $no++; ?></td>
                    <td><?php echo $new->id_region.' - '.strtoupper($new->nama_region) ?></td>
                    <td><?php if($new->status_region==1){ echo "AKTIF"; }else{ echo "TIDAK AKTIF"; }  ?></td>
                    <td><?php echo strtoupper($new->nama) ?></td>
                    <td><?php echo strtoupper($new->tanggal_update) ?></td>
                    <td>
                        <?php if($this->session->userdata('level')==4){ ?>
                        <a class="mybtn btn-info btn-xs edit-btn" data-toggle="tooltip" 
                    title="Click For Edit" href="<?php echo site_url('Admin/region_edit/'.$new->id_region) ?>">Edit</a> &nbsp; 
                    <a class="mybtn btn-danger btn-xs region-remove-btn" data-toggle="tooltip" title="Click For Delete" href="<?php echo site_url('Admin/region_add/remove/'.$new->id_region) ?>">Delete</a>
                        <?php } ?>
                    </td>
                </tr>
               <?php } ?>
            </tbody>       

        </table>
    </div>
    <!--End Panel Body-->
</div>
<!--End Panel-->    
    
</div>


</div><!--End Inner container-->
</div> <!-- End Row-->
</div><!--End Main-content DIV-->
</section><!--End Main-content Section-->


<script type="text/javascript">
$(document).ready(function() {
    $("body").tooltip({ selector: '[data-toggle=tooltip]' });   
        $(".manage-client").niceScroll({
        cursorwidth: "8px",cursorcolor:"#7f8c8d"
    });

    $("#repeat-region-table").DataTable();
    $(".dataTables_length select").addClass("show_entries");

    $(document).on('click','.edit-btn',function(){

        var link=$(this).attr("href"); 
        // alert(link);
        $.ajax({
            method : "POST",
            url : link,
            beforeSend : function(){
                $(".block-ui").css('display','block'); 
            },success : function(data){ 
                //var link = location.pathname.replace(/^.*[\\\/]/, ''); //get filename only  
                history.pushState(null, null,link);  
                $('.asyn-div').load(link+'/asyn',function() {
                    $(".block-ui").css('display','none');     
                });     
               
            }
        });

        return false;
    });

    $(document).on('click','.region-remove-btn',function(){  
        var main=$(this);
        swal({title: "Are you sure Want To Delete?",
        text: "You will not be able to recover this Data!",
        type: "warning",   showCancelButton: true,confirmButtonColor: "#DD6B55",   
        confirmButtonText: "Yes, delete it!",closeOnConfirm: false,
        showLoaderOnConfirm: true }, function(){ 
            ///////////////     
            var link=$(main).attr("href");    
            $.ajax({
                url : link,
                beforeSend : function(){
                    $(".block-ui").css('display','block'); 
                },success : function(data){
                    $(main).closest("tr").remove();    
                    //sucessAlert("Remove Sucessfully"); 
                    $(".system-alert-box").empty();
                    document.location.href = '<?php echo base_url()?>Admin/region_view';
                swal("Deleted!", "Remove Sucessfully", "success"); 
                    $(".block-ui").css('display','none');
                }    
            });
        }); 
        return false;       
    }); 

    $(document).on('click','.export-btn',function(){

        var link=$(this).attr("href"); 
        // alert(link);
        $.ajax({
            method : "POST",
            url : link,
            beforeSend : function(){
                $(".block-ui").css('display','block'); 
            },success : function(data){ 
                window.open(link+'/asyn','_blank');
                $(".block-ui").css('display','none');               
            }
        });

        return false;
    });
});

</script>

