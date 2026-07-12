<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\AgentProfile;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AgentProfileController extends Controller
{
    public function index(): View
    {
        $agents = AgentProfile::query()
            ->with(['user', 'agency'])
            ->latest()
            ->paginate(15);

        return view('Admin.agent_profiles.index', compact('agents'));
    }

    public function create(): View
    {
        return view('Admin.agent_profiles.create', $this->formData(new AgentProfile()));
    }

    public function store(Request $request): RedirectResponse
    {
        AgentProfile::create($this->validatedData($request));

        return redirect()->route('admin.agent-profiles.index')->with('status', 'Agent profile created successfully.');
    }

    public function edit(AgentProfile $agentProfile): View
    {
        return view('Admin.agent_profiles.edit', $this->formData($agentProfile));
    }

    public function update(Request $request, AgentProfile $agentProfile): RedirectResponse
    {
        $agentProfile->update($this->validatedData($request, $agentProfile));

        return redirect()->route('admin.agent-profiles.index')->with('status', 'Agent profile updated successfully.');
    }

    public function destroy(AgentProfile $agentProfile): RedirectResponse
    {
        $agentProfile->delete();

        return redirect()->route('admin.agent-profiles.index')->with('status', 'Agent profile deleted successfully.');
    }

    private function formData(AgentProfile $agentProfile): array
    {
        return [
            'agentProfile' => $agentProfile,
            'users' => User::query()->orderBy('name')->get(),
            'agencies' => Agency::query()->where('status', 'active')->orderBy('name')->get(),
        ];
    }

    private function validatedData(Request $request, ?AgentProfile $agentProfile = null): array
    {
        return $request->validate([
            'user_id' => ['required', 'exists:users,id', Rule::unique('agent_profiles', 'user_id')->ignore($agentProfile)],
            'agency_id' => ['nullable', 'exists:agencies,id'],
            'designation' => ['nullable', 'string', 'max:150'],
            'license_no' => ['nullable', 'string', 'max:100'],
            'bio' => ['nullable', 'string'],
            'experience_years' => ['nullable', 'integer', 'min:0', 'max:100'],
            'service_area' => ['nullable', 'string', 'max:255'],
            'rating' => ['nullable', 'numeric', 'min:0', 'max:5'],
            'status' => ['required', 'string', 'max:50'],
        ]);
    }
}
