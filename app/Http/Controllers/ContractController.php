<?php

namespace App\Http\Controllers;

use App\Jobs\GeneratePdf;
use App\Mail\SendMail;
use App\Mail\SendPdfMail;
use App\Mail\SendNotification;
use App\Models\Category;
use App\Models\Contract;
use App\Models\ContractCopy;
use App\Models\ContractNotification;
use App\Models\ContractTemplate;
use App\Models\ContractUser;
use App\Models\Signature;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use SebastianBergmann\Template\Template;

class ContractController extends Controller
{
    public function index($status = null)
    {
        // return $status;
        $contracts = Contract::with(
            'contract_users:id,user_id,contract_id,status',
            'contract_users.user:id,name'
        )
            ->when($status, function ($q) use ($status) {
                $q->where('status', $status);
            })
            ->latest()->get();

        $categories = Category::get();
        return view('contracts.index', compact('contracts', 'categories'));
    }
    public function add()
    {
        $categories = Category::get();
        $users = User::where('id', '!=', auth()->user()->id)->get();
        $selectUsers = [...$users, auth()->user()];
        $templates = ContractTemplate::get();
        return view('contracts.add', compact('users', 'templates', 'categories', 'selectUsers'));
    }
    public function store(Request $request)
    {
        // return $request;
        // return $data = json_decode($request->details);

        $request->validate([
            'title' => 'required',
            'users' => 'required',
            'details' => 'required',
        ], [
            'details.required' => "Please Save Your Form Data"
        ]);

        // dd();
        $formData = json_decode($request->details);
        $category_id = null;
        if ($request->template) {
            $category_id = ContractTemplate::where('id', $request->template)->value('category_id');
        }
        $contract = Contract::create([
            'title' => $request->title,
            'category_id' => $category_id,
            'form_data' => $formData,
            'status' => 'pending',
            'added_by' => auth()->user()->id,
        ]);
        foreach ($request->users as $user) {
            ContractUser::create([
                'user_id' => $user,
                'contract_id' => $contract->id,
                'status' => 'pending',
            ]);
            ContractNotification::create([
                'content' => 'You are tagged in a new contract "' . $contract->title . '" by ' . auth()->user()->name . '.',
                'user_id' => $user
            ]);
            $user = User::find($user);
            $Data = [
                'title' => 'New Contract – Whitefield Estate Agents',
                'body' => 'You have been tagged in a new contract, ' . $contract->title . ', by Whitefield Estate Agents. Please visit paperless.whitefieldestateagents.co.uk and sign the form as soon as possible." '
            ];
            Mail::to($user->email)->send(new SendMail($Data));
        }

        ContractUser::create([
            'user_id' => auth()->user()->id,
            'contract_id' => $contract->id,
            'status' => 'pending',
        ]);
        ContractNotification::create([
            'content' => 'You have created a new contract "' . $contract->title . '" successfully.',
            'user_id' => auth()->user()->id
        ]);
        ContractCopy::create([
            'contract_id' => $contract->id,
            'category_id' => $category_id,
            'title' => $request->title,
            'form_data' => $formData,
            'added_by' => auth()->user()->id,
        ]);
        $categories = Category::get();
        $contracts = Contract::get();
        return redirect()->route('contract')->with('success', 'Contract Created Successfully', compact('categories', 'contracts'));
    }
    public function edit($id)
    {
        $categories = Category::get();
        $contract = Contract::with('contract_users:id,user_id,contract_id')->findOrFail($id);
        $contract->form_data = json_encode($contract->form_data);
        $users = User::where('id', '!=', auth()->user()->id)->get();
        $selectUsers = [...$users, auth()->user()];
        return view('contracts.edit', compact('contract', 'users', 'selectUsers'));
    }
    public function update(Request $request)
    {
        $request->validate([
            'title' => 'required',
        ]);
        $formData = json_decode($request->details);
        Contract::findOrFail($request->id)->update([
            'user_id' => $request->user,
            'title' => $request->title,
            'form_data' => $formData,
            'added_by' => auth()->user()->id,
        ]);
        ContractCopy::where('contract_id', $request->id)->update([
            'title' => $request->title,
            'form_data' => $formData,
            'added_by' => auth()->user()->id,
        ]);
        ContractUser::where('contract_id', $request->id)->delete();

        foreach ($request->users ?? [] as $user) {

            ContractUser::create([
                'user_id' => $user,
                'contract_id' => $request->id,
                'status' => 'pending',
            ]);
            ContractNotification::create([
                'content' => auth()->user()->name . ' have updated the contract "' . $request->title . '".',
                'user_id' => $user
            ]);
            $user = User::find($user);
            $Data = [
                'title' => 'Contract Update – Whitefield Estate Agents',
                'body' => 'The contract has been updated by the administrator. If you haven’t signed your section yet, please visit paperless.whitefieldestateagents.co.uk and sign the form as soon as possible.'
            ];
            Mail::to($user->email)->send(new SendMail($Data));
        }
        ContractUser::create([
            'user_id' => auth()->user()->id,
            'contract_id' => $request->id,
            'status' => 'pending',
        ]);
        ContractNotification::create([
            'content' => 'You have updated the contract "' . $request->title . '" successfully.',
            'user_id' => auth()->user()->id
        ]);
        $categories = Category::get();
        $contracts = Contract::get();
        return redirect()->route('contract')->with('success', 'Contract Updated Successfully', compact('categories', 'contracts'));
    }

