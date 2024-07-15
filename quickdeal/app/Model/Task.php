<?php

namespace App\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Task extends Model
{
    protected $table = 'tasks';
    protected $fillable = ['title', 'description', 'status', 'deadline'];

    public static function makeNew(string $title, string $desc = '', int $status = 0, string $deadline = '') : array
    {
        try {
            $task = new Task;
            $task->title = $title;
            $task->description = $desc;
            $task->status = $status;
            $task->deadline = Carbon::parse($deadline)->toDateTime();
            $task->save();
        } catch (\Exception $exception) {
            return [ 'success' => 0, 'msg' => $exception->getMessage() ];
        }
        return ['success' => 1];
    }

    public function updateMe(array $data) : array
    {
        if (in_array('title', $data)) {
            $this->title = $data['title'];
        }
        if (in_array('description', $data)) {
            $this->description = $data['description'];
        }
        if (in_array('status', $data)) {
            $this->status = $data['status'];
        }
        if (in_array('deadline', $data)) {
            $this->deadline = Carbon::parse($data['deadline'])->toDateTime();
        }
        $this->save();
        return ['success' => 1];
    }
}