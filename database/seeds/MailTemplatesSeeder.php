<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class MailTemplatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mail_templates')->insert([
            [
                'template_name' => 'Time to create a timesheet',
                'title' => 'Đã đến giờ tạo timesheet',
                'message' => 'Đã bắt đầu thời gian tạo timesheet. Bạn hãy vào ngay '. URL::to('/') .'!!!'
            ],
            [
                'template_name' => 'Deadline to create timesheet.',
                'title' => 'Sắp hết giờ tạo timesheet',
                'message' => 'Bạn chưa nộp timesheet. Bạn hãy vào ngay '. URL::to('/') . '!!!'
            ],
            [
                'template_name' => 'Notice to leader and notified user',
                'title' => 'Thông báo về việc nhân viên {{$user.username}} nộp timesheet',
                'message' => '{{$user.username}} đã tạo timesheet. Bạn hãy vào approve nó đi!!!'
            ]
        ]);
    }
}
