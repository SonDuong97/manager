<?php

namespace App\Http\Resources\Staff;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Staff\TaskResource;

class TimesheetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'trouble' => $this->trouble,
            'plan_of_next_day' => $this->plan_of_next_day,
            'tasks' => TaskResource::collection($this->tasks),
        ];
    }
}
