@extends('layouts.back-end.app')
@section('title','Banner')
@push('css_or_js')
    <link href="{{asset('public/assets/back-end')}}/css/select2.min.css" rel="stylesheet"/>
    <link href="{{asset('public/assets/back-end/css/croppie.css')}}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Custom styles for this page -->
    <link href="{{asset('public/assets/back-end')}}/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <style>
        .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }

    input:checked + .slider {
        background-color: #258934;
    }

    input:focus + .slider {
        box-shadow: 0 0 1px #258934;
    }

    input:checked + .slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
    }

    /* Rounded sliders */
    .slider.round {
        border-radius: 34px;
    }

    .slider.round:before {
        border-radius: 50%;
    }

    @media (max-width:500px){
        .propup{
            margin-top: 2%;
        }

    }

        #main-banner-image-modal .modal-content{
                 width: 1116px !important;
               margin-left: -264px !important;
           }

           #secondary-banner-image-modal .modal-content{
                 width: 1116px !important;
               margin-left: -264px !important;
           }
           #popup-banner-image-modal .modal-content{
                 width: 1116px !important;
               margin-left: -264px !important;
           }
           @media(max-width:768px){
               #main-banner-image-modal .modal-content{
                   width: 698px !important;
       margin-left: -75px !important;
           }
           #secondary-banner-image-modal .modal-content{
                   width: 698px !important;
       margin-left: -75px !important;
           }
           #popup-banner-image-modal .modal-content{
                   width: 698px !important;
       margin-left: -75px !important;
           }


           }
           @media(max-width:375px){
               #main-banner-image-modal .modal-content{
                 width: 367px !important;
               margin-left: 0 !important;
           }
           #secondary-banner-image-modal .modal-content{
                 width: 367px !important;
               margin-left: 0 !important;
           }
           #popup-banner-image-modal .modal-content{
                 width: 367px !important;
               margin-left: 0 !important;
           }

           }

      @media(max-width:500px){
       #main-banner-image-modal .modal-content{
                 width: 400px !important;
               margin-left: 0 !important;
           }
           #secondary-banner-image-modal .modal-content{
                 width: 400px !important;
               margin-left: 0 !important;
           }
           #popup-banner-image-modal .modal-content{
                 width: 400px !important;
               margin-left: 0 !important;
           }


          }
      }
    </style>
@endpush

