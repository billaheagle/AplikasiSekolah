<?php

namespace App\Console\Commands;

use App\Models\Reminder;
use App\Mail\ReminderDMTL;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;

class SendReminderCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kirim Reminder DMTL';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $unitToReminds = Reminder::select('reminders.unit_id', 'reminders.unit_code',
        DB::raw("MAX(CAST(reminders.process_date AS CHAR)) AS last_process_date"))
        ->where('reminders.sent_status', '=', 'N')
        ->groupBy('reminders.unit_id')
        ->get();
        
        if(count($unitToReminds) > 0) {
            foreach($unitToReminds as $unitToRemind) {
                $reminders = Reminder::where(DB::raw("DATE(process_date)"), date('Y-m-d', strtotime($unitToRemind->last_process_date)))
                ->where('unit_id', $unitToRemind->unit_id)
                ->get()
                ->groupBy(function ($item) {
                    return $item->audit_type_name;
                })
                ->toBase();

                $data = Reminder::with('unit')->where(DB::raw("DATE(process_date)"), date('Y-m-d', strtotime($unitToRemind->last_process_date)))
                ->where('unit_id', $unitToRemind->unit_id)
                ->first();
                $this->mail($reminders, $data); // ==> KIRIM EMAIL DISINI, AMBIL DATA $reminders

                $updateStatus = Reminder::where(DB::raw("DATE(process_date)"), date('Y-m-d', strtotime($unitToRemind->last_process_date)))->where('unit_id', $unitToRemind->unit_id);
                $updateStatus->update(['sent_status' => 'Y', 'sent_date' => Carbon::now(), 'modifier' => 1]);
            }
        }

        return 0;
    }

    private function mail($reminders, $data)
    {
        // check if email to & cc more than 1 address
        $mailTo = explode(';', $data->reminder_email_to);
        $mailCC = explode(';', $data->reminder_email_cc);

        $urls = Config::get('app.url');
        
        $details = [
            'title' => 'Reminder Penyelesaian DMTL',
            'unit' => $data->unit->code . ' - ' . $data->unit->name,
            'today' => date('d-m-Y', strtotime(Carbon::today()->toDateString())),
            'due_date' => date('d-m-Y', strtotime(Carbon::createFromFormat('m', date('m', strtotime($data->process_date)))->endOfMonth()->toDateString())),
            'process_date' => date('d-m-Y', strtotime($data->process_date)),
            'url' => $urls
        ];

        $email = new ReminderDMTL($details, $reminders);

        Mail::to($mailTo)->cc($mailCC)->send($email);
        // Mail::to("reminder_email_to@bsm.co.id")->send($email); // ==> To Be Deleted
    }
}
