<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Set
 *
 * @property string $id
 * @property string $code
 * @property string $name
 * @property Collection $templates
 *
 * @method int getKey()
 *
 */
class Set extends Model
{
    /** @use HasFactory<\Database\Factories\SetFactory> */
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'code',
        'name',
    ];

    public function getRouteKeyName(): string
    {
        return 'code';
    }

    public function templates(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Template::class);
    }
}
