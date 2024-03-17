<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Source extends Model
{
    use HasFactory;

    public const TABLE = 'sources';

    protected $table = self::TABLE;

    // Константы для названий полей
    public const FIELD_NAME = 'name';
    public const FIELD_SOURCE_ID = 'source_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        self::FIELD_NAME,
        self::FIELD_SOURCE_ID,
    ];

    public function getId(): int
    {
        return $this->id;
    }

    public function setName(?string $name): self
    {
        $this->{self::FIELD_NAME} = $name;
        return $this;
    }

    public function setSourceId(?string $sourceId): self
    {
        $this->{self::FIELD_SOURCE_ID} = $sourceId;
        return $this;
    }

    public function news(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(News::class);
    }

    public function scopeFindBySourceId($query, $source_id)
    {
        return $query->where(self::FIELD_SOURCE_ID, $source_id)->first();
    }

    public function scopeFindByName($query, $name)
    {
        return $query->where(self::FIELD_NAME, $name)->first();
    }
}
