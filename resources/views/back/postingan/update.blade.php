@extends('back/layouts/template')

@section('title', 'Update Postingan - Admin')

@section('content')


    {{-- konten --}}
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Update Postingan</h1>
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

            <form action="{{ url('postingan/' . $postingan->id) }}" method="post" enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <input type="hidden" name="oldImg" value="{{ $postingan->img }}">

                <div class="row">
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="title">Mata Pelajaran</label>
                            <input type="text" name="title" id="title" class="form-control"
                                value="{{ old('title', $postingan->title) }}">
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="mb-3">
                            <label for="category_id">Category</label>
                            <select name="category_id" id="category_id" class="form-control">
                                @foreach ($categories as $item)
                                    @if ($item->id == $postingan->category_id)
                                        <option value="{{ $item->id }}" selected>{{ $item->name }}</option>
                                    @else
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="desc">Description</label>
                    <textarea name="desc" id="myeditor" cols="30" rows="10" class="form-control">
                    {{ old('desc', $postingan->desc) }}
                </textarea>
                </div>

                <div class="row">
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="group">Link Grup WhatsApp</label>
                            <input type="text" name="group" id="group" class="form-control"
                                value="{{ old('group', $postingan->group) }}">
                        </div>
                    </div>
                </div>

                <div class="col-6">
                    <div class="mb-3">
                        <label for="max_participants">Maksimal Anggota (1–10)</label>
                        <input type="number" name="max_participants" id="max_participants" class="form-control"
                            min="1" max="10" value="{{ old('max_participants', $postingan->max_participants) }}">
                    </div>
                </div>

                <div class="mb-1">
                    <label for="img">Image (Max 2MB)</label>
                    <input type="file" name="img" id="img" class="form-control">
                    <div class="mt-2">
                        <small>Gambar Sebelumnya</small> <br>
                        <img src="{{ asset('storage/back/' . $postingan->img) }}" alt="" width="180px">
                    </div>
                </div>

                <div class="row">
                    <div class="col-6">
                        <label for="status">Status
                            <select name="status" id="status" class="form-control">
                                <option value="1"{{ $postingan->status == 1 ? 'selected' : null }}>Publish</option>
                                <option value="0"{{ $postingan->status == 0 ? 'selected' : null }}>Private</option>
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
