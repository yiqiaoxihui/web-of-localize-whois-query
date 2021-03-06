@extends('layouts.blank')

@push('stylesheets')
    <!-- Example -->
    <!--<link href=" <link href="{{ asset("css/myFile.min.css") }}" rel="stylesheet">" rel="stylesheet">-->
@endpush

@section('main_container')
  <div class="right_col" role="main">
    <center><h1>BGP Monitors Data</h1></center>
    <form id="toolbar" form="bs-example bs-example-form" role="form" onsubmit="searchData(); return false;">
      <div class="input-group col-md-4 col-md-offset-8">
        <div class="input-group-btn">
          <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
            <span id="type">IP Address</span>
            <span class="caret"></span>
          </button>
          <ul class="dropdown-menu">
            <li>
              <a onclick="changeType(this)">IP Address</a>
            </li>
            <li>
              <a onclick="changeType(this)">ASN</a>
            </li>
            <li>
              <a onclick="changeType(this)">Type</a>
            </li>
            <li>
              <a onclick="changeType(this)">Message</a>
            </li>
            <li>
              <a onclick="changeType(this)">Frequency</a>
            </li>
          </ul>
        </div>
        <input id="content" type="text" class="form-control" value="">
        <span class="input-group-btn">
          <button class="btn btn-default" type="submit">Search</button>
        </span>
      </div><!-- /input-group -->
    </form>
    <table id="myTable"
           data-toggle="table" 
           data-method="post"
           data-side-pagination="server"
           data-pagination="true"
           data-page-list="[5, 10, 20, 50, 100, 200]"
           data-ajax="ajaxRequest"
           data-detail-view="true"
           data-detail-formatter="detailFormatter"
           data-toolbar="#toolbar"
           data-toolbar-align="right"
           data-url="/monitors">
      <thead>
        <tr>
          <th data-field="id">ID</th>
          <th data-field="ipaddr">IP Address</th>
          <th data-field="asn">ASN</th>
          <th data-field="type" data-sortable="ture">Type</th>
          <th data-field="prefixes" data-sortable="ture">Prefixes</th>
          <th data-field="message" data-sortable="ture">Message</th>
          <th data-field="first" data-sortable="ture">First</th>
          <th data-field="last" data-sortable="ture">Last</th>
          <th data-field="frequency" data-sortable="ture">Frequency</th>
        </tr>
      </thead>
    </table>
  </div>
@endsection

@push('scripts')
  <script>
    var $table = $('#myTable');
    $table.on('click-cell.bs.table', clickCell);
    var $searchType = $('#type');
    var $searchContent = $('#content');
    var column = '';
    $.ajaxSetup({
      url: "/monitors",
      type: "POST",
      headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' }
    });
    /*
    function queryParams(params){
      var temp = {
        limit: params.limit,
        offset: params.offset,
        sort: params.sort,
        order: params.order
      };
      return temp;
    }*/
    function ajaxRequest(params){
      var type = $searchType.html().toLowerCase(); 
      var content = $searchContent.val();
      if(type == 'ip address'){
        type = 'ipaddr';
      }
      $.ajax({
        data: {type: 'data',data:params.data, search: type, content: content},
        success: function(data, status){
          params.success(data);
        }
      })
    }
    function identifierFormatter(value, row, index) {
      return [
        '<a class="like" href="javascript:void(0)" title="Like">',
        value,
        '</a>'].join('');
    }
    function clickCell(e, field, value, row, $element){
      $table.bootstrapTable('collapseAllRows');
      var index = $element.parent().data('index');
      $table.bootstrapTable('expandRow', index );
    }
    function detailFormatter(index, row) {
      var html = [];
      var d_content = new Object();
      d_content['asn'] = row['asn']
      d_content['message'] = row['message']
      $.ajax({
        async: false,
        data:{type: 'detail', field: d_content},
        success:function(data){
          html.push('<p style="word-wrap:break-word; word-break:break-all; width:auto;"><b>' + 'AS' + ':</b> ' + data.asn.result.Name + '</p>');
          html.push('<p style="word-wrap:break-word; word-break:break-all; width:auto;"><b>' + 'Message' + ':</b> ' + data.message.content + '</p>');
          html.push('<p style="word-wrap:break-word; word-break:break-all; width:auto;"><b>' + 'Message first' + ':</b> ' + data.message.first + '</p>');
          html.push('<p style="word-wrap:break-word; word-break:break-all; width:auto;"><b>' + 'Message Last' + ':</b> ' + data.message.last + '</p>');
          html.push('<p style="word-wrap:break-word; word-break:break-all; width:auto;"><b>' + 'Message frequency' + ':</b> ' + data.message.frequency + '</p>');
        }
      });
      return html.join('');
    }
    function changeType(obj){
      $searchType.html($(obj).html());
    }
    function searchData(){
      $table.bootstrapTable('selectPage', 1);
      return false;
    }
  </script>

@endpush
