<x-mail::message>
{{-- Header dengan Logo (opsional) --}}
<x-mail::panel>
    <div style="text-align: center; padding: 20px;">
        <img src="{{ asset('images/logo-ngoceh.png') }}" alt="Ngoceh Go" style="max-width: 200px; height: auto;">
    </div>
</x-mail::panel>

{{-- Greeting --}}
# Halo!

{{-- Intro Lines (pesan utama) --}}
Kamu baru saja meminta untuk mengatur ulang kata sandi akun Ngoceh Go-mu.  
Jangan khawatir, klik tombol di bawah ini untuk membuat kata sandi baru.

{{-- Action Button - Tombol hijau keren --}}
<x-mail::button :url="$actionUrl" color="success">
Atur Ulang Kata Sandi
</x-mail::button>

Link ini hanya berlaku selama **60 menit**.  
Jika kamu **tidak merasa** meminta reset password, abaikan email ini — akunmu tetap aman kok! 😊

{{-- Outro Lines --}}
Ada pertanyaan atau butuh bantuan? Balas email ini aja atau hubungi tim kami.

Terima kasih telah menggunakan Ngoceh Go!  
**Tim Ngoceh Go**

{{-- Subcopy (link cadangan kalau tombol rusak) --}}
<x-slot:subcopy>
Jika tombol di atas tidak berfungsi, salin dan tempel link ini ke browser kamu:<br>
<span class="break-all" style="color: #10b981; font-weight: bold;">{{ $displayableActionUrl }}</span>
</x-slot:subcopy>
</x-mail::message>