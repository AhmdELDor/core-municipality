<?php

namespace Database\Seeders;

use App\Models\Poll;
use App\Models\PollVote;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PollSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Poll 1: Municipal Budget Priorities
        $poll1 = Poll::create([
            'title' => 'أولويات ميزانية البلدية 2026',
            'description' => 'ساعدنا في تحديد أولويات الإنفاق في ميزانية البلدية للعام القادم',
            'options' => [
                'التعليم' => 0,
                'الصحة' => 0,
                'البنية التحتية' => 0,
                'البيئة' => 0,
            ],
            'start_at' => now()->subDays(5),
            'end_at' => now()->addDays(25),
            'status' => 'in_progress',
            'votes_count' => 0,
        ]);

        // Poll 2: Park Renovation
        $poll2 = Poll::create([
            'title' => 'أي حديقة يجب تجديدها أولاً؟',
            'description' => 'لدينا ميزانية لتجديد حديقة واحدة هذا العام. صوت لاختيارك',
            'options' => [
                'الحديقة المركزية' => 0,
                'حديقة الشرق' => 0,
                'حديقة الغرب' => 0,
                'حديقة الشمال' => 0,
            ],
            'start_at' => now()->subDays(2),
            'end_at' => now()->addDays(28),
            'status' => 'in_progress',
            'votes_count' => 0,
        ]);

        // Poll 3: Community Center Activities
        $poll3 = Poll::create([
            'title' => 'أنشطة المركز المجتمعي المفضلة',
            'description' => 'ما هي الأنشطة التي تود رؤيتها في المركز المجتمعي الجديد؟',
            'options' => [
                'دورات تدريبية' => 0,
                'أنشطة رياضية' => 0,
                'فعاليات ثقافية' => 0,
                'ورش عمل فنية' => 0,
            ],
            'start_at' => now()->addDays(3),
            'end_at' => now()->addDays(33),
            'status' => 'pending',
            'votes_count' => 0,
        ]);

        // Poll 4: Public Transportation
        $poll4 = Poll::create([
            'title' => 'أفضل وقت لزيادة رحلات الحافلات',
            'description' => 'متى تحتاج رحلات حافلات إضافية أكثر؟',
            'options' => [
                'الصباح الباكر (6-9 صباحاً)' => 0,
                'وقت الظهيرة (12-2 ظهراً)' => 0,
                'بعد الظهر (3-6 مساءً)' => 0,
                'المساء (6-9 مساءً)' => 0,
            ],
            'start_at' => now()->subDays(10),
            'end_at' => now()->addDays(20),
            'status' => 'in_progress',
            'votes_count' => 0,
        ]);

        // Poll 5: Recycling Program
        $poll5 = Poll::create([
            'title' => 'هل تؤيد برنامج إعادة التدوير؟',
            'description' => 'نخطط لإطلاق برنامج إعادة تدوير شامل. هل تدعم هذه المبادرة؟',
            'options' => [
                'نعم، أؤيد بشدة' => 0,
                'نعم، لكن بشروط' => 0,
                'غير متأكد' => 0,
                'لا أؤيد' => 0,
            ],
            'start_at' => now()->subDays(15),
            'end_at' => now()->subDays(1),
            'status' => 'ended',
            'votes_count' => 0,
        ]);

        // Poll 6: New Shopping Area Location
        $poll6 = Poll::create([
            'title' => 'أين يجب إنشاء المنطقة التجارية الجديدة؟',
            'description' => 'اختر الموقع الأنسب للمنطقة التجارية الجديدة',
            'options' => [
                'المنطقة الشمالية' => 0,
                'المنطقة الجنوبية' => 0,
                'المنطقة الشرقية' => 0,
                'وسط المدينة' => 0,
            ],
            'start_at' => now()->subDays(1),
            'end_at' => now()->addDays(29),
            'status' => 'in_progress',
            'votes_count' => 0,
        ]);

        // Poll 7: WiFi in Public Places
        $poll7 = Poll::create([
            'title' => 'أي الأماكن العامة تحتاج WiFi مجاني أكثر؟',
            'description' => 'ساعدنا في تحديد أولويات تركيب شبكة WiFi المجانية',
            'options' => [
                'الحدائق العامة' => 0,
                'المراكز التجارية' => 0,
                'محطات الحافلات' => 0,
                'المكتبات العامة' => 0,
            ],
            'start_at' => now()->addDays(1),
            'end_at' => now()->addDays(31),
            'status' => 'pending',
            'votes_count' => 0,
        ]);

        // Poll 8: Youth Employment Programs
        $poll8 = Poll::create([
            'title' => 'برامج توظيف الشباب المقترحة',
            'description' => 'ما نوع برامج التوظيف التي تفضلها للشباب؟',
            'options' => [
                'تدريب مهني تقني' => 0,
                'برامج ريادة الأعمال' => 0,
                'وظائف حكومية' => 0,
                'تدريب في القطاع الخاص' => 0,
            ],
            'start_at' => now()->subDays(20),
            'end_at' => now()->subDays(5),
            'status' => 'ended',
            'votes_count' => 0,
        ]);

        // Poll 9: Evening Events
        $poll9 = Poll::create([
            'title' => 'أفضل يوم للفعاليات المسائية',
            'description' => 'متى تفضل حضور الفعاليات المسائية في البلدية؟',
            'options' => [
                'الأربعاء' => 0,
                'الخميس' => 0,
                'الجمعة' => 0,
                'السبت' => 0,
            ],
            'start_at' => now()->subDays(3),
            'end_at' => now()->addDays(27),
            'status' => 'in_progress',
            'votes_count' => 0,
        ]);

        // Poll 10: Street Lighting Energy Source
        $poll10 = Poll::create([
            'title' => 'نوع إنارة الشوارع المستقبلية',
            'description' => 'ما نوع الطاقة الذي تفضله لإنارة الشوارع الجديدة؟',
            'options' => [
                'طاقة شمسية' => 0,
                'LED موفرة للطاقة' => 0,
                'إنارة تقليدية محسّنة' => 0,
                'طاقة هجينة' => 0,
            ],
            'start_at' => now()->addDays(2),
            'end_at' => now()->addDays(32),
            'status' => 'pending',
            'votes_count' => 0,
        ]);

        // Get some citizen users for voting
        $citizens = User::where('role', 'citizen')->get();

        if ($citizens->count() > 0) {
            // Add votes to active and finished polls
            $this->addVotesToPoll($poll1, $citizens->random(min(25, $citizens->count())), ['التعليم', 'الصحة', 'البنية التحتية', 'البيئة']);
            $this->addVotesToPoll($poll2, $citizens->random(min(18, $citizens->count())), ['الحديقة المركزية', 'حديقة الشرق', 'حديقة الغرب', 'حديقة الشمال']);
            $this->addVotesToPoll($poll4, $citizens->random(min(30, $citizens->count())), ['الصباح الباكر (6-9 صباحاً)', 'وقت الظهيرة (12-2 ظهراً)', 'بعد الظهر (3-6 مساءً)', 'المساء (6-9 مساءً)']);
            $this->addVotesToPoll($poll5, $citizens->random(min(45, $citizens->count())), ['نعم، أؤيد بشدة', 'نعم، لكن بشروط', 'غير متأكد', 'لا أؤيد']);
            $this->addVotesToPoll($poll6, $citizens->random(min(12, $citizens->count())), ['المنطقة الشمالية', 'المنطقة الجنوبية', 'المنطقة الشرقية', 'وسط المدينة']);
            $this->addVotesToPoll($poll8, $citizens->random(min(38, $citizens->count())), ['تدريب مهني تقني', 'برامج ريادة الأعمال', 'وظائف حكومية', 'تدريب في القطاع الخاص']);
            $this->addVotesToPoll($poll9, $citizens->random(min(20, $citizens->count())), ['الأربعاء', 'الخميس', 'الجمعة', 'السبت']);
        }

        $this->command->info('10 polls with votes have been created successfully!');
    }

    /**
     * Add random votes to a poll
     */
    private function addVotesToPoll(Poll $poll, $users, array $availableOptions)
    {
        $votes = 0;
        $options = $poll->options;

        foreach ($users as $user) {
            // Randomly select an option
            $selectedOption = $availableOptions[array_rand($availableOptions)];

            // Create the vote
            PollVote::create([
                'poll_id' => $poll->id,
                'user_id' => $user->id,
                'option' => $selectedOption,
            ]);

            // Increment the vote count for this option
            $options[$selectedOption]++;
            $votes++;
        }

        // Update the poll with new vote counts
        $poll->update([
            'options' => $options,
            'votes_count' => $votes,
        ]);
    }
}
