<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get users
        $citizens = User::where('role', 'citizen')->take(10)->get();
        $admins = User::whereIn('role', ['admin', 'superadmin'])->take(3)->get();

        // Notification templates in Arabic
        $notificationTemplates = [
            // General notifications
            [
                'title' => 'مرحباً بك في البلدية الرقمية',
                'body' => 'نشكرك على تسجيلك في تطبيق البلدية الرقمية. يمكنك الآن الاستفادة من جميع الخدمات الإلكترونية.',
                'type' => 'general',
                'data' => ['welcome' => true],
            ],
            [
                'title' => 'تحديث النظام',
                'body' => 'تم إضافة خدمات جديدة للتطبيق. قم بتحديث التطبيق للحصول على أحدث الميزات.',
                'type' => 'general',
                'data' => ['version' => '2.0.0'],
            ],
            // Request notifications
            [
                'title' => 'تم استلام طلبك',
                'body' => 'تم استلام طلبك بنجاح وجاري مراجعته من قبل الفريق المختص. سيتم إبلاغك بأي تحديثات.',
                'type' => 'request',
                'data' => ['request_id' => '01JH2K3M4N5P6Q7R8S9T0V1W2X', 'status' => 'pending'],
            ],
            [
                'title' => 'تمت الموافقة على طلبك',
                'body' => 'تمت الموافقة على طلب رخصة البناء الخاص بك. يرجى مراجعة التفاصيل.',
                'type' => 'request',
                'data' => ['request_id' => '01JH2K3M4N5P6Q7R8S9T0V1W2X', 'status' => 'approved'],
            ],
            [
                'title' => 'مطلوب معلومات إضافية',
                'body' => 'يرجى تقديم معلومات إضافية لاستكمال معالجة طلبك. راجع التفاصيل في قسم الطلبات.',
                'type' => 'request',
                'data' => ['request_id' => '01JH2K3M4N5P6Q7R8S9T0V1W2X', 'status' => 'info_needed'],
            ],
            // Complaint notifications
            [
                'title' => 'تم استلام شكواك',
                'body' => 'شكراً لتواصلك معنا. تم استلام شكواك وجاري العمل على حلها في أقرب وقت.',
                'type' => 'complaint',
                'data' => ['complaint_id' => '01JH2K3M4N5P6Q7R8S9T0V1W2X', 'status' => 'received'],
            ],
            [
                'title' => 'تحديث حالة الشكوى',
                'body' => 'تم حل شكواك بنجاح. نشكرك على صبرك وتعاونك.',
                'type' => 'complaint',
                'data' => ['complaint_id' => '01JH2K3M4N5P6Q7R8S9T0V1W2X', 'status' => 'resolved'],
            ],
            // Bill notifications
            [
                'title' => 'فاتورة جديدة',
                'body' => 'تم إصدار فاتورة جديدة بقيمة 500 ريال. يرجى الدفع قبل تاريخ الاستحقاق.',
                'type' => 'bill',
                'data' => ['bill_id' => '01JH2K3M4N5P6Q7R8S9T0V1W2X', 'amount' => 500],
            ],
            [
                'title' => 'تذكير بالدفع',
                'body' => 'لديك فاتورة مستحقة. يرجى الدفع لتجنب الغرامات.',
                'type' => 'bill',
                'data' => ['bill_id' => '01JH2K3M4N5P6Q7R8S9T0V1W2X', 'amount' => 500, 'due_date' => '2026-01-15'],
            ],
            [
                'title' => 'تم استلام الدفع',
                'body' => 'تم استلام دفعتك بنجاح. شكراً لك!',
                'type' => 'bill',
                'data' => ['bill_id' => '01JH2K3M4N5P6Q7R8S9T0V1W2X', 'amount' => 500, 'paid' => true],
            ],
            // Poll notifications
            [
                'title' => 'استطلاع رأي جديد',
                'body' => 'شارك برأيك في استطلاع "تطوير الحدائق العامة". رأيك يهمنا!',
                'type' => 'poll',
                'data' => ['poll_id' => '01JH2K3M4N5P6Q7R8S9T0V1W2X', 'title' => 'تطوير الحدائق العامة'],
            ],
            [
                'title' => 'انتهى الاستطلاع',
                'body' => 'شكراً لمشاركتك في الاستطلاع. تم إغلاق التصويت وسيتم الإعلان عن النتائج قريباً.',
                'type' => 'poll',
                'data' => ['poll_id' => '01JH2K3M4N5P6Q7R8S9T0V1W2X', 'closed' => true],
            ],
            // Announcement notifications
            [
                'title' => 'إعلان هام',
                'body' => 'سيتم إغلاق مكتب البلدية يوم الجمعة القادم بسبب الصيانة الدورية.',
                'type' => 'general',
                'data' => ['announcement' => true, 'date' => '2026-01-10'],
            ],
            [
                'title' => 'خدمة جديدة متاحة',
                'body' => 'يمكنك الآن تجديد رخصة العمل إلكترونياً من خلال التطبيق.',
                'type' => 'general',
                'data' => ['new_service' => 'license_renewal'],
            ],
            [
                'title' => 'فعالية مجتمعية',
                'body' => 'ندعوك للمشاركة في فعالية تنظيف الحي يوم السبت القادم الساعة 8 صباحاً.',
                'type' => 'general',
                'data' => ['event' => 'community_cleanup', 'date' => '2026-01-11', 'time' => '08:00'],
            ],
        ];

        // Create notifications for citizens
        foreach ($citizens as $citizen) {
            // Each citizen gets 3-7 random notifications
            $notificationCount = rand(3, 7);
            $selectedTemplates = array_rand($notificationTemplates, $notificationCount);

            if (!is_array($selectedTemplates)) {
                $selectedTemplates = [$selectedTemplates];
            }

            foreach ($selectedTemplates as $index) {
                $template = $notificationTemplates[$index];
                $isRead = rand(0, 10) > 3; // 70% read
                $isSent = rand(0, 10) > 1; // 90% sent

                Notification::create([
                    'user_id' => $citizen->id,
                    'title' => $template['title'],
                    'body' => $template['body'],
                    'type' => $template['type'],
                    'data' => $template['data'],
                    'is_read' => $isRead,
                    'read_at' => $isRead ? now()->subDays(rand(0, 10)) : null,
                    'is_sent' => $isSent,
                    'sent_at' => $isSent ? now()->subDays(rand(0, 15)) : null,
                    'created_at' => now()->subDays(rand(0, 30)),
                ]);
            }
        }

        // Create notifications for admins
        $adminNotifications = [
            [
                'title' => 'طلب جديد يحتاج للمراجعة',
                'body' => 'تم تقديم طلب رخصة بناء جديد يحتاج إلى مراجعتك.',
                'type' => 'request',
                'data' => ['request_id' => '01JH2K3M4N5P6Q7R8S9T0V1W2X', 'action_required' => true],
            ],
            [
                'title' => 'شكوى جديدة',
                'body' => 'تم تقديم شكوى جديدة بخصوص نظافة الشوارع في حي الملز.',
                'type' => 'complaint',
                'data' => ['complaint_id' => '01JH2K3M4N5P6Q7R8S9T0V1W2X', 'priority' => 'high'],
            ],
            [
                'title' => 'تقرير يومي',
                'body' => 'تم معالجة 15 طلب اليوم. 10 موافقات، 5 قيد المراجعة.',
                'type' => 'general',
                'data' => ['report_type' => 'daily', 'approved' => 10, 'pending' => 5],
            ],
        ];

        foreach ($admins as $admin) {
            foreach ($adminNotifications as $template) {
                $isRead = rand(0, 10) > 5; // 50% read

                Notification::create([
                    'user_id' => $admin->id,
                    'title' => $template['title'],
                    'body' => $template['body'],
                    'type' => $template['type'],
                    'data' => $template['data'],
                    'is_read' => $isRead,
                    'read_at' => $isRead ? now()->subHours(rand(1, 48)) : null,
                    'is_sent' => true,
                    'sent_at' => now()->subHours(rand(1, 72)),
                    'created_at' => now()->subHours(rand(1, 72)),
                ]);
            }
        }
    }
}
