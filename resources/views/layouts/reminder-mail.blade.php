@component('mail::message')
# {{ $details['title'] }}

Kepada: <br>
{{ $details['unit'] }} <br><br>
Perihal: REMINDER PENYELESAIAN DMTL <br><br>
Assalamualaikum Wr. Wb. <br><br>
Semoga Bapak beserta jajaran {{ $details['unit'] }} senantiasa dalam keadaan sehat wal'afiat dan mendapatkan taufik dan hidayah dari Allah SWT. <br><br>
Menindaklanjuti perihal tersebut diatas, dengan ini kami informasikan bahwa sesuai data di Aplikasi {{ config('app.name') }} IAG Per {{ $details['process_date'] }},
{{ $details['unit'] }} mempunyai DMTL dengan komposisi sebagai berikut: <br><br>

@foreach($reminders as $key => $reminder)
<h2>{{ $key }}</h2>
@component('mail::table')
| Unit Kerja             | Periode Audit           | Jumlah DMTL                 | Done                     | Pending                     | Telah Jatuh Tempo       | Belum Jatuh Tempo           | Jatuh Tempo Sampai Akhir Bulan   |
| ---------------------- |:-----------------------:|:---------------------------:|:------------------------:|:---------------------------:|:-----------------------:|:---------------------------:|---------------------------------:|
@foreach($reminder as $temp)
| {{ $temp->unit_code }} | {{ $temp->audit_year }} | {{ $temp->sum_of_finding }} | {{ $temp->sum_of_done }} | {{ $temp->sum_of_pending }} | {{ $temp->sum_of_due }} | {{ $temp->sum_of_not_due }} | {{ $temp->sum_of_due_in_month }} |
@endforeach
@endcomponent
<br>
@endforeach

Sehubungan dengan hal tersebut, kami mengharapkan agar Bapak menindaklanjuti sebelum tanggal {{ $details['due_date'] }} <br><br>

@component('mail::panel')
Detail DMTL yang harus diselesaikan dapat dilihat pada Aplikasi {{ config('app.name') }}
@endcomponent

@component('mail::button', ['url' => $details['url'], 'color' => 'primary'])
GO TO DMTL
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