    public function delete($id)
    {
        Contract::findOrFail($id)->delete();
        return back()->with('danger', 'Contract Record Deleted !!!');
    }
    public function view($id)
    {
        $contract = Contract::where('user_id', auth()->user()->id)
            ->where('contracts.id', $id)
            ->join('contract_users', 'contracts.id', 'contract_users.contract_id')
            ->select('contracts.*', 'contract_users.status')->first();
        if (!$contract) {
            abort(404);
        }
        // $contract = Contract::with('user:id,name')->findOrFail($id);
        $contract->form_data = json_encode($contract->form_data);
        // return $contract->form_data;
        return view('contracts.view-test', compact('contract'));
    }
    public function saveDetails(Request $request)
    {
        // dd(123);
        $data = json_decode($request->details);
        ContractUser::where('user_id', auth()->user()->id)
            ->where('contract_id', $request->id)
            ->update([
                'status' => 'filled',
            ]);

        $contractUsers = ContractUser::where('contract_id', $request->id)->get();
        $status = $contractUsers->where('status', 'filled')->count() == $contractUsers->count() ? "filled" : 'partially filled';
        $contract = Contract::findOrFail($request->id);
        $contract->update([
            'form_data' => $data,
            'status' => $status,
        ]);

        $user = User::find(auth()->user()->id)->name;
        $title = Contract::find($request->id)->title;

        $userIds = $contract->users()->pluck('user_id')->toArray();
        foreach ($userIds as $userId) {
            $userMail = User::findOrFail($userId)->email;
            if (auth()->user()->id == $contract->added_by) {
                ContractNotification::create([
                    'content' => "Contract $title is successfully Completed",
                    'user_id' => $userId
                ]);
                return redirect()->route('contract.print', ['id' => $request->id]);
            } else {
                if ($userMail == auth()->user()->email) {
                    $Data['title'] = 'Contract Update – You’ve Successfully Signed the Contract ';
                    $Data['body'] = 'Thank you for signing the "' . $title . '." Once all parties have signed the agreement, you will receive the final copy of the contract with everyone’s signatures.
If you haven’t signed the contract, please visit paperless.whitefieldestateagents.co.uk and sign the form as soon as possible.
';
                    ContractNotification::create([
                        'content' => "You have successfully read and agree with the contract $title.",
                        'user_id' => $userId
                    ]);
                } else {
                    $Data['title'] = 'Contract Update – A User has Signed the Contract';
                    $Data['body'] = $user . " has successfully read and signed the contract. Once all parties have signed the agreement, you will receive the final copy of the contract with everyone's signatures.
If you haven't signed the contract yet, please visit paperless.whitefieldestateagents.co.uk and complete the form as soon as possible.
";
                    ContractNotification::create([
                        'content' => "$user has successfully read and agree with the contract $title .",
                        'user_id' => $userId
                    ]);
                }
                Mail::to($userMail)->send(new SendMail($Data));
            }
        }
        return redirect()->route('contract.my-contracts')->with("success", "Your Response Noted Thank You");
    }
    public function savePdf(Request $request)
    {
        $id = $request->input('dynamic_id');
        $contract = Contract::findOrFail($id);


        if ($contract->download_status == 'Not-Downloaded') {
            // dd('Check Pdf');
            $pdfData = $request->file('pdf');
            // Save PDF on server
            $pdfPath = $pdfData->storeAs('pdfs', 'Generate_Pdf_' . $id . '.pdf');
            // dd($pdfPath);
            $contract_name = Contract::find($id)->title;

            // Send email with PDF attachment to each user
            $user_mails = $contract->users()->pluck('email')->toArray();
            $data = [
                'title' => 'Contract Update – All Parties have Signed',
                'body' => 'The ' . $contract_name . ' has been successfully signed by all parties. Please see the attached signed copy of the ' . $contract_name . '.',
            ];
            foreach ($user_mails as $user_mail) {
                Mail::to($user_mail)->send(new SendPdfMail($data, $pdfPath));
            }
            // Queue
            // $this->dispatch(new GeneratePdf($pdfPath,$user_mails,$contract_name));
            $contract->update([
                'download_status' => 'Downloaded'
            ]);
            return response()->json(['Sent']);
        } else if ($contract->download_status == 'Downloaded') {
            return response()->json(['Already Sent']);
        } else {
            return response()->json(['Something went wrong.']);
        }
    }

