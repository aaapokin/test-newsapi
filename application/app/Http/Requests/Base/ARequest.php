<?php

namespace App\Http\Requests\Base;


use App\Http\Resources\Message422ByValidatorResponse;
use App\Http\Resources\Message422Response;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;

abstract class ARequest implements IRequest
{

    protected function rules(): array
    {
        return [];
    }

    protected function messages(): array
    {
        return [];
    }
    protected function customAttributes(): array
    {
        return [];
    }

    public function __construct()
    {
        try {
            $this->validate();
            $this->validateAfter();
        } catch (ERequestValidate $e) {
            $this->sendErrorMessage('error', $e->getMessage());
        }
    }
    protected function validate(): void
    {
        $validator = Validator::make(Request::all(), $this->rules(),$this->messages(), $this->customAttributes());
        if($validator->fails()){
            throw new HttpResponseException(new Message422ByValidatorResponse($validator));
        }
    }

    protected function validateAfter(): void
    {
    }

    public function sendErrorMessage(string $name, string $message): void
    {
        throw new HttpResponseException(new Message422Response($name,$message));
    }
}
