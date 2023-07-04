<?php

namespace Magein\Common;

class Faker
{
    /**
     * @param array $data
     * @return mixed
     */
    public static function random(array $data)
    {
        return $data[rand(0, count($data) - 1)];
    }

    public static function name()
    {
        $xing = '陈、李、黄、张、梁、林、刘、吴、罗、杨、陈、王、李、吴、符、林、黄、张、郑、李、张、陈、刘、王、杨、周、黄、罗、唐、马';

        $xing = explode('、', $xing);

        $ming = '达、耀、兴、荣、华、旺、盈、丰、余、昌、盛、俊、威、英、健、壮、焕、挺、帅、秀、伟、武、雄、巍、松、柏、山、石、婵、娟、姣、妯、婷、姿、媚、婉、丽、妩、美、倩、兰、颖、灵、睿、锐、哲、慧、敦、迪、明、晓、显、悉、晰、维、学、思、悟、析、文、书、勤仁、义、礼、智、信、德、诚、伦、孝、忠、良、勤、俭、廉、文、章、斌';

        $ming = explode('、', $ming);

        $len = rand(1, 2);

        $name = $xing[rand(0, count($xing) - 1)];

        for ($i = 0; $i < $len; $i++) {
            $name .= $ming[rand(0, count($ming) - 1)];
        }

        return $name;
    }

    /**
     * @return string
     */
    public static function phone(): string
    {
        $number = [
            '31',
            '35',
            '37',
            '38',
            '39',
            '50',
            '51',
            '55',
            '57',
            '59',
            '80',
            '81',
            '87',
            '88',
            '89',
            '91',
            '99',
        ];

        return '1' . $number[rand(0, count($number) - 1)] . rand(1000, 9999) . rand(1000, 9999);
    }

    public static function sex(): int
    {
        return rand(0, 2);
    }

    public static function status(): int
    {
        return rand(0, 1);
    }

    public static function favorite()
    {
        $favorite = [
            '打篮球',
            '看书',
            '羽毛球',
            '跑步',
            '看电影',
            '旅游',
            '散步',
        ];

        $keys = array_rand($favorite, rand(2, 4));

        $result = [];
        foreach ($favorite as $key => $item) {
            if (in_array($key, $keys)) {
                $result[] = $item;
            }
        }

        return $result;
    }
}
