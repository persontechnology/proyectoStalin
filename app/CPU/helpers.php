<?php

namespace App\CPU;

use App\Model\BusinessSetting;
use App\Model\Color;
use App\Model\Currency;
use App\Model\Review;
use Illuminate\Support\Facades\Session;

class Helpers
{
    public static function status($id)
    {
        if ($id == 1) {
            $x = 'active';
        } elseif ($id == 0) {
            $x = 'in-active';
        }

        return $x;
    }
    public static function rating_count($product_id, $rating)
    {
        return Review::where(['product_id' => $product_id, 'rating' => $rating])->count();
    }
    public static function get_business_settings($name)
    {
        $config = null;
        foreach (BusinessSetting::all() as $setting) {
            if ($setting['type'] == $name) {
                $config = json_decode($setting['value'], true);
            }
        }
        return $config;
    }

    public static function get_settings($object, $type)
    {
        $config = null;
        foreach ($object as $setting) {
            if ($setting['type'] == $type) {
                $config = $setting;
            }
        }
        return $config;
    }

    public static function get_image_path($type)
    {
        $path = asset('storage/app/public/');
        return $path;
    }

    public static function product_data_formatting($data, $multi_data = false)
    {
        $storage = [];
        if ($multi_data == true) {
            foreach ($data as $item) {
                $variation = [];
                $item['category_ids'] = json_decode($item['category_ids']);
                $item['images'] = json_decode($item['images']);
                $item['colors'] = Color::whereIn('code', json_decode($item['colors']))->get(['name', 'code']);
                $item['attributes'] = json_decode($item['attributes']);
                $item['choice_options'] = json_decode($item['choice_options']);
                foreach (json_decode($item['variation'], true) as $var) {
                    array_push($variation, [
                        'type'  => $var['type'],
                        'price' => (double) $var['price'],
                        'sku'   => $var['sku'],
                        'qty'   => (integer)$var['qty'],
                    ]);
                }
                $item['variation'] = $variation;
                array_push($storage, $item);
            }
            $data = $storage;
        } else {
            $variation = [];
            $data['category_ids'] = json_decode($data['category_ids']);
            $data['images'] = json_decode($data['images']);
            $data['colors'] = Color::whereIn('code', json_decode($data['colors']))->get(['name', 'code']);
            $data['attributes'] = json_decode($data['attributes']);
            $data['choice_options'] = json_decode($data['choice_options']);
            foreach (json_decode($data['variation'], true) as $var) {
                array_push($variation, [
                    'type'  => $var['type'],
                    'price' => (double) $var['price'],
                    'sku'   => $var['sku'],
                    'qty'   => (integer)$var['qty'],
                ]);
            }
            $data['variation'] = $variation;
        }

        return $data;
    }

    public static function units()
    {
        $x = ['kg', 'pc', 'gms', 'ltrs'];
        return $x;
    }

    public static function translate($key)
    {
        $key = ucfirst(str_replace('_', ' ', Helpers::remove_invalid_charcaters($key)));
        $jsonString = file_get_contents(base_path('resources/lang/en.json'));
        $jsonString = json_decode($jsonString, true);
        if (!isset($jsonString[$key])) {
            $jsonString[$key] = $key;
            Helpers::saveJSONFile('en', $jsonString);
        }
        return __($key);
    }

    public static function remove_invalid_charcaters($str)
    {
        return str_ireplace(['\'', '"', ',', ';', '<', '>', '?'], ' ', $str);
    }

    public static function saveJSONFile($code, $data)
    {
        ksort($data);
        $jsonData = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        file_put_contents(base_path('resources/lang/' . $code . '.json'), stripslashes($jsonData));
    }

    public static function combinations($arrays)
    {
        $result = [[]];
        foreach ($arrays as $property => $property_values) {
            $tmp = [];
            foreach ($result as $result_item) {
                foreach ($property_values as $property_value) {
                    $tmp[] = array_merge($result_item, [$property => $property_value]);
                }
            }
            $result = $tmp;
        }
        return $result;
    }

