<?php

namespace App\Infrastructure\APIExternal\Base\Responses;


use App\Infrastructure\APIExternal\Base\Requests\IRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

abstract class AResponse implements IResponse
{
    protected bool $isValid = true;
    protected bool $isSkip = false;

    public function __construct(protected IApiExternalResponse $response)
    {
        try {
            $this->validate();
            $this->setData();
        } catch (EResponseSkip $e) {
            $this->isSkip = true;
        } catch (EResponseValidate $e) {
            Log::error(get_class($this) . ' EResponseValidate ' . $e->getMessage(), [
                'payload' => $this->response->getPayload()
            ]);
            $this->isValid = false;
        }
    }

    protected function validate(): void
    {
    }

    protected function setData(): void
    {
    }

    protected function getByPath(string $key): mixed
    {
        return Arr::get($this->response->getPayload(), $key);
    }

    protected function hasByPath(string $key): bool
    {
        return Arr::has($this->response->getPayload(), $key);
    }

    /**
     * @throws EResponseValidate
     */
    protected function validateHasByPath(array $arrKey): void
    {
        foreach ($arrKey as $key) {
            if (!$this->hasByPath($key)) {
                throw EResponseValidate::notFound($key);
            }
        }
    }


    public function isValid(): bool
    {
        return $this->isValid;
    }

    public function isSkip(): bool
    {
        return $this->isSkip;
    }

    public function isOk(): bool
    {
        return $this->response->getStatus() === 200;
    }

    public function isEvent(): bool
    {
        return $this->response->getStatusEvent();
    }

    public function getError(): string
    {
        return $this->response->getErrorResponse();
    }

    public function getStatus(): int
    {
        return $this->response->getStatus();
    }

    public function getRequest(): IRequest
    {
        return $this->response->getRequest();
    }


}
