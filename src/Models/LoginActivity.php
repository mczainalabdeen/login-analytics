<?php
namespace LoginAnalytics\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginActivity extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id', 'ip_address', 'device_info', 'location',
        'login_time', 'successful_attempt', 'suspicious_score'
    ];
}
