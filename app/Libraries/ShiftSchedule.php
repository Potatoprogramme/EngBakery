<?php

namespace App\Libraries;

class ShiftSchedule
{
    /**
     * Return configured shift windows for a date.
     *
     * @param string $date Y-m-d
     * @return array<int, array{key:string,label:string,start:string,end:string}>
     */
    public static function getShiftWindowsForDate(string $date): array
    {
        return [
            [
                'key' => 'shift_a',
                'label' => 'Shift A (6 AM – 3 PM)',
                'start' => '06:00:00',
                'end' => '15:00:00',
            ],
            [
                'key' => 'shift_b',
                'label' => 'Shift B (3 PM – 8 PM)',
                'start' => '15:00:01',
                'end' => '20:00:00',
            ],
            [
                'key' => 'shift_c',
                'label' => 'Shift C (6 AM – 5 PM)',
                'start' => '06:00:00',
                'end' => '17:00:00',
            ],
            [
                'key' => 'shift_d',
                'label' => 'Shift D (5 PM – 8 PM)',
                'start' => '17:00:00',
                'end' => '20:00:00',
            ],
        ];
    }

    /**
     * Validate that a shift window exactly matches one configured shift window.
     */
    public static function isValidShiftWindow(string $date, string $shiftStart, string $shiftEnd): bool
    {
        $normalizedStart = self::normalizeTime($shiftStart);
        $normalizedEnd = self::normalizeTime($shiftEnd);

        foreach (self::getShiftWindowsForDate($date) as $window) {
            if ($window['start'] === $normalizedStart && $window['end'] === $normalizedEnd) {
                return true;
            }
        }

        return false;
    }

    private static function normalizeTime(string $time): string
    {
        $trimmed = trim($time);
        if (strlen($trimmed) === 5) {
            return $trimmed . ':00';
        }

        return $trimmed;
    }
}