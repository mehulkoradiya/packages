<?php

namespace Vendor\NurseryManagementSystem\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Vendor\NurseryManagementSystem\Models\Invoice;
use Vendor\NurseryManagementSystem\Models\InvoiceItem;
use Vendor\NurseryManagementSystem\Models\Student;

class InvoiceController extends BaseController
{
    public function index()
    {
        $invoices = Invoice::with('student')->orderByDesc('issue_date')->paginate(15);
        return view('nms::invoices.index', compact('invoices'));
    }

    public function create()
    {
        $students = Student::orderBy('last_name')->get();
        return view('nms::invoices.create', compact('students'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'student_id' => 'required|exists:nms_students,id',
            'issue_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:issue_date',
            'currency' => 'required|string|size:3',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
        ]);

        $invoice = new Invoice();
        $invoice->student_id = $data['student_id'];
        $invoice->issue_date = $data['issue_date'];
        $invoice->due_date = $data['due_date'];
        $invoice->currency = $data['currency'];
        $invoice->number = 'INV-'.now()->format('Ymd-His').'-'.rand(100, 999);

        $subtotal = 0;
        $itemsToCreate = [];
        foreach ($data['items'] as $item) {
            $line = $item['quantity'] * $item['unit_price'];
            $subtotal += $line;
            $itemsToCreate[] = new InvoiceItem([
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'line_total' => $line,
            ]);
        }

        $invoice->subtotal = $subtotal;
        $invoice->discount = $data['discount'] ?? 0;
        $invoice->tax = $data['tax'] ?? 0;
        $invoice->total = max(0, $subtotal - $invoice->discount + $invoice->tax);
        $invoice->status = 'unpaid';
        $invoice->save();
        $invoice->items()->saveMany($itemsToCreate);

        return redirect()->route('invoices.index')->with('success', 'Invoice created');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load('student', 'items', 'payments');
        return view('nms::invoices.show', compact('invoice'));
    }

    public function sendInvoice(Invoice $invoice)
    {
        // Stub: in a real app, dispatch job to send invoice via email/sms
        return back()->with('success', 'Invoice dispatched to queue');
    }
}
