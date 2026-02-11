<?php

namespace App\Http\Filters;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class HotelFilter extends AbstractFilter
{
    const CITY = 'city';
    const COUNTRY = 'country';
    const STARS = 'stars';
    const PRICETO = 'priceto';
    const PRICEFROM = 'pricefrom';
    const DATES = 'dates';

    protected function getCallbacks(): array
    {
        return [
            self::CITY => [$this, 'city'],
            self::COUNTRY => [$this, 'country'],
            self::STARS => [$this, 'stars'],
            self::PRICETO => [$this, 'priceto'],
            self::PRICEFROM => [$this, 'pricefrom'],
            self::DATES => [$this, 'availableDates'],
        ];
    }

    protected function city(Builder $builder, $value)
    {
        $builder->where('city_id', $value);
    }

    protected function country(Builder $builder, $value)
    {
        $builder->where('country_id', $value);
    }

    protected function priceto(Builder $builder, $value)
    {
        $builder->whereHas('room_type', function ($q) use ($value) {
            $q->where('price', '<=', $value);
        });
    }

    protected function pricefrom(Builder $builder, $value)
    {
        $builder->whereHas('room_type', function ($q) use ($value) {
            $q->where('price', '>=', $value);
        });
    }

    protected function stars(Builder $builder, $value)
    {
        $builder->whereIn('stars', (array)$value);
    }

    /**
     * Фильтр по доступным комнатам на выбранное количество ночей.
     */
    protected function availableDates(Builder $builder, $value)
    {
        if (!isset($value['check_in'], $value['check_out'])) {
            return $builder;
        }

        $checkIn = Carbon::parse($value['check_in'])->startOfDay();
        $checkOut = Carbon::parse($value['check_out'])->endOfDay();

        return $builder->whereHas('room_type.rooms', function ($roomQuery) use ($checkIn, $checkOut) {
            $roomQuery->whereDoesntHave('bookings', function ($bookingQuery) use ($checkIn, $checkOut) {
                $bookingQuery->whereDate('date_from', '<', $checkOut)
                    ->whereDate('date_to', '>', $checkIn);
            });
        });
    }
}
