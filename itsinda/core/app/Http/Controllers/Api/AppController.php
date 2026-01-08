<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\User;
use Illuminate\Http\Request;

class AppController extends Controller
{
    public function generalSetting()
    {
        $notify[] = 'General setting data';
        return response()->json([
            'remark' => 'general_setting',
            'status' => 'success',
            'message' => ['success' => $notify],
            'data' => [
                'general_setting' => gs(),
                'social_login_redirect' => route('user.social.login.callback', ''),
            ],
        ]);
    }

    public function getCountries()
    {
        $countryData = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $notify[] = 'Country List';
        foreach ($countryData as $k => $country) {
            $countries[] = [
                'country' => $country->country,
                'dial_code' => $country->dial_code,
                'country_code' => $k,
            ];
        }
        return response()->json([
            'remark' => 'country_data',
            'status' => 'success',
            'message' => ['success' => $notify],
            'data' => [
                'countries' => $countries,
            ],
        ]);
    }

    public function getLanguage($code)
    {
        $languages = Language::get();
        $languageCodes = $languages->pluck('code')->toArray();

        if (!in_array($code, $languageCodes)) {
            $notify[] = 'Invalid code given';
            return response()->json([
                'remark' => 'validation_error',
                'status' => 'error',
                'message' => ['error' => $notify]
            ]);
        }

        $jsonFile = file_get_contents(resource_path('lang/' . $code . '.json'));

        $notify[] = 'Language';
        return response()->json([
            'remark' => 'language',
            'status' => 'success',
            'message' => ['success' => $notify],
            'data' => [
                'languages' => $languages,
                'file' => json_decode($jsonFile) ?? [],
                'image_path' => getFilePath('language')
            ],
        ]);
    }

    public function policies()
    {
        $policies = getContent('policy_pages.element', orderById: true);
        $notify[] = 'All policies';
        return response()->json([
            'remark' => 'policy_data',
            'status' => 'success',
            'message' => ['success' => $notify],
            'data' => [
                'policies' => $policies,
            ],
        ]);
    }


    public function faq()
    {
        $faq = getContent('faq.element', orderById: true);
        $notify[] = 'FAQ';
        return response()->json([
            'remark' => 'faq',
            'status' => 'success',
            'message' => ['success' => $notify],
            'data' => [
                'faq' => $faq,
            ],
        ]);
    }

    public function kycContent()
    {
        $kyc = getContent('kyc.content', true);
        $notify[] = 'KYC content';
        return response()->json([
            'remark' => 'kyc',
            'status' => 'success',
            'message' => ['success' => $notify],
            'data' => [
                'kyc' => $kyc,
            ],
        ]);
    }

    public function checkPhone(Request $request)
    {
        $request->validate([
            'mobile' => 'required|string'
        ]);

        $mobile = $request->mobile;
        
        // Clean the phone number (remove spaces, dashes, etc.)
        $mobile = preg_replace('/[^0-9+]/', '', $mobile);
        
        // Check if user exists with this mobile number
        $user = User::where('mobile', $mobile)
                    ->orWhere('mobile', 'LIKE', '%' . substr($mobile, -9))
                    ->first();

        if ($user) {
            $userData = [
                'id' => $user->id,
                'firstname' => $user->firstname,
                'lastname' => $user->lastname,
                'username' => $user->username,
                'mobile' => $user->mobile,
                'account_number' => $user->account_number,
                'balance' => getAmount($user->balance)
            ];

            // Include branch information if user is assigned to a branch
            if ($user->branch_id && $user->branch) {
                $userData['branch'] = [
                    'id' => $user->branch->id,
                    'name' => $user->branch->name,
                    'code' => $user->branch->code,
                    'address' => $user->branch->address,
                    'mobile' => $user->branch->mobile,
                    'email' => $user->branch->email
                ];
            }

            return response()->json([
                'remark' => 'phone_found',
                'status' => 'success',
                'message' => ['success' => ['Phone number is registered']],
                'data' => [
                    'registered' => true,
                    'user' => $userData
                ]
            ]);
        }

        return response()->json([
            'remark' => 'phone_not_found',
            'status' => 'error',
            'message' => ['error' => ['Phone number is not registered']],
            'data' => [
                'registered' => false
            ]
        ]);
    }
}
