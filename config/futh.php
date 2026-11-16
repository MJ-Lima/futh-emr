<?php

/*
|--------------------------------------------------------------------------
| FUTH Lafia EMR — Cashier / Billing configuration
|--------------------------------------------------------------------------
|
| Default fees used when the auto-drop cashier service cannot find a
| matching service in the catalog. These are last-resort fallbacks —
| the system always tries to look up the actual fee from the
| appointment_types.default_fee or services.standard_price first.
|
*/

return [

    'cashier' => [
        /*
         * Default consultation fee (₦) when no matching Service or
         * AppointmentType is found. Set to 0 to suppress auto-drop
         * for consultations that have no configured fee.
         */
        'default_consultation_fee' => env('FUTH_DEFAULT_CONSULTATION_FEE', 0),

        /*
         * Default nursing service fee (₦) when no matching nursing
         * Service is found in the catalog. Set to 0 to suppress.
         */
        'default_nursing_fee' => env('FUTH_DEFAULT_NURSING_FEE', 0),
    ],

];
