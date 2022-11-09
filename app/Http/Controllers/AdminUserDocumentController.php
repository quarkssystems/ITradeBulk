<?php



namespace App\Http\Controllers;



use App\Http\Controllers\Helpers\DataGrid;

use App\Http\Requests\AdminUserDocumentRequest;

use App\Models\UserDocument;

use App\User;

use Illuminate\Http\Request;

use Illuminate\View\View;

use Illuminate\Support\Facades\Mail;

use App\Models\EmailTemplate;



class AdminUserDocumentController extends Controller

{



    use DataGrid;



    public $userRole = 'ADMIN';

    public $route = 'admin.user-document';

    public $redirectRoute = 'admin.users.edit';

    public $redirectBackRoute = 'admin.users.index';

    public $navTab = 'admin.users.admin.navTab';



    /**

     * @param $user_uuid

     * @return \Illuminate\Http\RedirectResponse

     */

    public function index($user_uuid)

    {

        return redirect()->route($this->redirectRoute, $user_uuid);

    }



    /**

     * @param $user_uuid

     * @param UserDocument $user_document

     * @param User $userModel

     * @return View

     */

    public function create($user_uuid, UserDocument $user_document, User $userModel) : View

    {

        $route = $this->route;

        $redirectBackRoute = $this->redirectBackRoute;

        $role = $this->userRole;

        $pageTitle = "DOCUMENTS";

        $user = $userModel->where('uuid', $user_uuid)->first();

        $navTab = $this->navTab;

        $user_document->setUserId($user_uuid);

        $documentTypes = [];

     

        switch($this->userRole)

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

             case 'COMPANY':

                $documentTypes = $user_document->getCompanyDocuments();

                break;    



            case 'ADMIN':

            default:

                break;

        }



        return view('admin.userDocument.form', compact('user', 'user_document', 'pageTitle', 'route', 'role', 'navTab', 'redirectBackRoute', 'documentTypes', 'user_document'));

    }



    /**

     * @param AdminUserDocumentRequest $request

     * @param $user_uuid

     * @param UserDocument $userDocument

     * @return \Illuminate\Http\RedirectResponse

     */

    public function store(AdminUserDocumentRequest $request, $user_uuid, UserDocument $userDocument, User $userModel)

    {

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

                $data['title'] = $titles[$key];

                $data['comment'] = $comments[$key];

                $data['approved'] = $approved[$key];

                $data['user_id'] = $user_uuid;

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

        $checkuser =$userModel->where('uuid',$user_uuid)->where('status','INACTIVE')->first();

        $facebook_url = 'https://www.facebook.com/';
        $instagram_url = 'https://www.instagram.com/';
        $twitter_url = 'https://www.twitter.com/';
        $pinterest_url = 'https://www.pinterest.com/';

        if($checkuser){

                if( $userDocument->where('user_id', $user_uuid)->where('approved' , 'NO')->count()==0){   

                   

                    $userModel->where('uuid',$user_uuid)->update(['status'=>'ACTIVE']);

                    $maildata = $userModel->where('uuid',$user_uuid)->first()->toArray();

                
                $email = EmailTemplate::where('name','=','kyc_approved')->first();

                if(isset($email)){
                    $email->description = str_replace('[CUSTOMER_NAME]', $maildata['first_name'].' '.$maildata['last_name'], $email->description);
                    $email->description = str_replace('[PHONE]', '011 813 1335', $email->description);
                    $email->description = str_replace('[SITE_NAME]', env('WEBSITE'), $email->description);
                    $email->description = str_replace('[EMAIL]', env('SUPPORT'), $email->description);
                    $email->description = str_replace('[FACEBOOK_URL]', $facebook_url, $email->description);
                    $email->description = str_replace('[TWITTER_URL]', $twitter_url, $email->description);
                    $email->description = str_replace('[INSTAGRAM_URL]', $instagram_url, $email->description);
                    $email->description = str_replace('[PINTEREST_URL]', $pinterest_url, $email->description);
                    $email->description = str_replace('[LOGO]', asset('assets/images/logo.png'), $email->description);
                } 

                $emailContent = $email->description;

                Mail::send([], [], function ($message) use ($maildata , $emailContent) {
                  $message->to($maildata['email'])
                    ->subject('KYC approved')
                    ->setBody($emailContent, 'text/html'); // for HTML rich messages
                    $message->from(env('MAIL_USERNAME'),env('APP_NAME'));
                });



             //    Mail::send([], $maildata, function($message) use ($maildata) {
             //    $message->to($maildata['email'])->subject
             //       ('KYC approved')
             //       ->setBody('Hello , :  '.$maildata['first_name'].' '.$maildata['last_name'].' KYC Document is approved .Regards 
             //       Itradezon');
             //    $message->from('info@itradezon.com','iTradezon');
             // });



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