<?php (defined('BASEPATH')) or exit('No direct script access allowed'); ?>

<script src="<?= $assets ?>plugins/highchart/highcharts.js"></script>

<?php
if ($chartData) {
    foreach ($chartData as $month_sale) {
        $months[] = date('M-Y', strtotime($month_sale->month));
        $sales[] = $month_sale->total;
        $tax[] = $month_sale->tax;
        $discount[] = $month_sale->discount;
    }
} else {
    $months[] = '';
    $sales[] = '';
    $tax[] = '';
    $discount[] = '';
}
?>

<script type="text/javascript">
    $(document).ready(function() {

        $('#shadow').hide();
            $('#searchButton').click(function() {
                const searchTerm = $('#search').val();

                // Make an AJAX request to the controller
                $.ajax({
                    url: '<?= base_url('welcome/get_members') ?>',
                    method: 'GET',
                    data: { search: searchTerm },
                    dataType: 'json',
                    success: function(data) {
                        $('#checkIn').prop('disabled', false);
                        $('#checkOut').prop('disabled', false);
                        // $('#name_person').prop('disabled', false);

                        // name_person
                        // name_
                        $("#hasil").html("");
                        const startDate =  data[0].end_date;
                        const date_checkin = data[0].date_checkin;
                        const StatusMember = data[0].status;
                        const NameMember = data[0].name;
                        
                        // id_a
                        // const id_att = 
                        $('#id_att').val(data[0].id_a);
                        var currentDate = new Date();

                        // Get year, month, and day
                        var year = currentDate.getFullYear();
                        var month = ('0' + (currentDate.getMonth() + 1)).slice(-2); // Months are zero-based
                        var day = ('0' + currentDate.getDate()).slice(-2);

                        // Format the date as "yyyy-mm-dd"
                        var today = year + '-' + month + '-' + day;
                       
                        if(startDate == null || startDate < today || StatusMember == 0) {
                            // console.log('--> no checkin')
                            $('#checkIn').prop('disabled', true);
                            $('#checkOut').prop('disabled', true);
                            // $("#package").append("<span class='label label-important'>"+'NO AKTIF'+'</span>');
                            $("#hasil").html("<div class=\"alert alert-danger alert-dismissible\">" +
                            "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>" +
                            "<h4><i class=\"icon fa fa-\"></i> Member Belum Aktif !!</h4>" +
                            "</div>");

                        }
                        if(date_checkin == today) {
                            // checkIn
                            if (data[0].status == 1) {
                                $('#checkIn').prop('disabled', true);
                            }
                        }
                        $('#shadow').show();
                        $('#member_code').val(data[0].member_code);
                        $('#exp').val(data[0].end_date);
                        $('#name_person').val(data[0].name);
                        // $('#name_person').prop('disabled', false);
                        
                    },
                    error: function(error) {
                        console.error('Error fetching data:', error);
                    }
                });
            });

            $('#checkIn').click(function() {
                const member = $('#member_code').val();
                const id_att = $('#id_att').val();
                const name = "X-One";
                // var csrfToken = $('[name="csrf_token"]').val();
                // var csrfToken = document.querySelector('meta[name="x-csrf-token"]').getAttribute('content');
                // console.log('token', csrfToken)
                $.ajax({
                    url: '<?= base_url('welcome/checkin') ?>',
                    method: 'GET',
                    headers: {
                        // 'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json',
                    //    'X-CSRF-TOKEN': csrfToken
                    },
                    data: { member_code: member,id_att:id_att },
                    dataType: 'json',
                    success: function(data) {
                        // console.log('>', data)
                        location.reload();
                        // $('#shadow').show();
                        // $('#member_code').val(data[0].member_code);
                        // $('#exp').val(data[0].end_date);
                        
                    },
                    error: function(error) {
                        console.error('Error fetching data:', error);
                    }
                });
                // const date_checkin = 

            });

            $('#checkOut').click(function() {
                const member = $('#member_code').val();
                const id_att = $('#id_att').val();
                $.ajax({
                    url: '<?= base_url('welcome/checkout') ?>',
                    method: 'GET',
                    headers: {
                        // 'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json',
                    //    'X-CSRF-TOKEN': csrfToken
                    },
                    data: { member_code: member,id_att:id_att },
                    dataType: 'json',
                    success: function(data) {
                        // console.log('>', data)
                       location.reload();
                        // $('#shadow').show();
                        // $('#member_code').val(data[0].member_code);
                        // $('#exp').val(data[0].end_date);
                        
                    },
                    error: function(error) {
                        console.error('Error fetching data:', error);
                    }
                });

            });

        Highcharts.getOptions().colors = Highcharts.map(Highcharts.getOptions().colors, function(color) {
            return {
                radialGradient: {
                    cx: 0.5,
                    cy: 0.3,
                    r: 0.7
                },
                stops: [
                    [0, color],
                    [1, Highcharts.Color(color).brighten(-0.3).get('rgb')]
                ]
            };
        });
        <?php if ($chartData) { ?>
            $('#chart').highcharts({
                chart: {},
                credits: {
                    enabled: false
                },
                exporting: {
                    enabled: false
                },
                title: {
                    text: ''
                },
                xAxis: {
                    categories: [<?php foreach ($months as $month) {
                                        echo "'" . $month . "', ";
                                    } ?>]
                },
                yAxis: {
                    min: 0,
                    title: ""
                },
                tooltip: {
                    shared: true,
                    followPointer: true,
                    headerFormat: '<div class="well well-sm" style="margin-bottom:0;"><span style="font-size:12px">{point.key}</span><table class="table table-striped" style="margin-bottom:0;">',
                    pointFormat: '<tr><td style="color:{series.color};padding:4px">{series.name}: </td>' +
                        '<td style="color:{series.color};padding:4px;text-align:right;"> <b>{point.y}</b></td></tr>',
                    footerFormat: '</table></div>',
                    useHTML: true,
                    borderWidth: 0,
                    shadow: false,
                    style: {
                        fontSize: '14px',
                        padding: '0',
                        color: '#000000'
                    }
                },
                plotOptions: {
                    series: {
                        stacking: 'normal'
                    }
                },
                series: [{
                        type: 'column',
                        name: '<?= $this->lang->line("tax"); ?>',
                        data: [<?= implode(', ', $tax); ?>]
                    },
                    {
                        type: 'column',
                        name: '<?= $this->lang->line("discount"); ?>',
                        data: [<?= implode(', ', $discount); ?>]
                    },
                    {
                        type: 'column',
                        name: '<?= $this->lang->line("sales"); ?>',
                        data: [<?= implode(', ', $sales); ?>]
                    }
                ]
            });
        <?php } ?>
        <?php if ($topProducts) { ?>
            $('#chart2').highcharts({
                chart: {},
                title: {
                    text: ''
                },
                credits: {
                    enabled: false
                },
                exporting: {
                    enabled: false
                },
                tooltip: {
                    shared: true,
                    followPointer: true,
                    headerFormat: '<div class="well well-sm" style="margin-bottom:0;"><span style="font-size:12px">{point.key}</span><table class="table table-striped" style="margin-bottom:0;">',
                    pointFormat: '<tr><td style="color:{series.color};padding:4px">{series.name}: </td>' +
                        '<td style="color:{series.color};padding:4px;text-align:right;"> <b>{point.y}</b></td></tr>',
                    footerFormat: '</table></div>',
                    useHTML: true,
                    borderWidth: 0,
                    shadow: false,
                    style: {
                        fontSize: '14px',
                        padding: '0',
                        color: '#000000'
                    }
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: false
                        },
                        showInLegend: false
                    }
                },

                series: [{
                    type: 'pie',
                    name: '<?= $this->lang->line('total_sold') ?>',
                    data: [
                        <?php
                        foreach ($topProducts as $tp) {
                            echo "['" . $tp->product_name . " (" . $tp->product_code . ")', " . $tp->quantity . "],";
                        } ?>
                    ]
                }]
            });
        <?php } ?>
    });
