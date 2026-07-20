@php
    $fmt = function ($value) {
        if ($value === null || $value === '') {
            return '..........';
        }
        $num = (float) $value;
        return rtrim(rtrim(number_format($num, 2, '.', ''), '0'), '.');
    };

    $total = $record->computed_total ?? $record->calculateTotal();
    $issueDate = $record->issue_date ? $record->issue_date->format('Y/m/d') : '..........';
    $transferDate = $record->transfer_date ? $record->transfer_date->format('Y/m/d') : '..........';
@endphp
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <style>
        * { box-sizing: border-box; }
        body {
            direction: rtl;
            text-align: right;
            font-size: 15px;
            line-height: 2.1;
            color: #111;
        }
        .head {
            width: 100%;
            margin-bottom: 12px;
        }
        .head td { vertical-align: top; }
        .org {
            font-size: 13px;
            font-weight: bold;
            line-height: 1.9;
        }
        .country {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
        }
        .title {
            text-align: center;
            font-size: 22px;
            font-weight: bold;
            text-decoration: underline;
            margin: 18px 0 26px;
        }
        p { margin: 0 0 10px; }
        .indent { text-indent: 30px; }
        .field {
            font-weight: bold;
            text-decoration: underline;
            padding: 0 4px;
        }
        .row { margin: 14px 0; }
        .label { font-weight: bold; }
        .total-line { font-weight: bold; }
        .sign {
            width: 100%;
            margin-top: 55px;
        }
        .sign td {
            text-align: center;
            font-weight: bold;
            font-size: 15px;
        }
        .sign .name { padding-top: 45px; }
        .date { margin-top: 30px; text-align: center; font-weight: bold; }
    </style>
</head>
<body>
    <table class="head">
        <tr>
            <td style="width: 40%;" class="org">
                محافظة الدقهلية<br>
                مديرية التربية والتعليم<br>
                إدارة شؤون الطلبة والامتحانات
            </td>
            <td style="width: 60%;" class="country">
                جمهورية مصر العربية<br>
                وزارة التربية والتعليم
            </td>
        </tr>
    </table>

    <div class="title">معادلة شهادة دراسية</div>

    <p class="indent">
        صدر القرار الوزاري رقم (58) لسنة 1969 والذي ينص في مادته الثالثة على:
    </p>
    <p class="indent">
        - تعتبر الشهادة الإعدادية الأزهرية معادلة للشهادة الإعدادية العامة ومناظرة لها.
    </p>
    <p class="indent">
        - وقد أُعطي هذا البيان بناءً على قرار إدارة المعادلات بوزارة التربية والتعليم.
    </p>

    <div class="row">
        <span class="label">اسم الطالب /</span>
        <span class="field">{{ $record->student_name }}</span>
    </div>

    <div class="row total-line">
        المجموع الاعتباري:
        <span class="field">{{ $fmt($record->total_obtained) }}</span>
        &times;
        <span class="field">{{ $fmt($record->multiplier) }}</span>
        &divide;
        <span class="field">{{ $fmt($record->total_max) }}</span>
        =
        <span class="field">{{ $fmt($total) }}</span>
        درجة
    </div>

    <div class="row">
        <span class="label">وذلك لتقديمه إلى مدرسة /</span>
        <span class="field">{{ $record->school_name }}</span>
    </div>

    <p class="indent">
        وقد تم تحرير هذا بناءً على طلب الطالب بعد سداد مبلغ
        <span class="field">{{ $fmt($record->amount) }}</span>
        جنيهاً بالحوالة رقم
        <span class="field">{{ $record->transfer_number ?: '..........' }}</span>
        بتاريخ
        <span class="field">{{ $transferDate }}</span>.
    </p>

    <div class="date">تحريراً في {{ $issueDate }} م</div>

    <table class="sign">
        <tr>
            <td style="width: 50%;">
                مدير إدارة شئون الطلبة والامتحانات
                <div class="name">إبراهيم طلعت محمد</div>
            </td>
            <td style="width: 50%;">
                المختص
                <div class="name">&nbsp;</div>
            </td>
        </tr>
    </table>
</body>
</html>
