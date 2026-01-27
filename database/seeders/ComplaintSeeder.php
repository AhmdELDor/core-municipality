<?php

namespace Database\Seeders;

use App\Models\Complaint;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ComplaintSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get citizen users to associate complaints with
        $citizens = User::where('role', 'citizen')->get();

        if ($citizens->isEmpty()) {
            $this->command->warn('No citizen users found. Please run UserSeeder first.');
            return;
        }

        // Complaint 1: Water supply issue
        Complaint::create([
            'title' => 'انقطاع المياه في الحي',
            'desc' => 'لم يتوفر الماء في منطقتنا منذ ثلاثة أيام. نحن بحاجة ماسة إلى حل عاجل لهذه المشكلة حيث أن السكان يعانون من نقص المياه للاستخدام اليومي.',
            'user_id' => $citizens->random()->id,
            'status' => 'received',
            'images_url' => [
                'https://images.unsplash.com/photo-1548839140-29a749e1cf4d?w=800',
                'https://images.unsplash.com/photo-1581092160562-40aa08e78837?w=800',
            ],
            'result' => null,
        ]);

        // Complaint 2: Street lighting
        Complaint::create([
            'title' => 'إنارة الشوارع معطلة',
            'desc' => 'أعمدة الإنارة في شارع الرئيسي لا تعمل منذ أسبوع. هذا يسبب مشاكل أمنية خاصة في الليل ويعرض المواطنين للخطر.',
            'user_id' => $citizens->random()->id,
            'status' => 'received',
            'images_url' => [
                'https://images.unsplash.com/photo-1509391366360-2e959784a276?w=800',
            ],
            'result' => 'تم إرسال فريق الصيانة للكشف على الأعطال',
        ]);

        // Complaint 3: Garbage collection
        Complaint::create([
            'title' => 'تأخر في جمع النفايات',
            'desc' => 'لم يتم جمع النفايات من الحي منذ عشرة أيام. تتراكم القمامة في الشوارع مما يسبب روائح كريهة ومشاكل صحية للسكان.',
            'user_id' => $citizens->random()->id,
            'status' => 'received',
            'images_url' => [
                'https://images.unsplash.com/photo-1532996122724-e3c354a0b15b?w=800',
                'https://images.unsplash.com/photo-1611284446314-60a58ac0deb9?w=800',
            ],
            'result' => null,
        ]);

        // Complaint 4: Road damage
        Complaint::create([
            'title' => 'حفر كبيرة في الطريق',
            'desc' => 'توجد حفر كبيرة وخطيرة في طريق الملك عبدالعزيز مما يسبب حوادث للسيارات ويعرض السائقين للخطر. نطلب إصلاحها بشكل عاجل.',
            'user_id' => $citizens->random()->id,
            'status' => 'received',
            'images_url' => [
                'https://images.unsplash.com/photo-1581094794329-c8112a89af12?w=800',
                'https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=800',
            ],
            'result' => 'تم إصلاح الطريق بالكامل وتعبيد الحفر',
        ]);

        // Complaint 5: Public park maintenance
        Complaint::create([
            'title' => 'حديقة عامة بحاجة للصيانة',
            'desc' => 'الحديقة العامة في حي الورود أصبحت مهملة جداً. الألعاب مكسورة، العشب جاف، والمقاعد متضررة. نطلب صيانتها لتعود آمنة للأطفال.',
            'user_id' => $citizens->random()->id,
            'status' => 'received',
            'images_url' => [
                'https://images.unsplash.com/photo-1519331379826-f10be5486c6f?w=800',
            ],
            'result' => 'جاري العمل على صيانة الحديقة وتجديد الألعاب',
        ]);

        // Complaint 6: Noise pollution
        Complaint::create([
            'title' => 'ضوضاء مزعجة من ورشة البناء',
            'desc' => 'يوجد ورشة بناء تعمل حتى ساعات متأخرة من الليل مما يسبب إزعاج شديد للسكان. نطلب تنظيم ساعات العمل.',
            'user_id' => $citizens->random()->id,
            'status' => 'received',
            'images_url' => [
                'https://images.unsplash.com/photo-1541888946425-d81bb19240f5?w=800',
            ],
            'result' => null,
        ]);

        // Complaint 7: Drainage system
        Complaint::create([
            'title' => 'مشكلة في نظام الصرف الصحي',
            'desc' => 'تتجمع المياه في الشارع بعد كل مطر بسبب انسداد في نظام الصرف. هذا يسبب مشاكل صحية ويعيق حركة المرور.',
            'user_id' => $citizens->random()->id,
            'status' => 'received',
            'images_url' => [
                'https://images.unsplash.com/photo-1581092580497-e0d23cbdf1dc?w=800',
            ],
            'result' => 'تم تنظيف نظام الصرف وحل المشكلة بالكامل',
        ]);

        // Complaint 8: Traffic congestion
        Complaint::create([
            'title' => 'ازدحام مروري شديد في التقاطع',
            'desc' => 'التقاطع عند شارع الملك فهد يشهد ازدحام مروري خانق خاصة في ساعات الذروة. نقترح تركيب إشارات مرورية أو عمل دوار.',
            'user_id' => $citizens->random()->id,
            'status' => 'received',
            'images_url' => [
                'https://images.unsplash.com/photo-1590674899484-d5640e854abe?w=800',
            ],
            'result' => null,
        ]);

        // Complaint 9: Stray animals
        Complaint::create([
            'title' => 'كلاب ضالة في الحي',
            'desc' => 'تجول مجموعة من الكلاب الضالة في الحي وتشكل خطر على الأطفال وكبار السن. نطلب التدخل العاجل من البلدية.',
            'user_id' => $citizens->random()->id,
            'status' => 'received',
            'images_url' => [
                'https://images.unsplash.com/photo-1558788353-f76d92427f16?w=800',
            ],
            'result' => 'تم التواصل مع جمعية الرفق بالحيوان',
        ]);

        // Complaint 10: Bus stop condition
        Complaint::create([
            'title' => 'محطة الحافلة في حالة سيئة',
            'desc' => 'محطة الحافلة أمام المدرسة ليس بها مقاعد ولا مظلة. المواطنون يقفون تحت الشمس في انتظار الحافلة.',
            'user_id' => $citizens->random()->id,
            'status' => 'received',
            'images_url' => [
                'https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?w=800',
            ],
            'result' => null,
        ]);

        // Complaint 11: Illegal parking
        Complaint::create([
            'title' => 'سيارات متوقفة بشكل عشوائي',
            'desc' => 'بعض السيارات تتوقف بشكل عشوائي على الرصيف مما يجبر المشاة على النزول للشارع ويعرضهم للخطر.',
            'user_id' => $citizens->random()->id,
            'status' => 'received',
            'images_url' => [
                'https://images.unsplash.com/photo-1449965408869-eaa3f722e40d?w=800',
            ],
            'result' => 'تم تنظيم حملة ضبط المخالفات ووضع علامات للوقوف',
        ]);

        // Complaint 12: Air pollution
        Complaint::create([
            'title' => 'تلوث الهواء من المصنع القريب',
            'desc' => 'يصدر المصنع القريب من الحي دخان كثيف يسبب مشاكل في التنفس للسكان خاصة الأطفال وكبار السن.',
            'user_id' => $citizens->random()->id,
            'status' => 'received',
            'images_url' => [
                'https://images.unsplash.com/photo-1611273426858-450d8e3c9fce?w=800',
                'https://images.unsplash.com/photo-1547683905-f686c993aae5?w=800',
            ],
            'result' => 'تم إرسال فريق من البيئة للتفتيش على المصنع',
        ]);

        $this->command->info('12 complaints with images have been created successfully!');
    }
}
