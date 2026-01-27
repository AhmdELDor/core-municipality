<?php

namespace Database\Seeders;

use App\Models\Circular;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class CircularSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get admin users to associate circulars with
        $admins = User::where('role', 'superadmin')->get();

        if ($admins->isEmpty()) {
            $this->command->warn('No admin users found. Please run UserSeeder first.');
            return;
        }

        // Circular 1: Water Service Interruption
        Circular::create([
            'title' => 'إشعار بقطع المياه المؤقت',
            'content' => 'تعلن البلدية عن قطع مؤقت لخدمة المياه يوم الخميس القادم من الساعة 8 صباحاً حتى 4 عصراً في الأحياء الشمالية بسبب أعمال صيانة للشبكة. نعتذر عن أي إزعاج قد يسببه ذلك.',
            'image_url' => 'https://images.unsplash.com/photo-1548839140-29a749e1cf4d?w=800',
            'created_by' => $admins->first()->id,
            'published_at' => Carbon::now()->subDays(2),
            'is_published' => true,
        ]);

        // Circular 2: Tax Payment Reminder
        Circular::create([
            'title' => 'تذكير بموعد دفع الضرائب البلدية',
            'content' => 'يرجى من جميع المواطنين دفع الضرائب البلدية السنوية قبل نهاية الشهر الجاري لتجنب الغرامات. يمكن الدفع إلكترونياً عبر التطبيق أو زيارة مكتب البلدية.',
            'image_url' => 'https://images.unsplash.com/photo-1554224311-beee4ece3c5d?w=800',
            'created_by' => $admins->random()->id,
            'published_at' => Carbon::now()->subDays(5),
            'is_published' => true,
        ]);

        // Circular 3: Cultural Event
        Circular::create([
            'title' => 'دعوة لحضور المهرجان الثقافي السنوي',
            'content' => 'تدعوكم البلدية لحضور المهرجان الثقافي السنوي الذي سيقام يوم السبت في الساحة العامة. البرنامج يشمل عروض فنية، معارض تراثية، وأنشطة للأطفال. الدخول مجاني للجميع.',
            'image_url' => 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?w=800',
            'created_by' => $admins->random()->id,
            'published_at' => Carbon::now()->subDays(7),
            'is_published' => true,
        ]);

        // Circular 4: Vaccination Campaign
        Circular::create([
            'title' => 'حملة تطعيم مجانية للأطفال',
            'content' => 'تنظم البلدية بالتعاون مع وزارة الصحة حملة تطعيم مجانية للأطفال دون سن 5 سنوات. الحملة ستبدأ الأسبوع المقبل في المركز الصحي البلدي من الساعة 9 صباحاً حتى 2 ظهراً.',
            'image_url' => 'https://images.unsplash.com/photo-1584515933487-779824d29309?w=800',
            'created_by' => $admins->random()->id,
            'published_at' => Carbon::now()->subDays(3),
            'is_published' => true,
        ]);

        // Circular 5: New Parking Regulations
        Circular::create([
            'title' => 'قوانين جديدة لتنظيم المواقف',
            'content' => 'تعلن البلدية عن تطبيق قوانين جديدة لتنظيم مواقف السيارات في الشوارع الرئيسية اعتباراً من الشهر المقبل. يرجى الالتزام بالمناطق المخصصة للوقوف وعدم إعاقة حركة المرور.',
            'image_url' => 'https://images.unsplash.com/photo-1590674899484-d5640e854abe?w=800',
            'created_by' => $admins->random()->id,
            'published_at' => Carbon::now()->subDays(10),
            'is_published' => true,
        ]);

        // Circular 6: Environmental Awareness
        Circular::create([
            'title' => 'حملة توعية بيئية - معاً للحفاظ على البيئة',
            'content' => 'تطلق البلدية حملة توعية بيئية تهدف لنشر ثقافة إعادة التدوير والحفاظ على نظافة المدينة. سيتم توزيع حاويات تدوير مجانية على الأحياء وتنظيم ورش عمل توعوية.',
            'image_url' => 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?w=800',
            'created_by' => $admins->random()->id,
            'published_at' => Carbon::now()->subDays(1),
            'is_published' => true,
        ]);

        // Circular 7: Road Closure
        Circular::create([
            'title' => 'إغلاق مؤقت للشارع الرئيسي',
            'content' => 'سيتم إغلاق الشارع الرئيسي أمام المحكمة لمدة أسبوعين بسبب أعمال صيانة الأسفلت. يرجى استخدام الطرق البديلة الموضحة على الخريطة المرفقة.',
            'image_url' => 'https://images.unsplash.com/photo-1581092160562-40aa08e78837?w=800',
            'created_by' => $admins->random()->id,
            'published_at' => Carbon::now()->subDays(4),
            'is_published' => true,
        ]);

        // Circular 8: Summer Activities
        Circular::create([
            'title' => 'برنامج الأنشطة الصيفية للشباب',
            'content' => 'تعلن البلدية عن فتح باب التسجيل للبرنامج الصيفي للشباب الذي يتضمن دورات رياضية، ورش فنية، ورحلات ترفيهية. التسجيل مجاني والأماكن محدودة.',
            'image_url' => 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=800',
            'created_by' => $admins->random()->id,
            'published_at' => Carbon::now()->subDays(12),
            'is_published' => true,
        ]);

        // Circular 9: Public Meeting
        Circular::create([
            'title' => 'اجتماع عام لمناقشة مشاريع التطوير',
            'content' => 'تدعو البلدية جميع المواطنين لحضور اجتماع عام يوم الثلاثاء القادم لمناقشة المشاريع التنموية المستقبلية والاستماع لآرائكم ومقترحاتكم. الاجتماع في قاعة البلدية الساعة 6 مساءً.',
            'image_url' => 'https://images.unsplash.com/photo-1505373877841-8d25f7d46678?w=800',
            'created_by' => $admins->random()->id,
            'published_at' => Carbon::now()->subDays(6),
            'is_published' => true,
        ]);

        // Circular 10: Digital Services Launch
        Circular::create([
            'title' => 'إطلاق خدمات إلكترونية جديدة',
            'content' => 'يسر البلدية الإعلان عن إطلاق منصة إلكترونية جديدة لتقديم جميع الخدمات البلدية عبر الإنترنت. الآن يمكنكم دفع الفواتير، تقديم الشكاوى، ومتابعة المعاملات من منازلكم.',
            'image_url' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=800',
            'created_by' => $admins->random()->id,
            'published_at' => Carbon::now()->subHours(6),
            'is_published' => true,
        ]);

        // Circular 11: Draft Circular (unpublished)
        Circular::create([
            'title' => 'مسودة - إعلان عن مناقصة عامة',
            'content' => 'تعلن البلدية عن مناقصة عامة لتنفيذ مشروع تطوير البنية التحتية. تفاصيل المناقصة وشروط التقديم ستنشر قريباً على الموقع الإلكتروني.',
            'image_url' => 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?w=800',
            'created_by' => $admins->random()->id,
            'published_at' => null,
            'is_published' => false,
        ]);

        // Circular 12: Emergency Contact Update
        Circular::create([
            'title' => 'تحديث أرقام الطوارئ البلدية',
            'content' => 'تم تحديث أرقام الطوارئ والتواصل مع البلدية. للشكاوى العاجلة: 1555، للاستفسارات: 1800-777، لخدمة العملاء: 1900-888. جميع الأرقام تعمل على مدار 24 ساعة.',
            'image_url' => 'https://images.unsplash.com/photo-1516387938699-a93567ec168e?w=800',
            'created_by' => $admins->random()->id,
            'published_at' => Carbon::now()->subDays(8),
            'is_published' => true,
        ]);

        $this->command->info('12 circulars have been created successfully.');
    }
}
