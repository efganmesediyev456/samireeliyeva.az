@extends('backend.layouts.layout')

@section('content')
    <div class="container">
        <!-- question Details Card -->
        <div class="card mb-4">
            <div class="card-header">
                
                <div class="d-flex justify-content-between align-items-center">
                    <h4>Sual #{{ $question->id }} - Ətraflı məlumat</h4>
                    <a href="{{ route('admin.users.exams.results.show', ['user'=>$user->id, 'exam'=>$exam->id]) }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left"></i> Geri qayıt
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Customer Information Column -->
                    <div class="col-md-6">
                        <h5 class="mb-3">Müştəri məlumatları</h5>
                        <table class="table table-bquestioned">
                            <tr>
                                <th>Ad Soyad:</th>
                                <td>{{ $question->user?->name }}</td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td>{{ $question->user?->email }}</td>
                            </tr>
                            <tr>
                                <th>Telefon:</th>
                                <td>{{ $question->user?->phone }}</td>
                            </tr>
                           
                        </table>
                    </div>

                    <!-- Delivery Information Column -->
                    <div class="col-md-6">
                        <h5 class="mb-3">Sual məlumatları</h5>
                        <table class="table table-bquestioned">
                            <tr>
                                <th>Sual:</th>
                                <td>{{ $question->examQuestion?->question_text }}</td>
                            </tr>
                             <tr>
                                <th>Verilən cavab:</th>
                                <td>{{ $question->answer }}</td>
                            </tr>
                        </table>
                    </div>

                    @if($question->examQuestion->type==2)
                    <div class="col-md-6 mx-auto mt-4">
                        <h5 class="mb-3">Doğrudur ya yanlışdır? </h5>
                        @if($question->is_admin_correct==1)
                        <div class="alert alert-success">
                            Doğru olaraq cavablandırıldı
                        </div>
                        @endif
                        @if($question->is_admin_correct==2)
                        <div class="alert alert-danger">
                            Yanlış olaraq cavablandırıldı
                        </div>
                        @endif
                       <form action="{{ route('admin.users.exams.results.acceptQuestion', ['user'=>$user->id, 'exam'=>$exam->id, 'question'=>$question->id]) }}" method="POST">
                            @csrf
                            <div class="form-group">
                                 <label for="">Cavabla</label>
                                  <select name="is_admin_correct" class="form-select">
                                        <option value="">Seçin</option>
                                        <option @selected($question->is_admin_correct==1) value="1">Doğrudur</option>
                                        <option @selected($question->is_admin_correct==2) value="2">Yanlışdır</option>
                                  </select>
                            </div>
                            <div class="form-group mt-2">
                                <input type="submit" class="btn btn-success" value="Təsdiqlə ">
                            </div>
                       </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>

      
    </div>
@endsection

