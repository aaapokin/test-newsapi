<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    public const TABLE = 'news';

    protected $table = self::TABLE;

    public const FIELD_AUTHOR = 'author';
    public const FIELD_TITLE = 'title';
    public const FIELD_DESCRIPTION = 'description';
    public const FIELD_URL = 'url';
    public const FIELD_URL_TO_IMAGE = 'url_to_image';
    public const FIELD_PUBLISHED_AT = 'published_at';
    public const FIELD_CONTENT = 'content';
    public const FIELD_SOURCE_ID = 'source_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        self::FIELD_AUTHOR,
        self::FIELD_TITLE,
        self::FIELD_DESCRIPTION,
        self::FIELD_URL,
        self::FIELD_URL_TO_IMAGE,
        self::FIELD_PUBLISHED_AT,
        self::FIELD_CONTENT,
        self::FIELD_SOURCE_ID,
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function setAuthor(?string $author): self
    {
        $this->{self::FIELD_AUTHOR} = $author;
        return $this;
    }


    public function setTitle(?string $title): self
    {
        $this->{self::FIELD_TITLE} = $title;
        return $this;
    }


    public function setDescription(?string $description): self
    {
        $this->{self::FIELD_DESCRIPTION} = $description;
        return $this;
    }


    public function setUrl(?string $url): self
    {
        $this->{self::FIELD_URL} = $url;
        return $this;
    }


    public function setUrlToImage(?string $urlToImage): self
    {
        $this->{self::FIELD_URL_TO_IMAGE} = $urlToImage;
        return $this;
    }


    public function setPublishedAt(?string $publishedAt): self
    {
        $this->{self::FIELD_PUBLISHED_AT} = $publishedAt;
        return $this;
    }


    public function setContent(?string $content): self
    {
        $this->{self::FIELD_CONTENT} = $content;
        return $this;
    }

 
    public function setSourceId(?string $sourceId): self
    {
        $this->{self::FIELD_SOURCE_ID} = $sourceId;
        return $this;
    }


    /////////////////////////////// scopes /////////////////////////////////////
    public function scopeFindByUrl($query, $url)
    {
        return $query->where(self::FIELD_URL, $url);
    }

    public function scopeSearchByTitle($query, $title)
    {
        return $query->where(self::FIELD_TITLE, 'ILIKE', '%' . $title . '%');
    }

    public function scopeSearchBySourceName($query, $name)
    {
        return $query->join('sources', function ($join) use ($name) {
            $join->on('sources.id', '=', 'news.source_id')
                ->where('sources.name', 'ILIKE', "%" . $name . "%");
        });
    }

    public function scopeSearchByDateFrom($query, Carbon $date)
    {
        return $query->where('published_at', '>=', $date);
    }

    public function scopeSearchByDateTo($query, Carbon $date)
    {
        return $query->where('published_at', '<=', $date);
    }
    /////////////////////////////// scopes /////////////////////////////////////


    /////////////////////////////// relations /////////////////////////////////////
    public function source(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Source::class);
    }
    /////////////////////////////// relations /////////////////////////////////////

}
