<?php

namespace App\Validators\Base;


use App\Payloads\Base\BadPayloads\Bad400;
use App\Payloads\Base\BadPayloads\Bad404;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Validator;

abstract class AValidator implements IValidator
{
    protected array $errors = [];
    protected bool $isValid = true;

    public function __construct(protected array $data = [])
    {
        $isHTTP = false;
        if ((!app()->runningInConsole() || !(Request::method() === "GET" && Request::path() === "/"))
            && !$data) {
            $this->data = Request::all();
            $isHTTP = true;
        }
        try {
            $validator = \Illuminate\Support\Facades\Validator::make(
                $this->data,
                $this->rules(),
                $this->messages(),
                $this->attributes()
            );
            $errors = $validator->errors();
            if ($errors->isNotEmpty()) {
                $this->addErrorsByValidator($validator);
                throw new EValidate();
            }
            if (!$this->validate()) {
                throw new EValidate();
            }
        } catch (EValidate $e) {
            $this->isValid = false;
            if ($isHTTP) {
                $this->response();
            }
        } catch (E404 $e) {
            $this->response404();
        }
    }

    /**
     * @throw ERequestValidate
     * @throw E404
     */
    protected function validate(): bool
    {
        return true;
    }

    protected function rules(): array
    {
        return [];
    }

    protected function messages(): array
    {
        return [];
    }


    protected function attributes(): array
    {
        return [];
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getErrorsJson(): string
    {
        return json_encode($this->errors, JSON_UNESCAPED_UNICODE);
    }

    public function isValid(): bool
    {
        return $this->isValid;
    }

    /** @example ["phone" =>  [ "текст ошибки"]] */
    protected function addErrors(array $errors): void
    {
        $this->errors = array_merge($this->errors, $errors);
    }

    protected function addErrorsByValidator(Validator $validator): void
    {
        $this->addErrors($validator->errors()->toArray());
    }

    protected function get(string $key): mixed
    {
        return Arr::get($this->data, $key);
    }

    protected function has(string $key): bool
    {
        return Arr::has($this->data, $key);
    }

    public function addErrorMessage(string $name, string $message): void
    {
        $this->addErrors([$name => [$message]]);
    }

    public function sendErrorMessage(string $name, string $message): void
    {
        $this->addErrors([$name => [$message]]);
        $this->response();
    }

    protected function response(): void
    {
        throw new HttpResponseException(
            (new Bad400($this->errors))
                ->setDocumentation($this->getDocumentation())
                ->log()
                ->toJsonResponse()
        );
    }

    protected function response404(): void
    {
        throw new HttpResponseException(
            (new Bad404())
                ->setDocumentation($this->getDocumentation())
                ->log()
                ->toJsonResponse()
        );
    }
}
