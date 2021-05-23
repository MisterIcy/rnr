<?php declare(strict_types=1);

namespace MisterIcy\RnR;

use DateTime;
use DateTimeInterface;

final class DateTimeHelper
{
    /**
     * @param array<int> $years
     * @return array<string>
     */
    public static function getHolidays(array $years): array
    {
        $holidays = [];
        foreach ($years as $year) {
            $holidays[] = self::createDateTimeFromYmd($year, 1, 1)->format('Y-m-d'); //Πρωτοχρονιά
            $holidays[] = self::createDateTimeFromYmd($year, 1, 6)->format('Y-m-d'); //Θεοφάνια
            $holidays[] = self::createDateTimeFromYmd($year, 3, 25)->format('Y-m-d'); //Ευαγγελισμός
            $holidays[] = self::createDateTimeFromYmd($year, 5, 1)->format('Y-m-d'); //Απεργία
            $holidays[] = self::createDateTimeFromYmd($year, 8, 15)->format('Y-m-d'); //Της παναγίας
            $holidays[] = self::createDateTimeFromYmd($year, 10, 28)->format('Y-m-d'); //ΟΧΙ
            $holidays[] = self::createDateTimeFromYmd($year, 12, 25)->format('Y-m-d'); //Χριστούγεννα
            $holidays[] = self::createDateTimeFromYmd($year, 12, 26)->format('Y-m-d'); //2η Χριστουγέννων
            /** @var DateTime $easter */
            $easter = self::getOrthodoxEaster($year);

            $holidays[] = (clone $easter)->sub(new \DateInterval('P2D'))->format('Y-m-d'); //Μεγάλη Παρασκευή
            $holidays[] = (clone $easter)->add(new \DateInterval('P1D'))->format('Y-m-d'); //Δευτέρα του Πάσχα
            $holidays[] = (clone $easter)->sub(new \DateInterval('P48D'))->format('Y-m-d'); //Καθαρά δευτέρα
            $holidays[] = (clone $easter)->add(new \DateInterval('P50D'))->format('Y-m-d'); //Πεντηκοστή
        }
        return $holidays;
    }

    public static function calculateBusinessDays(DateTimeInterface $startDate, DateTimeInterface $endDate) : int
    {
        $bucket = 0;
        /** @var DateTime $targetDate */
        $targetDate = $startDate;
        //Get the year difference
        $startYear = intval($startDate->format('Y'));
        $endYear = intval($endDate->format('Y'));

        $years = [];
        for ($i = $startYear; $i <= $endYear; $i++) {
            $years[] = $i;
        }

        $holidays = self::getHolidays($years);

        while ($targetDate <= $endDate) {
            //If it is a Saturday or a Sunday, skip it
            $weekday = intval($targetDate->format('w'));
			
            if ($weekday === 0 || $weekday === 6) {
                $targetDate->add(new \DateInterval('P1D'));
                continue;
            }
			
            $targetDateStr = $targetDate->format('Y-m-d');
			
            // Check if it is a holiday
            foreach ($holidays as $holiday) {
                if ($holiday === $targetDateStr) {
                    $targetDate->add(new \DateInterval('P1D'));
                    continue 2;
                }
				
            }
            $bucket++;
            $targetDate->add(new \DateInterval('P1D'));
        }
        return $bucket;

    }

    private static function createDateTimeFromYmd(int $year, int $month, int $day): DateTimeInterface
    {
        return DateTime::createFromFormat('Y-m-d', sprintf("%04d-%02d-%02d", $year, $month, $day));
    }

    public static function getOrthodoxEaster(int $year): DateTimeInterface
    {
        $a = $year % 19;
        $b = $year % 7;
        $c = $year % 4;

        $d = (19 * $a + 16) % 30;
        $e = (2 * $c + 4 * $b + 6 * $d) % 7;
        $f = (19 * $a + 16) % 30;
        $key = $f + $e + 3;

        $month = ($key > 30) ? 5 : 4;
        $day = ($key > 30) ? $key - 30 : $key;

        return self::createDateTimeFromYmd($year, $month, $day);
    }

}
