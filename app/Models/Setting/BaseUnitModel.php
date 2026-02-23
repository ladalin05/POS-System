<?php


namespace App\Models\Setting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Setting\Unit;

class BaseUnitModel extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'base_unit';

    protected $guarded = ['id'];

    protected $casts = [
        'numerator' => 'int',
        'is_active' => 'bool',
    ];

    // Relations
    public function fromUnit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'from_unit_id');
    }

    public function toUnit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'to_unit_id');
    }

    // Scopes
    public function scopeActive($q) { return $q->where('is_active', true); }

    public function scopeBetween($q, int $fromUnitId, int $toUnitId)
    {
        return $q->where('from_unit_id', $fromUnitId)
                 ->where('to_unit_id', $toUnitId);
    }

    public function scopeFrom($q, int $fromUnitId) { return $q->where('from_unit_id', $fromUnitId); }
    public function scopeTo($q, int $toUnitId)     { return $q->where('to_unit_id', $toUnitId); }

    public function convert(int|float $qty): float
    {
        return $qty * $this->numerator;
    }
    public function asString(): string
    {
        $from = $this->fromUnit?->name ?? $this->from_unit_id;
        $to   = $this->toUnit?->name ?? $this->to_unit_id;
        return "1 {$from} = {$this->numerator} {$to}";
    }

    protected static function booted(): void
    {
        static::saving(function (self $model) {
            if ($model->from_unit_id === $model->to_unit_id) {
                throw new \RuntimeException('From/To unit must be different.');
            }
            if ($model->numerator < 1) {
                throw new \RuntimeException('Numerator must be >= 1.');
            }
        });
    }
}
