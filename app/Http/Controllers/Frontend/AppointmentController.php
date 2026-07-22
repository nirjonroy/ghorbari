<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Property;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class AppointmentController extends Controller
{
    public function store(Request $request, Property $property): JsonResponse|RedirectResponse
    {
        abort_unless(Schema::hasTable('appointments'), 404);

        $data = $request->validate([
            'tour_type' => ['required', Rule::in(['in_person', 'video_chat'])],
            'first_name' => ['nullable', 'string', 'max:100'],
            'last_name' => ['nullable', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:30'],
            'message' => ['nullable', 'string', 'max:1000'],
        ]);

        $note = collect([
            trim(collect([$data['first_name'] ?? null, $data['last_name'] ?? null])->filter()->join(' ')),
            $data['email'] ?? null,
            $data['phone'] ?? null,
            $data['message'] ?? null,
        ])->filter()->join("\n");

        Appointment::query()->create([
            'user_id' => $request->user()->id,
            'property_id' => $property->id,
            'agent_profile_id' => $property->agent_profile_id,
            'tour_type' => $data['tour_type'],
            'status' => 'pending',
            'note' => $note ?: null,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Tour request submitted.',
            ]);
        }

        return back()->with('success', 'Tour request submitted.');
    }
}
