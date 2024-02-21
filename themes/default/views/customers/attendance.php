<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?>

<script type="text/javascript">
    $(document).ready(function() {

        var table = $('#CuData').DataTable({

            'ajax' : { url: '<?=site_url('customers/get_attendance');?>', type: 'POST', "data": function ( d ) {
                d.<?=$this->security->get_csrf_token_name();?> = "<?=$this->security->get_csrf_hash()?>";
            }},
            "buttons": [
            { extend: 'copyHtml5', 'footer': false, exportOptions: { columns: [ 0, 1, 2, 3, 4, 5 ] } },
            { extend: 'excelHtml5', 'footer': false, exportOptions: { columns: [ 0, 1, 2, 3, 4, 5 ] } },
            { extend: 'csvHtml5', 'footer': false, exportOptions: { columns: [ 0, 1, 2, 3, 4, 5 ] } },
            { extend: 'pdfHtml5', orientation: 'landscape', pageSize: 'A4', 'footer': false,
            exportOptions: { columns: [ 0, 1, 2, 3, 4, 5 ] } },
            { extend: 'colvis', text: 'Columns'},
            ],
            "columns": [
            { "data": "id_a", "visible": false },
            { "data": "customer_name" },
            { "data": "member_id" },
            { "data": "date_checkin" },
            { "data": "check_in" },
            { "data": "check_out" },
            {
            "data": "duration",
            "render": function(data, type, full, meta) {
                // console.log('-->', full)
                var date1 = new Date('1970-01-01T' + full.check_in + 'Z');
                var date2 = new Date('1970-01-01T' + full.check_out + 'Z');

                // Calculate the difference in milliseconds
                var durationMs = date2 - date1;

                // Convert milliseconds to hours
                var hours = Math.floor(durationMs / (1000 * 60 * 60));

                if (durationMs >= 0) {
                    // Convert milliseconds to hours
                    var hours = Math.floor(durationMs / (1000 * 60 * 60));
                    return '<div class="text-center"><span class="label label-info">'+ hours + ' hours </span></div>';
                } else {
                    // console.log('Duration is negative');
                    return '<div class="text-center"><span class="label label-warning   ">-</span></div>';
                }
            }
            },
            { "data": "Actions", "searchable": false, "orderable": false }
            ]

        });

        $('#search_table').on( 'keyup change', function (e) {
            var code = (e.keyCode ? e.keyCode : e.which);
            if (((code == 13 && table.search() !== this.value) || (table.search() !== '' && this.value === ''))) {
                table.search( this.value ).draw();
            }
        });

    });
</script>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title"><?= lang('list_results'); ?></h3>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="CuData" class="table table-bordered table-hover table-striped">
                            <thead>
                                <tr>
                                    <th style="max-width:30px;"><?= lang("id"); ?></th>
                                    <th><?= lang("name"); ?></th>
                                    <th>Member Code</th>
                                    <th>Date</th>
                                    <th>Check In</th>
                                    <th>Check Out</th>
                                    <th>Duration</th>
                                    <th style="width:65px;"><?= lang("actions"); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="7" class="dataTables_empty"><?= lang('loading_data_from_server') ?></td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="7" class="p0"><input type="text" class="form-control b0" name="search_table" id="search_table" placeholder="<?= lang('type_hit_enter'); ?>" style="width:100%;"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
</section>
