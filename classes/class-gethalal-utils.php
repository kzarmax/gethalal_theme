<?php

if(!defined('WPINC')) {
    die;
}

if(!class_exists('Gethalal_Utils')):

    /**
     * Utils class.
     */
    class Gethalal_Utils{

        const ADDRESS_KEY = 'gethalal_custom_address';

        const UPCOMING_ORDER_STATUSES = ['pending', 'processing', 'on-hold', 'shipping'];
        const HISTORY_ORDER_STATUSES = ['completed', 'cancelled', 'failed', 'refunded'];
        public static function order_shipping_statuses(){
            return [
                'draft' => __('Placed', 'gethalal'),
                'processing' => __('Preparing', 'gethalal'),
                'shipped' => __('Shipping', 'gethalal'),
                'delivered' => __('Delivered', 'gethalal')
            ];
        }

        public static function delivery_cycles(){
            return [
                ['text' => __('Once Time', 'gethalal')],
                ['text' => __('Weekly', 'gethalal')],
                ['text' => __('Monthly', 'gethalal')]
            ];
        }

        public static function delivery_times()
        {
//            return [
//                ['text' => __('13:00 ~ 16:00', 'gethalal')],
//                ['text' => __('16:00 ~ 20:00', 'gethalal')]
//            ];
            // TODO change timeslots
            return [
                ['text' => __('15:00 ~ 20:00', 'gethalal')]
            ];
        }

        /**
         * function for get get custom addresses.
         *
         * @param integer $user_id The user id
         * @param string $type The billing or shipping data
         * @param string $address_key The addresses key info
         *
         * @return array
         */
        public static function get_custom_addresses($user_id, $address_key=false, $key=false) {
            $custom_address = get_user_meta($user_id, self::ADDRESS_KEY, true);
            if(is_array($custom_address)) {
                if($address_key) {
                    if($key) {
                        if(isset($custom_address[$address_key][$key])) {
                            return $custom_address[$address_key][$key];
                        }else {
                            return false;
                        }
                    }
                    return $custom_address[$address_key];
                }
                return $custom_address;
            }
            return false;
        }

        public static function set_custom_addresses($user_id, $addresses) {
            $custom_address = get_user_meta($user_id, self::ADDRESS_KEY, true);
            if(is_array($custom_address)) {
                update_user_meta($user_id, self::ADDRESS_KEY, $addresses);
            } else {
                add_user_meta($user_id, self::ADDRESS_KEY, $addresses, true);
            }
        }

        /**
         * function for check the addresses are same.
         *
         * @param array $address_1 first address
         * @param array $address_2 second address
         *
         * @return string
         */
        public static function is_same_address($address_1, $address_2) {
            $is_same = true;
            if(!empty($address_1) && is_array($address_1)) {
                foreach($address_1 as $key => $value) {
                    if(!(isset($address_2[$key]) && isset($address_1[$key]) && $address_2[$key] == $address_1[$key])) {
                        $is_same = false;
                        break;
                    }
                    return $is_same;
                }
            }
            return false;
        }


        /**
         * function for get the default address.
         *
         * @param integer $user_id The user id
         * @param string $type The billing or shipping data
         *
         * @return bool | array
         */
        public static function get_default_address($user_id) {
            $default_address_index = self::get_default_address_index($user_id);
            if($default_address_index > -1){
                $addresses = self::get_addresses($user_id);
                return $addresses[$default_address_index];
            }
            return false;
        }

        /**
         * function for get the default address.
         *
         * @param integer $user_id The user id
         * @param string $type The billing or shipping data
         *
         * @return bool | integer
         */
        public static function get_default_address_index($user_id) {
            $default_address_index =  get_user_meta($user_id, 'default_' . self::ADDRESS_KEY, true);
            if($default_address_index == ""){
                return -1;
            }
            return intval($default_address_index);
        }

        public static function set_default_address($user_id, $address_id) {
            if(self::get_default_address_index($user_id) == -1){
                add_user_meta($user_id, 'default_' . self::ADDRESS_KEY, $address_id, true);
            } else {
                update_user_meta($user_id, 'default_' . self::ADDRESS_KEY, $address_id);
            }
        }

        /**
         * function for getting the address fields
         *
         * @param string $type The address type
         *
         * @return array
         */
        public static function get_address_fields() {
            return array('display_name', 'email', 'phone', 'address', 'postcode', 'city', 'floor', 'country');
        }
        /**
         * function for get addresses.
         *
         * @param  integer $customer_id $hook The user id
         * @param  string $type The billing or shipping data
         *
         * @return array
         */
        public static function get_addresses($customer_id) {
            $addresses = self::get_custom_addresses($customer_id);
            $default_address_index = self::get_default_address_index($customer_id);

            if(is_array($addresses)) {
                $ret = [];
                foreach($addresses as $index => $address){
                    $address['is_default'] = $index == $default_address_index;
                    $ret[] = $address;
                }
                return $ret;
            }else {
                return [];
            }
        }

        /**
         * Add account`s address
         * @param $customer_id
         * @param $new_address
         * return added address index
         */
        public static function add_address($customer_id, $new_address) {
            $address = self::get_custom_addresses($customer_id);

            if(is_array($address)) {
                array_push($address, $new_address);
            }else {
                $address = [$new_address];
                self::set_default_address($customer_id, 0);
            }
            self::set_custom_addresses($customer_id, $address);
            return count($address) - 1;
        }

        public static function update_address($customer_id, $index, $new_address){
            $address = self::get_custom_addresses($customer_id);
            if(isset($address[$index])){
                $address[$index] = $new_address;
                self::set_custom_addresses($customer_id, $address);
            }
            return $index;
        }

        public static function remove_address($customer_id, $index){
            $default_address_index = self::get_default_address_index($customer_id);
            $address = self::get_custom_addresses($customer_id);
            if(is_array($address) && count($address) > $index){
                array_splice($address, $index, 1);
                self::set_custom_addresses($customer_id, $address);
                if($default_address_index == $index){
                    self::set_default_address($customer_id, 0);
                } else if($default_address_index > $index) {
                    self::set_default_address($customer_id, $default_address_index - 1);
                }
            }
        }
    }

endif;
