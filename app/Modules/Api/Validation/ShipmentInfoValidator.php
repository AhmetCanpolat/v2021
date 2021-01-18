<?php namespace App\Modules\Api\Validation;

use App\Base\Abstracts\DecoratedValidator;

class ShipmentInfoValidator extends DecoratedValidator
{
    use ShipmentInfoRules {
        rules as traitRules;
    }

    public function rules(): array
    {
        return array_merge([
            'shippingCompany' => 'required|string|min:2|max:5|exists:shipping_companies,code',
        ], $this->traitRules());
    }

}
