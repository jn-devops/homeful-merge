<?php

namespace App\Models;

use App\Models\CivilStatus;
use App\Models\Documents;
use App\Models\EmploymentStatus;
use App\Models\MarketSegment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentMatrix extends Model
{
    use HasFactory;
    protected $fillable = [
        'civil_status',
        'employment_status',
        'market_segment',
        'documents',
    ];

    protected $casts = [
        'documents' => 'array',
    ];


}
