@extends('layouts.back-end.app')
@section('title','Categorías')
@push('css_or_js')
    <!-- Custom styles for this page -->
    <link href="{{asset('public/assets/back-end')}}/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="{{asset('public/assets/back-end/css/croppie.css')}}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
         @media(max-width:375px){
            #category-icon-modal .modal-content{
              width: 367px !important;
            margin-left: 0 !important;
        }

        }

   @media(max-width:500px){
    #category-icon-modal .modal-content{
              width: 400px !important;
            margin-left: 0 !important;
        }


   }
    </style>
@endpush

@section('content')
<div class="content container-fluid">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{trans('messages.Dashboard')}}</a></li>
            <li class="breadcrumb-item" aria-current="page">{{trans('messages.category')}}</li>
        </ol>
    </nav>
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-2">
        <h4 class=" mb-0 text-black-50">{{ trans('messages.category')}}</h4>
    </div>

    <!-- Content Row -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    {{ trans('messages.category_form')}}
                </div>
                <div class="card-body">
                    <form>
                        @csrf
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-10">
                                    <input type="hidden" id="id">
                                    <label for="name">{{ trans('messages.name')}}</label>
                                    <input type="text" name="name" class="form-control" id="name"
                                           placeholder="Ingrese una Categoría" required>
                                </div>
                                <div class="col-md-2">
                                    <label>{{ trans('messages.icon')}}</label><br>
                                    <button type="button" class="btn bg-secondary text-light btn-sm" data-toggle="modal"
                                            data-target="#category-icon-modal" data-whatever="@mdo"
                                            id="image-count-category-icon-modal">
                                            <i class="tio-add-circle"></i>Subir
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a id="add" class="btn btn-success" style="color: white; background: #258934">{{ trans('messages.save')}}</a>
                            <a id="update" class="btn btn-success"
                               style="display: none; color: #fff; background: #258934">{{ trans('messages.update')}}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--modal-->
    @include('shared-partials.image-process._image-crop-modal',['modal_id'=>'category-icon-modal'])
    <!--modal-->
    <div class="row" style="margin-top: 20px" id="cate-table">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>{{ trans('messages.category_table')}}</h5>
                </div>
                <div class="card-body" style="padding: 0">
                    <div class="table-responsive">
                        <table id="columnSearchDatatable"
                               class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                            <thead class="thead-light">
                            <tr>
                                <th>ID</th>
                                <th>{{ trans('messages.name')}}</th>
                                <th>{{ trans('messages.slug')}}</th>
                                <th>{{ trans('messages.icon')}}</th>
                                <th style="width:15%;">{{ trans('messages.action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($categories as $key=>$category)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{$category['name']}}</td>
                                    <td>{{$category['slug']}}</td>
                                    <td>
                                    <img width="64" onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                     src="{{asset('storage/app/public/category')}}/{{$category['icon']}}"></td>
                                    <td>

                                        <div class="dropdown">
                                            <button class="btn btn-outline-secondary dropdown-toggle" type="button"
                                                    id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                <i class="tio-settings"></i>
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <a class="dropdown-item  edit" style="cursor: pointer;"
                                                id="{{$category['id']}}"> {{ trans('messages.Edit')}}</a>
                                                <a class="dropdown-item delete"style="cursor: pointer;"
                                                id="{{$category['id']}}">  {{ trans('messages.Delete')}}</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer">
                    {{$categories->links()}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
    <!-- Page level plugins -->
    <script src="{{asset('public/assets/back-end')}}/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="{{asset('public/assets/back-end')}}/vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <!-- Page level custom scripts -->
    <script>
        $(document).ready(function () {
            $('#dataTable').DataTable();
        });
    </script>

    <script>
        /*fetch_category();*/

        function fetch_category() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{route('admin.category.fetch')}}",
                method: 'GET',
                success: function (data) {

                    if (data.length != 0) {
                        var html = '';
                        for (var count = 0; count < data.length; count++) {
                            html += '<tr>';
                            html += '<td class="column_name" data-column_name="sl" data-id="' + data[count].id + '">' + (count + 1) + '</td>';
                            html += '<td class="column_name" data-column_name="name" data-id="' + data[count].id + '">' + data[count].name + '</td>';
                            html += '<td class="column_name" data-column_name="slug" data-id="' + data[count].id + '">' + data[count].slug + '</td>';
                            html += '<td class="column_name" data-column_name="icon" data-id="' + data[count].id + '"><img src="{{asset('storage/app/public/category/')}}/' + data[count].icon + '" class="img-thumbnail" height="40" width="40" alt=""></td>';
                            html += '<td><a type="button"   class="btn btn-success btn-xs edit" id="' + data[count].id + '"><i class="fa fa-edit text-white"></i></a> <a type="button" class="btn btn-danger btn-xs delete" id="' + data[count].id + '"><i class="fa fa-trash text-white"></i></a></td></tr>';
                        }
                        $('tbody').html(html);
                    }
                }
            });
        }

        $('#add').on('click', function () {
            $('#add').attr("disabled", true);
            var name = $('#name').val();
            if (name == "") {
                toastr.error('');
                return false;
            }
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{route('admin.category.store')}}",
                method: 'POST',
                data: {
                    name: name,
                },
                success: function () {
                    toastr.success('Categoría insertada correctamente.');
                    $('#name').val('');
                    $('#image-set').val('');
                    $('.call-when-done').click();
                    fetch_category();
                    location.reload();
                }
            });
        });

        $('#update').on('click', function () {
            $('#update').attr("disabled", true);
            var id = $('#id').val();
            var name = $('#name').val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{route('admin.category.update')}}",
                method: 'POST',
                data: {
                    id: id,
                    name: name,
                },
                success: function (data) {
                    $('#name').val('');
                    $.ajax({
                        type: 'get',
                        url: '{{route('image-remove',[0,'category_icon_modal'])}}',
                        dataType: 'json',
                        success: function (data) {
                            if (data.success === 1) {
                                $("#img-suc").hide();
                                $("#img-err").hide();
                                $("#crop").hide();
                                $("#show-images").html(data.images);
                                $("#image-count").text(data.count);
                            } else if (data.success === 0) {
                                $("#img-suc").hide();
                                $("#img-err").show();
                            }
                        },
                    });
                    toastr.success('Categoría actualizada correctamente.');
                    $('#update').hide();
                    $('#cate-table').show();
                    $('#add').show();
                    fetch_category();
                    location.reload();
                }
            });
            $('#save').hide();
        });


        $(document).on('click', '.delete', function () {
            var id = $(this).attr("id");
            Swal.fire({
                title: 'Estas seguro',
                text: "¡No podrás revertir esto!",
                showCancelButton: true,
                confirmButtonColor: '#258934',
                cancelButtonColor: 'dark',
                confirmButtonText: '¡Sí, bórralo!'
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{route('admin.category.delete')}}",
                        method: 'POST',
                        data: {id: id},
                        success: function () {
                            fetch_category();
                            toastr.success('Categoría eliminada correctamente.');
                            location.reload();
                        }
                    });
                }
            })
        });

        $(document).on('click', '.edit', function () {
            $('#update').show();
            $('#add').hide();
            var id = $(this).attr("id");
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{route('admin.category.edit')}}",
                method: 'POST',
                data: {id: id},
                success: function (data) {
                    $('#id').val(data.id);
                    $('#name').val(data.name);
                    $('#cate-table').hide();
                    fetch_category()
                }
            });
        });
    </script>
    <!-- Page level custom scripts -->
    @include('shared-partials.image-process._script',[
    'id'=>'category-icon-modal',
    'height'=>320,
    'width'=>320,
    'multi_image'=>true,
    'route'=>route('image-upload')
    ])
@endpush
