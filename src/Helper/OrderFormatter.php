<?php

namespace App\Helper;

class OrderFormatter
{
    private static array $formatedOrders = [];

    public static function format(array $orders): array
    {
        foreach ($orders as $order) {
            $zip_code = $order['zip_code'];
            $city = $order['city'];
            $district = $order['district'];
            $street = $order['street'];
            $number = $order['number'];
            self::$formatedOrders[] = [
                'description' => $order['description'],
                'address' => "$zip_code, $city, $district, $street, $number",
                'status' => $order['status'] === 1 ? 'Em Andamento' : 'Concluído',
                'id' => $order['id']
            ];
        }

        return self::$formatedOrders;
    }


}