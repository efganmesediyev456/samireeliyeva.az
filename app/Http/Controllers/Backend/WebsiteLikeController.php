<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\WebsiteLikeDatatable;
use App\Http\Controllers\Controller;
use App\Models\WebsiteLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\FileUploadHelper;

class WebsiteLikeController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->mainService->model = WebsiteLike::class;
    }

    public function index(WebsiteLikeDatatable $dataTable)
    {
        return $dataTable->render('backend.pages.website_likes.index');
    }
}