    public function myContracts()
    {
        $contracts = Contract::where('user_id', auth()->user()->id)
            ->join('contract_users', 'contracts.id', 'contract_users.contract_id')
            ->select('contracts.id', 'contract_users.status', 'title')->latest('contracts.created_at')->get();

        return view('contracts.my-contracts', compact('contracts'));
    }
    public function saveSignature(Request $request)
    {

        $folderPath = public_path('uploads/signatures/'); // create signatures folder in public directory
        $image_parts = explode(";base64,", $request->signature);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $imageName = uniqid() . '.' . $image_type;
        $file = $folderPath . $imageName;
        file_put_contents($file, $image_base64);
        $signature = Signature::create([
            'name' => $imageName,
        ]);
        return $signature;
    }

    public function saveImage(Request $request)
    {

        $file = $request->file('file');
        $filename = uniqid() . '.' . $file->getClientOriginalExtension();
        $path = $file->move('uploads/images/', $filename);
        return $filename;
    }
    public function mark_as_readed()
    {

        ContractNotification::where('user_id', auth()->user()->id)->where('status', 'unreaded')->update([
            'status' => 'readed'
        ]);
        return back();
    }

    public function copy($id)
    {
        $contract = ContractCopy::where('contract_id', $id)->first();
        $contract->form_data = json_encode($contract->form_data);
        $users = User::where('id', '!=', auth()->user()->id)->get();
        $selectUsers = [...$users, auth()->user()];
        $categories = Category::get();
        // return $contract;
        $templates = ContractTemplate::get();
        return view('contracts.copy', compact('contract', 'templates', 'users', 'categories', 'selectUsers'));
    }

    public function print($id)
    {


        $contract = Contract::where('user_id', auth()->user()->id)
            ->where('contracts.id', $id)
            ->join('contract_users', 'contracts.id', 'contract_users.contract_id')
            ->select('contracts.*', 'contract_users.status')->first();
        if (!$contract) {
            abort(404);
        }
        $contract->form_data = json_encode($contract->form_data);
        return view('contracts.print', compact('contract'));
    }
}
