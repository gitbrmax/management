<!--Statt Main Content-->
<section>
<div class="main-content">
<div class="row">
<div class="inner-contatier">    
<div class="col-md-12 col-lg-12 col-sm-12 content-title"><h4>Report Viewer</h4></div>
<div class="col-md-12 col-lg-12 col-sm-12">
<!--Start Panel-->
<?php 
$status=array('sukses'=>'sukses',
  'valid'=>'valid',
  'masuk'=>'masuk');
$tgl = array("tanggal_aktif"=>"tanggal_aktif", "tanggal_validasi"=>"tanggal_validasi", "tanggal_masuk"=>"tanggal_masuk");
?>
<div class="panel panel-default">
    <!-- Default panel contents -->
    <div class="panel-heading">Service Level</div>
    <div class="panel-body" style="font-size: 10px; font-family: tahoma;">
        <div class="col-md-12 col-lg-12 col-sm-12 report-params">
            <form id="sales_cari" action="<?php echo site_url('Admin/reports_service_level/view') ?>">

                <div class="col-md-2 col-lg-2 col-sm-2"> 
                    <select class="form-control" name="vtanggal" id="vtanggal">
                        <?php foreach($tgl as $tgl){ 
                            if($btgl == $tgl){ ?>
                            <option value="<?php echo $tgl?>" selected><?php echo $tgl?></option>
                        <?php }else{ ?>
                            <option value="<?php echo $tgl?>"><?php echo $tgl?></option>
                         <?php } 
                        }?>
                    </select> 
                </div>

                <div class="col-md-2 col-lg-2 col-sm-2"> 
                    <select class="form-control" name="vstatus" id="vstatus">
                        <?php foreach($status as $status){ 
                            if($bstatus == $status){ ?>
                            <option value="<?php echo $status?>" selected><?php echo $status?></option>
                            <?php }else{ ?>
                            <option value="<?php echo $status?>"><?php echo $status?></option>
                            <?php }
                            } ?>
                        
                    </select> 
                </div>

                <div class="col-md-2 col-lg-2 col-sm-2"> 
                    <div class="form-group"> 
                        <div class='input-group'>
                            <input type="text" class="form-control" placeholder="<?php echo isset($bto_date) ? $bto_date : "Date To" ?>" name="vto-date" id="vto-date"/> 
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>  
                        </div> 
                    </div>
                </div>

                <div class="col-md-1 col-lg-1 col-sm-1"> 
                <button type="submit"  class="mybtn btn-submit"><i class="fa fa-play"></i></button>
                </div>
            </form>
        </div>


        <div class="Report-Toolbox col-md-6 col-lg-6 col-sm-6 col-md-offset-6 col-lg-offset-6 col-sm-offset-6">
        <a class="mybtn btn-default export-btn" style="float: right" href="<?php echo site_url('Admin/reports_service_level') ?>" >Export to Excel</a>
        </div>
        <div id="Report-Table" class="col-md-12 col-lg-12 col-sm-12" style="overflow: auto;">
            <div class="preloader"><img src="<?php echo base_url() ?>theme/images/ring.gif"></div>
            <div class="report-heading">
                <!-- <h4>Branch</h4> -->
            </div>
            <style style="text/css">
                /* Define the hover highlight color for the table row */
                .hoverTable tr:hover {
                      background-color: #b8d1f3;
                }
            </style>
            <div id="Table-div">
                <table class="table table-bordered hoverTable">
                    <thead>
                        <th>No</th>
                        <th>Branch</th>
                        <th>Rata2 SLA GraPARI</th>
                        <th>Rata2 SLA HVC </th>
                        <th>Total SLA</th>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>

        </div> 
    </div>
<!--End Panel Body-->
</div>     
    

<!--End Panel-->       
</div>
</div><!--End Inner container-->
</div><!--End Row-->
</div><!--End Main-content DIV-->
</section><!--End Main-content Section-->

<!--<script src="<?php echo base_url() ?>/theme/js/pdf/jspdf.debug.js"></script>-->

<script type="text/javascript">
$(document).ready(function() {
    $("#vto-date").datepicker(); 
    $("#vtanggal, #vstatus").select2({
    minimumResultsForSearch: Infinity    
    });

    $('#sales_cari').on('submit',function(){
        var link=$(this).attr("action");
        if($("#vto-date").val()!=""){
            //query data
            $.ajax({
                method : "POST",    
                url : link,
                data : $(this).serialize(),
                beforeSend : function(){
                    $(".preloader").css("display","block");
                },success : function(data){
                    $(".preloader").css("display","none"); 
                    if(data!="false"){
                        $("#Report-Table tbody").html(data);
                        // $(".report-heading p").html("Date From "+$("#from-date").val()+" To "+$("#to-date").val());
                    }else{
                        $("#Report-Table tbody").html("");
                        // $(".report-heading p").html("Date From "+$("#from-date").val()+" To "+$("#to-date").val());    
                        swal("Alert","Sorry, No Data Found !", "info");    
                    }
                }

            });
        }else{
            swal("Alert","Please Select Date Range.", "info");      
        }

        return false;
    });

    $(document).on('click','.export-btn',function(){

        var link=$(this).attr("href"); 
        var vtanggal = $("#vtanggal").val();
        var vstatus = $("#vstatus").val();
        var vtodate = $("#vto-date").val();
        // alert(link);
        $.ajax({
            method : "POST",
            url : link,
            beforeSend : function(){
                $(".block-ui").css('display','block'); 
            },success : function(data){ 
                window.open(link+'/export/'+vtanggal+'/'+vstatus+'/'+vtodate);
                $(".block-ui").css('display','none');               
            }
        });

        return false;
    });

});

 function Print(data) 
    {
        var w = (screen.width);
        var h = (screen.height);
        var mywindow = window.open('', 'Print-Report', 'width='+w+',height='+h);
        mywindow.document.write('<html><head><title>Print-Report</title>');
        mywindow.document.write('<link href="<?php echo base_url() ?>/theme/css/bootstrap.css" rel="stylesheet">');
        mywindow.document.write('<link href="<?php echo base_url() ?>/theme/css/my-style.css" rel="stylesheet">');
        mywindow.document.write('</head><body >');
        mywindow.document.write(data);
        mywindow.document.write('</body></html>');

        mywindow.document.close(); // necessary for IE >= 10
        mywindow.focus(); // necessary for IE >= 10

        mywindow.print();
        mywindow.close();

        return true;
    }


</script>

