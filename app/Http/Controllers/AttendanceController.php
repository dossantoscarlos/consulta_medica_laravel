<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAttendanceRequest;
use App\Http\Requests\UpdateAttendanceRequest;
use App\Models\Attendance;
use Illuminate\Http\JsonResponse;

class AttendanceController extends Controller
{
    public function  __construct(private Attendance $attendance)
    {
        
    }
    public function index(): JsonResponse
    {
        return response()->json($this->attendance->all()->paginate(20, ['*'], 'page'), JsonResponse::HTTP_OK);
    }

    public function store(StoreAttendanceRequest $request): JsonResponse
    {
        $attendance = $this->attendance->createOrFail($request->all());

        return response()
            ->json($attendance, JsonResponse::HTTP_CREATED)
            ->header('Location', url("/api/attendance/{$attendance->id}"));
    }

    public function show(Attendance $attendance): JsonResponse
    {
        return response()->json(['attendance' => $attendance]);
    }

    public function update(UpdateAttendanceRequest $request, Attendance $attendance): JsonResponse
    {
        $attendance->updateOrFail($request->all());
        $attendance->saveOrFail();
        return response()->json([
            'attendance' => $attendance,
            'status' => 'Atualizado com sucesso',
        ]);
    }

    public function destroy(Attendance $attendance): JsonResponse
    {
        $attendance->deleteOrFail();
        return response()->statusCode(JsonResponse::HTTP_NO_CONTENT);
    }
}
