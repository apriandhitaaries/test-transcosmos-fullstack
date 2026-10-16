<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskAttachment extends Model
{
    /** @use HasFactory<\Database\Factories\TaskAttachmentFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['task_id', 'file_name', 'file_path', 'file_size', 'mime_type'];
}
