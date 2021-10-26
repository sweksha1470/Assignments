<html>
   <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Assignment</title>      
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.css" />       
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>         
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.js"></script>       
   </head>
   <body>
        <div class="container">
            <br />
            <h3 align="center">Assignment</h3>
            <br />
            <div class="notify"></div>
            <br />
            <div class="row input-daterange">
                <div class="col-md-4">
                    <input type="text" name="from_date" id="from_date" class="form-control" placeholder="From Date" readonly />
                </div>
                <div class="col-md-4">
                    <input type="text" name="to_date" id="to_date" class="form-control" placeholder="To Date" readonly />
                </div>
                <div class="col-md-4">
                    <button type="button" name="filter" id="filter" class="btn btn-primary">Filter</button>
                    <button type="button" name="refresh" id="refresh" class="btn btn-default">Refresh</button>
                    <button type="button" disabled name="send_email" id="refresh" class="btn btn-success">Send Invite</button>
                </div>
            </div>
            <br />
         <div class="table-responsive">
            <table class="table table-bordered table-striped" id="order_table">
               <thead>
                  <tr>
                     <th>User ID</th>
                     <th>UserName</th>
                     <th>Event Name</th>
                     <th>Description</th>
                     <th>Date</th>
                  </tr>
               </thead>
            </table>
         </div>
      </div>
   </body>
</html>
<script>
   $.ajaxSetup({
       headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
   });
  
    $(document).ready(function(){
        $('.input-daterange').datepicker({
            todayBtn:'linked',
            format:'yyyy-mm-dd',
            autoclose:true
        });
   
        load_data();
    
        function load_data(from_date = '', to_date = '')
        {
            $('#order_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url:'{{ route("daterange.fetch_data") }}',
                    method:'post',
                    data:{from_date:from_date, to_date:to_date}           
                },
                columns: [                
                    {
                        data:'user.id',
                        name:'user.id',
                        render:function(data, type, row){
                            return row.user.id+"<input type='hidden' name='userid[]' value='"+row.user.id+"'/>"
                        }
                    },
                    {
                        data:'user.name',
                        name:'user.name'
                    },
                    {
                        data:'event_name',
                        name:'event_name'
                    },
                    {
                        data:'description',
                        name:'description'
                    },
                    {
                        data:'event_date',
                        name:'event_date'
                    }
                ],
                "drawCallback": function( settings ) {
                
                    var rows = this.fnGetData();
                
                    if (rows.length === 0 ) {
                        $('.btn-success').attr('disabled',true);
                    }else{
                        $('.btn-success').attr('disabled',false);
                    }    
                }
            });
        }
    
        $('#filter').click(function(){
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            if(from_date != '' &&  to_date != '')
            {
                $('#order_table').DataTable().destroy();
                load_data(from_date, to_date);
            }
            else
            {
                alert('Both Date is required');
            }
        });
    
        $('#refresh').click(function(){
            $('#from_date').val('');
            $('#to_date').val('');
            $('#order_table').DataTable().destroy();
            load_data();
        });

        $('.btn-success').click(function(){
            var InputIds = [];  //array to hold your id's
            $.each($('input[name="userid[]"]'),function(i,v){
                InputIds.push($(this).val());
            });
            $.ajax({
                    type: 'POST',
                    url: '{{ route("sendingQueueEmails") }}',
                    data:'id='+InputIds,		
                    beforeSend: function () {
                        $('.btn-success').attr('disabled',true);
                    },
                    success: function (msg) {
                        if(msg.status == 200){
                            $('.btn-success').attr('disabled',false);
                            console.log(msg);
                            $('.notify').html('<div class="alert alert-success" role="alert">'+msg.message+'</div>');
                        }
                        
                    },
                    error: function (msg) {
                        $('.btn-success').attr('disabled',false); 
                        $('.notify').html('<div class="alert alert-error" role="alert">Something Went Wrong</div>');
                    }
                });
            
           
        });
        
   
    });
</script>