</script>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="row">
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-aqua"><i class="fa fa-calendar"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">
                                <?php
                                //  echo date('Y-m-d H:i:s')
                                $currentDateTime = date('Y-m-d H:i:s');
                                $dateTime = new DateTime($currentDateTime);
                                // $formattedDate = $dateTime->format('F d, Y l, h:i A');
                                // Format the date
                                $formattedDate = $dateTime->format('F d, Y');

                                echo $formattedDate;; ?>
                            </span>
                            <span class="info-box-number">

                                <?php
                                //  echo date('Y-m-d H:i:s')
                                $currentDateTime = date('Y-m-d H:i:s');
                                $dateTime = new DateTime($currentDateTime);
                                // $formattedDate = $dateTime->format('F d, Y l, h:i A');
                                // Format the date
                                $formattedDate = $dateTime->format('l, h:i A');

                                echo $formattedDate;; ?>
                            </span>
                        </div>

                    </div>

                </div>

                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-red"><i class="fa fa-calendar"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Check In</span>
                            <span class="info-box-number"><?php echo $checkinData; ?></span>
                        </div>

                    </div>

                </div>


                <div class="clearfix visible-sm-block"></div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-green"><i class="fa fa-users"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Members Active</span>
                            <span class="info-box-number"><?php echo $members; ?></span>
                        </div>

                    </div>

                </div>

                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-yellow"><i class="fa fa-users"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Members InActive</span>
                            <span class="info-box-number"><?php echo $membersNon; ?></span>
                        </div>

                    </div>

                </div>

            </div>

            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Search Member</h3>
                </div>


                <form class="form-horizontal">

                    <div class="box-body">
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Member Id</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="search" placeholder="Members Id,Name....">
                            </div>
                        </div>
                   
                        <div class="box-footer">
                            <button type="button" id="searchButton" class="btn btn-info pull-right">Search</button>
                        </div>
                    </div>

                    <!-- Page details -->
                    <div id="shadow" class="box-body">
                    <div id="hasil">
                           
                           </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Member Id</label>
                            <div class="col-sm-10">
                                <input type="text" id="member_code" class="form-control" disabled >
                                <input type="hidden" id="id_att" class="form-control" >
                                <input type="hidden" name="csrf_token" value="<?php echo $this->security->get_csrf_hash(); ?>">

                            </div>
                            
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Name</label>
                            <div class="col-sm-10">
                                <input type="text"  id="name_person" class="form-control" disabled>
                            </div>
                            
                        </div>
                        <!-- <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Name Package</label>
                            <div class="col-sm-10">
                                <input type="text"  id="name_package" class="form-control" disabled>
                            </div>
                            
                        </div> -->
                       
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Active Package Date</label>
                            <div class="col-sm-10">
                                <input type="text"  id="exp" class="form-control" disabled>
                            </div>
                            
                        </div>
                        <!-- <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Checkin Time</label>
                            <div class="col-sm-10">
                                <input type="email" class="form-control" disabled>
                            </div>
                            
                        </div> -->

                        <div class="box-footer">
                        <button type="button"  id="checkIn" class="btn btn-info">Check In</button>
                        <button type="button" id="checkOut" class="btn btn-danger">Check Out</button>
                        <!-- <button type="submit" class="btn btn-success">POS</button> -->
                            
                        </div>
                   
                    </div>
                </form>
                
            </div>
        </div>


            <div class="row">
                <div class="col-md-8">
                    <div class="box box-primary">
                        <div class="box-header">
                            <h3 class="box-title"><?= lang('sales_chart'); ?></h3>
                        </div>
                        <div class="box-body">
                            <div id="chart" style="height:300px;"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="box box-primary">
                        <div class="box-header">
                            <h3 class="box-title"><?= lang('top_products') . ' (' . date('F Y') . ')'; ?></h3>
                        </div>
                        <div class="box-body">
                            <div id="chart2" style="height:300px;"></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>


</section>
