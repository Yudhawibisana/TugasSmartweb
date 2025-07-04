{{-- <div class="col-lg-4">
    <!-- Side widget-->
    <div class="card mb-4 shadow">
        <div class="card-header">Side Widget</div>
        <div class="card-body">You can put anything you want inside of these side widgets. They are easy to
            use, and feature the Bootstrap 5 card component!</div>
    </div>
 --}}

<div class="container">
    <div class="row custom-row justify-content-center">
        <div class="col-md-8">
            <div class="card widget mb-4 shadow mt-3">
                <div class="card-header text-center text-kat fw-bold">Kategori Yang Tersedia</div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2 justify-content-center">
                        @foreach ($categories as $item)
                            <span>
                                <a href="{{ url('category/' . $item->slug) }}"
                                    class="back-kat badge text-kat unstyle-list-categories">{{ $item->name }}</a>
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
