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

    'accepted' => 'ត្រូវតែយល់ព្រម :attribute។',
    'accepted_if' => 'ត្រូវតែយល់ព្រម :attribute នៅពេល :other គឺ :value។',
    'active_url' => ':attribute ត្រូវតែជាលីង URL ត្រឹមត្រូវ។',
    'after' => ':attribute ត្រូវតែជាកាលបរិច្ឆេទក្រោយពី :date។',
    'after_or_equal' => ':attribute ត្រូវតែជាកាលបរិច្ឆេទក្រោយឬស្មើនឹង :date។',
    'alpha' => ':attribute ត្រូវតែមានតែអក្សរតែប៉ុណ្ណោះ។',
    'alpha_dash' => ':attribute ត្រូវតែមានតែអក្សរ លេខ សញ្ញា និងគូសបន្ទាត់ក្រោម។',
    'alpha_num' => ':attribute ត្រូវតែមានតែអក្សរ និងលេខ។',
    'array' => ':attribute ត្រូវតែជាអារេ។',
    'ascii' => ':attribute ត្រូវតែមានតែតួអក្សរ និងសញ្ញាដែលមានប៉ុន្មានបៃ។',
    'before' => ':attribute ត្រូវតែជាកាលបរិច្ឆេទមុន :date។',
    'before_or_equal' => ':attribute ត្រូវតែជាកាលបរិច្ឆេទមុនឬស្មើនឹង :date។',
    'between' => [
        'array' => ':attribute ត្រូវតែមានចន្លោះ :min និង :max ធាតុ។',
        'file' => ':attribute ត្រូវតែមានចន្លោះ :min និង :max គីឡូបៃ។',
        'numeric' => ':attribute ត្រូវតែមានចន្លោះ :min និង :max។',
        'string' => ':attribute ត្រូវតែមានចន្លោះ :min និង :max តួអក្សរ។',
    ],
    'boolean' => ':attribute ត្រូវតែជា true ឬ false។',
    'can' => ':attribute មានតម្លៃដែលមិនមានសិទ្ធិ។',
    'confirmed' => ':attribute ការបញ្ជាក់មិនត្រូវគ្នា។',
    'contains' => ':attribute កំពុងខ្វះតម្លៃចាំបាច់។',
    'current_password' => 'ពាក្យសម្ងាត់មិនត្រឹមត្រូវ។',
    'date' => ':attribute ត្រូវតែជាកាលបរិច្ឆេទត្រឹមត្រូវ។',
    'date_equals' => ':attribute ត្រូវតែជាកាលបរិច្ឆេទស្មើនឹង :date។',
    'date_format' => ':attribute ត្រូវតែត្រូវនឹងទម្រង់ :format។',
    'decimal' => ':attribute ត្រូវតែមាន :decimal ចំណុចទសភាគ។',
    'declined' => ':attribute ត្រូវតែត្រូវបានច្រានចោល។',
    'declined_if' => ':attribute ត្រូវតែត្រូវបានច្រានចោលនៅពេល :other គឺ :value។',
    'different' => ':attribute និង :other ត្រូវតែខុសគ្នា។',
    'digits' => ':attribute ត្រូវតែមាន :digits ខ្ទង់។',
    'digits_between' => ':attribute ត្រូវតែមានចន្លោះ :min និង :max ខ្ទង់។',
    'dimensions' => ':attribute មានទំហំរូបភាពមិនត្រឹមត្រូវ។',
    'distinct' => ':attribute មានតម្លៃស្ទួន។',
    'doesnt_end_with' => ':attribute មិនត្រូវបញ្ចប់ដោយ៖ :values។',
    'doesnt_start_with' => ':attribute មិនត្រូវចាប់ផ្តើមដោយ៖ :values។',
    'email' => ':attribute ត្រូវតែជាអាសយដ្ឋានអ៊ីមែលត្រឹមត្រូវ។',
    'ends_with' => ':attribute ត្រូវតែបញ្ចប់ដោយ៖ :values។',
    'enum' => ':attribute ដែលបានជ្រើសគឺមិនត្រឹមត្រូវ។',
    'exists' => ':attribute ដែលបានជ្រើសគឺមិនត្រឹមត្រូវ។',
    'extensions' => ':attribute ត្រូវតែមានប្រភេទឯកសារ៖ :values។',
    'file' => ':attribute ត្រូវតែជាឯកសារ។',
    'filled' => ':attribute ត្រូវតែមានតម្លៃ។',
    'gt' => [
        'array' => ':attribute ត្រូវតែមានច្រើនជាង :value ធាតុ។',
        'file' => ':attribute ត្រូវតែធំជាង :value គីឡូបៃ។',
        'numeric' => ':attribute ត្រូវតែធំជាង :value។',
        'string' => ':attribute ត្រូវតែធំជាង :value តួអក្សរ។',
    ],
    'gte' => [
        'array' => ':attribute ត្រូវតែមាន :value ធាតុ ឬច្រើនជាងនេះ។',
        'file' => ':attribute ត្រូវតែធំជាងឬស្មើ :value គីឡូបៃ។',
        'numeric' => ':attribute ត្រូវតែធំជាងឬស្មើ :value។',
        'string' => ':attribute ត្រូវតែធំជាងឬស្មើ :value តួអក្សរ។',
    ],
    'hex_color' => ':attribute ត្រូវតែជាផ្នែកពណ៌ Hexadecimal ត្រឹមត្រូវ។',
    'image' => ':attribute ត្រូវតែជារូបភាព។',
    'in' => ':attribute ដែលបានជ្រើសគឺមិនត្រឹមត្រូវ។',
    'in_array' => ':attribute ត្រូវតែមាននៅក្នុង :other។',
    'integer' => ':attribute ត្រូវតែជាចំនួនគត់។',
    'ip' => ':attribute ត្រូវតែជាអាសយដ្ឋាន IP ត្រឹមត្រូវ។',
    'ipv4' => ':attribute ត្រូវតែជាអាសយដ្ឋាន IPv4 ត្រឹមត្រូវ។',
    'ipv6' => ':attribute ត្រូវតែជាអាសយដ្ឋាន IPv6 ត្រឹមត្រូវ។',
    'json' => ':attribute ត្រូវតែជាខ្សែអក្សរ JSON ត្រឹមត្រូវ។',
    'list' => ':attribute ត្រូវតែជាបញ្ជី។',
    'lowercase' => ':attribute ត្រូវតែជាអក្សរតូច។',
    'lt' => [
        'array' => ':attribute ត្រូវតែមានតិចជាង :value ធាតុ។',
        'file' => ':attribute ត្រូវតែតិចជាង :value គីឡូបៃ។',
        'numeric' => ':attribute ត្រូវតែតិចជាង :value។',
        'string' => ':attribute ត្រូវតែតិចជាង :value តួអក្សរ។',
    ],
    'lte' => [
        'array' => ':attribute ត្រូវតែមិនមានច្រើនជាង :value ធាតុ។',
        'file' => ':attribute ត្រូវតែតិចជាងឬស្មើ :value គីឡូបៃ។',
        'numeric' => ':attribute ត្រូវតែតិចជាងឬស្មើ :value។',
        'string' => ':attribute ត្រូវតែតិចជាងឬស្មើ :value តួអក្សរ។',
    ],
    'mac_address' => ':attribute ត្រូវតែជាអាសយដ្ឋាន MAC ត្រឹមត្រូវ។',
    'max' => [
        'array' => ':attribute មិនត្រូវមានច្រើនជាង :max ធាតុ។',
        'file' => ':attribute មិនត្រូវធំជាង :max គីឡូបៃ។',
        'numeric' => ':attribute មិនត្រូវធំជាង :max។',
        'string' => ':attribute មិនត្រូវធំជាង :max តួអក្សរ។',
    ],
    'max_digits' => ':attribute មិនត្រូវមានច្រើនជាង :max ខ្ទង់។',
    'mimes' => ':attribute ត្រូវតែជាឯកសារប្រភេទ៖ :values។',
    'mimetypes' => ':attribute ត្រូវតែជាឯកសារប្រភេទ៖ :values។',
    'min' => [
        'array' => ':attribute ត្រូវតែមានយ៉ាងហោចណាស់ :min ធាតុ។',
        'file' => ':attribute ត្រូវតែយ៉ាងហោចណាស់ :min គីឡូបៃ។',
        'numeric' => ':attribute ត្រូវតែយ៉ាងហោចណាស់ :min។',
        'string' => ':attribute ត្រូវតែយ៉ាងហោចណាស់ :min តួអក្សរ។',
    ],
    'min_digits' => ':attribute ត្រូវតែមានយ៉ាងហោចណាស់ :min ខ្ទង់។',
    'multiple_of' => ':attribute ត្រូវតែជាចំនួនដែលជាដងនៃ :value។',
    'not_in' => ':attribute ដែលបានជ្រើសគឺមិនត្រឹមត្រូវ។',
    'not_regex' => ':attribute ទម្រង់មិនត្រឹមត្រូវ។',
    'numeric' => ':attribute ត្រូវតែជាលេខ។',
    'password' => [
        'letters' => ':attribute ត្រូវតែមានអក្សរយ៉ាងហោចណាស់មួយ។',
        'mixed' => ':attribute ត្រូវតែមានអក្សរធំ និងអក្សរតូចយ៉ាងហោចណាស់មួយ។',
        'numbers' => ':attribute ត្រូវតែមានលេខយ៉ាងហោចណាស់មួយ។',
        'symbols' => ':attribute ត្រូវតែមានសញ្ញាយ៉ាងហោចណាស់មួយ។',
        'uncompromised' => 'លេខសម្ងាត់ :attribute ត្រូវបានប្រើឡើងវិញ។',
    ],
    'present' => ':attribute ត្រូវតែមាន។',
    'prohibited' => ':attribute ត្រូវតែត្រូវបានហាមឃាត់។',
    'prohibited_if' => ':attribute ត្រូវតែត្រូវបានហាមឃាត់នៅពេល :other គឺ :value។',
    'prohibited_unless' => ':attribute ត្រូវតែត្រូវបានហាមឃាត់លុះត្រាតែ :other គឺ :values។',
    'prohibits' => ':attribute ធ្វើឱ្យអសកម្មចំពោះ :other។',
    'regex' => ':attribute ទម្រង់មិនត្រឹមត្រូវ។',
    'required' => ':attribute ត្រូវតែមាន។',
    'required_array_keys' => ':attribute ត្រូវតែមានទាំង :values។',
    'required_if' => ':attribute ត្រូវតែមាននៅពេល :other គឺ :value។',
    'required_unless' => ':attribute ត្រូវតែមានលុះត្រាតែ :other គឺ :values។',
    'required_with' => ':attribute ត្រូវតែមាននៅពេល :values មាន។',
    'required_with_all' => ':attribute ត្រូវតែមាននៅពេល :values មាន។',
    'required_without' => ':attribute ត្រូវតែមាននៅពេល :values គ្មាន។',
    'required_without_all' => ':attribute ត្រូវតែមាននៅពេលគ្មាន :values។',
    'same' => ':attribute និង :other ត្រូវតែត្រូវគ្នា។',
    'size' => [
        'array' => ':attribute ត្រូវតែមាន :size ធាតុ។',
        'file' => ':attribute ត្រូវតែ :size គីឡូបៃ។',
        'numeric' => ':attribute ត្រូវតែ :size។',
        'string' => ':attribute ត្រូវតែមាន :size តួអក្សរ។',
    ],
    'starts_with' => ':attribute ត្រូវតែចាប់ផ្តើមដោយ៖ :values។',
    'string' => ':attribute ត្រូវតែជាខ្សែអក្សរ។',
    'timezone' => ':attribute ត្រូវតែជាតំបន់ពេលវេលាត្រឹមត្រូវ។',
    'unique' => ':attribute ត្រូវបានគេប្រើរួចហើយ។',
    'uploaded' => ':attribute បានបរាជ័យក្នុងការផ្ទុកឡើង។',
    'uppercase' => ':attribute ត្រូវតែជាអក្សរធំ។',
    'url' => ':attribute ត្រូវតែជាភាពត្រឹមត្រូវរបស់ URL។',
    'ulid' => ':attribute ត្រូវតែជាផ្នែក ULID ត្រឹមត្រូវ។',
    'uuid' => ':attribute ត្រូវតែជាផ្នែក UUID ត្រឹមត្រូវ។',

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
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader-friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
