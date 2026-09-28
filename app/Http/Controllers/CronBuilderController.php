<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CronBuilderController extends Controller
{
    public function index()
    {
        return view('cron.index');
    }

    public function calculate(Request $request)
    {
        $expression = $request->input('expression', '* * * * *');
        $timezone = $request->input('timezone', 'Asia/Manila');

        $parts = preg_split('/\s+/', trim($expression));

        if (count($parts) !== 5) {
            return response()->json([
                'success' => false,
                'message' => 'Cron expression must have exactly 5 fields.'
            ]);
        }

        if (!$this->validateCron($parts)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid cron expression.'
            ]);
        }

        try {
            $nextRuns = $this->getNextRuns(
                $parts,
                $timezone,
                5
            );

            return response()->json([
                'success' => true,
                'expression' => $expression,
                'description' => $this->getDescription($parts),
                'nextRuns' => $nextRuns
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to calculate next run times.'
            ]);
        }
    }

    private function validateCron(array $parts)
    {
        $ranges = [
            0 => [0, 59],
            1 => [0, 23],
            2 => [1, 31],
            3 => [1, 12],
            4 => [0, 7]
        ];

        foreach ($parts as $index => $part) {
            $values = preg_split('/[,\/\-\*]+/', $part);

            foreach ($values as $value) {
                if ($value === '') {
                    continue;
                }

                if (!is_numeric($value)) {
                    continue;
                }

                $number = intval($value);

                if (
                    $number < $ranges[$index][0] ||
                    $number > $ranges[$index][1]
                ) {
                    return false;
                }
            }
        }

        return true;
    }

    private function getDescription(array $parts)
    {
        [$minute, $hour, $day, $month, $weekday] = $parts;

        if (
            $minute === '*' &&
            $hour === '*' &&
            $day === '*' &&
            $month === '*' &&
            $weekday === '*'
        ) {
            return 'Every minute';
        }

        if (
            $minute === '0' &&
            $hour === '*' &&
            $day === '*' &&
            $month === '*' &&
            $weekday === '*'
        ) {
            return 'Every hour';
        }

        if (
            $minute === '0' &&
            $hour === '0' &&
            $day === '*' &&
            $month === '*' &&
            $weekday === '*'
        ) {
            return 'Every day at midnight';
        }

        if (
            $minute === '0' &&
            $hour === '12' &&
            $day === '*' &&
            $month === '*' &&
            $weekday === '*'
        ) {
            return 'Every day at 12:00 PM';
        }

        if (
            $minute === '0' &&
            $hour === '9' &&
            $day === '*' &&
            $month === '*' &&
            $weekday === '1-5'
        ) {
            return 'Every weekday at 9:00 AM';
        }

        if (
            $minute === '0' &&
            $hour === '0' &&
            $day === '1' &&
            $month === '*' &&
            $weekday === '*'
        ) {
            return 'At midnight on the first day of every month';
        }

        return "Runs according to the schedule: {$parts[0]} {$parts[1]} {$parts[2]} {$parts[3]} {$parts[4]}";
    }

    private function getNextRuns(array $parts, string $timezone, int $count)
    {
        date_default_timezone_set($timezone);

        $now = new \DateTime();
        $now->setTime(
            (int)$now->format('H'),
            (int)$now->format('i'),
            0
        );

        $runs = [];
        $current = clone $now;

        for ($i = 0; $i < 525600 && count($runs) < $count; $i++) {

            $current->modify('+1 minute');

            $minute = (int)$current->format('i');
            $hour = (int)$current->format('G');
            $day = (int)$current->format('j');
            $month = (int)$current->format('n');
            $weekday = (int)$current->format('w');

            if (
                $this->matches($minute, $parts[0], 0, 59) &&
                $this->matches($hour, $parts[1], 0, 23) &&
                $this->matches($day, $parts[2], 1, 31) &&
                $this->matches($month, $parts[3], 1, 12) &&
                $this->matches($weekday, $parts[4], 0, 7)
            ) {
                $runs[] = $current->format('M d, Y h:i A');
            }
        }

        return $runs;
    }

    private function matches($value, $expression, $min, $max)
    {
        if ($expression === '*') {
            return true;
        }

        $parts = explode(',', $expression);

        foreach ($parts as $part) {

            if (strpos($part, '/') !== false) {
                [$base, $step] = explode('/', $part);

                $step = intval($step);

                if ($step <= 0) {
                    return false;
                }

                if ($base === '*') {
                    if (($value - $min) % $step === 0) {
                        return true;
                    }
                } else {
                    $start = intval($base);

                    if (
                        $value >= $start &&
                        ($value - $start) % $step === 0
                    ) {
                        return true;
                    }
                }
            } elseif (strpos($part, '-') !== false) {
                [$start, $end] = explode('-', $part);

                if (
                    $value >= intval($start) &&
                    $value <= intval($end)
                ) {
                    return true;
                }
            } else {
                if ($value == intval($part)) {
                    return true;
                }
            }
        }

        return false;
    }
}