    public static function error_processor($validator)
    {
        $err_keeper = [];
        foreach ($validator->errors()->getMessages() as $index => $error) {
            array_push($err_keeper, ['code' => $index, 'message' => $error[0]]);
        }
        return $err_keeper;
    }

    public static function currency_load()
    {
        if (\session()->has('system_default_currency_info') == false) {
            \session()->put('system_default_currency_info', Currency::find(1));
        }
    }

    public static function currency_converter($amount)
    {
        return format_price(convert_price($amount));
    }

    public static function language_load()
    {
        if (\session()->has('language_settings')) {
            $language = \session('language_settings');
        } else {
            $language = BusinessSetting::where('type', 'language')->first();
            \session()->put('language_settings', $language);
        }
        return $language;
    }

    public static function tax_calculation($price, $tax, $tax_type)
    {
        $amount = 0;
        if ($tax_type == 'percent') {
            $amount = ($price * $tax) / 100;
        } elseif ($tax_type == 'amount') {
            $amount = $tax;
        }

        return $amount;
    }

    public static function get_price_range($product)
    {

        $lowest_price = $product->unit_price;
        $highest_price = $product->unit_price;

        foreach (json_decode($product->variation) as $key => $variation) {
            if ($lowest_price > $variation->price) {
                $lowest_price = $variation->price;
            }
            if ($highest_price < $variation->price) {
                $highest_price = $variation->price;
            }
        }

        /*if ($product->tax_type == 'percent') {
        $lowest_price += ($lowest_price * $product->tax) / 100;
        $highest_price += ($highest_price * $product->tax) / 100;
        } elseif ($product->tax_type == 'amount') {
        $lowest_price += $product->tax;
        $highest_price += $product->tax;
        }*/

        $lowest_price = convert_price($lowest_price - Helpers::get_product_discount($product, $lowest_price));
        $highest_price = convert_price($highest_price - Helpers::get_product_discount($product, $highest_price));

        if ($lowest_price == $highest_price) {
            return currency_symbol().number_format($lowest_price, 2) ;
        }
        return currency_symbol() .number_format($lowest_price, 2) .  ' - '. currency_symbol() . number_format($highest_price, 2) ;
    }

    public static function get_product_discount($product, $price)
    {
        $discount = 0;
        if ($product->discount_type == 'percent') {
            $discount = ($price * $product->discount) / 100;
        } elseif ($product->discount_type == 'amount') {
            $discount = $product->discount;
        }

        return floatval($discount);
    }

    public static function module_permission_check($mod_name)
    {
        $permission = auth('admin')->user()->role->module_access;
        if (isset($permission) && in_array($mod_name, (Array) json_decode($permission)) == true) {
            return true;
        }

        if (auth('admin')->user()->admin_role_id == 1) {
            return true;
        }
        return false;
    }

    public static function convert_currency_to_usd($price)
    {
        Helpers::currency_load();
        $code = session('currency_code') == null ? 'USD' : session('currency_code');
        $currency = Currency::where('code', $code)->first();
        $price = floatval($price) / floatval($currency->exchange_rate);
        return $price;
    }

    public static function order_status_update_message($status)
    {
        if ($status == 'pending') {
            $data = BusinessSetting::where('type', 'order_pending_message')->first()->value;
        } elseif ($status == 'confirmed') {
            $data = BusinessSetting::where('type', 'order_confirmation_msg')->first()->value;
        } elseif ($status == 'processing') {
            $data = BusinessSetting::where('type', 'order_processing_message')->first()->value;
        } elseif ($status == 'out_for_delivery') {
            $data = BusinessSetting::where('type', 'out_for_delivery_message')->first()->value;
        } elseif ($status == 'delivered') {
            $data = BusinessSetting::where('type', 'order_delivered_message')->first()->value;
        } elseif ($status == 'delivery_boy_delivered') {
            $data = BusinessSetting::where('type', 'delivery_boy_delivered_message')->first()->value;
        } elseif ($status == 'del_assign') {
            $data = BusinessSetting::where('type', 'delivery_boy_assign_message')->first()->value;
        } elseif ($status == 'ord_start') {
            $data = BusinessSetting::where('type', 'delivery_boy_start_message')->first()->value;
        } else {
            $data = '{"status":"0","message":""}';
        }

        $res = json_decode($data, true);

        if ($res['status'] == 0) {
            return 0;
        }
        return $res['message'];
    }

