<?php

namespace App\Http\Controllers\Web;

use App\CPU\CartManager;
use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function apply(Request $request)
    {

        $cartItem=0;
        $coupon = Coupon::where(['code' => $request['code']])->where('start_date', '<=', now())->where('expire_date', '>', now())->first();
        if ($coupon) {
            $total = 0;
            $total_pro=0;
            foreach (CartManager::get_cart() as $cart) {
                $product_subtotal = $cart['price'] * $cart['quantity'];
                $total += $product_subtotal;
                $total_pro=$total_pro+$cart['quantity'];

            }
            if( $total_pro>=6){

                if ($total >= $coupon['min_purchase']) {
                    if ($coupon['discount_type'] == 'percentage') {
                        $discount = (($total / 100) * $coupon['discount']);
                    }

                    session()->put('coupon_code', $request['code']);
                    session()->put('coupon_discount', $discount);

                    return response()->json([
                        'status' => 1,
                        'discount' => Helpers::currency_converter($discount),
                        'total' => Helpers::currency_converter($total - $discount),
                        'messages' => ['0' => '¡Cupón aplicado con éxito!']
                    ]);
                }

            }
                else{
                        return response()->json([
                            'messages' => ['0' => '¡Debe Comprar mas de 6 articulos!']
                        ]);
                    }
            }


        return response()->json([
            'status' => 0,
            'messages' => ['0' => 'Cupón inválido']
        ]);
    }
}
