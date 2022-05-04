@extends('layouts.app')

@section('content')
<div class="dx-viewport demo-container">
@if (session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
@endif
<div class="row">
    <div class="col-sm-4 p-4">
        <label for="area">
            <h4>البلدة</h4>
        </label>
        <select class="form-control " id="area" >
            @foreach ($districts as $district)
                <option value="{{ $district->district }}">{{ $district->district }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-sm-4 text-center m-y p-2" style="font-size: 20px;font-weight: bold;">
        اجمالي الناخبين:  <span id="total_count"></span>
    </div>
    <div class="col-sm-4"></div>
</div>
<div id="grid" class="p-2" style="font-size:11px"></div>
<div id="popup"></div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/babel-polyfill/7.4.0/polyfill.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.1.1/exceljs.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.2/FileSaver.min.js"></script>
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    const popupContentTemplate = function () {

    return $('<div class="row p-2">').append(
            $('<div class="col-sm-2 mb-2">مركز الاقتراع</div>'),
            $('<div class="col-sm-10 mb-2"><input type="text" id="election_center" class="form-control"></div>'),
            $('<div class="col-sm-2">القلم</div>'),
            $('<div class="col-sm-10"><input type="text" id="ballot_pen" class="form-control"></div>'),
        );
    };

  

</script>
<script type="text/javascript">
    var url = '/electors/get?district='+ $('#area').val();

    var store = new DevExpress.data.CustomStore({
        key: "id",
        load: function (loadOptions) {
            return $.getJSON(url)
                    .fail(function() { throw "Data loading error" });
        },
        insert: function (values) {
            // ...
        },
        update: function (key, values) {

        },
        remove: function (key) {
            // ...
        }
    });

    var dataGrid = $('#grid').dxDataGrid({
        dataSource: store,
        keyExpr: 'id',
        filterRow: {
            visible: true,
            applyFilter: 'auto',
        },
        searchPanel: {
            visible: true,
            width: 240,
            placeholder: 'بحث...',
        },
        filterRow: {
            visible: true,
        },
        filterPanel: { visible: true },
        headerFilter: { visible: true },
        editing: {
            mode: 'batch',
            allowUpdating: true,
            allowAdding: false,
            allowDeleting: false,
            selectTextOnEditStart: false,
            startEditAction: 'click',
        },
        allowColumnReordering: false,
        allowColumnResizing: true,
        showBorders: true,
        selection: {
            mode: 'multiple',
            selectAllMode: "page"   
        },
        export: {
            enabled: true,
            allowExportSelectedData: true,
        },
        showColumnLines: true,
        showRowLines: true,
        rowAlternationEnabled: true,
        showBorders: true,
        onExporting(e) {
            const workbook = new ExcelJS.Workbook();
            const worksheet = workbook.addWorksheet('Electors');
            
            DevExpress.excelExporter.exportDataGrid({
                component: e.component,
                worksheet,
                autoFilterEnabled: true,
            }).then(() => {
                workbook.xlsx.writeBuffer().then((buffer) => {
                saveAs(new Blob([buffer], { type: 'application/octet-stream' }), 'Electors.xlsx');
                });
            });
            e.cancel = true;
        },
        columns: [
            {
                caption: 'الرمز',
                dataField:'id',
                width:50,
                allowEditing: false
            },
            {
                caption: 'الرقم الوهمي',
                dataField:'virtual_number',
                allowEditing: true
            },
            {
                caption: 'الاسم',
                dataField:'FullName',
                width:220,
                allowEditing: false,
                allowFiltering: true,
            /*    calculateCellValue(e) {
                    let { firstname, fathername, lastname} = e;
                    return ` ${firstname} ${fathername} ${lastname}`;
                 //   return data.firstname + ' ' + data.fathername + ' ' + data.lastname
                }*/
            },
            {
                caption: 'إسم الأم',
                dataField:'mothername',
                allowEditing: false
            },
            {
                caption: 'الهاتف',
                dataField:'telephone'
            },
            {
                caption: 'تاريخ الولادة',
                dataField:'date_of_birth',
                allowEditing: false
            },
            {
                caption: 'الجنس',
                dataField:'sex',
                allowEditing: false,
                width:60,
            },
            {
                caption: 'السجل',
                dataField:'log',
                allowEditing: false,
                width:60,
            },
            {
                caption: 'مركز الاقتراع',
                dataField:'election_center'
            },
            {
                caption: 'القلم',
                dataField:'ballot_pen',
                width:60,
            },
            {
                caption: 'مذهب السجل',
                dataField:'log_doctrine',
                allowEditing: false
            },
            {
                caption: 'بلد الإقتراع',
                dataField:'election_country',
                allowEditing: false
            },
            {
            type: "buttons",
            buttons: [{
                text: "تعديل",
                hint: "تعديل ",
                onClick: function (e) {
                    window.open(
                    '/electors/edit/'+e.row.cells[0].data.id,
                    '_blank' 
                    );
                }
            },{
                text:'انتخب',
                hint:'انتخب',
                cssClass:'done-btn',
                onClick:function(e){
                    showLoadPanel
                    $.ajax({
                        type:'post',
                        url:'/electors/edit/done',
                        data:{id:e.row.cells[0].data.id},
                        success:function(e){
                            DevExpress.ui.notify('تم الحفظ', 'success', 2500);
                        }
                    })
                }
            }]}
        
        ],
        summary: {
        totalItems: [{
            column: 'id',
            summaryType: 'count',
        }
        ],
        },
        paging: {
            pageSize: 500,
        },
        onContentReady: function(e) {  
            var toolbar = e.element.find('.dx-datagrid-header-panel .dx-toolbar').dxToolbar('instance');

            toolbar.on('optionChanged', function(arg) {  
                addCustomItem(toolbar);  
            }); 

            addCustomItem(toolbar);

            $('#total_count').html(e.component.totalCount().toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","))
        },
     
        onSaving: function(e) {

            var data = []
            $.each(e.changes,function(k,v){
                var vall =  {
                    id:v.key,
                    value:v.data.virtual_number
                }
                data.push(vall)
            })

          
          $.ajax({
            type:'POST',
            url:url,
            data:{data:data},
            success:function(data){
                DevExpress.ui.notify('تم الحفظ', 'success', 2500);
            }
          })
        },
  
        onRowPrepared(e){
            if (e.rowType === "data") {
            if(e.data.done == "1"){
                e.rowElement.addClass('grid-row-done') ;
                }
            }
        }
       
    }).dxDataGrid("instance");

  
    const popup = $('#popup').dxPopup({
        contentTemplate: popupContentTemplate,
        width: 600,
        height: 250,
        container: '.dx-viewport',
        showTitle: true,
        title: 'فرز الاسماء الى الاقلام',
        visible: false,
        dragEnabled: false,
        closeOnOutsideClick: true,
        showCloseButton: false,
        position: {
        at: 'center',
        my: 'center',
        },
        toolbarItems: [{
        widget: 'dxButton',
        toolbar: 'bottom',
        location: 'after',
        options: {
            text: 'الغاء',
            onClick() {
            popup.hide();
            },
        },
        },{
        widget: 'dxButton',
        toolbar: 'bottom',
        location: 'before',
        options: {
            icon: 'save',
            text: 'حفظ',
            onClick() {
            var values = {
                election_center:$('#election_center').val(),
                ballot_pen:$('#ballot_pen').val(),
                electors: dataGrid.getSelectedRowsData()
            };

            $.ajax({
                type:'POST',
                url:'/electors/saveElectionCenter',
                data:{
                    data:JSON.stringify(values)
                },
                success: function(data){
                    const message = 'تم الحفظ!';
                    DevExpress.ui.notify({
                    message,
                    position: {
                        my: 'center top',
                        at: 'center top',
                    },
                    }, 'success', 3000);   
                             
                    window.location.reload();
                }
            })
            },
        },
        }],
    }).dxPopup('instance');
    
    function addCustomItem(toolbar) {  
        var items = toolbar.option('items');  
        var myItem = DevExpress.data.query(items).filter(function(item) {  
            return item.name == 'assign_button';  
        }).toArray();

        if (!myItem.length) {  
            items.push({  
            location: 'after',  
            widget: 'dxButton',  
            name: 'assign_button',  
            options: {  
                text: 'فزر الاسماء الى الاقلام',  
                onClick: function(e) {  
                    console.log(dataGrid.getSelectedRowsData());
                    popup.show();
                }  
            }  
            });  
            toolbar.option('items', items);  
        }
    } 
</script>
<script type="text/javascript">
    $('#area').on('change', function() {
        var id = $(this).val();


        dataGrid.refresh();
        var url = '/electors/get?district='+ $(this).val();

        
        var store = new DevExpress.data.CustomStore({
            key: "id",
            load: function (loadOptions) {
                return $.getJSON(url)
                        .fail(function() { throw "Data loading error" });
            },
            insert: function (values) {
                // ...
            },
            update: function (key, values) {
         
            },
            remove: function (key) {
                // ...
            }
        });

        dataGrid.option("dataSource", store);
    


    });

    const loadPanel = $('.loadpanel').dxLoadPanel({
    shadingColor: 'rgba(0,0,0,0.4)',
    position: { of: 'body' },
    visible: false,
    showIndicator: true,
    showPane: true,
    shading: true,
    closeOnOutsideClick: false,
    onShown() {
      setTimeout(() => {
        loadPanel.hide();
      }, 3000);
    },
    onHidden() {
      showEmployeeInfo(employee);
    },
  }).dxLoadPanel('instance');


  const showLoadPanel = function () {
    loadPanel.show();
    showEmployeeInfo({});
  };

  showLoadPanel


</script>
<style>
    .done-btn{
        color:green !important;
        font-weight: bold !important;
        text-decoration: none !important;
    }
    .grid-row-done{
        background-color: greenyellow !important;
    }
</style>
@endsection
