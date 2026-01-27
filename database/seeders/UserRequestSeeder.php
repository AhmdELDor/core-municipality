<?php

namespace Database\Seeders;

use App\Models\RequestForm;
use App\Models\User;
use App\Models\UserRequest;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserRequestSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get citizen users
        $citizens = User::where('role', 'citizen')->get();

        if ($citizens->isEmpty()) {
            $this->command->warn('No citizen users found. Please run UserSeeder first.');
            return;
        }

        // Get request forms
        $buildingPermitForm = RequestForm::where('title', 'طلب رخصة بناء')->first();
        $businessLicenseForm = RequestForm::where('title', 'طلب رخصة تجارية')->first();
        $waterConnectionForm = RequestForm::where('title', 'طلب توصيل ماء')->first();
        $electricityForm = RequestForm::where('title', 'طلب توصيل كهرباء')->first();
        $complaintForm = RequestForm::where('title', 'شكوى أو استفسار')->first();

        // User Request 1: Building Permit - Pending
        if ($buildingPermitForm) {
            UserRequest::create([
                'user_id' => $citizens->first()->id,
                'request_form_id' => $buildingPermitForm->id,
                'data' => [
                    'property_address' => 'بغداد، المنصور، شارع 14، دار 25',
                    'building_type' => 'سكني',
                    'plot_area' => 250,
                    'building_area' => 180,
                    'number_of_floors' => 2,
                    'additional_notes' => 'أحتاج معالجة سريعة للطلب'
                ],
                'attachments' => [
                    'https://picsum.photos/seed/deed1/800/600',
                    'https://picsum.photos/seed/plan1/800/600',
                    'https://picsum.photos/seed/id1/600/400',
                    'https://picsum.photos/seed/map1/800/600'
                ],
                'status' => 'pending',
                'admin_note' => null,
            ]);
        }

        // User Request 2: Business License - Approved
        if ($businessLicenseForm) {
            UserRequest::create([
                'user_id' => $citizens->random()->id,
                'request_form_id' => $businessLicenseForm->id,
                'data' => [
                    'business_name' => 'مطعم الزهور',
                    'business_type' => 'مطعم',
                    'business_address' => 'بغداد، الكرادة، شارع 52، محل رقم 10',
                    'number_of_employees' => 8,
                    'business_description' => 'مطعم يقدم المأكولات التقليدية والحديثة',
                    'previous_license' => 'لا'
                ],
                'attachments' => [
                    'https://picsum.photos/seed/register1/800/600',
                    'https://picsum.photos/seed/tax1/800/600',
                    'https://picsum.photos/seed/rent1/800/600',
                    'https://picsum.photos/seed/id2/600/400'
                ],
                'status' => 'approved',
                'admin_note' => 'تم الموافقة على طلبكم. يمكنكم استلام الرخصة من مكتب البلدية خلال 3 أيام عمل. يرجى إحضار الهوية الأصلية.',
            ]);
        }

        // User Request 3: Water Connection - Info Needed
        if ($waterConnectionForm) {
            UserRequest::create([
                'user_id' => $citizens->random()->id,
                'request_form_id' => $waterConnectionForm->id,
                'data' => [
                    'applicant_name' => 'علي حسن محمود',
                    'property_address' => 'بغداد، الجادرية، شارع 7، دار 15',
                    'property_type' => 'سكني',
                    'meter_location' => 'أمام المنزل على الجدار الخارجي',
                    'phone_number' => '07701234567'
                ],
                'attachments' => [
                    'https://picsum.photos/seed/deed2/800/600',
                    'https://picsum.photos/seed/map2/800/600',
                    'https://picsum.photos/seed/id3/600/400'
                ],
                'status' => 'info_needed',
                'admin_note' => 'يرجى تقديم صورة واضحة من سند الملكية تظهر رقم القطعة والمقاطعة. المستند الحالي غير واضح.',
            ]);
        }

        // User Request 4: Electricity Connection - Approved
        if ($electricityForm) {
            UserRequest::create([
                'user_id' => $citizens->random()->id,
                'request_form_id' => $electricityForm->id,
                'data' => [
                    'applicant_name' => 'سارة أحمد علي',
                    'property_address' => 'بغداد، الأعظمية، شارع 20، دار 30',
                    'connection_type' => 'منزلي - أحادي الطور',
                    'estimated_load' => 40,
                    'meter_location' => 'خارج المنزل على الجدار الأيمن'
                ],
                'attachments' => [
                    'https://picsum.photos/seed/deed3/800/600',
                    'https://picsum.photos/seed/electric1/800/600',
                    'https://picsum.photos/seed/id4/600/400'
                ],
                'status' => 'approved',
                'admin_note' => 'تم الموافقة. سيتم تركيب العداد خلال 5 أيام عمل. سيتصل بكم الفني قبل الزيارة.',
            ]);
        }

        // User Request 5: Complaint - Pending
        if ($complaintForm) {
            UserRequest::create([
                'user_id' => $citizens->random()->id,
                'request_form_id' => $complaintForm->id,
                'data' => [
                    'subject' => 'انقطاع المياه المتكرر',
                    'type' => 'شكوى',
                    'description' => 'منطقتنا تعاني من انقطاع متكرر للمياه لمدة تزيد عن 6 ساعات يومياً. نحتاج حلاً عاجلاً.',
                    'location' => 'حي الجهاد، المنطقة الثانية',
                    'contact_preference' => 'هاتف'
                ],
                'attachments' => [
                    'https://picsum.photos/seed/complaint1/800/600',
                    'https://picsum.photos/seed/complaint2/800/600'
                ],
                'status' => 'pending',
                'admin_note' => null,
            ]);
        }

        // User Request 6: Building Permit - Rejected
        if ($buildingPermitForm) {
            UserRequest::create([
                'user_id' => $citizens->random()->id,
                'request_form_id' => $buildingPermitForm->id,
                'data' => [
                    'property_address' => 'بغداد، الكاظمية، شارع 10، دار 8',
                    'building_type' => 'تجاري',
                    'plot_area' => 150,
                    'building_area' => 300,
                    'number_of_floors' => 4,
                    'additional_notes' => 'بناء محل تجاري'
                ],
                'attachments' => [
                    'https://picsum.photos/seed/deed4/800/600',
                    'https://picsum.photos/seed/plan2/800/600',
                    'https://picsum.photos/seed/id5/600/400'
                ],
                'status' => 'rejected',
                'admin_note' => 'تم رفض الطلب. مساحة البناء تتجاوز المساحة المسموح بها للقطعة. يرجى تعديل المخطط وإعادة التقديم.',
            ]);
        }

        // User Request 7: Water Connection - Pending
        if ($waterConnectionForm) {
            UserRequest::create([
                'user_id' => $citizens->random()->id,
                'request_form_id' => $waterConnectionForm->id,
                'data' => [
                    'applicant_name' => 'محمد خالد إبراهيم',
                    'property_address' => 'بغداد، الدورة، شارع 5، دار 40',
                    'property_type' => 'سكني',
                    'meter_location' => 'في الحديقة الأمامية',
                    'phone_number' => '07709876543'
                ],
                'attachments' => [
                    'https://picsum.photos/seed/deed5/800/600',
                    'https://picsum.photos/seed/map3/800/600',
                    'https://picsum.photos/seed/id6/600/400'
                ],
                'status' => 'pending',
                'admin_note' => null,
            ]);
        }

        // User Request 8: Business License - Info Needed
        if ($businessLicenseForm) {
            UserRequest::create([
                'user_id' => $citizens->random()->id,
                'request_form_id' => $businessLicenseForm->id,
                'data' => [
                    'business_name' => 'صيدلية النور',
                    'business_type' => 'صيدلية',
                    'business_address' => 'بغداد، البياع، شارع الرئيسي، محل 5',
                    'number_of_employees' => 3,
                    'business_description' => 'صيدلية لبيع الأدوية والمستلزمات الطبية',
                    'previous_license' => 'لا'
                ],
                'attachments' => [
                    'https://picsum.photos/seed/register2/800/600',
                    'https://picsum.photos/seed/tax2/800/600',
                    'https://picsum.photos/seed/id7/600/400'
                ],
                'status' => 'info_needed',
                'admin_note' => 'يرجى تقديم موافقة دائرة الصحة لمزاولة نشاط الصيدلية. هذا المستند إلزامي.',
            ]);
        }

        // User Request 9: Inquiry - Approved
        if ($complaintForm) {
            UserRequest::create([
                'user_id' => $citizens->random()->id,
                'request_form_id' => $complaintForm->id,
                'data' => [
                    'subject' => 'استفسار عن رسوم الخدمات',
                    'type' => 'استفسار',
                    'description' => 'أريد معرفة الرسوم المطلوبة لتجديد رخصة المحل التجاري',
                    'location' => '',
                    'contact_preference' => 'بريد إلكتروني'
                ],
                'attachments' => [],
                'status' => 'approved',
                'admin_note' => 'رسوم تجديد الرخصة التجارية هي 200 دينار سنوياً. يمكنكم زيارة مكتب الرخص من الساعة 8 صباحاً حتى 2 ظهراً.',
            ]);
        }

        // User Request 10: Electricity - Pending
        if ($electricityForm) {
            UserRequest::create([
                'user_id' => $citizens->random()->id,
                'request_form_id' => $electricityForm->id,
                'data' => [
                    'applicant_name' => 'فاطمة حسين علي',
                    'property_address' => 'بغداد، الشعب، شارع 15، دار 20',
                    'connection_type' => 'منزلي - ثلاثي الطور',
                    'estimated_load' => 60,
                    'meter_location' => 'على الجدار الخارجي الأمامي'
                ],
                'attachments' => [
                    'https://picsum.photos/seed/deed6/800/600',
                    'https://picsum.photos/seed/electric2/800/600',
                    'https://picsum.photos/seed/id8/600/400'
                ],
                'status' => 'pending',
                'admin_note' => null,
            ]);
        }

        $this->command->info('10 user requests have been created successfully with online image URLs.');
    }
}
