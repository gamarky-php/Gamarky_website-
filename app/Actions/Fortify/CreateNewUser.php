<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email:rfc,dns', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'country' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'min:8', 'max:20', 'regex:/^\+?[0-9]{8,20}$/'],
            'activity_type' => ['required', 'string', 'in:import,export,manufacturing,broker,containers,agent'],
            'business_sector' => ['required', 'string', 'max:100'],
            'newsletter' => ['sometimes', 'boolean'],
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ], [
            'name.required' => 'الاسم الكامل مطلوب',
            'name.max' => 'الاسم يجب ألا يتجاوز 255 حرف',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'يرجى إدخال بريد إلكتروني صحيح',
            'email.unique' => 'هذا البريد الإلكتروني مسجل مسبقاً',
            'password.required' => 'كلمة المرور مطلوبة',
            'password.confirmed' => 'كلمة المرور غير متطابقة',
            'password.min' => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',
            'country.required' => 'الدولة مطلوبة',
            'country.max' => 'اسم الدولة يجب ألا يتجاوز 100 حرف',
            'phone.required' => 'رقم الهاتف مطلوب',
            'phone.regex' => 'رقم الهاتف غير صحيح، يجب أن يبدأ بـ + متبوعاً بالأرقام',
            'phone.min' => 'رقم الهاتف يجب أن يكون 8 أرقام على الأقل',
            'phone.max' => 'رقم الهاتف يجب ألا يتجاوز 20 رقم',
            'activity_type.required' => 'نوع النشاط مطلوب',
            'activity_type.in' => 'نوع النشاط المحدد غير صحيح',
            'business_sector.required' => 'مجال النشاط مطلوب',
            'business_sector.max' => 'مجال النشاط يجب ألا يتجاوز 100 حرف',
        ])->validate();

        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'country' => $input['country'],
            'phone' => $input['phone'],
            'activity_type' => $input['activity_type'],
            'business_sector' => $input['business_sector'],
            'newsletter' => isset($input['newsletter']) && $input['newsletter'] ? true : false,
        ]);
    }
}
