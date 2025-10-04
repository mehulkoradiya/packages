<?php

namespace Vendor\NurseryManagementSystem\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Vendor\NurseryManagementSystem\Models\ParentGuardian;
use Vendor\NurseryManagementSystem\Models\Student;
use Vendor\NurseryManagementSystem\Models\Classroom;

use Vendor\NurseryManagementSystem\Models\CommBatch;
use Vendor\NurseryManagementSystem\Models\CommRecipient;
use Vendor\NurseryManagementSystem\Jobs\DispatchCommunicationBatch;

class CommunicationController extends BaseController
{
    public function index()
    {
        $batches = CommBatch::orderByDesc('id')->paginate(20);
        return view('nms::communications.index', compact('batches'));
    }

    public function create()
    {
        $classrooms = Classroom::orderBy('name')->get();
        return view('nms::communications.create', compact('classrooms'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'channel' => 'required|in:email,sms,whatsapp',
            'subject' => 'nullable|string|max:255',
            'content' => 'required|string',
            'classroom_id' => 'nullable|exists:nms_classrooms,id',
        ]);

        $query = ParentGuardian::query();
        if ($data['classroom_id'] ?? null) {
            $query->whereHas('students', fn($q) => $q->where('classroom_id', $data['classroom_id']));
        }
        $parents = $query->get();

        $batch = CommBatch::create([
            'channel' => $data['channel'],
            'subject' => $data['subject'] ?? null,
            'content' => $data['content'],
            'total' => $parents->count(),
        ]);

        foreach ($parents as $parent) {
            CommRecipient::create([
                'batch_id' => $batch->id,
                'parent_id' => $parent->id,
                'status' => 'queued',
            ]);
        }

        DispatchCommunicationBatch::dispatch($batch);

        return redirect()->route('nms.comm.show', $batch)->with('success', 'Batch queued');
    }

    public function show(CommBatch $batch)
    {
        $recipients = $batch->recipients()->orderBy('id')->paginate(50);
        return view('nms::communications.show', compact('batch', 'recipients'));
    }
}
