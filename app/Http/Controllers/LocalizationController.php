<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LocalizationController extends Controller
{
    /**
     * Mengalihkan bahasa aplikasi dan menyimpannya di session.
     *
     * @param  string  $locale
     * @return \Illuminate\Http\RedirectResponse
     */
    public function switch($locale)
    {
        // 1. Validasi bahasa yang didukung oleh aplikasi Anda
        if (in_array($locale, ['id', 'en'])) {

            // 2. Simpan kode bahasa ('id' atau 'en') ke dalam session
            Session::put('locale', $locale);
        }

        // 3. Arahkan pengguna kembali ke halaman yang mereka kunjungi sebelumnya
        return redirect()->back();
    }
}
