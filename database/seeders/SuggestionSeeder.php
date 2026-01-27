<?php

namespace Database\Seeders;

use App\Models\Suggestion;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SuggestionSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get citizen users to associate suggestions with
        $citizens = User::where('role', 'citizen')->get();

        if ($citizens->isEmpty()) {
            $this->command->warn('No citizen users found. Please run UserSeeder first.');
            return;
        }

        // Suggestion 1: Park development
        Suggestion::create([
            'citizen_id' => $citizens->first()->id,
            'desc' => 'اقترح إنشاء حديقة عامة في الحي مع ملاعب للأطفال ومسارات للمشي. هذا سيوفر مساحة ترفيهية للعائلات ويحسن جودة الحياة في المنطقة.',
        ]);

        // Suggestion 2: Public transportation
        Suggestion::create([
            'citizen_id' => $citizens->random()->id,
            'desc' => 'نحتاج إلى تحسين خدمات النقل العام من خلال زيادة عدد الحافلات وتوسيع شبكة الطرق لتشمل المناطق النائية.',
        ]);

        // Suggestion 3: Street cleaning
        Suggestion::create([
            'citizen_id' => $citizens->random()->id,
            'desc' => 'اقتراح زيادة عدد مرات تنظيف الشوارع إلى مرتين في الأسبوع بدلاً من مرة واحدة للحفاظ على نظافة المدينة.',
        ]);

        // Suggestion 4: Youth center
        Suggestion::create([
            'citizen_id' => $citizens->random()->id,
            'desc' => 'إنشاء مركز شبابي يوفر أنشطة رياضية وثقافية للشباب، بما في ذلك مكتبة عامة وصالة رياضية.',
        ]);

        // Suggestion 5: Digital services
        Suggestion::create([
            'citizen_id' => $citizens->random()->id,
            'desc' => 'تطوير خدمات إلكترونية أكثر لتسهيل التواصل بين المواطنين والبلدية وتقديم الطلبات عبر الإنترنت بدلاً من الزيارات الشخصية.',
        ]);

        // Suggestion 6: Recycling program
        Suggestion::create([
            'citizen_id' => $citizens->random()->id,
            'desc' => 'إطلاق برنامج إعادة تدوير في البلدية مع توفير حاويات مخصصة للبلاستيك والورق والزجاج في جميع الأحياء.',
        ]);

        // Suggestion 7: Traffic lights
        Suggestion::create([
            'citizen_id' => $citizens->random()->id,
            'desc' => 'تركيب إشارات مرورية ذكية في التقاطعات الرئيسية للحد من الازدحام المروري وتحسين السلامة على الطرق.',
        ]);

        // Suggestion 8: Community events
        Suggestion::create([
            'citizen_id' => $citizens->random()->id,
            'desc' => 'تنظيم فعاليات مجتمعية شهرية مثل أسواق المزارعين والمهرجانات الثقافية لتعزيز التواصل بين السكان.',
        ]);

        // Suggestion 9: Solar energy
        Suggestion::create([
            'citizen_id' => $citizens->random()->id,
            'desc' => 'الاستثمار في الطاقة الشمسية للمباني العامة لتقليل تكاليف الكهرباء والمساهمة في حماية البيئة.',
        ]);

        // Suggestion 10: Public WiFi
        Suggestion::create([
            'citizen_id' => $citizens->random()->id,
            'desc' => 'توفير خدمة إنترنت مجاني في الأماكن العامة مثل الحدائق والساحات لتسهيل الوصول إلى المعلومات للمواطنين.',
        ]);

        $this->command->info('10 suggestions have been created successfully.');
    }
}
