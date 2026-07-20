<?php

namespace Database\Seeders;

use App\Models\About;
use App\Models\Branch;
use App\Models\BranchContent;
use App\Models\Hero;
use App\Models\Setting;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RestaurantSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@restaurant.test'],
            [
                'name' => 'مدير المطعم',
                'password' => Hash::make('password'),
            ],
        );

        Setting::query()->updateOrCreate(
            ['id' => 1],
            [
                'restaurant_name' => 'طنط',
                'description' => 'مطعم يجمع بين نكهات التراث العربي والضيافة الأصيلة في أجواء راقية تليق بذوقك.',
                'facebook' => 'https://facebook.com/tantrestaurant',
                'instagram' => 'https://instagram.com/tantrestaurant',
                'twitter' => 'https://x.com/tantrestaurant',
                'youtube' => 'https://youtube.com/@tantrestaurant',
                'tiktok' => 'https://tiktok.com/@tantrestaurant',
                'whatsapp' => '966500000000',
            ],
        );

        Hero::query()->updateOrCreate(
            ['id' => 1],
            [
                'title' => 'طنط',
                'description' => 'استمتع بأشهى المأكولات العربية الأصيلة المحضّرة بحب وخبرة تمتد عبر الأجيال.',
                'content' => 'من قلب المطبخ التراثي إلى مائدتك، نقدم تجربة طعام لا تُنسى تجمع بين الجودة والضيافة.',
                'button_1_name' => 'عرض القائمة',
                'button_2_name' => 'اطلب الآن',
            ],
        );

        About::query()->updateOrCreate(
            ['id' => 1],
            [
                'title' => 'من نحن',
                'description' => 'قصة مطعم بدأ بحلم بسيط: إعادة إحياء نكهات الجدّات بطابع عصري أنيق.',
                'content' => "نؤمن أن الطعام أكثر من وجبة؛ هو ذكرى ودفء ولقاء.\nفي مطعم طنط نختار أجود المكونات، ونطهى وصفاتنا على مهل، لنقدم لك تجربة تستحق الزيارة مرارًا.",
            ],
        );

        BranchContent::query()->updateOrCreate(
            ['id' => 1],
            [
                'title' => 'فروعنا',
                'description' => 'زورونا في أقرب فرع إليكم واستمتعوا بأجواء الضيافة العربية الأصيلة.',
            ],
        );

        $branches = [
            [
                'name' => 'الفرع الرئيسي — الرياض',
                'address' => 'حي العليا، طريق الملك فهد، الرياض',
                'open_from' => '09:00',
                'open_to' => '23:00',
            ],
            [
                'name' => 'فرع جدة',
                'address' => 'حي الزهراء، شارع التحلية، جدة',
                'open_from' => '10:00',
                'open_to' => '00:00',
            ],
            [
                'name' => 'فرع الدمام',
                'address' => 'حي الشاطئ، كورنيش الدمام',
                'open_from' => '09:00',
                'open_to' => '23:30',
            ],
        ];

        foreach ($branches as $branch) {
            Branch::query()->updateOrCreate(
                ['name' => $branch['name']],
                $branch,
            );
        }

        $testimonials = [
            [
                'name' => 'سارة الأحمد',
                'rating' => 5,
                'review' => 'تجربة رائعة! الأكل لذيذ جدًا والأجواء فخمة وهادية. أنصح فيه بقوة.',
            ],
            [
                'name' => 'محمد العتيبي',
                'rating' => 5,
                'review' => 'أفضل مشاوي جربتها منذ فترة طويلة، والخدمة ممتازة والسعر مناسب.',
            ],
            [
                'name' => 'نورة القحطاني',
                'rating' => 4,
                'review' => 'المكان جميل والنكهات أصيلة. هرجع مرة ثانية أكيد مع العائلة.',
            ],
            [
                'name' => 'خالد الشمري',
                'rating' => 5,
                'review' => 'من أول زيارة حسيت إني في بيت الضيافة. طعم يفتح النفس!',
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::query()->updateOrCreate(
                ['name' => $testimonial['name']],
                $testimonial,
            );
        }
    }
}
