@component('mail::message')
# {{ $details['title'] }}

Bapak/Ibu yang terhormat: <br><br>
Kami informasikan bahwa tindak lanjut Bapak/Ibu terhadap DMTL berikut:<br><br>

@component('mail::table')
| Detail           | Keterangan                                           |
| ---------------- |:----------------------------------------------------:|
| Penugasan Audit  | {{$finding->audit->name}}                            |
| Tgl. DMTL        | {{ date('d-m-Y', strtotime($finding->created_at)) }} |
| Tgl. Jt. Tempo   | {{ date('d-m-Y', strtotime($finding->due_date)) }}   |
| Permasalahan     | {{$finding->problems}}                               |
| Rekomendasi      | {{$finding->recommendation}}                         |
@endcomponent

@if ($type == 'reject')
telah ditolak oleh pihak yang berwenang. Adapun status saat ini masih <b>{{$finding->finding_status->code}}</b>. <br><br>
@else
telah disetujui oleh pihak yang berwenang. Adapun status saat ini telah <b>{{$finding->finding_status->code}}</b>. <br><br>
@endif
{{-- telah berubah status menjadi <b>{{$finding->finding_status->code}}</b> oleh pihak yang berwenang <br><br> --}}

@component('mail::panel')
Detail DMTL tersebut dapat dilihat pada aplikasi {{ config('app.name') }}
@endcomponent

@component('mail::button', ['url' => $details['url'], 'color' => 'primary'])
GO TO DMTL
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
