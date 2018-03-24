<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => __('The :attribute must be accepted.', 'selesti'),
    'active_url'           => __('The :attribute is not a valid URL.', 'selesti'),
    'after'                => __('The :attribute must be a date after :date.', 'selesti'),
    'after_or_equal'       => __('The :attribute must be a date after or equal to :date.', 'selesti'),
    'alpha'                => __('The :attribute may only contain letters.', 'selesti'),
    'alpha_dash'           => __('The :attribute may only contain letters, numbers, and dashes.', 'selesti'),
    'alpha_num'            => __('The :attribute may only contain letters and numbers.', 'selesti'),
    'array'                => __('The :attribute must be an array.', 'selesti'),
    'before'               => __('The :attribute must be a date before :date.', 'selesti'),
    'before_or_equal'      => __('The :attribute must be a date before or equal to :date.', 'selesti'),
    'between'              => [
        'numeric' => __('The :attribute must be between :min and :max.', 'selesti'),
        'file'    => __('The :attribute must be between :min and :max kilobytes.', 'selesti'),
        'string'  => __('The :attribute must be between :min and :max characters.', 'selesti'),
        'array'   => __('The :attribute must have between :min and :max items.', 'selesti'),
    ],
    'boolean'              => __('The :attribute field must be true or false.', 'selesti'),
    'confirmed'            => __('The :attribute confirmation does not match.', 'selesti'),
    'date'                 => __('The :attribute is not a valid date.', 'selesti'),
    'date_format'          => __('The :attribute does not match the format :format.', 'selesti'),
    'different'            => __('The :attribute and :other must be different.', 'selesti'),
    'digits'               => __('The :attribute must be :digits digits.', 'selesti'),
    'digits_between'       => __('The :attribute must be between :min and :max digits.', 'selesti'),
    'dimensions'           => __('The :attribute has invalid image dimensions.', 'selesti'),
    'distinct'             => __('The :attribute field has a duplicate value.', 'selesti'),
    'email'                => __('The :attribute must be a valid email address.', 'selesti'),
    'exists'               => __('The selected :attribute is invalid.', 'selesti'),
    'file'                 => __('The :attribute must be a file.', 'selesti'),
    'filled'               => __('The :attribute field must have a value.', 'selesti'),
    'image'                => __('The :attribute must be an image.', 'selesti'),
    'in'                   => __('The selected :attribute is invalid.', 'selesti'),
    'in_array'             => __('The :attribute field does not exist in :other.', 'selesti'),
    'integer'              => __('The :attribute must be an integer.', 'selesti'),
    'ip'                   => __('The :attribute must be a valid IP address.', 'selesti'),
    'ipv4'                 => __('The :attribute must be a valid IPv4 address.', 'selesti'),
    'ipv6'                 => __('The :attribute must be a valid IPv6 address.', 'selesti'),
    'json'                 => __('The :attribute must be a valid JSON string.', 'selesti'),
    'max'                  => [
        'numeric' => __('The :attribute may not be greater than :max.', 'selesti'),
        'file'    => __('The :attribute may not be greater than :max kilobytes.', 'selesti'),
        'string'  => __('The :attribute may not be greater than :max characters.', 'selesti'),
        'array'   => __('The :attribute may not have more than :max items.', 'selesti'),
    ],
    'mimes'                => __('The :attribute must be a file of type: :values.', 'selesti'),
    'mimetypes'            => __('The :attribute must be a file of type: :values.', 'selesti'),
    'min'                  => [
        'numeric' => __('The :attribute must be at least :min.', 'selesti'),
        'file'    => __('The :attribute must be at least :min kilobytes.', 'selesti'),
        'string'  => __('The :attribute must be at least :min characters.', 'selesti'),
        'array'   => __('The :attribute must have at least :min items.', 'selesti'),
    ],
    'not_in'               => __('The selected :attribute is invalid.', 'selesti'),
    'numeric'              => __('The :attribute must be a number.', 'selesti'),
    'present'              => __('The :attribute field must be present.', 'selesti'),
    'regex'                => __('The :attribute format is invalid.', 'selesti'),
    'required'             => __('The :attribute field is required.', 'selesti'),
    'required_if'          => __('The :attribute field is required when :other is :value.', 'selesti'),
    'required_unless'      => __('The :attribute field is required unless :other is in :values.', 'selesti'),
    'required_with'        => __('The :attribute field is required when :values is present.', 'selesti'),
    'required_with_all'    => __('The :attribute field is required when :values is present.', 'selesti'),
    'required_without'     => __('The :attribute field is required when :values is not present.', 'selesti'),
    'required_without_all' => __('The :attribute field is required when none of :values are present.', 'selesti'),
    'same'                 => __('The :attribute and :other must match.', 'selesti'),
    'size'                 => [
        'numeric' => __('The :attribute must be :size.', 'selesti'),
        'file'    => __('The :attribute must be :size kilobytes.', 'selesti'),
        'string'  => __('The :attribute must be :size characters.', 'selesti'),
        'array'   => __('The :attribute must contain :size items.', 'selesti'),
    ],
    'string'               => __('The :attribute must be a string.', 'selesti'),
    'timezone'             => __('The :attribute must be a valid zone.', 'selesti'),
    'unique'               => __('The :attribute has already been taken.', 'selesti'),
    'uploaded'             => __('The :attribute failed to upload.', 'selesti'),
    'url'                  => __('The :attribute format is invalid.', 'selesti'),

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
        'token' => [
            'unique' => 'This token has already been added',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],

];
