<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\Rule;

class ValidPhoneRule implements Rule{

    public function passes($attribute, $value){
        return preg_match('/^01[0125]\d{8}$/', $value);
    }

    public function message(){
        return 'يجب أن يكون رقم الهاتف صالحًا, لابد أن يبدأ بالرقم 01 ثم 0,1,2,5 ويتبعه 8 أرقام.';
    }
}