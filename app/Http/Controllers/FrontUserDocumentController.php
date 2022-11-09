<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\DataGrid;
use App\Http\Requests\FrontUserDocumentRequest;
use App\Models\UserDocument;
use App\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FrontUserDocumentController extends Controller
{
    use DataGrid;

    public $userRole = 'SUPPLIER';
    public $route = 'supplier.document';
    public $redirectRoute = 'supplier.document.edit';
    public $redirectBackRoute = 'supplier.document.index';

    /**
     * @param $user_uuid
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        $user_uuid = auth()->user()->uuid;
        return redirect()->route($this->redirectRoute, $user_uuid);
    }

    /**
     * @param UserDocument $user_document
     * @param User $userModel
     * @return View
     */
    public function create( UserDocument $user_document, User $userModel) : View
    {


        $user_uuid = auth()->user()->uuid;
        $route = $this->route;
        $redirectBackRoute = $this->redirectBackRoute;
        // $role = $this->userRole;
        $role = \Auth::user()->role;
        $pageTitle = "DOCUMENTS";
        $user = $userModel->where('uuid', $user_uuid)->first();
        $user_document->setUserId($user_uuid);
        $documentTypes = [];
        switch($role)
        {
            case 'VENDOR':
                $documentTypes = $user_document->getVendorDocuments();
                break;

            case 'SUPPLIER':
                $documentTypes = $user_document->getSupplierDocuments();
                break;

            case 'LOGISTICS':
                $documentTypes = $user_document->getLogisticsDocuments();
                break;
            case 'DRIVER':
                $documentTypes = $user_document->getLogisticsDocuments();
                break;
            case 'COMPANY':
                $documentTypes = $user_document->getCompanyDocuments();
                break;

            case 'ADMIN':
            default:
                break;
        }

        return view('supplier.document.index', compact('user', 'user_document', 'pageTitle', 'route', 'role', 'redirectBackRoute', 'documentTypes', 'user_document'));
    }

    /**
     * @param FrontUserDocumentRequest $request
     * @param UserDocument $userDocument
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(FrontUserDocumentRequest $request, UserDocument $userDocument)
    {
        $user_uuid = auth()->user()->uuid;
        $titles = $request->get('title');
        $comments = $request->get('comment');
        $approved = $request->get('approved');
        $documentOneExists = $request->get('document_one_exists');
        $keysCreated = [];
        if ($request->has('document_one')){
            foreach($request->file('document_one') as $key => $documentOne)
            {
                $data = [];
                if ($documentOne->isValid()) {
                    $documentFile = $userDocument->uploadMedia($documentOne);
                    $document = $documentFile['path'] . $documentFile['name'];
                    $data['document_file_one'] = $document;
                }
                $data['title'] = $titles[$key] ?? NULL;
                $data['comment'] = $comments[$key] ?? NULL;
                $data['approved'] = $approved[$key] ?? NULL;
                $data['user_id'] = $user_uuid ?? NULL;
                $userDocument->updateOrCreate(['user_id' => $user_uuid, 'title' => $titles[$key]], $data);
                $keysCreated[] = $key;
            }
        }

        foreach ($documentOneExists as $key => $documentOneExist)
        {
            if(!in_array($key, $keysCreated)){
                $data = [];
                $data['title'] = $titles[$key];
                $data['comment'] = $comments[$key];
                $data['approved'] = $approved[$key];
                $data['user_id'] = $user_uuid;
                $userDocument->updateOrCreate(['user_id' => $user_uuid, 'title' => $titles[$key]], $data);
            }
        }
        $route = $this->route;
        return redirect(route("$route.create", $user_uuid))->with(['status' => 'success', 'message' => trans('success.admin|userDocument|updated')]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UserDocument  $userDocument
     * @return \Illuminate\Http\Response
     */
    public function show($user_uuid, UserDocument $userDocument)
    {
        return redirect()->route($this->redirectRoute, $user_uuid);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\UserDocument  $userDocument
     * @return \Illuminate\Http\Response
     */
    public function edit($user_uuid, UserDocument $userDocument)
    {
        return redirect()->route($this->redirectRoute, $user_uuid);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UserDocument  $userDocument
     * @return \Illuminate\Http\Response
     */
    public function update($user_uuid, Request $request, UserDocument $userDocument)
    {
        return redirect()->route($this->redirectRoute, $user_uuid);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserDocument  $userDocument
     * @return \Illuminate\Http\Response
     */
    public function destroy($user_uuid, UserDocument $userDocument)
    {
        return redirect()->route($this->redirectRoute, $user_uuid);
    }
}
