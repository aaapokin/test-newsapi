<?php

namespace App\Http\Requests;

use App\DTO\Requests\NewsRequestDTO;
use App\Http\Requests\Base\ARequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Request;

class NewsListRequest extends ARequest
{

    protected function rules():array
    {
        return [
            'source' => 'nullable|min:3',
            'from'   => 'nullable|date_format:' . \DateTimeInterface::ATOM,
            'to'     => 'nullable|date_format:' . \DateTimeInterface::ATOM,
            'title'  => 'nullable|min:3',
        ];
    }

    protected function messages():array
    {
        return [
            'from.date_format' => 'Возможно проблема с конвертацией символов. Неверно задано время. from - ATOM - '.Carbon::now()->toAtomString(),
            'to.date_format'   => 'Возможно проблема с конвертацией символов. Неверно задано время. to - ATOM - '.Carbon::now()->toAtomString(),
            'title.min'        => 'title минимум 3 символа',
            'source.min'       => 'source минимум 3 символа',
        ];
    }
    public function getDto():NewsRequestDTO
    {
        return new NewsRequestDTO(
            Request::input('source'),
            Request::input('from')?Carbon::parse(Request::input('from')):null,
            Request::input('title'),
            Request::input('to')?Carbon::parse(Request::input('to')):null,
        );
    }
}
