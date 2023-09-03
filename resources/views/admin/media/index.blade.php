@extends('admin.layout.master')
@section('page-title')
  Manage Media
@endsection
@section('main-content')
<section class="content">      
      <!-- /.row -->
     <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title"> 
                    <a id="activeAllStatus" url="{{ route('status_active_media') }}" class="btn btn-danger btn-xm" data-toggle="tooltip" title="Active Selected"><i class="fa fa-eye"></i></a>
                    <a id="deactiveAllStatus" url="{{ route('status_deactive_media') }}" class="btn btn-danger btn-xm" data-toggle="tooltip" title="Deactive Selected"><i class="fa fa-eye-slash"></i></a>
                    <a id="delete_all" url="{{ route('delete_all_media') }}" class="btn btn-danger btn-xm" data-toggle="tooltip" title="Delete All"><i class="fa fa-trash"></i></a>
                    <a href="/admin/media/create" class="btn btn-default btn-xm" data-toggle="tooltip" title="Create"><i class="fa fa-plus"></i></a>
              </h3>
              <div class="box-tools">
                <form action="/admin/media" method="get">
                <div class="input-group input-group-sm" style="width: 250px;">
                  <input type="text" name="s" class="form-control pull-right" placeholder="Search" value="{{ request()->query('s') }}">
                  <div class="input-group-btn">
                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                  </div>
                </div>
                </form>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              @if($medias)
              <table class="table table-bordered">
                <thead style="background-color: #F8F8F8;">
                  <tr>
                    <th width="4%"><input type="checkbox" name="" id="checkAll"></th>
                    <th width="20%">Title</th>
                    <th width="20%">Media Type</th>
                    <th width="20%">Media Image</th>
                    <th width="10%">Status</th>
                    <th width="10%">Manage</th>
                  </tr>
                </thead>
                @foreach($medias as $media)
                <tr>
                  <td><input type="checkbox" name="statusAll[]" id="" value="{{ $media->id }}" class="checkSingle"></td>
                  <td>{{ $media->title }}</td>
                  <td>{{ $media->media_type }}</td>
                  <td>@if($media->media_img == 'No image found')
                        <img src="/assets/admin/dist/img/no-img.jpg" width="80" height="80" alt="No image found" class="img-thumbnail">
                      @else
                        <img src="/uploads/{{ $media->media_img }}" width="80" height="80" alt="{{ $media->title }}" class="img-thumbnail">
                      @endif
                  </td>
                  <td>
                    <form action="/admin/media/{{ $media->id }}/status" method="POST">
                    @csrf
                    @method('put')
                    @if($media->status == 'DEACTIVE')
                    <button class="btn btn-danger btn-sm singleStatus"><i class="fa fa-thumbs-down"></i></button>
                    @else
                    <button class="btn btn-info btn-sm singleStatus"><i class="fa fa-thumbs-up"></i></button>
                    @endif
                    </form>
                  </td>
                  <td>
                    <form action="/admin/media/{{ $media->id }}" method="POST">
                      @csrf
                      @method('delete')
                      <a href="/admin/media/{{ $media->id }}/edit" class="btn btn-info btn-flat btn-sm"> <i class="fa fa-edit"></i></a>
                      <button class="btn btn-danger btn-flat btn-sm singleDelete"> <i class="fa fa-trash-o"></i></button>
                  </form>
                  </td>
                </tr>
                @endforeach
            </table>
            </div>
            <!-- /.box-body -->
              <div class="box-footer clearfix">
                        <div class="row">
                            <div class="col-sm-6">
                                <span style="display:block;font-size:15px;line-height:34px;margin:20px 0;">
                                    Showing {{($medias->currentpage()-1)*$medias->perpage()+1}} to {{$medias->currentpage()*$medias->perpage()}}
                                    of  {{$medias->total()}} entries</span>
                            </div>
                          <div class="col-sm-6 text-right">
                              {{ $medias->links() }}
                          </div>
                        </div>
                    </div>
              @else
                <div class="alert alert-danger">No record found!</div>
              @endif
          </div>
            <!-- /.box-body -->
          </div>


    </section>
