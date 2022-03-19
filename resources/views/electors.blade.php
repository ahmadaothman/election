@extends('layouts.app')

@section('content')
<div class="dx-viewport demo-container">
@if (session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
@endif
<div class="row">
    <div class="col-sm-12 text-center m-y p-2" style="font-size: 20px;font-weight: bold;">
        اجمالي الناخبين:  <span id="total_count"></span>
    </div>
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

    var dataGrid = $('#grid').dxDataGrid({
    dataSource: '/electors/get',
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
        allowUpdating: false,
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
            caption: 'الاسم',
            dataField:'FullName',
            width:250,
            allowEditing: false
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
            allowEditing: false
        },
        {
            caption: 'المذهب',
            dataField:'doctrine',
            allowEditing: false
        },
        {
            caption: 'مركز الاقتراع',
            dataField:'election_center'
        },
        {
            caption: 'القلم',
            dataField:'ballot_pen'
        },
        {
            caption: 'مذهب السجل',
            dataField:'log_doctrine',
            allowEditing: false
        },
        {
            caption: 'البلدة أو الحي',
            dataField:'district',
            allowEditing: false
        },
        {
            caption: 'بلد الإقتراع',
            dataField:'election_country',
            allowEditing: false
        },
        
        {
            caption: 'الصوت التفضيلي',
            dataField:'SideDetails'
        },
        {
            caption: 'اقترع العام الماضي',
            dataField:'elected_last_election'
        },
        {
            caption: 'ملاحظة',
            dataField:'note'
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
    }  
    }).dxDataGrid("instance");

  
    const popup = $('#popup').dxPopup({
        contentTemplate: popupContentTemplate,
        width: 600,
        height: 250,
        container: '.dx-viewport',
        showTitle: true,
        title: 'فزر الاسماء الى الاقلام',
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


@endsection
