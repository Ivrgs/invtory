<script type="text/javascript">

var save_method; //for save method string
var table;

$(document).ready(function() {
    table = $('#table').DataTable({ 
        responsive: true,
        "emptyTable":  "No data available in table",
        "info": false,
        "bInfo" : false,
        "processing": true,
        "serverSide": true,
        "order": [0, "asc"],
		"autoWidth": false,
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo base_url()."Datatables";?>",
            "type": "POST"
        },

        "columnDefs": [{ 
                "targets": [ -1 , -9],
                "orderable": false,
                "order": [0, "asc"]
            },  {
				className: "TableAlignment Version", targets: 1
			},
			{
                className: "TableAlignment Size", targets: 6
            },
			{
                className: "TableAlignment Date", targets: [8] 
            },
            {
                className: "TableAlignment ", targets: [7] 
            },
            {
                className: "TableAlignment Simplified", targets: [2, 5]
            },
            {
                className: "TableAlignment ExtraSimplified", targets: [3,4]
            },
            {  
                className: "TableAlignment Button", targets: 9 
            },
            {  
                className: "TableAlignment Title", targets: 0 
        }],
    });
 
    $('#datepickerAdd').datepicker({
            uiLibrary: 'bootstrap4',
            format: "mmmm dd, yyyy",
            autoclose: true,
            todayHighlight: true,
            showRightIcon: false
            , iconsLibrary: 'fontawesome',orientation: "top auto", todayBtn: true,
    
        });

    //set input/textarea/select event when change value, remove class error and remove text help block 
    $("input").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });
    $("textarea").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });
    $("select").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });

});

function add_game(){
    save_method = 'add';
    
    $('#form')[0].reset(); 
    $('.form-group').removeClass('has-error'); 
    $('.help-block').empty();

    $('#modal_form').modal('show'); 
    $('.modal-title').text('Add Game'); 
    $('#modal_form').on('shown.bs.modal', function(e){
        $('#field_product_name').focus();
    });
}

function reload_table(){
    table.ajax.reload(null,false);
}

function edit_game(id){
    save_method = 'update';
    $('#form')[0].reset(); 
    $('.form-group').removeClass('has-error'); 
    $('.help-block').empty(); 
	$('#modal_form').on('shown.bs.modal', function(e){
        $('#field_product_name').focus();
        $(".TextField").hide(1000)
    });

    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo base_url().'DataEdit'?>/" + id,
        type: "POST",
        dataType: "JSON",
        success: function(data){
            $('[name="id"]').val(data.id);
            $('[name="field_product_name"]').val(data.product_name);
			$('[name="field_pn_sn"]').val(data.pn_sn);
            $('[name="field_brand"]').val(data.brand);
            $('[name="field_type"]').val(data.type);
            $('[name="field_quantity"]').val(data.quantity);
            $('[name="field_price"]').val(data.price);
            $('[name="field_status"]').val(data.status);
            $('[name="datepickerAddG"]').val(data.stored_date);
            $('#modal_form').modal('show');
            $('.modal-title').text('Edit Details');
       
        },
        error: function (jqXHR, textStatus, errorThrown){
            alert('Data not Found');
        }
    });
}

function save(){
    $('#btnSave').text('saving...'); 
    $('#btnSave').attr('disabled',true); 
    var url;

    if(save_method == 'add') {
        url = "<?php echo base_url().'DataAdd'?>";
    }else {
        url = "<?php echo base_url().'DataUpdate'?>";
    }
   // ajax adding data to database

    var formData = new FormData($('#form')[0]);
    $.ajax({
        url : url,
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "JSON",
        success: function(data){
            if(data.status){
                $('#modal_form').modal('hide');
                reload_table();
            }else{
                for (var i = 0; i < data.inputerror.length; i++){
                    $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                }
            }
            $('#btnSave').text('save'); 
            $('#btnSave').attr('disabled',false);  
        },
        error: function (jqXHR, textStatus, errorThrown){
            alert('Error adding / update data');
            $('#btnSave').text('save'); 
            $('#btnSave').attr('disabled',false);  
        }
     });
 }


function delete_game(id){
    $('#delForm')[0].reset(); 
    $('.form-groupD').removeClass('has-error'); 
    $('.help-block').empty(); 
	$('#ModalDeleteGame').on('shown.bs.modal', function(e){
        $('#btnTDel').focus();
    });
       //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo base_url().'DataEdit'?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data){

            $('[name="id"]').val(data.id);
            $('#ModalDeleteGame').modal('show'); 
            $('.modal-title').text(data.title); 

           },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Data not Found');
        }
    });
}

