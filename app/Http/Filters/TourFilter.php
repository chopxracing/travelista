<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

class TourFilter extends AbstractFilter
{
    const CITY = 'city';
    const COUNTRY = 'country';
    const PRICEFROM = 'pricefrom';
    const PRICETO = 'priceto';
    const DATES = 'dates';

    const STARS = 'stars';

    protected function getCallbacks(): array
    {
        return [
            self::CITY => [$this, 'city'],
            self::COUNTRY => [$this, 'country'],
            self::PRICEFROM => [$this, 'priceFrom'],
            self::PRICETO => [$this, 'priceTo'],
            self::DATES => [$this, 'dates'],
            self::STARS     => [$this, 'stars'],
        ];
    }
    protected function stars(Builder $builder, $value)
    {
        if (!is_array($value) || empty($value)) {
            return;
        }

        $builder->whereHas('hotel', function (Builder $q) use ($value) {
            $q->whereIn('stars', $value);
        });
    }
    protected function city(Builder $builder, $value)
    {
        $builder->where('city_id', $value);
    }

    protected function country(Builder $builder, $value)
    {
        $builder->where('country_id', $value);
    }

    protected function priceFrom(Builder $builder, $value)
    {
        $builder->where('price', '>=', $value);
    }

    protected function priceTo(Builder $builder, $value)
    {
        $builder->where('price', '<=', $value);
    }

    protected function dates(Builder $builder, $value)
    {
        if (!is_array($value) || empty($value['from']) || empty($value['to'])) {
            return;
        }

        $from = $value['from'];
        $to   = $value['to'];

        // пересечение дат
        $builder->whereDate('date_to', '>=', $from)
            ->whereDate('date_from', '<=', $to);
    }
}
