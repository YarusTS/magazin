<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int    $id
 * @property string $name
 * @property string $image
 * @property string $description
 * @property string $content
 * @property int    $price
 * @property string $article
 * @property string $quantity
 * @property string $created_at
 * @property string $updated_at
 */

class Product extends Model
{
    use HasFactory;

    public function Basket(): BelongsToMany
    {
        return $this->belongsToMany(Basket::class)->withPivot('quantity');
    }

    public function categories():BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }
}
