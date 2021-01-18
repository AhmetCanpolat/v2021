<?php namespace App\Base\Abstracts;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Validator as ValidatorFacade;

abstract class DecoratedValidator implements Validator
{
    protected $validator;

    public function __construct($data)
    {
        $this->validator = ValidatorFacade::make($data, $this->rules());
        $this->withValidator($this->validator);
    }

    abstract protected function rules(): array;
    abstract protected function withValidator(Validator $validator): void; 

    public function validated()
    {
        return $this->validator->validated();
    }

    public function errors()
    {
        return $this->validator->errors();
    }

    public function after($callback)
    {
        return $this->validator->after($callback);
    }

    public function getMessageBag()
    {
        return $this->validator->getMessageBag();
    }

    public function failed()
    {
        return $this->validator->failed();
    }

    public function fails()
    {
        return $this->validator->fails();
    }

    public function sometimes($attribute, $rules, callable $callback)
    {
        return $this->validator->sometimes($attribute, $rules, $callback);
    }

    public function validate()
    {
        return $this->validator->validate();
    }

}