function deleteG(){
    $('#btnDel').text('Deleting...');
    $('#btnDel').attr('disabled',true);
    var formData = new FormData($('#delForm')[0]);
    $.ajax({
        url : "<?php echo base_url().'DataDelete'?>",
        type: "POST",
        data: formData,
        dataType: "JSON",
        contentType: false,
        processData: false,
        success: function(data){
            $('#ModalDeleteGame').modal('hide');
			$('#btnDel').text('Permanent Delete'); 
            $('#btnDel').attr('disabled',false);  
            reload_table();
        },
        error: function (jqXHR, textStatus, errorThrown){
            console.dir('Error deleting data');
			$('#btnDel').text('Permanent Delete'); 
            $('#btnDel').attr('disabled',false);  
        }
    });
}

//CMS ADD
function cms_add(){
    save_method = 'cms_add';
    $('#formCMS')[0].reset();
    $('.form-groupC').removeClass('has-error');
    $('.help-block').empty(); 
    $('#modal_cmsadd').modal('show');
    $('.modal-title').text('Add Content');
	$('#modal_cmsadd').on('shown.bs.modal', function(e){
        $('#addnewcms').focus();
    });
}

function save_cms(){

    var formData = new FormData($('#formCMS')[0]);
    $.ajax({
        url : "<?php echo site_url('addCMS')?>",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "JSON",
        async: true,
        success: function(data){
            $('#modal_cmsadd').modal('hide');
            $('#btnSaveC').text('saving...'); 
            $('#btnSaveC').attr('disabled',false);  
        }
    });
  
}

//CMS UPDATE
function cms_edit(){

    $('#formUpdateCMS')[0].reset(); 
    $('.form-groupCU').removeClass('has-error'); 
    $('.help-block').empty(); 
    $('#modal_cmsupdate').modal('show');
    $('.modal-title').text('Update Content');
	$('#modal_cmsupdate').on('shown.bs.modal', function(e){
        $('#updateType').focus();
    });

    $('[name="gtypeUpdate"]').val("#gtypeUpdate option:selected");
    $('[name="selectType"]').val("#selectType option:selected");
    $('[name="updateType"]').val();

}

function FindType(){
    var data= $('#gtypeUpdate option:selected').val();
    $.ajax({
        type: "POST",
        url: "<?php echo base_url(). "CMSSType"?>", 
        data: {data : data},
        async: false,
        dataType: "JSON",
        success: function(response) { 
            $("#selectType").empty(); 
            for (var a = 0; a < response.length; a++){  
                var opt = new Option(response[a].title, response[a].title);  
                $("#selectType").append(opt); 
            }
           
        },error: function (jqXHR, textStatus, errorThrown){
            alert('Data not Found');
        }
        
    });
    
}

function TypeChange(){
    $("#updateType").val($("#selectType option:selected").val());
}

function cms_update(){
        $('#btnUpdateCMS').text('updating...'); 
        $('#btnUpdateCMS').attr('disabled',true); 
    var formData = new FormData($('#formUpdateCMS')[0]);
    $.ajax({
        url : "<?php echo base_url(). "CMSUpdate"?>",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "JSON",
        async: false,
        success: function(data){
            $('#modal_cmsupdate').modal('hide');
            $('#btnUpdateCMS').text('Update'); 
            $('#btnUpdateCMS').attr('disabled',false);  
            reload_table();
        }
    });
  
}
function cms_delete() {
    var formData = new FormData($('#formUpdateCMS')[0]);
    $.ajax({
        url : "<?php echo base_url(). "CMSDelete"?>",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "JSON",
        async: false,
        success: function(data){
            if(data.status){
                $('#modal_cmsupdate').modal('hide');
                reload_table();

            }else{
                for (var i = 0; i < data.inputerror.length; i++){
                  $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]);
                }
            }
            $('#btnUpdateCMS').text('save'); 
            $('#btnUpdateCMS').attr('disabled',false);  
        }
    });
}
$(document).ready(function() {
    $.ajax({
        type: "POST",
        url: "<?php echo base_url(). "home/getDropdown"?>", 
        async: false,
        dataType: "JSON",
        success: function(response) { 
            for (var i = 0; i < response['getrepack'].length; i++) { 
                var opt = new Option(response['getrepack'][i].title, response['getrepack'][i].title); 
                $("#field_brand").append(opt); 

            }
            for (var i = 0; i < response['getstatus'].length; i++) { 
                var opt = new Option(response['getstatus'][i].title, response['getstatus'][i].title); 
                $("#field_status").append(opt);  

            }
            for (var i = 0; i < response['getgenre'].length; i++) { 
                var opt = new Option(response['getgenre'][i].title, response['getgenre'][i].title); 
                $("#field_type").append(opt);  

            }
            for (var i = 0; i < response['gettypes'].length; i++) { 
                var opt = new Option(response['gettypes'][i].type, response['gettypes'][i].type); 
                $("#gtypeUpdate, #gtypeAdd").append(opt); 

            }
        },
        error: function (jqXHR, textStatus, errorThrown){
            alert('Data not Found');
        }
    });

});
</script>
</html>
