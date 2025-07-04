@extends('back/layouts/template')

@section('title', 'Create Postingan - Admin')

@section('content')


    {{-- konten --}}
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Create Postingan</h1>
        </div>

        <div class="mt-3">
            @if ($errors->any())
                <div class="my-3">
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form action="{{ url('postingan') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="title">Mata Pelajaran</label>
                            <input type="text" name="title" id="title" class="form-control"
                                value="{{ old('title') }}">
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="mb-3">
                            <label for="category_id">Kategori</label>
                            <select name="category_id" id="category_id" class="form-control">
                                <option value="" hidden>-- choose --</option>
                                @foreach ($categories as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="desc">Deskripsi Kegiatan</label>
                    <textarea name="desc" id="myeditor" cols="30" rows="10" class="form-control"></textarea>
                </div>

                <div class="mb-3">
                    <label for="title">Link Grup WhatsApp</label>
                    <input type="text" name="group" id="group" class="form-control" value="{{ old('group') }}">
                </div>

                <div class="mb-3">
                    <label for="max_participants">Maksimal Anggota (1–10)</label>
                    <input type="number" name="max_participants" id="max_participants" class="form-control"
                        min="1" max="10" value="{{ old('max_participants') }}">
                </div>

                <div class="mb-3">
                    <label for="img">Image (Max 2MB)</label>
                    <input type="file" name="img" id="img" class="form-control">
                    <div class="mt-1">
                        <img src="" class="img-thumbnail img-preview" width="100px">
                    </div>
                </div>

                <div class="row">
                    <div class="col-6">
                        <label for="status">Status
                            <select name="status" id="status" class="form-control">
                                <option value="" hidden>-- choose --</option>
                                <option value="1">Publish</option>
                                <option value="0">Private</option>
                            </select>
                        </label>
                    </div>
                </div>

                <div class="float-end mt-3">
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>


    @endsection

    @push('js')
        <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
        <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
        <script>
            var options = {
                filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
                filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images&_token=',
                filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
                filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files&_token=',
                clipboard_handleImages: false
            };
        </script>

        <script>
            CKEDITOR.replace('myeditor', options);

            // img preview
            $("#img").change(function() {
                previewImage(this);
            });

            function previewImage(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        $('.img-preview').attr('src', e.target.result);
                    };

                    reader.readAsDataURL(input.files[0]);
                }
            }
        </script>
    @endpush
