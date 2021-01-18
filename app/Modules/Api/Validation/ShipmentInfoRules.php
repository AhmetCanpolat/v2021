<?php namespace App\Modules\Api\Validation;

use Illuminate\Contracts\Validation\Validator;

trait ShipmentInfoRules
{

    public function rules(): array
    {
        return array_merge(
            $this->contactRules('sender'),
            $this->contactRules('receiver'),
            $this->contactRules('from', true),
            [
                'trackingCode' => 'required|string',
                'package.weightInKg' => 'required|string|numeric',
            ]
        );
    }

    private function contactRules(string $prefix, bool $isOptional = false): array
    {
        return [

            $prefix . '.title' => 'sometimes|string',
            $prefix . '.firstName' => $isOptional ? 'required_with:' . $prefix . '|string' : 'required|string',
            $prefix . '.lastName' => $isOptional ? 'required_with:' . $prefix . '|string' : 'required|string',
            $prefix . '.suffix' => 'sometimes|string',
            $prefix . '.companyName' => 'sometimes|string',
            $prefix . '.companyBranch' => 'sometimes|string',

            $prefix . '.isBusiness' => 'sometimes|string|in:true,false',

            $prefix . '.countryCode' => ($isOptional ? 'required_with:' . $prefix . '|string' : 'required|string') . '|exists:countries,code',
            $prefix . '.postalCode' => ($isOptional ? 'required_with:' . $prefix . '|string' : 'required|string') . '|postal_code_for:' . $prefix . '.countryCode',
            $prefix . '.cityName' => $isOptional ? 'required_with:' . $prefix . '|string' : 'required|string',
            $prefix . '.streetName' => $isOptional ? 'required_with:' . $prefix . '|string' : 'required|string',
            $prefix . '.districtName' => $isOptional ? 'required_with:' . $prefix . '|string' : 'sometimes|string',
            $prefix . '.extraLine' => 'sometimes|string',
            $prefix . '.streetNumber' => 'sometimes|string',
            $prefix . '.address' => 'sometimes|string',
            $prefix . '.building' => 'sometimes|string',

            $prefix . '.emailAdress' => 'sometimes|string',
            $prefix . '.phoneNumber' => 'sometimes|string',

            $prefix . '.taxNumber' => 'sometimes|string',
            $prefix . '.eori' => 'sometimes|string',
        ];

    }

    protected function withValidator(Validator $validator): void
    {}

}