    public static function send_push_notif_to_device($fcm_token, $data)
    {

        $key = BusinessSetting::where(['type' => 'push_notification_key'])->first()->value;

        $url = "https://fcm.googleapis.com/fcm/send";
        $header = ["authorization: key=" . $key . "",
            "content-type: application/json",
        ];

        $postdata = '{
            "to" : "' . $fcm_token . '",
            "data" : {
                "title":"' . $data['title'] . '",
                "body" : "' . $data['description'] . '",
                "image" : "' . $data['image'] . '",
                "is_read": 0
              }
        }';

        $ch = curl_init();
        $timeout = 120;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        // Get URL content
        $result = curl_exec($ch);
        // close handle to release resources
        curl_close($ch);

        return $result;
    }

    public static function send_push_notif_to_topic($data)
    {
        /*https://fcm.googleapis.com/v1/projects/myproject-b5ae1/messages:send*/
        $key = BusinessSetting::where(['type' => 'push_notification_key'])->first()->value;
        /*$topic = BusinessSetting::where(['key' => 'fcm_topic'])->first()->value;*/
        /*$project_id = BusinessSetting::where(['key' => 'fcm_project_id'])->first()->value;*/

        $url = "https://fcm.googleapis.com/fcm/send";
        $header = ["authorization: key=" . $key . "",
            "content-type: application/json",
        ];
        $postdata = '{
            "to" : "/topics/sixvalley",
            "data" : {
                "title":"' . $data->title . '",
                "body" : "' . $data->description . '",
                "image" : "' . $data->image . '",
                "is_read": 0
              }
        }';

        $ch = curl_init();
        $timeout = 120;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        // Get URL content
        $result = curl_exec($ch);
        // close handle to release resources
        curl_close($ch);

        return $result;
    }
}

/*if (!function_exists('translate')) {
function translate($key)
{
$key = ucfirst(str_replace('_', ' ', Helpers::remove_invalid_charcaters($key)));
$jsonString = file_get_contents(base_path('resources/lang/en.json'));
$jsonString = json_decode($jsonString, true);
if (!isset($jsonString[$key])) {
$jsonString[$key] = $key;
Helpers::saveJSONFile('en', $jsonString);
}
return __($key);
}
}*/
//currency symbol
if (!function_exists('currency_symbol')) {
    function currency_symbol()
    {
        Helpers::currency_load();
        if (\session()->has('currency_symbol')) {
            $symbol = \session('currency_symbol');
        } else {
            $system_default_currency_info = \session('system_default_currency_info');
            $symbol = $system_default_currency_info->symbol;
        }

        return $symbol;
    }
}
//formats currency
if (!function_exists('format_price')) {
    function format_price($price)
    {
        return  currency_symbol(). number_format($price, 2) ;
    }
}
//converts currency to home default currency
if (!function_exists('convert_price')) {
    function convert_price($price)
    {
        Helpers::currency_load();
        $system_default_currency_info = \session('system_default_currency_info');

        $price = floatval($price) / floatval($system_default_currency_info->exchange_rate);

        if (Session::has('currency_exchange_rate')) {
            $exchange = \session('currency_exchange_rate');
        } else {
            $exchange = $system_default_currency_info->exchange_rate;
        }

        $price = floatval($price) * floatval($exchange);

        return $price;
    }

}