@section('content')
<div class="content container-fluid">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{trans('messages.Dashboard')}}</a></li>
            <li class="breadcrumb-item" aria-current="page">{{trans('messages.Banner')}}</li>
        </ol>
    </nav>
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-2">
        <h1 class="h3 mb-0 text-black-50"> {{trans('messages.Banner')}}</h1>
    </div>
    <div class="row">
        <div class="col-md-12" id="banner-btn">
            <button id="main-banner-add" class="btn btn-success" style="background: #258934"><i class="tio-add-circle"></i> {{ trans('messages.add_main_banner')}}</button>
            <button id="secondary-banner-add"
                    class="btn btn-success ml-2" style="background: #258934"><i class="tio-add-circle"></i> {{ trans('messages.add_secondary_banner')}}</button>
            <button id="popup-banner-add"
                    class="btn btn-success ml-2 propup" style="background: #258934"><i class="tio-add-circle"></i>  {{ trans('messages.add_popup_banner')}}</button>
        </div>
    </div>
    <!-- Content Row -->
    <div class="row pt-4" id="main-banner" style="display: none">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    {{ trans('messages.main_banner_form')}}
                </div>
                <div class="card-body">
                    <form action="{{route('admin.banner.store')}}" method="post">
                        @csrf
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-10">
                                    <input type="hidden" id="id">
                                    <label for="name">{{ trans('messages.banner_url')}}</label>
                                    <input type="text" name="url" class="form-control" id="url" required>
                                </div>
                                <div class="col-md-2">
                                    <input type="hidden" id="type" name="banner_type" value="Main Banner">
                                    <label for="name">{{ trans('messages.Image')}}</label><br>
                                    <button type="button" class="btn btn-secondary text-light btn-sm" data-toggle="modal"
                                            data-target="#main-banner-image-modal" data-whatever="@mdo"
                                            id="image-count-main-banner-image-modal">
                                            <i class="tio-add-circle"></i>  {{ trans('messages.Upload')}}
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <a class="btn btn-secondary text-white cancel">{{ trans('messages.Cancel')}}</a>
                            <button id="add" type="submit" class="btn btn-success" style="background: #258934">{{ trans('messages.save')}}</button>
                            <a id="update" class="btn btn-success"
                            style="display: none; color: #fff; background: #258934;">{{ trans('messages.update')}}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row pt-4" id="secondary-banner" style="display: none">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    {{ trans('messages.secondary_banner_form')}}
                </div>
                <div class="card-body">
                    <form action="{{route('admin.banner.store')}}" method="post">
                        @csrf
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-10">
                                    <input type="hidden" id="id">
                                    <label for="name">{{ trans('messages.banner_url')}}</label>
                                    <input type="text" name="url" class="form-control" id="footerurl" required>
                                </div>
                                <div class="col-md-2">
                                    <input type="hidden"id="footertype" name="banner_type" value="Footer Banner">
                                    <label for="name">{{ trans('messages.Image')}}</label><br>
                                    <button type="button" class="btn btn-secondary text-light btn-sm" data-toggle="modal"
                                            data-target="#secondary-banner-image-modal" data-whatever="@mdo"
                                            id="image-count-secondary-banner-image-modal">
                                            <i class="tio-add-circle"></i> {{ trans('messages.Upload')}}
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <a class="btn btn-secondary text-white cancel">{{ trans('messages.Cancel')}}</a>
                            <button type="submit" id="addfooter" class="btn btn-success" style="background: #258934" >{{ trans('messages.save')}}</button>
                            <a id="footerupdate" class="btn btn-success"
                            style="display: none; color: #fff; background: #258934;">{{ trans('messages.update')}}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row pt-4" id="popup-banner" style="display: none">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    {{ trans('messages.popup_banner_form')}}
                </div>
                <div class="card-body">
                    <form action="{{route('admin.banner.store')}}" method="post">
                        @csrf
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-10">
                                    <input type="hidden" id="id">
                                    <label for="name">{{ trans('messages.banner_url')}}</label>
                                    <input type="text" name="url" class="form-control" id="popupurl" required>
                                </div>
                                <div class="col-md-2">
                                    <input type="hidden" id="popuptype" name="banner_type" value="Popup Banner">
                                    <label for="name">{{trans('messages.Image')}}</label>
                                    <br>
                                    <button type="button" id="add" class="btn btn-secondary text-light btn-sm" data-toggle="modal"
                                            data-target="#popup-banner-image-modal" data-whatever="@mdo"
                                            id="image-count-popup-banner-image-modal">
                                            <i class="tio-add-circle"></i> {{trans('messages.Upload')}}
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="card-secondary">
                            <a class="btn btn-secondary text-white cancel">{{ trans('messages.Cancel')}}</a>
                            <button id="addpopup"
                            type="submit" class="btn btn-success" style="background: background: #258934">{{ trans('messages.save')}}</button>
                            <a id="popupupdate" class="btn btn-success"
                            style="display: none; color: #fff; background: #258934;">{{ trans('messages.update')}}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--modal-->
    @include('shared-partials.image-process._image-crop-modal',['modal_id'=>'main-banner-image-modal'])
    @include('shared-partials.image-process._image-crop-modal',['modal_id'=>'secondary-banner-image-modal'])
    @include('shared-partials.image-process._image-crop-modal',['modal_id'=>'popup-banner-image-modal'])
    <!--modal-->

    <div class="row" style="margin-top: 20px" id="banner-table">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>{{ trans('messages.banner_table')}}</h5>
                </div>
                <div class="card-body" style="padding: 0">
                    <div class="table-responsive">
                        <table id="columnSearchDatatable"
                               class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                            <thead class="thead-light">
                            <tr>
                                <th>{{trans('messages.sl')}}</th>
                                <th>{{trans('messages.image')}}</th>
                                <th>{{trans('messages.banner_type')}}</th>
                                <th>{{trans('messages.published')}}</th>
                                <th style="width: 50px">{{trans('messages.action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($banners as $key=>$banner)
                                <tr>
                                    <th scope="row">{{$key+1}}</th>
                                    <td>
                                        <img width="80"
                                             onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                             src="{{asset('storage/app/public/banner')}}/{{$banner['photo']}}">
                                    </td>
                                    <td>{{$banner->banner_type}}</td>
                                    <td><label class="switch"><input type="checkbox" class="status"
                                                                     id="{{$banner->id}}" <?php if ($banner->published == 1) echo "checked" ?>><span
                                                class="slider round"></span></label></td>

                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-outline-secondary dropdown-toggle" type="button"
                                                    id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                <i class="tio-settings"></i>
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <a class="dropdown-item  edit" style="cursor: pointer;"
                                                id="{{$banner['id']}}"> {{ trans('messages.Edit')}}</a>
                                                <a class="dropdown-item delete" style="cursor: pointer;"
                                                   id="{{$banner['id']}}"> {{ trans('messages.Delete')}}</a>
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
                    {{$banners->links()}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
    <script src="{{asset('public/assets/back-end')}}/js/select2.min.js"></script>
    <script>
        $('#main-banner-add').on('click', function () {
            $('#main-banner').show();
            $('#secondary-banner').hide();
            $('#popup-banner').hide();
            $('#banner-table').hide();
        });
        $('#secondary-banner-add').on('click', function () {
            $('#main-banner').hide();
            $('#secondary-banner').show();
            $('#popup-banner').hide();
            $('#banner-table').hide();
        });

        $('#popup-banner-add').on('click', function () {
            $('#main-banner').hide();
            $('#secondary-banner').hide();
            $('#popup-banner').show();
            $('#banner-table').hide();
        });

        $('.cancel').on('click', function () {
            $('#main-banner').hide();
            $('#secondary-banner').hide();
            $('#popup-banner').hide();
            $('#banner-table').show();
        });

        $(document).on('change', '.status', function () {
            var id = $(this).attr("id");
            if ($(this).prop("checked") == true) {
                var status = 1;
            } else if ($(this).prop("checked") == false) {
                var status = 0;
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{route('admin.banner.status')}}",
                method: 'POST',
                data: {
                    id: id,
                    status: status
                },
                success: function (data) {
                    if (data == 1) {
                        toastr.success('Banner publicado con ??xito');
                    } else {
                        toastr.success('Banner anulado correctamente');
                    }
                }
            });
        });

        $(document).on('click', '.delete', function () {
            var id = $(this).attr("id");
            Swal.fire({
                title: '??Est??s seguro de eliminar este banner?',
                text: "??No podr??s revertir esto!",
                showCancelButton: true,
                confirmButtonColor: '#258934',
                cancelButtonColor: 'dark',
                confirmButtonText: '??S??, b??rralo!'
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{route('admin.banner.delete')}}",
                        method: 'POST',
                        data: {id: id},
                        success: function () {
                            toastr.success('Banner eliminado correctamente');
                            location.reload();
                        }
                    });
                }
            })
        });

  $(document).on('click', '.edit', function () {

            var id = $(this).attr("id");
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{route('admin.banner.edit')}}",
                method: 'POST',
                data: {id: id},
                success: function (data) {

                    // console.log(data);
                    if(data.banner_type=='Main Banner'){

                        $('#main-banner').show();
                        $('#banner-table').hide();
                    $('#add').hide();
                    $('#update').show();
                    $('#id').val(data.id);
                        $('#url').val(data.url);
                        $('#cate-table').hide();

                    }
                    else if(data.banner_type=='Footer Banner')
                    {

                      $('#secondary-banner').show();
                      $('#banner-table').hide();
                    $('#addfooter').hide();
                    $('#footerupdate').show();
                    $('#id').val(data.id);
                        $('#footerurl').val(data.url);
                        $('#cate-table').hide();


                    }
                    else{
                        $('#popup-banner').show();
                        $('#banner-table').hide();
                    $('#addpopup').hide();
                    $('#popupupdate').show();
                    $('#id').val(data.id);
                        $('#popupurl').val(data.url);
                        $('#cate-table').hide();
                    }


                }
            });
        });
        $('#update').on('click', function () {
            $('#update').attr("disabled", true);
            var id = $('#id').val();
            var name = $('#url').val();
            var type =  $('#type').val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });

             $.ajax({
                url: "{{route('admin.banner.update')}}",
                method: 'POST',
                data: {
                    id: id,
                    url: name,
                    banner_type: type,

                },
                success: function (data) {
                    console.log(data);
                    $('#url').val('');
                    $.ajax({
                        type: 'get',
                        url: '{{route('image-remove',[0,'main_banner_image_modal'])}}',
                        dataType: 'json',
                        success: function (data) {
                            if (data.success === 1) {
                                $("#img-suc").hide();
                                $("#img-err").hide();
                                $("#crop").hide();
                                $("#show-images").html(data.photo);
                                $("#image-count").text(data.count);
                            } else if (data.success === 0) {
                                $("#img-suc").hide();
                                $("#img-err").show();
                            }
                        },
                    });
                    toastr.success('Banner principal actualizado correctamente.');


                    location.reload();
                }
            });
            $('#save').hide();

        });
        $('#footerupdate').on('click', function () {
            $('#footerupdate').attr("disabled", true);
            var id = $('#id').val();
            var name = $('#footerurl').val();
            var type =  $('#footertype').val();
            console.log(type)

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });

             $.ajax({
                url: "{{route('admin.banner.update')}}",
                method: 'POST',
                data: {
                    id: id,
                    url: name,
                    banner_type: type,

                },
                success: function (data) {

                    $('#url').val('');
                    $.ajax({
                        type: 'get',
                        url: '{{route('image-remove',[0,'secondary_banner_image_modal'])}}',
                        dataType: 'json',
                        success: function (data) {
                            if (data.success === 1) {
                                $("#img-suc").hide();
                                $("#img-err").hide();
                                $("#crop").hide();
                                $("#show-images").html(data.photo);
                                $("#image-count").text(data.count);
                            } else if (data.success === 0) {
                                $("#img-suc").hide();
                                $("#img-err").show();
                            }
                        },
                    });
                    toastr.success('Banner secundario actualizado correctamente.');


                    location.reload();
                }
            });
            $('#save').hide();

        });
        $('#popupupdate').on('click', function () {
            $('#popupupdate').attr("disabled", true);
            var id = $('#id').val();
            var name = $('#popupurl').val();
            var type =  $('#popuptype').val();


            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });

             $.ajax({
                url: "{{route('admin.banner.update')}}",
                method: 'POST',
                data: {
                    id: id,
                    url: name,
                    banner_type: type,

                },
                success: function (data) {

                    $('#url').val('');
                    $.ajax({
                        type: 'get',
                        url: '{{route('image-remove',[0,'popup_banner_image_modal'])}}',
                        dataType: 'json',
                        success: function (data) {
                            if (data.success === 1) {
                                $("#img-suc").hide();
                                $("#img-err").hide();
                                $("#crop").hide();
                                $("#show-images").html(data.photo);
                                $("#image-count").text(data.count);
                            } else if (data.success === 0) {
                                $("#img-suc").hide();
                                $("#img-err").show();
                            }
                        },
                    });
                    toastr.success('Banner emergente actualizado correctamente.');


                    location.reload();
                }
            });
            $('#save').hide();

        });

    </script>

    @include('shared-partials.image-process._script',[
     'id'=>'main-banner-image-modal',
     'height'=>590,
     'width'=>960,
     'multi_image'=>false,
     'route'=>route('image-upload')
     ])
    @include('shared-partials.image-process._script',[
     'id'=>'secondary-banner-image-modal',
     'height'=>390,
     'width'=>960,
     'multi_image'=>false,
     'route'=>route('image-upload')
     ])
    @include('shared-partials.image-process._script',[
     'id'=>'popup-banner-image-modal',
     'height'=>500,
     'width'=>800,
     'multi_image'=>false,
     'route'=>route('image-upload')
     ])

    <!-- Page level plugins -->
    <script src="{{asset('public/assets/back-end')}}/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="{{asset('public/assets/back-end')}}/vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#dataTable').DataTable();
        });
    </script>
@endpush
