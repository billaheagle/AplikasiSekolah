<?php

namespace App\Console\Commands;

use App\Models\Reminder;
use App\Models\UnitIncharge;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ReminderCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rekapitulasi DMTL';

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
        $today = date('d-m-Y', strtotime(Carbon::today()->toDateString()));
        $result = $this->summarize($today);

        if(count($result) > 0) {
            foreach ($result as $reminder) {
                Reminder::create([
                    'process_date' => date('Y-m-d', strtotime($today)),
                    'unit_id' => $reminder->unit_id,
                    'unit_code' => $reminder->unit_code,
                    'audit_type_id' => $reminder->audit_type_id,
                    'audit_type_name' => $reminder->audit_type_name,
                    'due_date' => $reminder->due_date,
                    'reminder_email_to' => $reminder->reminder_email_to,
                    'reminder_email_cc' => $reminder->reminder_email_cc,
                    'job_email_to' => $reminder->job_email_to,
                    'initial_email_to' => $reminder->initial_email_to,
                    'audit_year' => $reminder->audit_year,
                    'sum_of_finding' => $reminder->sum_of_finding,
                    'sum_of_done' => $reminder->sum_of_done,
                    'sum_of_pending' => $reminder->sum_of_pending,
                    'sum_of_due' => $reminder->sum_of_due,
                    'sum_of_not_due' => $reminder->sum_of_not_due,
                    'sum_of_due_in_month' => $reminder->sum_of_due_in_month,
                    'maker' => 1,
                    'modifier' => 1,
                ]);
            }
        }
        
        return 0;
    }

    private function summarize($date)
    {
        return UnitIncharge::join('findings','findings.id','=','unit_incharge.finding_id')
        ->join('ref_unit','ref_unit.id','=','unit_incharge.unit_id')
        ->join('audits','audits.id','=','findings.audit_id')
        ->join('ref_audit_type','ref_audit_type.id','=','audits.audit_type_id')
        ->join('ref_finding_status','ref_finding_status.id','=','findings.status_id')
        ->select('unit_incharge.unit_id AS unit_id', 'ref_unit.code AS unit_code', 'audits.audit_type_id AS audit_type_id', 'ref_audit_type.name AS audit_type_name', 'findings.due_date AS due_date',
            'ref_unit.email_to AS reminder_email_to', 'ref_unit.email_cc AS reminder_email_cc', 'ref_unit.job_email_to', 'ref_unit.initial_email_to', 'audits.year AS audit_year',
            DB::raw("COUNT(findings.id) AS sum_of_finding"),
            DB::raw("COUNT(CASE WHEN ref_finding_status.code = 'DONE' THEN 1 ELSE NULL END) AS sum_of_done"),
            DB::raw("COUNT(CASE WHEN ref_finding_status.code = 'PENDING' THEN 1 ELSE NULL END) AS sum_of_pending"),
            DB::raw("COUNT(CASE WHEN ref_finding_status.code = 'PENDING' AND findings.due_date < " . date('Y-m-d', strtotime($date)) . " THEN 1 ELSE NULL END) AS sum_of_due"),
            DB::raw("COUNT(CASE WHEN ref_finding_status.code = 'PENDING' AND findings.due_date >= " . date('Y-m-d', strtotime($date)) . " THEN 1 ELSE NULL END) AS sum_of_not_due"),
            DB::raw("COUNT(CASE WHEN ref_finding_status.code = 'PENDING' AND MONTH(findings.due_date) = " . date('m', strtotime($date)) . " AND YEAR(findings.due_date) = " . date('Y', strtotime($date)) . " THEN 1 ELSE NULL END) AS sum_of_due_in_month"),
        )
        ->groupBy('audits.year')
        ->groupBy('audits.audit_type_id')
        ->groupBy('unit_incharge.unit_id')
        ->get();
    }
}
