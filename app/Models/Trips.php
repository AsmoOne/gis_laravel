<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

use function Laravel\Prompts\select;

class Trips extends Model
{
    use HasFactory;

    protected $casts = [
        'pickup' => 'datetime',
        'dropoff' => 'datetime',
    ];

    /**
     * App\Models\Trips
     * @property Carbon $pickup
     * @property Carbon $dropoff
     */

    public static function getDriverIds()
    {
        return self::query()->distinct()->pluck('driver_id')->all();
    }

    public static function getTripsByDriverId(int $idDriver)
    {
        return self::query()->select()->where('driver_id', '=', $idDriver)->get();
    }

    public static function calculateTripsDurationMinutes($trips)
    {
        $payableTime = 0.0;

        $sortedPassengers = $trips->sortBy('pickup');

        $mergedPassengers = [$sortedPassengers[0]];

        foreach ($sortedPassengers->slice(1) as $current) {

            $previous = end($mergedPassengers);

            //check overlaps
            if ($current->pickup->getTimestamp() <= $previous->dropoff->getTimestamp()) {
                $previous->dropoff = max($previous->dropoff, $current->dropoff);
                $mergedPassengers[count($mergedPassengers) - 1] = $previous;
            } else {
                $mergedPassengers[] = $current;
            }
        }

        foreach ($mergedPassengers as $p) {
            $payableTime += $p->dropoff->getTimestamp() - $p->pickup->getTimestamp();
        }

        return $payableTime / 60.0;
    }
}
