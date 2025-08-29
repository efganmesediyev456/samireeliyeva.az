<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\PriceQuotesDatatable;
use App\DataTables\HaveQuestionDatatable;
use App\Http\Controllers\Controller;
use App\Models\HaveQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\FileUploadHelper;
use App\Models\SocialLink;

class HaveQuestionController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->mainService->model = HaveQuestion::class;
    }

    public function index(HaveQuestiondatatable $dataTable)
    {
        return $dataTable->render('backend.pages.have_questions.index');
    }
}
