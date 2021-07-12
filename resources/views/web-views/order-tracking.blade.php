@extends('layouts.front-end.app')

@section('title','Seguir_Pedido')

@push('css_or_js')
    <meta property="og:image" content="{{asset('storage/app/public/company')}}/{{$web_config['web_logo']->value}}"/>
    <meta property="og:title" content="{{$web_config['name']->value}} "/>
    <meta property="og:url" content="{{env('APP_URL')}}">
    <meta property="og:description" content="{!! substr($web_config['about']->value,0,100) !!}">

    <meta property="twitter:card" content="{{asset('storage/app/public/company')}}/{{$web_config['web_logo']->value}}"/>
    <meta property="twitter:title" content="{{$web_config['name']->value}}"/>
    <meta property="twitter:url" content="{{env('APP_URL')}}">
    <meta property="twitter:description" content="{!! substr($web_config['about']->value,0,100) !!}">

    <link rel="stylesheet" media="screen"
          href="{{asset('public/assets/front-end')}}/vendor/nouislider/distribute/nouislider.min.css"/>
@endpush

@section('content')
    <!-- Order Details Modal-->
    <?php
    $order = \App\Model\OrderDetail::where('order_id', $orderDetails->id)->get();
    ?>
    <div class="modal fade" id="order-details">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pedido No - {{$orderDetails['id']}}</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body pb-0">
                    @php( $totalTax = 0)

                    @php($sub_total=0)
                    @php($total_tax=0)
                    @php($total_shipping_cost=0)
                    @php($total_discount_on_product=0)
                    @foreach($order as $product)
                        @php($productDetails = App\Model\Product::where('id', $product->product_id)->first())

                        <div class="d-sm-flex justify-content-between mb-4 pb-3 pb-sm-2 border-bottom">
                            <div class="media d-block d-sm-flex text-center text-sm-left">
                                <a class="d-inline-block mx-auto mr-sm-4"
                                   href="{{route('product',$productDetails->slug)}}" style="width: 10rem;">
                                    <img
                                        onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                        src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$productDetails['thumbnail']}}">
                                </a>
                                <div class="media-body pt-2">
                                    <h4 class="product-title font-size-base mb-2"><a
                                            href="{{route('product',$productDetails->slug)}}">{{$productDetails['name']}}</a>
                                    </h4>
                          @if($product['variation'])
                                    @foreach(json_decode($product['variation'],true) as $key1 =>$variation)
                                        <div class="text-muted"><span
                                                class="mr-2">{{$key1}} :</span>{{$variation}}</div>
                                    @endforeach
                                    @endif
                                    <div
                                        class="font-size-lg text-accent pt-2">{{\App\CPU\Helpers::currency_converter($product['price'])}}</div>
                                </div>
                            </div>
                            <div class="pt-2 pl-sm-3 mx-auto mx-sm-0 text-center">
                                <div class="text-muted mb-2">Cantidad:</div>{{$product['qty']}}
                            </div>
                            <div class="pt-2 pl-sm-2 mx-auto mx-sm-0 text-center">
                                <div class="text-muted mb-2">Impuesto(12%):
                                </div>{{\App\CPU\Helpers::currency_converter($product['tax'])}}
                            </div>
                            <div class="pt-2 pl-sm-3 mx-auto mx-sm-0 text-center">
                                <div class="text-muted mb-2">Subtotal</div>{{\App\CPU\Helpers::currency_converter($product['price']*$product['qty'])}}
                            </div>
                        </div>
                        @php($sub_total+=$product['price']*$product['qty'])
                        @php($total_tax+=$product['tax'])
                        @php($total_shipping_cost+=\App\Model\ShippingMethod::find($product['shipping_method_id'])->cost)
                        @php($total_discount_on_product+=$product['discount'])
                    @endforeach
                </div>
                <!-- Footer-->
                <div class="modal-footer flex-wrap justify-content-between bg-secondary font-size-md">
                    <div class="px-2 py-1">
                        <span
                            class="text-muted">Subtotal:&nbsp;</span>{{\App\CPU\Helpers::currency_converter($sub_total)}}
                        <span></span>
                    </div>
                    <div class="px-2 py-1">
                        <span
                            class="text-muted">Método de envío:&nbsp;</span>{{\App\CPU\Helpers::currency_converter($total_shipping_cost)}}
                        <span></span>
                    </div>
                    <div class="px-2 py-1">
                        <span class="text-muted">Impuesto(12%):&nbsp;</span> {{\App\CPU\Helpers::currency_converter($total_tax)}}
                        <span></span>
                    </div>

                    <div class="px-2 py-1">
                        <span
                            class="text-muted">Descuento:&nbsp;</span>
                        - {{\App\CPU\Helpers::currency_converter($product['discount'])}}
                        <span></span>
                    </div>

                    <div class="px-2 py-1">
                        <span class="text-muted">Total:&nbsp; </span>
                        <span class="font-size-lg">
                             {{\App\CPU\Helpers::currency_converter($sub_total+$total_tax+$total_shipping_cost-($orderDetails->discount)-$total_discount_on_product)}}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page Title (Dark)-->
    <div class="container">

        <div class="pt-3 pb-3">
            <h2>Mi pedido</h2>
        </div>
        <div class="btn-primary">
            <div class="container d-lg-flex justify-content-between py-2 py-lg-3">

                <div class="order-lg-1 pr-lg-4 text-center text-lg-left">
                    <h4 class="text-light mb-0">PEDIDO ID : <span
                            class="h4 font-weight-normal text-light">{{$orderDetails['id']}}</span></h4>
                </div>
            </div>
        </div>

    </div>
    <!-- Page Content-->
    <div class="container mb-md-3">
        <!-- Details-->
        <div class="row" style="background: #e2f0ff; margin: 0; width: 100%;">
            <div class="col-sm-4">
                <div class="pt-2 pb-2 text-center rounded-lg">
                    <span class="font-weight-medium text-dark mr-2">Estado de Pedido:</span><br>
                    @if($orderDetails['order_status']=='pending')
                    <span class="text-uppercase ">PENDIENTE</span>
                    @elseif(($orderDetails['order_status']=='processed'))
                    <span class="text-uppercase ">PROCESANDO</span>
                    @elseif(($orderDetails['order_status']=='returned'))
                    <span class="text-uppercase ">DEVUELTO</span>
                    @elseif(($orderDetails['order_status']=='delivered'))
                    <span class="text-uppercase ">ENTREGADO</span>
                    @else
                    <span class="text-uppercase ">FÁLLO</span>

                    @endif


                    {{-- <span class="text-uppercase ">Courier</span>
                    @if($order['order_status']=='pending')
                                            <span class="badge badge-soft-success ml-sm-3">
                                                <span class="legend-indicator bg-success"></span>Pendiente
                                            </span>
                                        @elseif($order['order_status']=='processed')
                                            <span class="badge badge-soft-danger ml-sm-3">
                                                <span class="legend-indicator bg-danger"></span>Procesando
                                            </span>
                                            @elseif($order['order_status']=='returned')
                                            <span class="badge badge-soft-danger ml-sm-3">
                                                <span class="legend-indicator bg-danger"></span>Devuelto
                                            </span>
                                            @elseif($order['order_status']=='delivered')
                                            <span class="badge badge-soft-danger ml-sm-3">
                                                <span class="legend-indicator bg-danger"></span>Entregado
                                            </span>
                                            @else
                                            <span class="badge badge-soft-danger ml-sm-3">
                                                <span class="legend-indicator bg-danger"></span>Fállo
                                            </span>

                                        @endif



                    --}}

                </div>
            </div>
            <div class="col-sm-4">
                <div class="pt-2 pb-2 text-center rounded-lg">
                    <span class="font-weight-medium text-dark mr-2">Estado de Pago:</span> <br>
                    @if($orderDetails['payment_status']=='unpaid')
                    <span class="text-uppercase">Sin pagar</span>
                    @else
                    <span class="text-uppercase">Pagado</span>
                    @endif

                </div>
            </div>
            <div class="col-sm-4">
                <div class="pt-2 pb-2 text-center rounded-lg">
                    <span class="font-weight-medium text-dark mr-2"> Fecha estimada de entrega: </span> <br>
                    <span class="text-uppercase"
                          style="font-weight: 600; color: {{$web_config['primary_color']}}">{{Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$orderDetails['updated_at'])->format('Y-m-d')}}</span>
                </div>
            </div>
        </div>
        <!-- Progress-->
        <div class="card border-0 box-shadow-lg mt-5">
            <div class="card-body pb-2">
                <ul class="nav nav-tabs media-tabs nav-justified">
                    @if ($orderDetails['order_status']!='returned' && $orderDetails['order_status']!='failed')

                        <li class="nav-item">
                            <div class="nav-link">
                                <div class="align-items-center">
                                    <div class="media-tab-media"
                                         style="margin: 0 auto; background: #4bcc02; color: white;">
                                        <i class="czi-check"></i>
                                    </div>
                                    <div class="media-body" style="text-align: center;">
                                        <div class="media-tab-subtitle text-muted font-size-xs mb-1">Primer paso</div>
                                        <h6 class="media-tab-title text-nowrap mb-0">Pedido realizado</h6>
                                    </div>
                                </div>
                            </div>
                        </li>

                        <li class="nav-item ">
                            <div class="nav-link ">
                                <div class="align-items-center">
                                    <div class="media-tab-media"
                                         style="margin: 0 auto; @if(($orderDetails['order_status']=='processed') || ($orderDetails['order_status']=='processed') || ($orderDetails['order_status']=='delivered')) background: #4bcc02; color: white; @endif ">
                                        @if(($orderDetails['order_status']=='processed') || ($orderDetails['order_status']=='processed') || ($orderDetails['order_status']=='delivered'))
                                            <i class="czi-check"></i>
                                        @endif
                                    </div>
                                    <div class="media-body" style="text-align: center;">
                                        <div class="media-tab-subtitle text-muted font-size-xs mb-1">Segundo paso</div>
                                        <h6 class="media-tab-title text-nowrap mb-0">Orden procesada</h6>
                                    </div>
                                </div>
                            </div>
                        </li>

                        <li class="nav-item">
                            <div class="nav-link  ">
                                <div class="align-items-center">
                                    <div class="media-tab-media"
                                         style="margin: 0 auto; @if(($orderDetails['order_status']=='processed') || ($orderDetails['order_status']=='delivered')) background: #4bcc02; color: white; @endif ">
                                        @if(($orderDetails['order_status']=='processed') || ($orderDetails['order_status']=='delivered'))
                                            <i class="czi-check"></i>
                                        @endif
                                    </div>
                                    <div class="media-body" style="text-align: center;">
                                        <div class="media-tab-subtitle text-muted font-size-xs mb-1">Tercer paso</div>
                                        <h6 class="media-tab-title text-nowrap mb-0">Producto enviado</h6>
                                    </div>
                                </div>
                            </div>
                        </li>

                        <li class="nav-item">
                            <div class="nav-link ">
                                <div class="align-items-center">
                                    <div class="media-tab-media"
                                         style="margin: 0 auto; @if(($orderDetails['order_status']=='delivered')) background: #4bcc02; color: white; @endif">
                                        @if(($orderDetails['order_status']=='delivered'))
                                            <i class="czi-check"></i>
                                        @endif
                                    </div>
                                    <div class="media-body" style="text-align: center;">
                                        <div class="media-tab-subtitle text-muted font-size-xs mb-1">Cuarto paso</div>
                                        <h6 class="media-tab-title text-nowrap mb-0">Producto enviado</h6>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @elseif($orderDetails['order_status']=='returned')
                        <li class="nav-item">
                            <div class="nav-link" style="text-align: center;">
                                <h1 class="text-warning">Producto devuelto con éxito</h1>
                            </div>
                        </li>
                    @else
                        <li class="nav-item">
                            <div class="nav-link" style="text-align: center;">
                                <h1 class="text-danger">Lo sentimos, no podemos completar su pedido.</h1>
                            </div>
                        </li>
                    @endif

                </ul>
            </div>
        </div>
        <!-- Footer-->
        <div class="d-sm-flex flex-wrap justify-content-between align-items-center text-center pt-3">
            <div class="custom-control custom-checkbox mt-1 mr-3">
            </div>
            <a class="btn btn-primary btn-sm mt-2 mb-2" href="#order-details" data-toggle="modal">Ver detalles del Pedido</a>
        </div>
    </div>

@endsection

@push('script')
    <script src="{{asset('public/assets/front-end')}}/vendor/nouislider/distribute/nouislider.min.js"></script>
@endpush
