<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class ProjectSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Project 1: Road Construction
        Project::create([
            'title' => 'إنشاء طريق رئيسي جديد',
            'description' => 'مشروع إنشاء طريق رئيسي بطول 5 كيلومترات يربط بين الأحياء الشمالية والجنوبية. المشروع يشمل إنارة كاملة، أرصفة للمشاة، ومسارات للدراجات.',
            'status' => 'inprogress',
            'category' => 'Infrastructure',
            'location' => 'الأحياء الشمالية',
            'image_urls' => [
                'https://images.unsplash.com/photo-1581094794329-c8112a89af12?w=800',
                'https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=800',
            ],
            'start_date' => Carbon::now()->subMonths(3),
            'end_date' => Carbon::now()->addMonths(6),
        ]);

        // Project 2: Public Park
        Project::create([
            'title' => 'حديقة عامة متعددة الاستخدامات',
            'description' => 'إنشاء حديقة عامة على مساحة 10 آلاف متر مربع تشمل ملاعب للأطفال، مسارات للجري، مناطق جلوس، ونافورة مياه.',
            'status' => 'pending',
            'category' => 'Recreation',
            'location' => 'حي الزهور',
            'image_urls' => [
                'https://images.unsplash.com/photo-1587974928442-77dc3e0dba72?w=800',
                'https://images.unsplash.com/photo-1519331379826-f10be5486c6f?w=800',
                'https://images.unsplash.com/photo-1585320806297-9794b3e4eeae?w=800',
            ],
            'start_date' => Carbon::now()->addMonths(2),
            'end_date' => Carbon::now()->addMonths(10),
        ]);

        // Project 3: Community Center
        Project::create([
            'title' => 'مركز مجتمعي متكامل',
            'description' => 'بناء مركز مجتمعي يحتوي على قاعات اجتماعات، مكتبة عامة، صالة رياضية، وقاعة متعددة الأغراض للفعاليات المجتمعية.',
            'status' => 'finished',
            'category' => 'Community',
            'location' => 'المنطقة الوسطى',
            'image_urls' => [
                'https://images.unsplash.com/photo-1497366216548-37526070297c?w=800',
                'https://images.unsplash.com/photo-1497366754035-f200968a6e72?w=800',
            ],
            'start_date' => Carbon::now()->subMonths(12),
            'end_date' => Carbon::now()->subMonths(1),
        ]);

        // Project 4: Water Network
        Project::create([
            'title' => 'تطوير شبكة المياه',
            'description' => 'مشروع تحديث وتوسيع شبكة المياه لتشمل المناطق الجديدة وتحسين ضغط المياه في الأحياء القديمة. يشمل المشروع تركيب أنابيب جديدة وصيانة المحطات.',
            'status' => 'inprogress',
            'category' => 'Infrastructure',
            'location' => 'جميع الأحياء',
            'image_urls' => [
                'https://images.unsplash.com/photo-1581092160562-40aa08e78837?w=800',
                'https://images.unsplash.com/photo-1581092580497-e0d23cbdf1dc?w=800',
            ],
            'start_date' => Carbon::now()->subMonths(6),
            'end_date' => Carbon::now()->addMonths(8),
        ]);

        // Project 5: Solar Street Lights
        Project::create([
            'title' => 'إنارة الشوارع بالطاقة الشمسية',
            'description' => 'استبدال إنارة الشوارع التقليدية بأنظمة LED تعمل بالطاقة الشمسية لتقليل استهلاك الكهرباء وتكاليف الصيانة. المشروع يغطي 200 عمود إنارة.',
            'status' => 'pending',
            'category' => 'Energy',
            'location' => 'الشوارع الرئيسية',
            'image_urls' => [
                'https://images.unsplash.com/photo-1509391111737-a9c80078f87e?w=800',
                'https://images.unsplash.com/photo-1473341304170-971dccb5ac1e?w=800',
            ],
            'start_date' => Carbon::now()->addMonths(1),
            'end_date' => Carbon::now()->addMonths(5),
        ]);

        // Project 6: Public Transportation
        Project::create([
            'title' => 'توسيع خطوط النقل العام',
            'description' => 'إضافة 3 خطوط حافلات جديدة لتحسين التنقل داخل المدينة والربط مع المناطق النائية. المشروع يشمل إنشاء محطات انتظار حديثة.',
            'status' => 'inprogress',
            'category' => 'Transportation',
            'location' => 'شبكة المدينة',
            'image_urls' => [
                'https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?w=800',
                'https://images.unsplash.com/photo-1570125909232-eb263c188f7e?w=800',
                'https://images.unsplash.com/photo-1520333789090-1afc82db536a?w=800',
            ],
            'start_date' => Carbon::now()->subMonths(2),
            'end_date' => Carbon::now()->addMonths(4),
        ]);

        // Project 7: Recycling Center
        Project::create([
            'title' => 'مركز إعادة التدوير',
            'description' => 'إنشاء مركز حديث لإعادة التدوير يستقبل النفايات القابلة للتدوير من المواطنين ويعالجها. المركز سيوفر أيضاً برامج توعوية بيئية.',
            'status' => 'pending',
            'category' => 'Environment',
            'location' => 'المنطقة الصناعية',
            'image_urls' => [
                'https://images.unsplash.com/photo-1532996122724-e3c354a0b15b?w=800',
                'https://images.unsplash.com/photo-1611284446314-60a58ac0deb9?w=800',
            ],
            'start_date' => Carbon::now()->addMonths(3),
            'end_date' => Carbon::now()->addMonths(12),
        ]);

        // Project 8: Digital Infrastructure
        Project::create([
            'title' => 'بنية تحتية رقمية متطورة',
            'description' => 'تركيب شبكة ألياف بصرية في جميع المباني الحكومية وتوفير WiFi مجاني في الأماكن العامة مع نظام مراقبة ذكي.',
            'status' => 'finished',
            'category' => 'Technology',
            'location' => 'المباني الحكومية والساحات العامة',
            'image_urls' => [
                'https://images.unsplash.com/photo-1451187580459-43490279c0fa?w=800',
                'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?w=800',
            ],
            'start_date' => Carbon::now()->subMonths(8),
            'end_date' => Carbon::now()->subWeeks(2),
        ]);

        // Project 9: Sports Complex
        Project::create([
            'title' => 'مجمع رياضي متكامل',
            'description' => 'بناء مجمع رياضي يضم ملاعب كرة قدم، كرة سلة، مسبح أولمبي، وصالة ألعاب رياضية مغطاة. المشروع يهدف لتعزيز الأنشطة الرياضية.',
            'status' => 'inprogress',
            'category' => 'Recreation',
            'location' => 'الحي الرياضي',
            'image_urls' => [
                'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?w=800',
                'https://images.unsplash.com/photo-1577223625816-7546f8589883?w=800',
                'https://images.unsplash.com/photo-1517649763962-0c623066013b?w=800',
            ],
            'start_date' => Carbon::now()->subMonths(4),
            'end_date' => Carbon::now()->addMonths(9),
        ]);

        // Project 10: Emergency Services Center
        Project::create([
            'title' => 'مركز خدمات الطوارئ',
            'description' => 'إنشاء مركز متطور لخدمات الطوارئ يشمل غرف عمليات حديثة، معدات إنقاذ متقدمة، وفريق تدريب متخصص للاستجابة السريعة.',
            'status' => 'onhold',
            'category' => 'Safety',
            'location' => 'الموقع المركزي',
            'image_urls' => [
                'https://images.unsplash.com/photo-1516549655169-df83a0774514?w=800',
                'https://images.unsplash.com/photo-1587351021759-3e566b6af7cc?w=800',
            ],
            'start_date' => Carbon::now()->addMonths(4),
            'end_date' => Carbon::now()->addMonths(16),
        ]);

        $this->command->info('10 projects have been created successfully.');
    }
}
