<?php



return [

    'app' => [



        'elements' => [

            [

                'type' => 'text', // input fields type

                'data' => 'string', // data type, string, int, boolean

                'name' => 'itz_supplier_charge', // unique name for field

                'label' => 'iTradeBulk™ Supplier Charge(%)', // you know what label it is
                // 'label' => 'ITZ Supplier Charge(%)', // you know what label it is

                'rules' => 'required|min:1|max:50', // validation rule of laravel

                // 'class' => 'w-auto px-2', // any class for input

                'value' => '' // default value if you want

            ],

            [

                'type' => 'text', // input fields type

                'data' => 'string', // data type, string, int, boolean

                'name' => 'itz_transporter_charge', // unique name for field

                'label' => 'iTradeBulk™ Transporter Charge(%)', // you know what label it is
                // 'label' => 'ITZ Transporter Charge(%)', // you know what label it is

                'rules' => 'required|min:1|max:50', // validation rule of laravel

                // 'class' => 'w-auto px-2', // any class for input

                'value' => '' // default value if you want

            ],
            [

                'type' => 'text', // input fields type

                'data' => 'string', // data type, string, int, boolean

                'name' => 'itz_courier_charge', // unique name for field

                'label' => 'iTradeBulk™ Courier Charge(%)', // you know what label it is
                // 'label' => 'ITZ Transporter Charge(%)', // you know what label it is

                'rules' => 'required|min:1|max:50', // validation rule of laravel

                // 'class' => 'w-auto px-2', // any class for input

                'value' => '' // default value if you want

            ]


        ]

    ],

];