@endsection    
@section('scripts')
<script>
  $(document).ready(function() {
      $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
  });  
  $(".singleStatus").on('click', function(event) {
      event.preventDefault();

      var self = $(this);
      var url = self.closest('form').attr('action');

      $.ajax({
          url : url,
          type: 'PUT',
      })
      .done(function(data) {
        if (data == 'ACTIVE') 
        {
          self.closest('form').find('button').removeClass('btn-danger');
          self.closest('form').find('button').addClass('btn-info');
          self.html('<i class="fa fa-thumbs-up"></i>');

        }
        else
        {
          self.closest('form').find('button').removeClass('btn-info');
          self.closest('form').find('button').addClass('btn-danger');
          self.html('<i class="fa fa-thumbs-down"></i>');
        }
      })
      .fail(function(data) {
        alert("something went wrong");
      })
      .always(function() {
        console.log("complete");
      });

  });

  // SINGLE DELETE
  $(".singleDelete").on('click', function(event) {
      event.preventDefault();
      
      var self = $(this);
      var url = self.closest('form').attr('action');
      if (confirm('Are you sure?')) 
      {
        $.ajax({
          url: url,
          type: 'delete',
        })

        .done(function(data) {
          if (data == 'true') 
          {
            self.closest('tr').css('background-color', 'red').fadeOut(1000);
            self.remove();
          }
        })

        .fail(function() {
            alert("Something went wrong");
        })

        .always(function() {
            console.log("complete");
        });
      }
      return false;

  });

// ACTIVE ALL STATUS.
  $("#activeAllStatus").on('click', function(event) {
      event.preventDefault();
    
      var statusAllValues = [];
      $("input[name='statusAll[]']:checked").each(function() {
          statusAllValues.push($(this).val());
      });

      $.ajax({
          url: $('#activeAllStatus').attr('url'),
          type: 'GET',
          data: {statusAll: statusAllValues}
      })

      .done(function(data) {
          $.each(data, function(index, val) {
              $("input[value='"+val.id+"']")
              .closest('tr')
              .find('.singleStatus')
              .addClass('btn-info')
              .removeClass('btn-danger')
              .html('<i class="fa fa-thumbs-up"></i>');
          });
      })

      .fail(function() {
          alert('Something went wrong.');
      })

      .always(function() {
          console.log('complete');
      });

  });

// DEACTIVE ALL STATUS.
  $("#deactiveAllStatus").on('click', function(event) {
      event.preventDefault();
    
      var statusAllValues = [];
      $("input[name='statusAll[]']:checked").each(function() {
          statusAllValues.push($(this).val());
      });

      $.ajax({
          url: $('#deactiveAllStatus').attr('url'),
          type: 'GET',
          data: {statusAll: statusAllValues}
      })

      .done(function(data) {
          $.each(data, function(index, val) {
              $("input[value='"+val.id+"']")
              .closest('tr')
              .find('.singleStatus')
              .addClass('btn-danger')
              .removeClass('btn-info')
              .html('<i class="fa fa-thumbs-down"></i>');
          });
      })

      .fail(function() {
          alert('Something went wrong.');
      })

      .always(function() {
          console.log('complete');
      });

  });

// DELETE ALL.
  $("#delete_all").on('click', function(event) {
      event.preventDefault();
    
      if (confirm('Are you sure?')) {
      var statusAllValues = [];
      $("input[name='statusAll[]']:checked").each(function() {
          statusAllValues.push($(this).val());
      });

      $.ajax({
          url: $('#delete_all').attr('url'),
          type: 'GET',
          data: {statusAll: statusAllValues}
      })

      .done(function(data) {
            location.reload(true);
      })

      .fail(function() {
          alert('Something went wrong.');
      })

      .always(function() {
          console.log('complete');
      });
    }
  });


  });





</script>
@endsection