<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePollRequest;
use App\Http\Requests\UpdatePollRequest;
use App\Http\Resources\PollResource;
use App\Http\Resources\PollSimpleResource;
use App\Models\Poll;
use App\Models\PollVote;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PollController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Poll::query();

        // Search by title if search parameter is provided
        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $polls = $query->latest()->paginate(10);
        return $this->paginatedResponse($polls, PollResource::collection($polls), 'Polls retrieved successfully');
    }

    /**
     * Get active polls offering only question and options.
     */
    public function active()
    {
        $polls = Poll::where('status', 'in_progress')
            ->where(function($q) {
                $q->whereNull('start_at')->orWhere('start_at', '<=', now());
            })
            ->where(function($q) {
                $q->whereNull('end_at')->orWhere('end_at', '>=', now());
            })
            ->with('currentUserVote')
            ->latest()
            ->get();

        return $this->successResponse(PollSimpleResource::collection($polls), 'Active polls retrieved successfully');
    }

    /**
     * Get ended polls with full results.
     */
    public function ended()
    {
        $polls = Poll::where(function($q) {
            $q->where('status', 'ended')
              ->orWhere(function($sq) {
                  $sq->whereNotNull('end_at')->where('end_at', '<', now());
              });
        })
        ->with('currentUserVote')
        ->latest()->paginate(10);

        return $this->paginatedResponse($polls, PollResource::collection($polls), 'Ended polls retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePollRequest $request)
    {
        $data = $request->validated();

        // Initialize options with 0 votes if provided as a simple array
        if (isset($data['options']) && array_is_list($data['options'])) {
            $options = [];
            foreach ($data['options'] as $option) {
                $options[$option] = 0;
            }
            $data['options'] = $options;
        }

        $poll = Poll::create($data);
        return $this->successResponse(new PollResource($poll), 'Poll created successfully', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Poll $poll)
    {
        return $this->successResponse(new PollResource($poll), 'Poll retrieved successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePollRequest $request, Poll $poll)
    {
        $poll->update($request->validated());
        return $this->successResponse(new PollResource($poll), 'Poll updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Poll $poll)
    {
        $poll->delete();
        return $this->successResponse(null, 'Poll deleted successfully');
    }

    public function vote(Request $request, Poll $poll)
    {
        $request->validate([
            'option' => 'required|string',
        ]);

        $user = $request->user();
        $option = $request->option;

        if ($poll->status !== 'in_progress') {
            return $this->errorResponse('This poll is not currently active.', 400);
        }

        $now = now();
        if (($poll->start_at && $now->lt($poll->start_at)) || ($poll->end_at && $now->gt($poll->end_at))) {
            return $this->errorResponse('This poll is closed.', 400);
        }

        $currentOptions = $poll->options;
        if (!array_key_exists($option, $currentOptions)) {
            return $this->errorResponse('Invalid option selected.', 400);
        }

        $existingVote = PollVote::where('poll_id', $poll->id)
            ->where('user_id', $user->id)
            ->first();

        DB::transaction(function () use ($poll, $user, $option, $currentOptions, $existingVote) {
            if ($existingVote) {
                // If same option, nothing to do
                if ($existingVote->option === $option) {
                    return;
                }

                // Decrement old option count
                if (isset($currentOptions[$existingVote->option])) {
                    $currentOptions[$existingVote->option] = max(0, $currentOptions[$existingVote->option] - 1);
                }

                // Increment new option count
                $currentOptions[$option]++;

                // Update vote
                $existingVote->update(['option' => $option]);

                // Update poll options (votes_count remains unchanged)
                $poll->update(['options' => $currentOptions]);
            } else {
                // New vote
                PollVote::create([
                    'poll_id' => $poll->id,
                    'user_id' => $user->id,
                    'option' => $option,
                ]);

                $currentOptions[$option]++;

                $poll->update([
                    'options' => $currentOptions,
                    'votes_count' => $poll->votes_count + 1
                ]);
            }
        });

        return $this->successResponse(null, $existingVote ? 'Vote updated successfully.' : 'Vote cast successfully.');
    }
}
