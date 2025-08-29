<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\LanguagesDataTable;
use App\DataTables\SubScriptionDatatable;
use App\DataTables\UsersDataTable;
use App\Http\Controllers\Controller;
use App\Models\About;
use App\Models\Language;
use App\Models\Order;
use App\Models\TermsAndCondition;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Yajra\DataTables\EloquentDataTable;
use App\Helpers\FileUploadHelper;


class SubScriptionController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->mainService->model = Order::class;
    }

    public function index(User $user, SubScriptionDatatable $dataTable)
    {
        return $dataTable->with('user', $user)->render('backend.pages.users.subscriptions.index', compact('user'));
    }
}
