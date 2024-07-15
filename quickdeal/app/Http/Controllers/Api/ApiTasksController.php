<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MyRequest;
use App\Model\Clas;
use App\Model\Lecture;
use App\Model\Student;
use App\Model\Task;
use App\Services\MyServicer;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;

class ApiTasksController extends Controller
{
    public function all(MyRequest $request) : JsonResponse
    {
        return Response::json( Task::all()->makeHidden(['created_at', 'updated_at']) );
    }

    public function getone(MyRequest $request) : JsonResponse
    {
        $task = Task::findOrFail($request->id);
        $response = [
            'title' => $task->title,
            'description' => $task->description,
            'status' => $task->status,
            'deadline' => Carbon::parse($task->deadline)->format('d.m.Y H:i:s'),
        ];
        return Response::json( $response );
    }

    public function new(MyRequest $request) : JsonResponse
    {
        $data = $request->isValid(MyRequest::$validationRules['tasks']['create']);
        if (!empty($data['is_valid'])) {
            return Response::json( $data['is_valid'] );
        }

        try {
            $resp = Task::makeNew($data['data']['title'], $data['data']['description'], $data['data']['status'], $data['data']['deadline']);
        } catch (\ErrorException $ex) {
            throw new \Exception($ex->getMessage(), $ex->getCode(), $ex->getPrevious());
        }

        return Response::json( $resp );
    }

    public function update(MyRequest $request) : JsonResponse
    {
        $data = $request->isValid(MyRequest::$validationRules['tasks']['update']);
        if (!empty($data['is_valid'])) {
            return Response::json( $data['is_valid'] );
        }

        try {
            $task = Task::findOrFail($data['data']['id']);
            $resp = $task->updateMe($data['data']);
        } catch (\ErrorException $ex) {
            throw new \Exception($ex->getMessage(), $ex->getCode(), $ex->getPrevious());
        }

        return Response::json( $resp );
    }

    public function delete(MyRequest $request) : JsonResponse
    {
        $data = $request->isValid(MyRequest::$validationRules['tasks']['delete']);
        if (!empty($data['is_valid'])) {
            return Response::json( $data['is_valid'] );
        }

        try {
            $task = Task::findOrFail($data['data']['id']);
            $task->delete();
        } catch (\ErrorException $ex) {
            throw new \Exception($ex->getMessage(), $ex->getCode(), $ex->getPrevious());
        }

        return Response::json( ['success' => 1] );
    }

    public function search(MyRequest $request) : JsonResponse
    {
        $resp = [];
        $tasks = '';

        if ($request->get('status')) {
            $tasks = Task::where('status', '=', $request->get('status'));
        }
        if ($request->get('deadline')) {
            if (!$tasks) {
                $tasks = Task::where('deadline', '>=', Carbon::parse( $request->get('deadline') )->format('Y-m-d') . ' 00:00:00' )
                    ->where('deadline', '<=', Carbon::parse( $request->get('deadline') )->format('Y-m-d') . ' 23:59:59' );
            } else {
                $tasks->where('deadline', '>=', Carbon::parse( $request->get('deadline') )->format('Y-m-d') . ' 00:00:00' )
                    ->where('deadline', '<=', Carbon::parse( $request->get('deadline') )->format('Y-m-d') . ' 23:59:59' );
            }
        }
        if ($tasks) $resp = $tasks->get();
        return Response::json( $resp );
    }
}