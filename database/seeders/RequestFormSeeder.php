<?php

namespace Database\Seeders;

use App\Models\RequestForm;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RequestFormSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Request Form 1: Building Permit Application
        RequestForm::create([
            'title' => 'طلب رخصة بناء',
            'description' => 'تقديم طلب للحصول على رخصة بناء للعقار',
            'fields' => [
                [
                    'name' => 'property_address',
                    'type' => 'textarea',
                    'label' => 'عنوان العقار',
                    'required' => true,
                    'placeholder' => 'أدخل العنوان الكامل للعقار'
                ],
                [
                    'name' => 'building_type',
                    'type' => 'select',
                    'label' => 'نوع المبنى',
                    'required' => true,
                    'options' => ['سكني', 'تجاري', 'صناعي', 'مختلط']
                ],
                [
                    'name' => 'plot_area',
                    'type' => 'number',
                    'label' => 'مساحة القطعة (متر مربع)',
                    'required' => true,
                    'placeholder' => '250'
                ],
                [
                    'name' => 'building_area',
                    'type' => 'number',
                    'label' => 'مساحة البناء (متر مربع)',
                    'required' => true,
                    'placeholder' => '180'
                ],
                [
                    'name' => 'number_of_floors',
                    'type' => 'number',
                    'label' => 'عدد الطوابق',
                    'required' => true,
                    'placeholder' => '2'
                ],
                [
                    'name' => 'additional_notes',
                    'type' => 'textarea',
                    'label' => 'ملاحظات إضافية',
                    'required' => false,
                    'placeholder' => 'أي معلومات إضافية'
                ]
            ],
            'version' => '1.0',
            'status' => 'active',
            'instructions' => 'يرجى إرفاق سند الملكية وخرائط البناء المعتمدة وصورة الهوية. يجب أن تكون جميع المستندات واضحة وسارية المفعول.',
            'attachments_required' => ['سند الملكية', 'خرائط البناء', 'صورة الهوية', 'خريطة الموقع'],
            'fee_amount' => 500.00,
            'allowed_file_types' => ['pdf', 'jpg', 'png', 'jpeg'],
        ]);

        // Request Form 2: Business License Application
        RequestForm::create([
            'title' => 'طلب رخصة تجارية',
            'description' => 'تقديم طلب للحصول على رخصة لمزاولة نشاط تجاري',
            'fields' => [
                [
                    'name' => 'business_name',
                    'type' => 'text',
                    'label' => 'اسم النشاط التجاري',
                    'required' => true,
                    'placeholder' => 'أدخل اسم النشاط التجاري'
                ],
                [
                    'name' => 'business_type',
                    'type' => 'select',
                    'label' => 'نوع النشاط',
                    'required' => true,
                    'options' => ['مطعم', 'محل تجاري', 'صالون تجميل', 'صيدلية', 'مخبز', 'ورشة', 'أخرى']
                ],
                [
                    'name' => 'business_address',
                    'type' => 'textarea',
                    'label' => 'عنوان النشاط التجاري',
                    'required' => true,
                    'placeholder' => 'أدخل العنوان الكامل'
                ],
                [
                    'name' => 'number_of_employees',
                    'type' => 'number',
                    'label' => 'عدد العاملين',
                    'required' => true,
                    'placeholder' => '5'
                ],
                [
                    'name' => 'business_description',
                    'type' => 'textarea',
                    'label' => 'وصف النشاط التجاري',
                    'required' => true,
                    'placeholder' => 'صف طبيعة النشاط التجاري'
                ],
                [
                    'name' => 'previous_license',
                    'type' => 'select',
                    'label' => 'هل لديك رخصة سابقة؟',
                    'required' => true,
                    'options' => ['نعم', 'لا']
                ]
            ],
            'version' => '1.0',
            'status' => 'active',
            'instructions' => 'يرجى إرفاق السجل التجاري، البطاقة الضريبية، عقد الإيجار أو سند الملكية، وصورة الهوية.',
            'attachments_required' => ['السجل التجاري', 'البطاقة الضريبية', 'عقد الإيجار', 'صورة الهوية'],
            'fee_amount' => 300.00,
            'allowed_file_types' => ['pdf', 'jpg', 'png', 'jpeg', 'doc', 'docx'],
        ]);

        // Request Form 3: Water Connection Request
        RequestForm::create([
            'title' => 'طلب توصيل ماء',
            'description' => 'تقديم طلب لتوصيل المياه إلى العقار',
            'fields' => [
                [
                    'name' => 'applicant_name',
                    'type' => 'text',
                    'label' => 'اسم مقدم الطلب',
                    'required' => true,
                    'placeholder' => 'الاسم الكامل'
                ],
                [
                    'name' => 'property_address',
                    'type' => 'textarea',
                    'label' => 'عنوان العقار',
                    'required' => true,
                    'placeholder' => 'أدخل العنوان الكامل'
                ],
                [
                    'name' => 'property_type',
                    'type' => 'select',
                    'label' => 'نوع العقار',
                    'required' => true,
                    'options' => ['سكني', 'تجاري', 'صناعي']
                ],
                [
                    'name' => 'meter_location',
                    'type' => 'text',
                    'label' => 'موقع العداد المقترح',
                    'required' => true,
                    'placeholder' => 'مثال: أمام المنزل'
                ],
                [
                    'name' => 'phone_number',
                    'type' => 'text',
                    'label' => 'رقم الهاتف',
                    'required' => true,
                    'placeholder' => '07xxxxxxxxx'
                ]
            ],
            'version' => '1.0',
            'status' => 'active',
            'instructions' => 'يرجى إرفاق سند الملكية، خريطة الموقع، وصورة الهوية.',
            'attachments_required' => ['سند الملكية', 'خريطة الموقع', 'صورة الهوية'],
            'fee_amount' => 150.00,
            'allowed_file_types' => ['pdf', 'jpg', 'png', 'jpeg'],
        ]);

        // Request Form 4: Electricity Connection
        RequestForm::create([
            'title' => 'طلب توصيل كهرباء',
            'description' => 'تقديم طلب لتوصيل الكهرباء إلى العقار',
            'fields' => [
                [
                    'name' => 'applicant_name',
                    'type' => 'text',
                    'label' => 'اسم مقدم الطلب',
                    'required' => true,
                    'placeholder' => 'الاسم الكامل'
                ],
                [
                    'name' => 'property_address',
                    'type' => 'textarea',
                    'label' => 'عنوان العقار',
                    'required' => true,
                    'placeholder' => 'أدخل العنوان الكامل'
                ],
                [
                    'name' => 'connection_type',
                    'type' => 'select',
                    'label' => 'نوع التوصيل',
                    'required' => true,
                    'options' => ['منزلي - أحادي الطور', 'منزلي - ثلاثي الطور', 'تجاري', 'صناعي']
                ],
                [
                    'name' => 'estimated_load',
                    'type' => 'number',
                    'label' => 'الحمل المتوقع (أمبير)',
                    'required' => true,
                    'placeholder' => '40'
                ],
                [
                    'name' => 'meter_location',
                    'type' => 'text',
                    'label' => 'موقع العداد المقترح',
                    'required' => true,
                    'placeholder' => 'خارج المنزل'
                ]
            ],
            'version' => '1.0',
            'status' => 'active',
            'instructions' => 'يرجى إرفاق سند الملكية، المخطط الكهربائي المعتمد، وصورة الهوية.',
            'attachments_required' => ['سند الملكية', 'المخطط الكهربائي', 'صورة الهوية'],
            'fee_amount' => 200.00,
            'allowed_file_types' => ['pdf', 'jpg', 'png', 'jpeg'],
        ]);

        // Request Form 5: Complaint or Inquiry
        RequestForm::create([
            'title' => 'شكوى أو استفسار',
            'description' => 'تقديم شكوى أو استفسار للبلدية',
            'fields' => [
                [
                    'name' => 'subject',
                    'type' => 'text',
                    'label' => 'الموضوع',
                    'required' => true,
                    'placeholder' => 'موضوع الشكوى أو الاستفسار'
                ],
                [
                    'name' => 'type',
                    'type' => 'select',
                    'label' => 'النوع',
                    'required' => true,
                    'options' => ['شكوى', 'استفسار', 'اقتراح']
                ],
                [
                    'name' => 'description',
                    'type' => 'textarea',
                    'label' => 'التفاصيل',
                    'required' => true,
                    'placeholder' => 'اشرح بالتفصيل'
                ],
                [
                    'name' => 'location',
                    'type' => 'text',
                    'label' => 'الموقع (إن وجد)',
                    'required' => false,
                    'placeholder' => 'موقع المشكلة'
                ],
                [
                    'name' => 'contact_preference',
                    'type' => 'select',
                    'label' => 'طريقة التواصل المفضلة',
                    'required' => true,
                    'options' => ['هاتف', 'بريد إلكتروني', 'رسالة نصية']
                ]
            ],
            'version' => '1.0',
            'status' => 'active',
            'instructions' => 'يمكنك إرفاق صور أو مستندات داعمة إن وجدت.',
            'attachments_required' => [],
            'fee_amount' => 0.00,
            'allowed_file_types' => ['pdf', 'jpg', 'png', 'jpeg'],
        ]);

        $this->command->info('5 request forms have been created successfully.');
    }
}
